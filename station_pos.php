<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

// Mock list of waste items with prices
$wasteItems = [
    ['item_id' => 1, 'category' => 'กระดาษ', 'name' => 'กระดาษขาวดำ (A4)', 'price' => 4.50, 'unit' => 'กก.', 'co2' => 1.34, 'icon' => 'fa-file-lines', 'color' => 'bg-blue-500'],
    ['item_id' => 2, 'category' => 'กระดาษ', 'name' => 'ลังกระดาษลูกฟูก', 'price' => 3.00, 'unit' => 'กก.', 'co2' => 1.12, 'icon' => 'fa-box', 'color' => 'bg-amber-600'],
    ['item_id' => 3, 'category' => 'กระดาษ', 'name' => 'กระดาษหนังสือพิมพ์/สมุด', 'price' => 2.50, 'unit' => 'กก.', 'co2' => 0.95, 'icon' => 'fa-newspaper', 'color' => 'bg-blue-400'],
    ['item_id' => 4, 'category' => 'พลาสติก', 'name' => 'ขวดพลาสติกใส (PET)', 'price' => 9.00, 'unit' => 'กก.', 'co2' => 1.55, 'icon' => 'fa-bottle-water', 'color' => 'bg-emerald-500'],
    ['item_id' => 5, 'category' => 'พลาสติก', 'name' => 'ขวดขุ่น (HDPE แกลลอน)', 'price' => 8.00, 'unit' => 'กก.', 'co2' => 1.42, 'icon' => 'fa-jug-detergent', 'color' => 'bg-teal-600'],
    ['item_id' => 6, 'category' => 'พลาสติก', 'name' => 'พลาสติกรวม/ถุงแกง', 'price' => 2.00, 'unit' => 'กก.', 'co2' => 0.88, 'icon' => 'fa-bag-shopping', 'color' => 'bg-emerald-400'],
    ['item_id' => 7, 'category' => 'โลหะ', 'name' => 'กระป๋องอลูมิเนียม', 'price' => 35.00, 'unit' => 'กก.', 'co2' => 9.13, 'icon' => 'fa-can-food', 'color' => 'bg-yellow-500'],
    ['item_id' => 8, 'category' => 'โลหะ', 'name' => 'เศษเหล็กหนา/เหล็กเส้น', 'price' => 6.50, 'unit' => 'กก.', 'co2' => 1.85, 'icon' => 'fa-hammer', 'color' => 'bg-slate-600'],
    ['item_id' => 9, 'category' => 'แก้ว', 'name' => 'ขวดเบียร์กล่อง (ขวดดี)', 'price' => 12.00, 'unit' => 'กล่อง', 'co2' => 0.65, 'icon' => 'fa-wine-bottle', 'color' => 'bg-purple-500'],
    ['item_id' => 10, 'category' => 'แก้ว', 'name' => 'ขวดแก้วรวม/เศษแก้ว', 'price' => 1.00, 'unit' => 'กก.', 'co2' => 0.52, 'icon' => 'fa-champagne-glasses', 'color' => 'bg-purple-400'],
    ['item_id' => 11, 'category' => 'E-Waste', 'name' => 'สายไฟเก่าทองแดง', 'price' => 40.00, 'unit' => 'กก.', 'co2' => 3.20, 'icon' => 'fa-bolt', 'color' => 'bg-rose-500'],
    ['item_id' => 12, 'category' => 'E-Waste', 'name' => 'เมนบอร์ด/การ์ดคอม', 'price' => 80.00, 'unit' => 'กก.', 'co2' => 4.50, 'icon' => 'fa-microchip', 'color' => 'bg-red-600'],
];
?>
<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Station POS | <?= APP_NAME ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Kanit', sans-serif; }
        .digital-weight-display {
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 2px;
        }
        @media print {
            body * { visibility: hidden; }
            #thermal-print-area, #thermal-print-area * { visibility: visible; }
            #thermal-print-area { position: absolute; left: 0; top: 0; width: 80mm; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 h-full flex flex-col">

    <!-- Top POS Header -->
    <header class="bg-emerald-900 text-white px-4 py-3 shrink-0 shadow-md">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <a href="index.php" class="w-10 h-10 bg-emerald-800 hover:bg-emerald-700 rounded-xl flex items-center justify-center text-lg transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-base sm:text-xl font-bold font-heading leading-tight flex items-center gap-2">
                        <span>RBRU Station POS</span>
                        <span class="text-xs bg-amber-400 text-slate-900 px-2 py-0.5 rounded-full font-semibold">จุดรับฝากชั่งน้ำหนัก</span>
                    </h1>
                    <p class="text-xs text-emerald-200">
                        <i class="fa-solid fa-location-dot mr-1"></i>ศูนย์คัดแยกขยะกลาง (อาคาร 36 สำนักงานอธิการบดี) • จนท. นายสมคิด สิ่งแวดล้อมดี
                    </p>
                </div>
            </div>

            <!-- Hardware Web Serial Status Indicator -->
            <div class="flex items-center space-x-2">
                <div id="scale-status-badge" class="bg-emerald-950/70 border border-emerald-400/40 text-emerald-200 text-xs px-3 py-1.5 rounded-xl flex items-center gap-2">
                    <span id="scale-status-dot" class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                    <span id="scale-status-text">ตราชั่ง USB: ยังไม่เชื่อมต่อ</span>
                </div>
                <button id="btn-connect-scale" onclick="connectUsbScale()" class="bg-teal-600 hover:bg-teal-500 text-white text-xs font-semibold px-3 py-1.5 rounded-xl shadow transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plug"></i>
                    <span>เชื่อมต่อตราชั่ง USB</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Workspace Grid -->
    <div class="flex-grow max-w-7xl w-full mx-auto p-3 sm:p-4 grid grid-cols-1 lg:grid-cols-12 gap-4 overflow-hidden">

        <!-- LEFT PANEL: Member Lookup & Waste Item Selector (col 7) -->
        <div class="lg:col-span-7 flex flex-col space-y-3 overflow-y-auto">
            
            <!-- 1. Member Scan / Search Card -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase">ขั้นตอนที่ 1: สแกนหรือค้นหาสมาชิก</span>
                    <span class="text-xs text-emerald-700 font-medium">
                        <i class="fa-brands fa-line text-emerald-500 mr-1"></i>ระบบผูก LINE E-Slip อัตโนมัติ
                    </span>
                </div>

                <div class="flex gap-2">
                    <div class="relative flex-grow">
                        <input type="text" id="memberInput" value="6614401001" placeholder="สแกน QR หรือพิมพ์รหัส นศ. / รหัสบุคลากร" 
                               class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-heading"
                               onkeydown="if(event.key==='Enter') lookupMember()">
                        <i class="fa-solid fa-qrcode absolute left-3.5 top-3.5 text-slate-400"></i>
                    </div>
                    <button onclick="lookupMember()" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition font-heading shrink-0">
                        ค้นหา
                    </button>
                    <button onclick="openCameraScanner()" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3 py-2.5 rounded-xl transition font-heading flex items-center gap-1.5 shrink-0 shadow-sm">
                        <i class="fa-solid fa-camera"></i>
                        <span class="hidden sm:inline">เปิดกล้องสแกน</span>
                    </button>
                </div>

                <!-- Member Verified Banner -->
                <div id="memberCard" class="mt-3 p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <h4 id="memName" class="text-sm font-bold text-slate-900 font-heading">นางสาวรักดี มีวินัย (6614401001)</h4>
                            <p id="memDept" class="text-xs text-slate-500">คณะวิทยาศาสตร์และเทคโนโลยี • ยอดคงเหลือ: <strong class="text-emerald-700">345.50 ฿</strong></p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-emerald-200 text-emerald-900 text-[10px] font-bold rounded-md flex items-center gap-1">
                        <i class="fa-brands fa-line text-emerald-800"></i> ผูก LINE แล้ว
                    </span>
                </div>
            </div>

            <!-- 2. Dual-Mode Weight Acquisition Station -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase">ขั้นตอนที่ 2: ชั่งน้ำหนัก (2 ระบบในตัว)</span>
                    <span class="text-xs text-slate-400">Mode A: คีย์มือ (ระยะแรก) | Mode B: Web Serial USB</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    
                    <!-- Live Weight Screen Display -->
                    <div class="sm:col-span-7 bg-slate-900 text-emerald-400 rounded-2xl p-4 flex flex-col justify-between shadow-inner border border-slate-800">
                        <div class="flex justify-between items-center text-xs text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                REAL-TIME SCALE DISPLAY
                            </span>
                            <span id="weight-unit">UNIT: KG</span>
                        </div>
                        <div class="text-center my-2">
                            <span id="liveWeightDisplay" class="digital-weight-display text-4xl sm:text-5xl font-black text-emerald-300">0.000</span>
                            <span class="text-lg font-bold text-emerald-500 ml-1">กก.</span>
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-500">
                            <span>STABLE: YES</span>
                            <span>TARE: 0.000</span>
                        </div>
                    </div>

                    <!-- Manual Key-In Controls (ระยะแรก) -->
                    <div class="sm:col-span-5 space-y-2">
                        <label class="block text-xs font-semibold text-slate-700">คีย์น้ำหนักโดยเจ้าหน้าที่ (กก.):</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.05" id="manualWeightInput" value="3.50" 
                                   class="w-full px-3 py-2 text-lg font-bold text-slate-900 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 font-heading text-center"
                                   oninput="syncManualWeight(this.value)">
                            <button onclick="setWeightToBasket()" class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-3 py-2 rounded-xl text-xs font-heading shrink-0">
                                ใช้ค่าน้ำหนักนี้
                            </button>
                        </div>
                        <div class="grid grid-cols-3 gap-1 pt-1">
                            <button onclick="addManualWeight(1)" class="bg-slate-100 hover:bg-slate-200 py-1 rounded text-xs font-semibold">+1 กก.</button>
                            <button onclick="addManualWeight(5)" class="bg-slate-100 hover:bg-slate-200 py-1 rounded text-xs font-semibold">+5 กก.</button>
                            <button onclick="clearWeight()" class="bg-rose-50 text-rose-700 hover:bg-rose-100 py-1 rounded text-xs font-semibold">Zero/Tare</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Waste Item Selector Grid -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex-grow">
                <span class="text-xs font-bold text-slate-500 uppercase block mb-2">ขั้นตอนที่ 3: เลือกประเภทขยะลงบิล</span>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                    <?php foreach ($wasteItems as $w): ?>
                    <button onclick="addItemToBasket(<?= htmlspecialchars(json_encode($w)) ?>)" 
                            class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition text-left flex flex-col justify-between active:scale-95 shadow-sm">
                        <div class="flex items-center space-x-2 mb-1.5">
                            <div class="w-7 h-7 rounded-lg <?= $w['color'] ?> text-white flex items-center justify-center text-xs">
                                <i class="fa-solid <?= $w['icon'] ?>"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800 line-clamp-1 font-heading"><?= $w['name'] ?></span>
                        </div>
                        <div class="flex justify-between items-baseline pt-1 border-t border-slate-100">
                            <span class="text-xs font-bold text-emerald-800 font-heading"><?= number_format($w['price'], 2) ?> ฿</span>
                            <span class="text-[10px] text-slate-400"><?= $w['unit'] ?></span>
                        </div>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: Current Bill / Basket & Final Checkout (col 5) -->
        <div class="lg:col-span-5 flex flex-col bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 shrink-0">
                <div>
                    <h2 class="text-base sm:text-lg font-bold font-heading text-slate-900">รายการขยะในรอบนี้ (Current Basket)</h2>
                    <p class="text-xs text-slate-400">ตรวจสอบรายการก่อนกดยืนยันบันทึก</p>
                </div>
                <button onclick="clearBasket()" class="text-xs text-rose-600 hover:text-rose-800 font-medium">
                    <i class="fa-solid fa-trash-can mr-1"></i>ล้างทั้งหมด
                </button>
            </div>

            <!-- Basket Items Table -->
            <div id="basketList" class="flex-grow overflow-y-auto divide-y divide-slate-100 py-2 min-h-[220px]">
                <!-- Dynamic Items Inserted via JS -->
                <div id="emptyBasketMsg" class="py-12 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-scale-unbalanced text-3xl mb-2 text-slate-300 block"></i>
                    ยังไม่มีรายการขยะในรอบนี้ <br> กรุณาเลือกประเภทขยะและใส่น้ำหนัก
                </div>
            </div>

            <!-- Summary Totals Card -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 space-y-2 shrink-0 mt-3">
                <div class="flex justify-between text-xs text-slate-600">
                    <span>น้ำหนักขยะรวม:</span>
                    <span id="sumWeight" class="font-bold text-slate-900">0.00 กก.</span>
                </div>
                <div class="flex justify-between text-xs text-teal-700">
                    <span>ลดก๊าซเรือนกระจกสะสม:</span>
                    <span id="sumCo2" class="font-bold">0.00 kgCO₂eq</span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200 font-heading">
                    <span>ยอดเงินสุทธิที่สมาชิกได้รับ:</span>
                    <span id="sumAmount" class="text-emerald-700 text-xl font-bold">0.00 ฿</span>
                </div>
            </div>

            <!-- Checkout Action Buttons -->
            <div class="mt-4 pt-3 border-t border-slate-100 space-y-2 shrink-0">
                <button onclick="submitDeposit()" id="btnSubmitDeposit" class="w-full bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white font-bold py-3.5 rounded-xl shadow-lg transition flex items-center justify-center gap-2 font-heading text-sm">
                    <i class="fa-brands fa-line text-lg text-emerald-300"></i>
                    <span>บันทึกและส่งสลิปเข้า LINE สมาชิกทันที</span>
                </button>
                <div class="flex gap-2">
                    <button onclick="printThermalReceipt()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 rounded-xl text-xs transition flex items-center justify-center gap-1.5 font-heading">
                        <i class="fa-solid fa-print"></i>
                        <span>พิมพ์สลิปกระดาษ (Thermal)</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL: SUCCESSFUL DEPOSIT & LINE E-SLIP PREVIEW -->
    <div id="successModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl relative">
            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl mx-auto mb-3">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="text-xl font-bold font-heading text-slate-900">บันทึกการฝากขยะสำเร็จ!</h3>
            <p id="succMessage" class="text-xs text-slate-500 mt-1">ส่งสลิปดิจิทัลเข้า LINE ของนักศึกษาเรียบร้อยแล้ว</p>

            <div class="my-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-left text-xs space-y-1.5">
                <div class="flex justify-between"><span class="text-slate-500">เลขที่ธุรกรรม:</span><span id="resTxNo" class="font-mono font-bold text-slate-800"></span></div>
                <div class="flex justify-between"><span class="text-slate-500">ผู้ฝาก:</span><span id="resMemName" class="font-bold text-slate-800"></span></div>
                <div class="flex justify-between"><span class="text-slate-500">ยอดเงินรอบนี้:</span><span id="resAmount" class="font-bold text-emerald-700"></span></div>
                <div class="flex justify-between"><span class="text-slate-500">ลด CO₂:</span><span id="resCo2" class="font-bold text-teal-700"></span></div>
                <div class="flex justify-between border-t border-emerald-200 pt-1.5"><span class="text-slate-500">ยอดคงเหลือสะสมใหม่:</span><span id="resNewBalance" class="font-extrabold text-slate-900"></span></div>
            </div>

            <div class="flex gap-2">
                <button onclick="printThermalReceipt()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-xs font-heading">
                    <i class="fa-solid fa-print mr-1"></i>พิมพ์สลิป
                </button>
                <button onclick="resetPOS()" class="flex-1 bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-2.5 rounded-xl text-xs font-heading">
                    ทำรายการถัดไป
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: CAMERA QR SCANNER (สแกนผ่านกล้องแท็บเล็ต/มือถือโดยตรง) -->
    <div id="cameraModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 text-center shadow-2xl relative">
            <button onclick="closeCameraScanner()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-800 text-xs font-semibold mb-3">
                <i class="fa-solid fa-camera"></i> กล้องสแกน QR Code สมาชิก
            </div>
            <div class="relative bg-black rounded-2xl overflow-hidden aspect-square mb-3 flex items-center justify-center border-2 border-dashed border-indigo-400">
                <video id="cameraVideo" autoplay playsinline class="w-full h-full object-cover"></video>
                <div class="absolute inset-8 border-2 border-emerald-400 rounded-xl pointer-events-none animate-pulse"></div>
            </div>
            <p class="text-xs text-slate-500 mb-3">ส่องกรอบกล้องไปที่ Virtual QR บนหน้าจอมือถือของนักศึกษา</p>
            <button onclick="simulateCameraScan()" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 rounded-xl text-xs font-heading">
                จำลองผลการสแกนสำเร็จ (Test Scan)
            </button>
        </div>
    </div>

    <!-- HIDDEN THERMAL RECEIPT AREA FOR PRINTING (58mm/80mm) -->
    <div id="thermal-print-area" class="hidden">
        <div style="font-family: monospace; font-size: 12px; line-height: 1.4; padding: 10px; width: 72mm;">
            <center>
                <strong>มหาวิทยาลัยราชภัฏรำไพพรรณี</strong><br>
                <strong>ธนาคารขยะรีไซเคิล (RBRU Waste Bank)</strong><br>
                ศูนย์คัดแยกขยะกลาง (อาคาร 36)<br>
                --------------------------------
            </center>
            <div id="p-txno">TX: -</div>
            <div id="p-date">วันที่: -</div>
            <div id="p-member">สมาชิก: -</div>
            --------------------------------<br>
            <div id="p-items"></div>
            --------------------------------<br>
            <div style="display: flex; justify-content: space-between;">
                <strong>ยอดเงินรอบนี้:</strong>
                <strong id="p-amount">0.00 ฿</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>ลด CO2:</span>
                <span id="p-co2">0.00 kgCO2eq</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>ยอดเงินคงเหลือ:</span>
                <span id="p-balance">0.00 ฿</span>
            </div>
            --------------------------------<br>
            <center>
                ขอบคุณที่ร่วมรักษาความสะอาดและสิ่งแวดล้อม<br>
                ตรวจสมุดเงินฝากออนไลน์ได้ที่ RBRU Waste Bank
            </center>
        </div>
    </div>

    <!-- JAVASCRIPT: WEB SERIAL API & POS ENGINE -->
    <script>
        let currentWeight = 3.50;
        let serialPort = null;
        let serialReader = null;
        let basket = [];

        // 1. Web Serial API for Digital Scale (เตรียมการฮาร์ดแวร์จริง)
        async function connectUsbScale() {
            if (!("serial" in navigator)) {
                alert("เบราว์เซอร์นี้ยังไม่รองรับ Web Serial API (แนะนำให้ใช้ Google Chrome หรือ Microsoft Edge)");
                return;
            }

            try {
                // Request user to select USB/Serial port
                serialPort = await navigator.serial.requestPort();
                await serialPort.open({ baudRate: 9600 });

                document.getElementById('scale-status-dot').className = "w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse";
                document.getElementById('scale-status-text').innerText = "ตราชั่ง USB: เชื่อมต่อแล้ว (9600 bps)";
                document.getElementById('btn-connect-scale').innerText = "ตัดการเชื่อมต่อ";
                document.getElementById('btn-connect-scale').onclick = disconnectUsbScale;

                readSerialData();
            } catch (err) {
                console.error("Web Serial error:", err);
                alert("ไม่สามารถเชื่อมต่อตราชั่งได้: " + err.message);
            }
        }

        async function disconnectUsbScale() {
            if (serialReader) {
                await serialReader.cancel();
            }
            if (serialPort) {
                await serialPort.close();
            }
            serialPort = null;
            document.getElementById('scale-status-dot').className = "w-2.5 h-2.5 rounded-full bg-slate-400";
            document.getElementById('scale-status-text').innerText = "ตราชั่ง USB: ยังไม่เชื่อมต่อ";
            document.getElementById('btn-connect-scale').innerText = "เชื่อมต่อตราชั่ง USB";
            document.getElementById('btn-connect-scale').onclick = connectUsbScale;
        }

        // Loop to read ASCII weight data from Digital Scale
        async function readSerialData() {
            const textDecoder = new TextDecoderStream();
            const readableStreamClosed = serialPort.readable.pipeTo(textDecoder.writable);
            serialReader = textDecoder.readable.getReader();

            let buffer = "";
            try {
                while (true) {
                    const { value, done } = await serialReader.read();
                    if (done) break;
                    if (value) {
                        buffer += value;
                        const lines = buffer.split("\n");
                        buffer = lines.pop(); // keep partial line

                        for (const line of lines) {
                            parseScaleOutput(line);
                        }
                    }
                }
            } catch (err) {
                console.warn("Serial read loop closed:", err);
            }
        }

        // Parse scale output (e.g. "+ 003.50 kg" or "ST,GS,003.50")
        function parseScaleOutput(rawText) {
            // Regex to find decimal numbers in scale string
            const match = rawText.match(/[-+]?\s*(\d+\.?\d*)/);
            if (match && match[1]) {
                const wt = parseFloat(match[1]);
                if (!isNaN(wt) && wt >= 0) {
                    currentWeight = wt;
                    updateLiveWeightUI(wt);
                }
            }
        }

        // 2. Manual Controls (ระยะแรก)
        function syncManualWeight(val) {
            currentWeight = parseFloat(val) || 0;
            updateLiveWeightUI(currentWeight);
        }

        function addManualWeight(delta) {
            currentWeight = Math.max(0, currentWeight + delta);
            document.getElementById('manualWeightInput').value = currentWeight.toFixed(2);
            updateLiveWeightUI(currentWeight);
        }

        function clearWeight() {
            currentWeight = 0.00;
            document.getElementById('manualWeightInput').value = "0.00";
            updateLiveWeightUI(0);
        }

        function updateLiveWeightUI(wt) {
            document.getElementById('liveWeightDisplay').innerText = wt.toFixed(3);
        }

        function setWeightToBasket() {
            alert("ค่าน้ำหนัก " + currentWeight.toFixed(2) + " กก. พร้อมนำไปคำนวณกับประเภทขยะที่เลือก");
        }

        // 3. Basket & Cart Engine
        function addItemToBasket(item) {
            if (currentWeight <= 0) {
                alert("กรุณาใส่น้ำหนักขยะก่อนกดเลือกรายการ (น้ำหนักต้องมากกว่า 0)");
                return;
            }

            const subtotal = currentWeight * item.price;
            const subCo2 = currentWeight * item.co2;

            basket.push({
                item_id: item.item_id,
                name: item.name,
                weight: currentWeight,
                unit_price: item.price,
                amount: subtotal,
                co2_factor: item.co2,
                sub_co2: subCo2
            });

            renderBasket();
        }

        function removeBasketItem(index) {
            basket.splice(index, 1);
            renderBasket();
        }

        function clearBasket() {
            basket = [];
            renderBasket();
        }

        function renderBasket() {
            const list = document.getElementById('basketList');
            if (basket.length === 0) {
                list.innerHTML = `
                    <div id="emptyBasketMsg" class="py-12 text-center text-slate-400 text-xs">
                        <i class="fa-solid fa-scale-unbalanced text-3xl mb-2 text-slate-300 block"></i>
                        ยังไม่มีรายการขยะในรอบนี้ <br> กรุณาเลือกประเภทขยะและใส่น้ำหนัก
                    </div>`;
                document.getElementById('sumWeight').innerText = '0.00 กก.';
                document.getElementById('sumCo2').innerText = '0.00 kgCO₂eq';
                document.getElementById('sumAmount').innerText = '0.00 ฿';
                return;
            }

            let html = '';
            let totalWeight = 0;
            let totalAmount = 0;
            let totalCo2 = 0;

            basket.forEach((item, idx) => {
                totalWeight += item.weight;
                totalAmount += item.amount;
                totalCo2 += item.sub_co2;

                html += `
                <div class="py-2.5 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 font-heading">${item.name}</h4>
                        <span class="text-[11px] text-slate-500">${item.weight.toFixed(2)} กก. × ${item.unit_price.toFixed(2)} ฿</span>
                        <span class="text-[10px] text-teal-700 ml-2">(ลด CO₂: ${item.sub_co2.toFixed(2)} kg)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs font-bold text-emerald-800 font-heading">${item.amount.toFixed(2)} ฿</span>
                        <button onclick="removeBasketItem(${idx})" class="text-slate-300 hover:text-rose-600 p-1">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>
                </div>`;
            });

            list.innerHTML = html;
            document.getElementById('sumWeight').innerText = totalWeight.toFixed(2) + ' กก.';
            document.getElementById('sumCo2').innerText = totalCo2.toFixed(2) + ' kgCO₂eq';
            document.getElementById('sumAmount').innerText = totalAmount.toFixed(2) + ' ฿';
        }

        // 4. Submit to API & Trigger LINE Slip
        async function submitDeposit() {
            if (basket.length === 0) {
                alert("กรุณาเพิ่มรายการขยะลงบิลก่อนกดบันทึก");
                return;
            }

            const btn = document.getElementById('btnSubmitDeposit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>กำลังบันทึกและส่ง LINE...';

            const payload = {
                member_code: document.getElementById('memberInput').value,
                member_name: 'นางสาวรักดี มีวินัย',
                station_name: 'ศูนย์คัดแยกขยะกลาง (อาคาร 36)',
                officer_name: 'นายสมคิด สิ่งแวดล้อมดี',
                items: basket
            };

            try {
                const response = await fetch('api/deposit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const res = await response.json();

                if (res.success) {
                    showSuccessModal(res.receipt);
                } else {
                    alert('เกิดข้อผิดพลาด: ' + (res.error || 'ไม่สามารถบันทึกได้'));
                }
            } catch (err) {
                console.error("Submit error:", err);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ API: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-brands fa-line text-lg text-emerald-300"></i><span>บันทึกและส่งสลิปเข้า LINE สมาชิกทันที</span>';
            }
        }

        let lastReceipt = null;

        function showSuccessModal(rc) {
            playPosBeep('success');
            triggerHaptic();
            lastReceipt = rc;
            document.getElementById('resTxNo').innerText = rc.transaction_no;
            document.getElementById('resMemName').innerText = rc.member_name;
            document.getElementById('resAmount').innerText = '+' + rc.total_amount.toFixed(2) + ' ฿';
            document.getElementById('resCo2').innerText = '+' + rc.total_co2.toFixed(2) + ' kgCO₂eq';
            document.getElementById('resNewBalance').innerText = '฿ ' + rc.current_balance.toFixed(2);
            document.getElementById('successModal').classList.remove('hidden');

            // Populate thermal print
            document.getElementById('p-txno').innerText = 'TX: ' + rc.transaction_no;
            document.getElementById('p-date').innerText = 'วันที่: ' + rc.transaction_date;
            document.getElementById('p-member').innerText = 'สมาชิก: ' + rc.member_name + ' (' + rc.member_code + ')';
            document.getElementById('p-amount').innerText = '+' + rc.total_amount.toFixed(2) + ' ฿';
            document.getElementById('p-co2').innerText = '+' + rc.total_co2.toFixed(2) + ' kgCO2eq';
            document.getElementById('p-balance').innerText = '฿ ' + rc.current_balance.toFixed(2);

            let itemHtml = '';
            rc.items.forEach(it => {
                itemHtml += `<div>• ${it.name} (${it.weight} กก.) = ${it.amount.toFixed(2)} ฿</div>`;
            });
            document.getElementById('p-items').innerHTML = itemHtml;
        }

        function printThermalReceipt() {
            window.print();
        }

        function resetPOS() {
            document.getElementById('successModal').classList.add('hidden');
            clearBasket();
            clearWeight();
        }

        function lookupMember() {
            playPosBeep('scan');
            triggerHaptic();
            alert('ตรวจสอบพบสมาชิก: นางสาวรักดี มีวินัย (6614401001) พร้อมทำรายการ');
        }

        // Web Audio Synthesizer for High-Tech POS Cues
        function playPosBeep(type = 'scan') {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                if (type === 'scan') {
                    // Quick crisp beep for barcode scan
                    osc.frequency.setValueAtTime(1750, ctx.currentTime);
                    gain.gain.setValueAtTime(0.15, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.08);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.08);
                } else if (type === 'success') {
                    // Double pleasant chime for transaction completion
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.setValueAtTime(1320, ctx.currentTime + 0.1);
                    gain.gain.setValueAtTime(0.2, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.3);
                }
            } catch (e) {
                console.warn('Web Audio not allowed or supported:', e);
            }
        }

        // Tactile Haptic Vibration for Mobile Devices
        function triggerHaptic() {
            if ("vibrate" in navigator) {
                navigator.vibrate([60, 40, 60]);
            }
        }

        // Camera QR Scanner Functions
        let cameraStream = null;

        async function openCameraScanner() {
            document.getElementById('cameraModal').classList.remove('hidden');
            const video = document.getElementById('cameraVideo');
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' }
                });
                video.srcObject = cameraStream;
            } catch (err) {
                console.warn('Camera access denied or unavailable:', err);
            }
        }

        function closeCameraScanner() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            document.getElementById('cameraModal').classList.add('hidden');
        }

        function simulateCameraScan() {
            closeCameraScanner();
            playPosBeep('scan');
            triggerHaptic();
            document.getElementById('memberInput').value = "6614401001";
            lookupMember();
        }
    </script>
</body>
</html>

