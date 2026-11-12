<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

// Sample fallback data if DB is not yet imported
$wasteCategories = [
    ['icon' => 'fa-file-alt', 'name' => 'กลุ่มกระดาษ', 'desc' => 'กระดาษขาวดำ ลังลูกฟูก หนังสือพิมพ์', 'color' => 'bg-blue-50 text-blue-700 border-blue-200', 'price' => '2.50 - 4.50 ฿/กก.'],
    ['icon' => 'fa-bottle-water', 'name' => 'กลุ่มพลาสติก', 'desc' => 'ขวด PET ใส แกลลอน HDPE ถุงสะอาด', 'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'price' => '2.00 - 9.00 ฿/กก.'],
    ['icon' => 'fa-can-food', 'name' => 'กลุ่มโลหะ/อลูมิเนียม', 'desc' => 'กระป๋องเครื่องดื่ม เศษเหล็ก สังกะสี', 'color' => 'bg-amber-50 text-amber-700 border-amber-200', 'price' => '3.50 - 35.00 ฿/กก.'],
    ['icon' => 'fa-wine-bottle', 'name' => 'กลุ่มแก้ว', 'desc' => 'ขวดเบียร์กล่อง ขวดแก้วรวม เศษแก้ว', 'color' => 'bg-purple-50 text-purple-700 border-purple-200', 'price' => '1.00 - 12.00 ฿/กล่อง'],
    ['icon' => 'fa-laptop-code', 'name' => 'ขยะอิเล็กทรอนิกส์', 'desc' => 'สายไฟทองแดง อุปกรณ์คอม แบตเตอรี่', 'color' => 'bg-rose-50 text-rose-700 border-rose-200', 'price' => '40.00 - 80.00 ฿/กก.'],
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> | <?= APP_UNIVERSITY ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Sarabun & Kanit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Kanit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="bg-emerald-800 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center text-2xl shadow-inner border border-emerald-400">
                        <i class="fa-solid fa-recycle text-emerald-100"></i>
                    </div>
                    <div>
                        <span class="text-xs font-light text-emerald-200 tracking-wider uppercase block">RBRU Green Campus</span>
                        <a href="index.php" class="text-xl sm:text-2xl font-bold tracking-tight text-white font-heading hover:text-emerald-200 transition">
                            <?= APP_NAME ?>
                        </a>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="#about" class="text-emerald-100 hover:text-white transition">เกี่ยวกับโครงการ</a>
                    <a href="#prices" class="text-emerald-100 hover:text-white transition">ราคารับฝากวันนี้</a>
                    <a href="#metrics" class="text-emerald-100 hover:text-white transition">สถิติ UI GreenMetric</a>
                    <a href="#stations" class="text-emerald-100 hover:text-white transition">จุดบริการ</a>
                    <a href="#portal" class="bg-amber-400 hover:bg-amber-300 text-slate-900 px-5 py-2.5 rounded-lg font-semibold shadow-md transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i>เข้าสู่ระบบ
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-900 text-white py-16 lg:py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-900/60 border border-emerald-400/30 text-emerald-200 text-xs font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        ต้นแบบเทียบเคียง MU Recyclable สู่มาตรฐานสากล
                    </div>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold font-heading leading-tight tracking-tight">
                        เปลี่ยนขยะเป็นเงินออม <br>
                        <span class="text-amber-300">สร้างคุณค่าสู่มหาวิทยาลัยสีเขียว</span>
                    </h1>
                    <p class="text-lg text-emerald-100 max-w-2xl font-light leading-relaxed">
                        ระบบบริหารจัดการธนาคารขยะรีไซเคิล มหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU Recyclable Waste Bank Management) ฝากขยะ ชั่งน้ำหนัก สะสมเงิน และคำนวณการลดการปล่อยก๊าซเรือนกระจก (CO<sub>2</sub> Offset) ตอบรับเกณฑ์ UI GreenMetric ระดับโลก
                    </p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        <a href="#portal" class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-8 py-3.5 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 font-heading">
                            <i class="fa-solid fa-wallet mr-2"></i>เปิดสมุดบัญชีเงินฝาก
                        </a>
                        <a href="#prices" class="bg-emerald-900/70 hover:bg-emerald-900 text-white font-medium px-6 py-3.5 rounded-xl border border-emerald-400/40 shadow-md transition font-heading">
                            <i class="fa-solid fa-tags mr-2"></i>ตรวจราคารับซื้อประจำวัน
                        </a>
                    </div>
                </div>

                <!-- Live Metrics Card -->
                <div class="lg:col-span-5">
                    <div class="glass-card text-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="font-bold text-lg text-slate-900 font-heading">สถิติความยั่งยืนสะสม</h3>
                                <p class="text-xs text-slate-500">RBRU Green Campus Metric</p>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-md">Real-Time</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="p-4 bg-emerald-50/70 rounded-2xl border border-emerald-100">
                                <span class="text-xs text-emerald-700 font-medium block">ขยะรีไซเคิลสะสม</span>
                                <span class="text-2xl font-bold font-heading text-emerald-900">1,248.5</span>
                                <span class="text-xs text-emerald-600 block">กิโลกรัม (กก.)</span>
                            </div>

                            <div class="p-4 bg-teal-50/70 rounded-2xl border border-teal-100">
                                <span class="text-xs text-teal-700 font-medium block">ลดการปล่อยก๊าซเรือนกระจก</span>
                                <span class="text-2xl font-bold font-heading text-teal-900">1,894.2</span>
                                <span class="text-xs text-teal-600 block">kgCO<sub>2</sub>eq</span>
                            </div>

                            <div class="p-4 bg-amber-50/70 rounded-2xl border border-amber-100">
                                <span class="text-xs text-amber-700 font-medium block">มูลค่าเงินหมุนเวียน</span>
                                <span class="text-2xl font-bold font-heading text-amber-900">12,450</span>
                                <span class="text-xs text-amber-600 block">บาท (THB)</span>
                            </div>

                            <div class="p-4 bg-indigo-50/70 rounded-2xl border border-indigo-100">
                                <span class="text-xs text-indigo-700 font-medium block">สมาชิกผู้ร่วมโครงการ</span>
                                <span class="text-2xl font-bold font-heading text-indigo-900">342</span>
                                <span class="text-xs text-indigo-600 block">บัญชี (คน/หน่วยงาน)</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                            <p class="text-xs text-slate-500">
                                <i class="fa-solid fa-tree text-emerald-600 mr-1"></i> เทียบเท่าการปลูกต้นไม้ทดแทน <strong>198</strong> ต้น
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Waste Pricing & Categories Section -->
    <section id="prices" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-emerald-600 font-semibold text-sm uppercase tracking-wider">Dynamic Pricing</span>
                <h2 class="text-3xl font-bold text-slate-900 font-heading mt-1">ราคารับฝากขยะรีไซเคิลประจำวัน</h2>
                <p class="text-slate-600 mt-2">โปร่งใส ตรวจสอบได้ อ้างอิงราคาตลาดรีไซเคิล พร้อมคำนวณอัตราลดก๊าซเรือนกระจก</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <?php foreach ($wasteCategories as $cat): ?>
                <div class="rounded-2xl p-6 border <?= $cat['color'] ?> shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid <?= $cat['icon'] ?>"></i>
                    </div>
                    <h3 class="font-bold text-lg font-heading text-slate-900 mb-1"><?= $cat['name'] ?></h3>
                    <p class="text-xs text-slate-500 mb-4 h-8"><?= $cat['desc'] ?></p>
                    <div class="pt-3 border-t border-slate-200/60">
                        <span class="text-[11px] text-slate-400 block">ราคารับฝากประมาณ</span>
                        <span class="text-base font-bold text-slate-900 font-heading"><?= $cat['price'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Portal Entry Switcher -->
    <section id="portal" class="py-16 bg-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-emerald-600 font-semibold text-sm uppercase tracking-wider">User Portals</span>
                <h2 class="text-3xl font-bold text-slate-900 font-heading mt-1">เลือกระบบเข้าใช้งานตามบทบาท</h2>
                <p class="text-slate-600 mt-2">รองรับสมาชิกบุคคล สมาชิกหน่วยงาน และเจ้าหน้าที่จุดรับฝาก</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Portal 1: Members -->
                <div class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mb-6">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading mb-2">Member Portal (สมาชิก)</h3>
                        <p class="text-sm text-slate-600 mb-6">สำหรับนักศึกษา บุคลากร และหน่วยงาน ตรวจสอบยอดเงินสะสม สมุดบัญชี Statement แบบ Drill-down และแสดงบัตร Virtual QR</p>
                    </div>
                    <a href="login.php?role=member" class="w-full text-center bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-3 rounded-xl transition font-heading">
                        เข้าสู่ระบบสมาชิก
                    </a>
                </div>

                <!-- Portal 2: Station Officers -->
                <div class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl mb-6">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading mb-2">Station Weighing POS</h3>
                        <p class="text-sm text-slate-600 mb-6">สำหรับเจ้าหน้าที่ประจำจุดบริการรับฝาก สแกน QR สมาชิก บันทึกการชั่งขยะ คำนวณราคาอัตโนมัติ และพิมพ์ใบเสร็จ E-Slip</p>
                    </div>
                    <a href="login.php?role=officer" class="w-full text-center bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition font-heading">
                        เข้าสู่ระบบจุดรับฝาก
                    </a>
                </div>

                <!-- Portal 3: Admin & Analytics -->
                <div class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl mb-6">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 font-heading mb-2">Executive & GreenMetric</h3>
                        <p class="text-sm text-slate-600 mb-6">สำหรับผู้บริหารและคณะกรรมการสิ่งแวดล้อม ติดตามตัวชี้วัด UI GreenMetric รายงานคาร์บอนฟุตพริ้นท์ และงบดุลกองทุนธนาคารขยะ</p>
                    </div>
                    <a href="login.php?role=admin" class="w-full text-center bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-3 rounded-xl transition font-heading">
                        เข้าสู่ระบบผู้ดูแล
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-emerald-700 rounded-lg flex items-center justify-center text-white text-xl">
                        <i class="fa-solid fa-recycle"></i>
                    </div>
                    <div>
                        <span class="text-white font-bold block font-heading"><?= APP_NAME ?></span>
                        <span class="text-xs text-slate-500"><?= APP_UNIVERSITY ?></span>
                    </div>
                </div>
                <div class="text-xs text-slate-500 text-center md:text-right">
                    <p>ขับเคลื่อนตามเกณฑ์ UI GreenMetric World University Rankings (Category: Waste)</p>
                    <p class="mt-1">© 2026 <?= APP_UNIVERSITY ?>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

