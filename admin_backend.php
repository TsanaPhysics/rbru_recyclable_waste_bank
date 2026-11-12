<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

// Mock summary metrics for Admin Dashboard
$adminMetrics = [
    'total_stock_weight' => 1248.50, // kg
    'fund_balance' => 45680.00, // THB
    'total_revenue' => 184500.00,
    'total_profit' => 38240.00, // Margin
    'co2_reduced_tons' => 1.894, // tCO2eq
    'active_members' => 342,
    'central_depot' => 'ศูนย์คัดแยกขยะกลาง (อาคาร 36 สำนักงานอธิการบดี)'
];

// Current Physical Stock in Depot Building 36
$stockItems = [
    ['item_id' => 1, 'category' => 'กระดาษ', 'name' => 'กระดาษขาวดำ (A4)', 'stock' => 185.0, 'buy_price' => 4.50, 'sell_price' => 6.00, 'margin' => 1.50],
    ['item_id' => 2, 'category' => 'กระดาษ', 'name' => 'ลังกระดาษลูกฟูก', 'stock' => 340.5, 'buy_price' => 3.00, 'sell_price' => 4.50, 'margin' => 1.50],
    ['item_id' => 4, 'category' => 'พลาสติก', 'name' => 'ขวดพลาสติกใส (PET)', 'stock' => 420.0, 'buy_price' => 9.00, 'sell_price' => 12.50, 'margin' => 3.50],
    ['item_id' => 5, 'category' => 'พลาสติก', 'name' => 'ขวดขุ่น (HDPE แกลลอน)', 'stock' => 110.0, 'buy_price' => 8.00, 'sell_price' => 11.00, 'margin' => 3.00],
    ['item_id' => 7, 'category' => 'โลหะ', 'name' => 'กระป๋องอลูมิเนียม', 'stock' => 68.0, 'buy_price' => 35.00, 'sell_price' => 45.00, 'margin' => 10.00],
    ['item_id' => 9, 'category' => 'แก้ว', 'name' => 'ขวดเบียร์กล่อง (ขวดดี)', 'stock' => 95.0, 'buy_price' => 12.00, 'sell_price' => 16.00, 'margin' => 4.00],
    ['item_id' => 11, 'category' => 'E-Waste', 'name' => 'สายไฟเก่าทองแดง', 'stock' => 30.0, 'buy_price' => 40.00, 'sell_price' => 65.00, 'margin' => 25.00],
];

