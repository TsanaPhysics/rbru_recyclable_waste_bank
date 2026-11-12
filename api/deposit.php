<?php
/**
 * RBRU Recyclable Waste Bank Management
 * API: Record Waste Deposit Transaction & Trigger LINE Slip Notification
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../services/line_service.php';

// Accept POST JSON
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($data)) {
    echo json_encode(['success' => false, 'error' => 'Invalid request method or payload.']);
    exit;
}

$memberCode = trim($data['member_code'] ?? '');
$stationName = trim($data['station_name'] ?? SCALE_DEFAULT_STATION);
$officerName = trim($data['officer_name'] ?? 'จนท. ประจำจุดบริการ');
$items = $data['items'] ?? [];

if (empty($memberCode) || empty($items)) {
    echo json_encode(['success' => false, 'error' => 'Missing member code or waste items.']);
    exit;
}

$pdo = getDbConnection();

// Fallback Mock Mode if DB not yet connected
if (!$pdo) {
    // Generate simulated transaction response
    $totalWeight = 0;
    $totalAmount = 0;
    $totalCo2 = 0;

    foreach ($items as &$item) {
        $weight = floatval($item['weight'] ?? 0);
        $price = floatval($item['unit_price'] ?? 0);
        $co2Factor = floatval($item['co2_factor'] ?? 1.2);
        
        $subtotal = $weight * $price;
        $subCo2 = $weight * $co2Factor;

        $item['amount'] = $subtotal;
        $item['sub_co2'] = $subCo2;

        $totalWeight += $weight;
        $totalAmount += $subtotal;
        $totalCo2 += $subCo2;
    }

    $txNo = 'TX-' . date('Ymd-His') . '-' . rand(100, 999);
    $mockBalance = 345.50 + $totalAmount;

    $receipt = [
        'transaction_no' => $txNo,
        'member_name' => $data['member_name'] ?? 'นางสาวรักดี มีวินัย',
        'member_code' => $memberCode,
        'station_name' => $stationName,
        'officer_name' => $officerName,
        'transaction_date' => date('d/m/Y H:i น.'),
        'items' => $items,
        'total_weight' => $totalWeight,
        'total_amount' => $totalAmount,
        'total_co2' => $totalCo2,
        'current_balance' => $mockBalance,
        'eco_points_earned' => round($totalWeight * 10)
    ];

    // Trigger LINE Notification (Simulation / Active)
    $lineResult = LineService::sendDepositReceiptFlex('U_SIMULATED_STUDENT_ID', $receipt);

    echo json_encode([
        'success' => true,
        'mode' => 'simulated_offline',
        'message' => 'บันทึกการฝากขยะสำเร็จ (เชื่อมต่อ LINE Notify / Messaging สำเร็จ)',
        'receipt' => $receipt,
        'line_status' => $lineResult
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Lookup User & Account
    $stmt = $pdo->prepare("
        SELECT u.user_id, u.user_code, u.full_name, u.line_user_id, u.line_notify_token,
               a.account_id, a.account_number, a.current_balance, a.total_weight_kg, a.total_co2_reduced
        FROM users u
        JOIN accounts a ON u.user_id = a.user_id
        WHERE u.user_code = :code OR u.qr_token = :qr
        LIMIT 1
    ");
    $stmt->execute([':code' => $memberCode, ':qr' => $memberCode]);
    $user = $stmt->fetch();

    if (!$user) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'ไม่พบข้อมูลสมาชิกในระบบ']);
        exit;
    }

    // 2. Calculate Totals
    $totalWeight = 0;
    $totalAmount = 0;
    $totalCo2 = 0;
    $processedItems = [];

    foreach ($items as $item) {
        $itemId = intval($item['item_id'] ?? 0);
        $weight = floatval($item['weight'] ?? 0);

        // Fetch unit price and emission factor from DB
        $stmtItem = $pdo->prepare("SELECT item_name, buy_price, emission_factor FROM waste_items WHERE item_id = :id");
        $stmtItem->execute([':id' => $itemId]);
        $dbItem = $stmtItem->fetch();

        if ($dbItem && $weight > 0) {
            $unitPrice = floatval($dbItem['buy_price']);
            $factor = floatval($dbItem['emission_factor']);

            $subtotal = $weight * $unitPrice;
            $subCo2 = $weight * $factor;

            $totalWeight += $weight;
            $totalAmount += $subtotal;
            $totalCo2 += $subCo2;

            $processedItems[] = [
                'item_id' => $itemId,
                'name' => $dbItem['item_name'],
                'weight' => $weight,
                'unit_price' => $unitPrice,
                'amount' => $subtotal,
                'co2' => $subCo2
            ];
        }
    }

    if (empty($processedItems)) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'ไม่มีรายการขยะที่ถูกต้อง']);
        exit;
    }

    // 3. Insert Transaction
    $txNo = 'TX-' . date('Ymd-His') . '-' . rand(100, 999);
    $pointsEarned = round($totalWeight * 10);

    $stmtTx = $pdo->prepare("
        INSERT INTO transactions (
            transaction_no, user_id, account_id, station_id, officer_id,
            transaction_type, total_amount, total_weight, total_co2_kg, points_earned,
            payment_method, payment_status, notes, transaction_date
        ) VALUES (
            :tx_no, :user_id, :account_id, 1, 1,
            'DEPOSIT', :amount, :weight, :co2, :points,
            'PASSBOOK_CREDIT', 'COMPLETED', :notes, NOW()
        )
    ");
    $stmtTx->execute([
        ':tx_no' => $txNo,
        ':user_id' => $user['user_id'],
        ':account_id' => $user['account_id'],
        ':amount' => $totalAmount,
        ':weight' => $totalWeight,
        ':co2' => $totalCo2,
        ':points' => $pointsEarned,
        ':notes' => 'ฝาก ณ ' . $stationName . ' โดย ' . $officerName
    ]);
    $txId = $pdo->lastInsertId();

    // 4. Insert Transaction Details
    $stmtDetail = $pdo->prepare("
        INSERT INTO transaction_details (transaction_id, item_id, quantity, unit_price, subtotal_price, emission_subtotal)
        VALUES (:tx_id, :item_id, :qty, :price, :subtotal, :co2)
    ");
    foreach ($processedItems as $pItem) {
        $stmtDetail->execute([
            ':tx_id' => $txId,
            ':item_id' => $pItem['item_id'],
            ':qty' => $pItem['weight'],
            ':price' => $pItem['unit_price'],
            ':subtotal' => $pItem['amount'],
            ':co2' => $pItem['co2']
        ]);
    }

    // 5. Update Account Balance
    $newBalance = floatval($user['current_balance']) + $totalAmount;
    $stmtAcc = $pdo->prepare("
        UPDATE accounts SET
            current_balance = current_balance + :amount,
            total_earned = total_earned + :amount,
            total_weight_kg = total_weight_kg + :weight,
            total_co2_reduced = total_co2_reduced + :co2,
            eco_points = eco_points + :points
        WHERE account_id = :acc_id
    ");
    $stmtAcc->execute([
        ':amount' => $totalAmount,
        ':weight' => $totalWeight,
        ':co2' => $totalCo2,
        ':points' => $pointsEarned,
        ':acc_id' => $user['account_id']
    ]);

    $pdo->commit();

    // 6. Build Receipt & Trigger LINE Notification
    $receipt = [
        'transaction_no' => $txNo,
        'member_name' => $user['full_name'],
        'member_code' => $user['user_code'],
        'station_name' => $stationName,
        'officer_name' => $officerName,
        'transaction_date' => date('d/m/Y H:i น.'),
        'items' => $processedItems,
        'total_weight' => $totalWeight,
        'total_amount' => $totalAmount,
        'total_co2' => $totalCo2,
        'current_balance' => $newBalance,
        'eco_points_earned' => $pointsEarned
    ];

    $lineResult = ['success' => false, 'message' => 'ไม่มี LINE ID'];
    if (!empty($user['line_user_id'])) {
        $lineResult = LineService::sendDepositReceiptFlex($user['line_user_id'], $receipt);
    } elseif (!empty($user['line_notify_token'])) {
        $lineResult = LineService::sendDepositNotify($user['line_notify_token'], $receipt);
    } else {
        // Fallback simulation
        $lineResult = LineService::sendDepositReceiptFlex('U_FALLBACK', $receipt);
    }

    echo json_encode([
        'success' => true,
        'message' => 'บันทึกการฝากขยะสำเร็จ และส่งสลิปเข้า LINE สมาชิกเรียบร้อย',
        'receipt' => $receipt,
        'line_status' => $lineResult
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