// Pending Member Withdrawal Requests
$withdrawalRequests = [
    ['req_id' => 'W-2026-101', 'name' => 'น.ส.รักดี มีวินัย', 'code' => '6614401001', 'amount' => 150.00, 'method' => 'PromptPay (089-876-5432)', 'date' => '12 พ.ย. 69 14:10', 'status' => 'PENDING'],
    ['req_id' => 'W-2026-102', 'name' => 'นายสมศักดิ์ ขยันยิ่ง', 'code' => '6512301044', 'amount' => 300.00, 'method' => 'PromptPay (081-999-1122)', 'date' => '12 พ.ย. 69 13:45', 'status' => 'PENDING'],
    ['req_id' => 'W-2026-103', 'name' => 'คณะวิทยาศาสตร์ฯ (Green Office)', 'code' => 'DEPT-SCI01', 'amount' => 1200.00, 'method' => 'โอนเข้ากองทุนคณะ', 'date' => '11 พ.ย. 69 16:00', 'status' => 'COMPLETED'],
];
?>
<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบหลังบ้านและคลังขยะ | <?= APP_NAME ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Kanit', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Admin Header -->
    <header class="bg-slate-900 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-3.5">
                    <a href="index.php" class="w-10 h-10 bg-slate-800 hover:bg-slate-700 rounded-xl flex items-center justify-center text-lg transition text-slate-300">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs bg-indigo-500 text-white font-bold px-2 py-0.5 rounded">ADMIN CMS</span>
                            <span class="text-xs text-slate-400">ระบบหลังบ้านและการเงิน</span>
                        </div>
                        <h1 class="text-lg sm:text-xl font-bold font-heading text-white">
                            RBRU Waste Bank Management Backoffice
                        </h1>
                    </div>
                </div>

                <div class="flex items-center space-x-3 text-xs">
                    <a href="station_pos.php" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 font-heading">
                        <i class="fa-solid fa-scale-balanced"></i>
                        <span>เปิดจุดชั่ง POS (อาคาร 36)</span>
                    </a>
                    <a href="sdg_climate.php" class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 font-heading">
                        <i class="fa-solid fa-leaf"></i>
                        <span>เพจ SDG & Climate</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-8">

        <!-- Section 1: Executive KPI Summary -->
        <section>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold font-heading text-slate-900">ภาพรวมสถานะกองทุนและคลังขยะรีไซเคิล</h2>
                    <p class="text-xs text-slate-500">คลังพัสดุและศูนย์คัดแยกขยะกลาง: <?= $adminMetrics['central_depot'] ?></p>
                </div>
                <div class="flex gap-2">
                    <button onclick="exportGreenMetricReport()" class="bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold px-3.5 py-2 rounded-xl shadow-sm transition flex items-center gap-1.5 font-heading">
                        <i class="fa-solid fa-file-excel text-emerald-600"></i>
                        <span>ส่งออกรายงาน UI GreenMetric (WS)</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">สต็อกขยะรอจำหน่าย</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm"><i class="fa-solid fa-boxes-stacked"></i></div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900"><?= number_format($adminMetrics['total_stock_weight'], 1) ?></span>
                    <span class="text-xs text-slate-400 ml-1">กก.</span>
                    <p class="text-[11px] text-emerald-700 mt-2 font-medium"><i class="fa-solid fa-warehouse mr-1"></i>กองรวม ณ อาคาร 36 สำนักงานอธิการบดี</p>
                </div>

                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">เงินหมุนเวียนกองทุน</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-700">฿ <?= number_format($adminMetrics['fund_balance'], 2) ?></span>
                    <p class="text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-chart-line text-emerald-600 mr-1"></i>กำไรส่วนต่างสะสม ฿ <?= number_format($adminMetrics['total_profit'], 2) ?></p>
                </div>

                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">ลดก๊าซเรือนกระจกสะสม</span>
                        <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center text-sm"><i class="fa-solid fa-leaf"></i></div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold font-heading text-teal-700"><?= number_format($adminMetrics['co2_reduced_tons'], 3) ?></span>
                    <span class="text-xs text-slate-400 ml-1">ตัน (tCO₂eq)</span>
                    <p class="text-[11px] text-teal-700 mt-2 font-medium">เทียบเท่าปลูกป่า 190 ต้น (อ้างอิง อบก.)</p>
                </div>

                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">สมาชิกผู้ร่วมโครงการ</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900"><?= $adminMetrics['active_members'] ?></span>
                    <span class="text-xs text-slate-400 ml-1">บัญชี</span>
                    <p class="text-[11px] text-indigo-700 mt-2 font-medium"><i class="fa-solid fa-building mr-1"></i>ครอบคลุมทุกคณะและกองงาน</p>
                </div>
            </div>
        </section>

        <!-- Section 2: Physical Stock in Depot & Outflow Sale -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Current Stock Table (col 8) -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">ปริมาณขยะคงคลัง ณ ศูนย์คัดแยกกลาง (อาคาร 36)</h3>
                        <p class="text-xs text-slate-500">ตรวจสอบสต็อกรอขายให้โรงงานรับซื้อของเก่า</p>
                    </div>
                    <span class="text-xs bg-emerald-50 text-emerald-800 font-semibold px-3 py-1 rounded-full border border-emerald-200">
                        พร้อมจำหน่าย <?= count($stockItems) ?> รายการ
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="pb-3 font-semibold">ประเภทขยะ</th>
                                <th class="pb-3 font-semibold text-right">สต็อกคงเหลือ</th>
                                <th class="pb-3 font-semibold text-right">ราคารับซื้อ</th>
                                <th class="pb-3 font-semibold text-right">ราคาขายต่อ</th>
                                <th class="pb-3 font-semibold text-right">ส่วนต่างกำไร</th>
                                <th class="pb-3 font-semibold text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($stockItems as $it): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 font-bold text-slate-900 font-heading">
                                    <span class="text-xs text-slate-400 block font-normal"><?= $it['category'] ?></span>
                                    <?= $it['name'] ?>
                                </td>
                                <td class="py-3 text-right font-extrabold text-slate-900 font-heading text-sm"><?= number_format($it['stock'], 1) ?> กก.</td>
                                <td class="py-3 text-right text-slate-500"><?= number_format($it['buy_price'], 2) ?> ฿</td>
                                <td class="py-3 text-right font-semibold text-emerald-800"><?= number_format($it['sell_price'], 2) ?> ฿</td>
                                <td class="py-3 text-right font-bold text-amber-700">+<?= number_format($it['margin'], 2) ?> ฿/กก.</td>
                                <td class="py-3 text-center">
                                    <button onclick="openOutflowModal('<?= $it['name'] ?>', <?= $it['stock'] ?>, <?= $it['sell_price'] ?>, <?= $it['buy_price'] ?>)" 
                                            class="bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-2.5 py-1.5 rounded-lg text-[11px] shadow-sm transition font-heading">
                                        บันทึกขายออก
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: Record Outflow Sale Form (col 4) -->
            <div class="lg:col-span-4 bg-slate-900 text-white rounded-3xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2 text-emerald-400 text-xs font-semibold mb-2">
                        <i class="fa-solid fa-truck-moving text-base"></i>
                        <span>STOCK OUTFLOW SALE</span>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white mb-2">บันทึกจำหน่ายขยะออกสู่โรงงาน</h3>
                    <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                        เมื่อรถบรรทุกของโรงงานรีไซเคิลมารับซื้อขยะจากอาคาร 36 บันทึกรายการนี้เพื่อตัดสต็อกและบันทึกกำไรเข้ากองทุนธนาคารขยะ มรภ.รำไพพรรณี
                    </p>

                    <div class="space-y-3 text-xs">
                        <div>
                            <label class="block text-slate-400 mb-1">ชื่อร้านรับซื้อ/โรงงานรีไซเคิล:</label>
                            <input type="text" id="buyerName" value="บริษัท จันทบุรีรีไซเคิล จำกัด" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-slate-400 mb-1">เลือกประเภทขยะ:</label>
                            <select id="saleItemSelect" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 font-heading">
                                <?php foreach ($stockItems as $it): ?>
                                <option value="<?= $it['item_id'] ?>"><?= $it['name'] ?> (สต็อก: <?= $it['stock'] ?> กก.)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-slate-400 mb-1">น้ำหนักที่ขาย (กก.):</label>
                                <input type="number" id="saleWeight" value="100.0" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">ราคาขายต่อ (฿/กก.):</label>
                                <input type="number" id="salePrice" value="12.50" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-emerald-400 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-800 mt-6">
                    <button onclick="submitOutflowSale()" class="w-full bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-bold py-3 rounded-xl shadow-lg transition text-xs font-heading flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i>
                        <span>ยืนยันบันทึกจำหน่ายและเข้ากองทุน</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Section 3: Withdrawal Requests Approval Manager -->
        <section class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">คำขอเบิกถอนเงินของสมาชิก (Withdrawal Requests)</h3>
                    <p class="text-xs text-slate-500">ตรวจสอบและอนุมัติการจ่ายเงินสด/โอน PromptPay เข้าบัญชีสมาชิก</p>
                </div>
                <span class="text-xs bg-amber-50 text-amber-800 font-semibold px-3 py-1 rounded-full border border-amber-200">
                    รอตรวจสอบ 2 รายการ
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <th class="pb-3 font-semibold">รหัสคำขอ</th>
                            <th class="pb-3 font-semibold">ชื่อสมาชิก</th>
                            <th class="pb-3 font-semibold">ช่องทางรับเงิน</th>
                            <th class="pb-3 font-semibold text-right">จำนวนเงิน</th>
                            <th class="pb-3 font-semibold">วัน-เวลาขอถอน</th>
                            <th class="pb-3 font-semibold text-center">สถานะ</th>
                            <th class="pb-3 font-semibold text-center">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($withdrawalRequests as $w): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 font-mono font-bold text-slate-700"><?= $w['req_id'] ?></td>
                            <td class="py-3 font-semibold text-slate-900 font-heading"><?= $w['name'] ?> <span class="text-[11px] text-slate-400 block font-normal"><?= $w['code'] ?></span></td>
                            <td class="py-3 text-slate-600"><?= $w['method'] ?></td>
                            <td class="py-3 text-right font-extrabold text-emerald-800 font-heading text-sm">฿ <?= number_format($w['amount'], 2) ?></td>
                            <td class="py-3 text-slate-500"><?= $w['date'] ?></td>
                            <td class="py-3 text-center">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold <?= $w['status'] === 'PENDING' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' ?>">
                                    <?= $w['status'] === 'PENDING' ? 'รออนุมัติ' : 'โอนแล้ว' ?>
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <?php if ($w['status'] === 'PENDING'): ?>
                                <button onclick="approveWithdrawal('<?= $w['req_id'] ?>', '<?= $w['name'] ?>', <?= $w['amount'] ?>)" class="bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-3 py-1 rounded-lg text-[11px] transition shadow-sm font-heading">
                                    อนุมัติโอน
                                </button>
                                <?php else: ?>
                                <span class="text-slate-400 text-xs"><i class="fa-solid fa-check text-emerald-600 mr-1"></i>เรียบร้อย</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- Outflow Sale Quick Modal -->
    <div id="outflowModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl relative">
            <button onclick="closeOutflowModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <h3 class="text-lg font-bold font-heading text-slate-900">บันทึกขายออกขยะรีไซเคิล</h3>
            <p id="m-itemName" class="text-xs text-slate-500 mb-4"></p>
            <div class="space-y-2 text-xs text-left mb-4">
                <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500">สต็อกคงเหลือ:</span><span id="m-stock" class="font-bold"></span></div>
                <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500">ราคาขายต่อ:</span><span id="m-sellPrice" class="font-bold text-emerald-700"></span></div>
                <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500">ส่วนต่างกำไรเข้ากองทุน:</span><span id="m-margin" class="font-bold text-amber-700"></span></div>
            </div>
            <button onclick="confirmOutflowModal()" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-2.5 rounded-xl text-xs font-heading">
                ยืนยันการขายออก
            </button>
        </div>
    </div>

    <!-- Include Modern Shared Footer -->
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- JAVASCRIPT: ADMIN OPERATIONS -->
    <script>
        function openOutflowModal(name, stock, sellPrice, buyPrice) {
            document.getElementById('m-itemName').innerText = name;
            document.getElementById('m-stock').innerText = stock.toFixed(1) + ' กก.';
            document.getElementById('m-sellPrice').innerText = sellPrice.toFixed(2) + ' ฿/กก.';
            document.getElementById('m-margin').innerText = '+' + (sellPrice - buyPrice).toFixed(2) + ' ฿/กก.';
            document.getElementById('outflowModal').classList.remove('hidden');
        }

        function closeOutflowModal() {
            document.getElementById('outflowModal').classList.add('hidden');
        }

        function confirmOutflowModal() {
            alert('บันทึกจำหน่ายออกสำเร็จ! ยอดกำไรสุทธิได้รับการเครดิตเข้าบัญชีกองทุนธนาคารขยะ มรภ.รำไพพรรณี เรียบร้อยแล้ว');
            closeOutflowModal();
        }

        function submitOutflowSale() {
            const buyer = document.getElementById('buyerName').value;
            const wt = document.getElementById('saleWeight').value;
            const pr = document.getElementById('salePrice').value;
            const total = wt * pr;
            alert(`บันทึกการขายให้: ${buyer}\nน้ำหนัก: ${wt} กก.\nยอดเงินรวม: ${total.toLocaleString()} บาท\nตัดสต็อก ณ อาคาร 36 เรียบร้อยแล้ว`);
        }

        function approveWithdrawal(reqId, name, amount) {
            if (confirm(`ยืนยันการอนุมัติจ่ายเงิน ${amount.toFixed(2)} บาท ให้กับ ${name} ใช่หรือไม่?`)) {
                alert(`อนุมัติคำขอ ${reqId} สำเร็จ! ระบบได้ทำรายการโอนเงินและส่งข้อความแจ้งเตือนผ่าน LINE สมาชิกเรียบร้อยแล้ว`);
            }
        }

        function exportGreenMetricReport() {
            alert('ระบบจัดเตรียมไฟล์รายงาน UI GreenMetric Category: Waste (WS) ในรูปแบบ Excel/CSV เรียบร้อยแล้ว กำลังเริ่มดาวน์โหลด...');
        }
    </script>
</body>
</html>
