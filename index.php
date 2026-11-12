<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

// Mock user session for demonstration (Default: Student Member)
$currentUser = [
    'name' => 'นางสาวรักดี มีวินัย',
    'code' => '6614401001',
    'faculty' => 'คณะวิทยาศาสตร์และเทคโนโลยี',
    'balance' => 345.50,
    'total_weight' => 38.60,
    'co2_reduced' => 56.42,
    'eco_points' => 380,
    'qr_token' => 'RBRU-QR-6614401001'
];

$wasteCategories = [
    [
        'id' => 1,
        'icon' => 'fa-file-alt', 
        'name' => 'กลุ่มกระดาษ (Paper)', 
        'desc' => 'กระดาษขาวดำ ลังลูกฟูก หนังสือพิมพ์ สมุดเก่า', 
        'color' => 'bg-blue-50 text-blue-700 border-blue-200', 
        'badge' => 'bg-blue-100 text-blue-800',
        'price' => '2.50 - 4.50',
        'unit' => '฿/กก.',
        'co2' => '1.34 kgCO₂eq'
    ],
    [
        'id' => 2,
        'icon' => 'fa-bottle-water', 
        'name' => 'กลุ่มพลาสติก (Plastic)', 
        'desc' => 'ขวด PET ใส แกลลอนนม HDPE ถุงพลาสติกสะอาด', 
        'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 
        'badge' => 'bg-emerald-100 text-emerald-800',
        'price' => '2.00 - 9.00',
        'unit' => '฿/กก.',
        'co2' => '1.55 kgCO₂eq'
    ],
    [
        'id' => 3,
        'icon' => 'fa-can-food', 
        'name' => 'กลุ่มโลหะและอลูมิเนียม', 
        'desc' => 'กระป๋องเครื่องดื่มอลูมิเนียม สังกะสี และเศษเหล็ก', 
        'color' => 'bg-amber-50 text-amber-700 border-amber-200', 
        'badge' => 'bg-amber-100 text-amber-800',
        'price' => '3.50 - 35.00',
        'unit' => '฿/กก.',
        'co2' => '9.13 kgCO₂eq'
    ],
    [
        'id' => 4,
        'icon' => 'fa-wine-bottle', 
        'name' => 'กลุ่มแก้ว (Glass)', 
        'desc' => 'ขวดเบียร์กล่อง ขวดแก้วรวม และเศษแก้วแตก', 
        'color' => 'bg-purple-50 text-purple-700 border-purple-200', 
        'badge' => 'bg-purple-100 text-purple-800',
        'price' => '1.00 - 12.00',
        'unit' => '฿/หน่วย',
        'co2' => '0.65 kgCO₂eq'
    ],
    [
        'id' => 5,
        'icon' => 'fa-laptop-code', 
        'name' => 'ขยะอิเล็กทรอนิกส์ (E-Waste)', 
        'desc' => 'สายไฟทองแดง แผงวงจร ตลับหมึก แบตเตอรี่เก่า', 
        'color' => 'bg-rose-50 text-rose-700 border-rose-200', 
        'badge' => 'bg-rose-100 text-rose-800',
        'price' => '40.00 - 80.00',
        'unit' => '฿/กก.',
        'co2' => '4.50 kgCO₂eq'
    ],
];

// Sample Transaction Ledger
$sampleTransactions = [
    [
        'date' => '12 พ.ย. 2569 • 11:20 น.',
        'station' => 'ศูนย์คัดแยกขยะกลาง (อาคาร 36)',
        'type' => 'ฝากขยะรีไซเคิล',
        'items' => 'ขวด PET 3.5 กก., ลังกระดาษ 5.0 กก.',
        'weight' => '8.50 กก.',
        'amount' => '+46.50 ฿',
        'co2' => '+11.02 kgCO₂eq',
        'is_deposit' => true
    ],
    [
        'date' => '05 พ.ย. 2569 • 14:15 น.',
        'station' => 'จุดบริการคณะวิทยาศาสตร์ฯ',
        'type' => 'ฝากขยะรีไซเคิล',
        'items' => 'กระป๋องอลูมิเนียม 1.2 กก., กระดาษขาวดำ 4.0 กก.',
        'weight' => '5.20 กก.',
        'amount' => '+60.00 ฿',
        'co2' => '+16.31 kgCO₂eq',
        'is_deposit' => true
    ],
    [
        'date' => '28 ต.ค. 2569 • 09:40 น.',
        'station' => 'ระบบถอนเงินออนไลน์',
        'type' => 'ถอนเงินเข้า PromptPay',
        'items' => 'โอนเข้า 089-876-xxxx สำเร็จ',
        'weight' => '-',
        'amount' => '-150.00 ฿',
        'co2' => '-',
        'is_deposit' => false
    ]
];
?>
<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title><?= APP_NAME ?> | <?= APP_UNIVERSITY ?></title>

    <!-- PWA & Mobile Web App Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#064e3b">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="RBRU Waste">
    <link rel="apple-touch-icon" href="assets/icons/icon-192.svg">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rbru: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts: Sarabun & Kanit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Sarabun', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        h1, h2, h3, h4, .font-heading { font-family: 'Kanit', sans-serif; }
        
        /* Glassmorphism Styling */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Virtual Card Metallic Hologram Gradient */
        .card-hologram {
            background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0f766e 100%);
            box-shadow: 0 16px 32px -8px rgba(6, 78, 59, 0.45);
        }

        /* Safe area inset for modern smartphones (iPhone Notch & Home Indicator) */
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 1rem); }
        .pt-safe { padding-top: env(safe-area-inset-top, 0.5rem); }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-emerald-500 selection:text-white">

    <!-- ========================================================================= -->
    <!-- DESKTOP / TABLET TOP NAVIGATION BAR                                      -->
    <!-- ========================================================================= -->
    <header class="bg-rbru-900 text-white shadow-lg sticky top-0 z-40 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner border border-emerald-400/40">
                        <i class="fa-solid fa-recycle text-emerald-100"></i>
                    </div>
                    <div>
                        <span class="text-xs font-light text-emerald-300 tracking-wider uppercase block">RBRU Green Campus • UI GreenMetric</span>
                        <a href="index.php" class="text-xl lg:text-2xl font-bold tracking-tight text-white font-heading hover:text-emerald-200 transition">
                            <?= APP_NAME ?>
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-6 text-sm font-medium">
                    <a href="#about" class="text-emerald-100 hover:text-white transition">เกี่ยวกับโครงการ</a>
                    <a href="#prices" class="text-emerald-100 hover:text-white transition">ราคารับฝากวันนี้</a>
                    <a href="#passbook" class="text-emerald-100 hover:text-white transition">สมุดบัญชีเงินฝาก</a>
                    <a href="station_pos.php" class="text-amber-300 hover:text-amber-200 font-semibold transition flex items-center gap-1.5 bg-emerald-950/60 px-3 py-1.5 rounded-lg border border-amber-400/30">
                        <i class="fa-solid fa-scale-balanced"></i>
                        <span>จุดรับฝาก POS (อาคาร 36)</span>
                    </a>
                    
                    <button onclick="openQrModal()" class="bg-amber-400 hover:bg-amber-300 text-slate-900 px-5 py-2.5 rounded-xl font-semibold shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>บัตรสมาชิก Virtual QR</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- MOBILE APP TOP BAR (เมื่อเปิดบนสมาร์ตโฟน)                                    -->
    <!-- ========================================================================= -->
    <div class="md:hidden bg-rbru-900 text-white px-4 pt-safe pb-4 sticky top-0 z-40 shadow-md">
        <div class="flex items-center justify-between pt-2">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center text-lg shadow-sm border border-emerald-400/30">
                    <i class="fa-solid fa-recycle text-white"></i>
                </div>
                <div>
                    <h1 class="font-heading font-bold text-base leading-tight">RBRU WasteBank</h1>
                    <p class="text-[10px] text-emerald-300 font-light">ม.ราชภัฏรำไพพรรณี</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button onclick="openQrModal()" class="bg-amber-400 text-slate-900 px-3 py-1.5 rounded-lg text-xs font-semibold shadow flex items-center gap-1.5 active:scale-95 transition">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>QR ของฉัน</span>
                </button>
            </div>
        </div>
    </div>

    <!-- PWA Install Banner for Mobile -->
    <div id="pwa-install-banner" class="hidden bg-emerald-800 text-white px-4 py-2.5 text-xs flex items-center justify-between border-b border-emerald-700 md:hidden">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-mobile-screen-button text-amber-300 text-base"></i>
            <span>ติดตั้ง RBRU Waste บนหน้าจอหลักเพื่อความรวดเร็ว</span>
        </div>
        <button id="pwa-install-btn" class="bg-white text-emerald-900 font-bold px-2.5 py-1 rounded text-[11px] shadow">ติดตั้ง</button>
    </div>

    <!-- ========================================================================= -->
    <!-- MAIN CONTENT AREA (Tab Switcher for Mobile App Mode & Full Web on Desktop)-->
    <!-- ========================================================================= -->
    <main class="flex-grow pb-24 md:pb-12">

        <!-- 1. TAB: HOME (หน้าหลัก) -->
        <section id="tab-home" class="tab-content">
            
            <!-- Mobile App Member Wallet Card (แสดงเมื่ออยู่บนมือถือ) -->
            <div class="md:hidden px-4 pt-4">
                <div class="card-hologram text-white rounded-3xl p-5 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 opacity-15 text-8xl">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <span class="text-xs text-emerald-200 block font-light">ยอดเงินคงเหลือสะสม</span>
                            <div class="text-3xl font-extrabold font-heading text-white tracking-tight mt-0.5">
                                ฿ <?= number_format($currentUser['balance'], 2) ?>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-white/20 backdrop-blur rounded-full text-[11px] font-medium text-emerald-100 flex items-center gap-1">
                            <i class="fa-solid fa-coins text-amber-300"></i> <?= $currentUser['eco_points'] ?> แต้ม
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-emerald-500/40 text-xs">
                        <div>
                            <span class="text-emerald-200 text-[11px] block">น้ำหนักขยะสะสม</span>
                            <span class="font-bold text-white"><?= number_format($currentUser['total_weight'], 1) ?> กก.</span>
                        </div>
                        <div>
                            <span class="text-emerald-200 text-[11px] block">ลดก๊าซเรือนกระจก</span>
                            <span class="font-bold text-teal-200"><?= number_format($currentUser['co2_reduced'], 1) ?> kgCO₂eq</span>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button onclick="openQrModal()" class="flex-1 bg-amber-400 hover:bg-amber-300 active:scale-95 text-slate-900 text-xs font-bold py-2.5 rounded-xl text-center shadow-md transition font-heading">
                            <i class="fa-solid fa-qrcode mr-1"></i> ยื่นชั่งน้ำหนัก
                        </button>
                        <button onclick="switchTab('tab-passbook')" class="flex-1 bg-white/15 hover:bg-white/25 active:scale-95 text-white text-xs font-semibold py-2.5 rounded-xl text-center backdrop-blur transition font-heading border border-white/20">
                            <i class="fa-solid fa-file-invoice mr-1"></i> สมุดเงินฝาก
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Hero Section (แสดงบน Desktop และ Tablet) -->
            <section class="hidden md:block relative bg-gradient-to-br from-rbru-900 via-emerald-800 to-teal-950 text-white py-14 lg:py-20 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                        <div class="lg:col-span-7 space-y-5">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/70 border border-emerald-400/40 text-emerald-200 text-xs font-medium">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                ระบบ RBRU Recyclable Waste Bank Management พร้อมใช้งานจริง
                            </div>
                            <h1 class="text-3xl sm:text-5xl font-extrabold font-heading leading-tight tracking-tight">
                                เปลี่ยนขยะรีไซเคิลเป็นเงินออม <br>
                                <span class="text-amber-300">สร้างความยั่งยืนสู่มหาวิทยาลัยสีเขียว</span>
                            </h1>
                            <p class="text-base lg:text-lg text-emerald-100 max-w-2xl font-light">
                                ระบบบริหารจัดการธนาคารขยะรีไซเคิล มหาวิทยาลัยราชภัฏรำไพพรรณี เชื่อมโยงการชั่งน้ำหนัก สแกน Virtual QR สมุดเงินฝากออนไลน์ และคำนวณการลดก๊าซเรือนกระจก (CO₂ Offset) ตอบสนองเกณฑ์ UI GreenMetric World University Rankings
                            </p>
                            <div class="pt-2 flex flex-wrap gap-4">
                                <button onclick="openQrModal()" class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-7 py-3 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 font-heading flex items-center gap-2">
                                    <i class="fa-solid fa-qrcode text-lg"></i>
                                    <span>เปิดบัตร Virtual QR ประจำตัว</span>
                                </button>
                                <a href="#prices" class="bg-emerald-900/80 hover:bg-emerald-900 text-white font-medium px-6 py-3 rounded-xl border border-emerald-400/40 shadow-md transition font-heading">
                                    <i class="fa-solid fa-tags mr-2"></i>ตรวจราคารับฝากวันนี้
                                </a>
                            </div>
                        </div>

                        <!-- Desktop Live Dashboard Card -->
                        <div class="lg:col-span-5">
                            <div class="glass-card text-slate-800 rounded-3xl p-7 shadow-2xl border border-white/50">
                                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                    <div>
                                        <h3 class="font-bold text-lg text-slate-900 font-heading">ภาพรวมบัญชีของฉัน</h3>
                                        <p class="text-xs text-slate-500"><?= $currentUser['name'] ?> (<?= $currentUser['code'] ?>)</p>
                                    </div>
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-md">บัญชีใช้งานได้</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3.5 mt-5">
                                    <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-100">
                                        <span class="text-xs text-emerald-700 font-medium block">ยอดเงินคงเหลือสะสม</span>
                                        <span class="text-2xl font-bold font-heading text-emerald-900">฿ <?= number_format($currentUser['balance'], 2) ?></span>
                                    </div>
                                    <div class="p-3.5 bg-teal-50 rounded-2xl border border-teal-100">
                                        <span class="text-xs text-teal-700 font-medium block">ลดการปล่อย CO₂</span>
                                        <span class="text-2xl font-bold font-heading text-teal-900"><?= number_format($currentUser['co2_reduced'], 1) ?></span>
                                        <span class="text-[10px] text-teal-600 block">kgCO₂eq</span>
                                    </div>
                                    <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-100">
                                        <span class="text-xs text-amber-700 font-medium block">ขยะที่นำฝากรวม</span>
                                        <span class="text-2xl font-bold font-heading text-amber-900"><?= number_format($currentUser['total_weight'], 1) ?></span>
                                        <span class="text-[10px] text-amber-600 block">กิโลกรัม</span>
                                    </div>
                                    <div class="p-3.5 bg-indigo-50 rounded-2xl border border-indigo-100">
                                        <span class="text-xs text-indigo-700 font-medium block">กรีนพอยท์สะสม</span>
                                        <span class="text-2xl font-bold font-heading text-indigo-900"><?= $currentUser['eco_points'] ?></span>
                                        <span class="text-[10px] text-indigo-600 block">Eco-Points</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Service Stations Carousel / Cards -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg sm:text-2xl font-bold font-heading text-slate-900">จุดบริการรับฝากในมหาวิทยาลัย</h2>
                    <span class="text-xs text-emerald-700 font-medium cursor-pointer">ดูตารางบริการ <i class="fa-solid fa-arrow-right ml-1"></i></span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 text-lg">
                            <i class="fa-solid fa-warehouse"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-emerald-700 uppercase block">จุดบริการหลัก (Depot)</span>
                            <h3 class="font-bold text-sm text-slate-900 font-heading">ศูนย์คัดแยกขยะกลาง (อาคาร 36 สำนักงานอธิการบดี)</h3>
                            <p class="text-xs text-slate-500 mt-0.5">เปิดทุกวันทำการ 08.30 - 16.30 น. (ตราชั่งใหญ่ 150 กก.)</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 text-lg">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-amber-700 uppercase block">จุดบริการ นศ.</span>
                            <h3 class="font-bold text-sm text-slate-900 font-heading">กองพัฒนานักศึกษา (ชั้น 1)</h3>
                            <p class="text-xs text-slate-500 mt-0.5">เปิดพุธ-พฤหัสบดี 11.30 - 15.30 น.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0 text-lg">
                            <i class="fa-solid fa-flask"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-purple-700 uppercase block">จุดบริการคณะ</span>
                            <h3 class="font-bold text-sm text-slate-900 font-heading">ลานหน้าอาคาร 40 คณะวิทย์ฯ</h3>
                            <p class="text-xs text-slate-500 mt-0.5">เปิดวันศุกร์ 09.00 - 14.00 น.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Preview for Home Tab -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-lg sm:text-2xl font-bold font-heading text-slate-900">ราคารับฝากขยะยอดนิยม</h2>
                        <p class="text-xs text-slate-500">อัปเดตราคาตลาดรับซื้อประจำวัน</p>
                    </div>
                    <button onclick="switchTab('tab-prices')" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                        ดูราคาทั้งหมด (<?= count($wasteCategories) ?> หมวด) <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    <?php foreach ($wasteCategories as $cat): ?>
                    <div class="bg-white rounded-2xl p-3.5 shadow-sm border border-slate-200 hover:border-emerald-400 transition flex flex-col justify-between">
                        <div>
                            <div class="w-9 h-9 rounded-xl <?= $cat['badge'] ?> flex items-center justify-center text-base mb-2">
                                <i class="fa-solid <?= $cat['icon'] ?>"></i>
                            </div>
                            <h3 class="font-bold text-xs sm:text-sm text-slate-900 font-heading line-clamp-1"><?= $cat['name'] ?></h3>
                            <p class="text-[11px] text-slate-400 line-clamp-2 mt-0.5"><?= $cat['desc'] ?></p>
                        </div>
                        <div class="pt-2.5 mt-2 border-t border-slate-100 flex items-baseline justify-between">
                            <span class="text-xs font-bold text-emerald-800 font-heading"><?= $cat['price'] ?></span>
                            <span class="text-[10px] text-slate-500"><?= $cat['unit'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Activity List on Home -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg sm:text-2xl font-bold font-heading text-slate-900">การเคลื่อนไหวทางบัญชีล่าสุด</h2>
                    <button onclick="switchTab('tab-passbook')" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                        ดูสมุดบัญชีทั้งหมด
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 divide-y divide-slate-100 overflow-hidden">
                    <?php foreach (array_slice($sampleTransactions, 0, 2) as $tx): ?>
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition cursor-pointer" onclick="switchTab('tab-passbook')">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full <?= $tx['is_deposit'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?> flex items-center justify-center text-sm font-bold shrink-0">
                                <i class="fa-solid <?= $tx['is_deposit'] ? 'fa-arrow-down-long' : 'fa-arrow-up-long' ?>"></i>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 font-heading"><?= $tx['type'] ?></h4>
                                <p class="text-[11px] text-slate-500"><?= $tx['station'] ?> • <?= $tx['date'] ?></p>
                                <span class="text-[11px] text-emerald-700 font-medium"><?= $tx['items'] ?></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm sm:text-base font-bold font-heading <?= $tx['is_deposit'] ? 'text-emerald-700' : 'text-slate-900' ?>">
                                <?= $tx['amount'] ?>
                            </span>
                            <span class="text-[10px] text-slate-400 block"><?= $tx['weight'] !== '-' ? $tx['weight'] : '' ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- 2. TAB: PRICES (ราคารับซื้อประจำวัน) -->
        <section id="tab-prices" class="tab-content hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="text-center max-w-2xl mx-auto mb-6">
                <h2 class="text-2xl font-bold text-slate-900 font-heading">ทะเบียนราคารับฝากขยะรีไซเคิล</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">ประกาศราคารับฝาก มหาวิทยาลัยราชภัฏรำไพพรรณี (อิงราคาตลาดและเกณฑ์ อบก.)</p>
            </div>

            <!-- Search Filter Bar -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" id="priceSearch" placeholder="พิมพ์ชื่อขยะ เช่น ขวด PET, ลังกระดาษ, อลูมิเนียม..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                </div>
            </div>

            <div class="space-y-3">
                <?php foreach ($wasteCategories as $cat): ?>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-xl <?= $cat['badge'] ?> flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid <?= $cat['icon'] ?>"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm sm:text-base text-slate-900 font-heading"><?= $cat['name'] ?></h3>
                            <p class="text-xs text-slate-500"><?= $cat['desc'] ?></p>
                            <span class="inline-block mt-1 text-[10px] bg-teal-50 text-teal-800 px-2 py-0.5 rounded-full border border-teal-200">
                                <i class="fa-solid fa-leaf text-teal-600 mr-1"></i>ลด CO₂: <?= $cat['co2'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-right shrink-0 pl-2">
                        <span class="text-xs text-slate-400 block">ราคารับซื้อ</span>
                        <span class="text-base sm:text-lg font-bold text-emerald-800 font-heading"><?= $cat['price'] ?></span>
                        <span class="text-[11px] text-slate-500 block"><?= $cat['unit'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- 3. TAB: PASSBOOK (สมุดบัญชีเงินฝาก Drill-down แบบ MU Recyclable) -->
        <section id="tab-passbook" class="tab-content hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-200 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs text-emerald-700 font-semibold uppercase">Digital Passbook</span>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-heading">สมุดบัญชีเงินฝากขยะออนไลน์</h2>
                        <p class="text-xs text-slate-500">เลขที่บัญชี: RB-2026-0001 (<?= $currentUser['name'] ?>)</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="requestWithdrawal()" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center gap-1.5 font-heading">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                            <span>ขอถอนเงิน / โอนออก</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 my-4 text-center">
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-[11px] text-slate-500 block">ยอดเงินคงเหลือ</span>
                        <span class="text-lg font-bold font-heading text-emerald-800">฿ <?= number_format($currentUser['balance'], 2) ?></span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-[11px] text-slate-500 block">น้ำหนักขยะสะสม</span>
                        <span class="text-lg font-bold font-heading text-slate-800"><?= $currentUser['total_weight'] ?> กก.</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-[11px] text-slate-500 block">ลด CO₂ รวม</span>
                        <span class="text-lg font-bold font-heading text-teal-800"><?= $currentUser['co2_reduced'] ?> kg</span>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold font-heading text-slate-800">ประวัติการทำรายการ (Double click เพื่อดูรายการย่อย)</h3>
                        <span class="text-[11px] text-slate-400">เรียงจากล่าสุด</span>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-100">
                        <?php foreach ($sampleTransactions as $tx): ?>
                        <div class="p-4 flex items-center justify-between hover:bg-emerald-50/50 transition cursor-pointer" onclick="openDetailModal('<?= $tx['type'] ?>', '<?= $tx['station'] ?>', '<?= $tx['items'] ?>', '<?= $tx['weight'] ?>', '<?= $tx['amount'] ?>', '<?= $tx['co2'] ?>')">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $tx['is_deposit'] ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?>">
                                        <?= $tx['is_deposit'] ? 'ฝากขยะ' : 'ถอนเงิน' ?>
                                    </span>
                                    <span class="text-xs text-slate-500"><?= $tx['date'] ?></span>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 font-heading"><?= $tx['station'] ?></h4>
                                <p class="text-xs text-slate-600"><?= $tx['items'] ?></p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm sm:text-base font-bold font-heading <?= $tx['is_deposit'] ? 'text-emerald-700' : 'text-rose-700' ?>">
                                    <?= $tx['amount'] ?>
                                </span>
                                <span class="text-[11px] text-slate-400 block">แตะดูสลิป</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. TAB: PROFILE & SETTINGS (โปรไฟล์และการตั้งค่า) -->
        <section id="tab-profile" class="tab-content hidden max-w-xl mx-auto px-4 pt-4">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 text-center">
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-3xl mx-auto mb-3 border-2 border-emerald-300">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 font-heading"><?= $currentUser['name'] ?></h2>
                <p class="text-xs text-slate-500">รหัสนักศึกษา: <?= $currentUser['code'] ?></p>
                <p class="text-xs text-emerald-700 font-medium mt-1"><?= $currentUser['faculty'] ?></p>

                <div class="mt-6 border-t border-slate-100 pt-4 text-left space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs text-slate-500">สถานะบัญชี</span>
                        <span class="text-xs font-bold text-emerald-700">ปกติ (Verified)</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs text-slate-500">บัญชีพร้อมเพย์ (PromptPay)</span>
                        <span class="text-xs font-semibold text-slate-800">089-876-5432</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs text-slate-500">สังกัดการประเมิน Green Office</span>
                        <span class="text-xs font-semibold text-slate-800">สาขาวิชาวิทยาการคอมพิวเตอร์</span>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <button onclick="openQrModal()" class="w-full bg-emerald-700 text-white py-3 rounded-xl font-semibold text-xs shadow flex items-center justify-center gap-2 font-heading">
                        <i class="fa-solid fa-qrcode"></i> แสดง Virtual Member Card
                    </button>
                    <a href="station_pos.php" class="w-full block bg-amber-400 hover:bg-amber-300 text-slate-900 py-3 rounded-xl font-bold text-xs shadow transition font-heading">
                        <i class="fa-solid fa-scale-balanced mr-1"></i> เข้าสู่หน้าจอเจ้าหน้าที่ (Station POS ชั่งน้ำหนัก)
                    </a>
                    <a href="login.php?logout=1" class="w-full block bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-semibold text-xs transition font-heading">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> ออกจากระบบ
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- ========================================================================= -->
    <!-- MOBILE BOTTOM NAVIGATION BAR (เสมือน Native Application บนสมาร์ตโฟน)      -->
    <!-- ========================================================================= -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-slate-200 z-50 px-2 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
        <div class="flex items-center justify-around h-16">
            <!-- Tab 1: Home -->
            <button onclick="switchTab('tab-home')" id="btn-tab-home" class="nav-btn flex flex-col items-center justify-center w-14 text-emerald-700">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px] font-medium mt-1">หน้าหลัก</span>
            </button>

            <!-- Tab 2: Prices -->
            <button onclick="switchTab('tab-prices')" id="btn-tab-prices" class="nav-btn flex flex-col items-center justify-center w-14 text-slate-400">
                <i class="fa-solid fa-tags text-lg"></i>
                <span class="text-[10px] font-medium mt-1">ราคาวันนี้</span>
            </button>

            <!-- Center Elevated Action: My QR Card -->
            <button onclick="openQrModal()" class="-mt-6 w-14 h-14 rounded-full bg-gradient-to-tr from-emerald-700 to-teal-500 text-amber-300 flex items-center justify-center text-2xl shadow-lg border-4 border-white active:scale-90 transition">
                <i class="fa-solid fa-qrcode"></i>
            </button>

            <!-- Tab 3: Passbook -->
            <button onclick="switchTab('tab-passbook')" id="btn-tab-passbook" class="nav-btn flex flex-col items-center justify-center w-14 text-slate-400">
                <i class="fa-solid fa-file-invoice text-lg"></i>
                <span class="text-[10px] font-medium mt-1">สมุดเงินฝาก</span>
            </button>

            <!-- Tab 4: Profile -->
            <button onclick="switchTab('tab-profile')" id="btn-tab-profile" class="nav-btn flex flex-col items-center justify-center w-14 text-slate-400">
                <i class="fa-solid fa-user text-lg"></i>
                <span class="text-[10px] font-medium mt-1">โปรไฟล์</span>
            </button>
        </div>
    </nav>

    <!-- ========================================================================= -->
    <!-- MODAL 1: VIRTUAL QR MEMBER PASS (บัตรสมาชิกดิจิทัลสำหรับยื่นชั่งน้ำหนัก)     -->
    <!-- ========================================================================= -->
    <div id="qrModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl relative overflow-hidden animate-in fade-in zoom-in duration-200">
            <button onclick="closeQrModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-semibold mb-3">
                <i class="fa-solid fa-id-card"></i> บัตรสมาชิกธนาคารขยะ มรภ.รำไพพรรณี
            </div>

            <h3 class="text-lg font-bold font-heading text-slate-900"><?= $currentUser['name'] ?></h3>
            <p class="text-xs text-slate-500 mb-4">รหัส นศ.: <?= $currentUser['code'] ?> • <?= $currentUser['faculty'] ?></p>

            <!-- Realistic QR Code Display Area -->
            <div class="bg-white p-4 rounded-2xl border-2 border-dashed border-emerald-300 inline-block shadow-inner mb-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode($currentUser['qr_token']) ?>" 
                     alt="Member QR Code" 
                     class="w-48 h-48 mx-auto" />
                <span class="text-[11px] font-mono text-slate-400 mt-2 block tracking-wider"><?= $currentUser['qr_token'] ?></span>
            </div>

            <p class="text-xs text-emerald-700 font-medium">
                <i class="fa-solid fa-sun text-amber-500 mr-1"></i> ยื่นหน้าจอนี้ให้เจ้าหน้าที่จุดบริการเพื่อสแกนชั่งน้ำหนัก
            </p>

            <div class="mt-4 pt-4 border-t border-slate-100 flex gap-2">
                <button onclick="closeQrModal()" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl text-xs font-heading">
                    ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: TRANSACTION DETAIL DRILL-DOWN (รายละเอียดรายการฝาก-ถอน)         -->
    <!-- ========================================================================= -->
    <div id="detailModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl relative">
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="text-center pb-4 border-b border-slate-100">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl mx-auto mb-2">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 id="dt-type" class="text-base font-bold font-heading text-slate-900">รายละเอียดธุรกรรม</h3>
                <span id="dt-station" class="text-xs text-slate-500 block"></span>
            </div>

            <div class="py-4 space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-500">รายการขยะ</span>
                    <span id="dt-items" class="font-semibold text-slate-800 text-right"></span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-500">น้ำหนักรวม</span>
                    <span id="dt-weight" class="font-semibold text-slate-800"></span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-500">ก๊าซเรือนกระจกที่ลดได้</span>
                    <span id="dt-co2" class="font-semibold text-teal-700"></span>
                </div>
                <div class="flex justify-between py-2 border-t border-slate-100 text-sm">
                    <span class="font-bold text-slate-900">ยอดเงินสุทธิ</span>
                    <span id="dt-amount" class="font-extrabold text-emerald-700 font-heading"></span>
                </div>
            </div>

            <button onclick="closeDetailModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-2.5 rounded-xl text-xs font-heading">
                ตกลง
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- JAVASCRIPT: TAB CONTROLLER, PWA SERVICE WORKER & MODAL LOGIC              -->
    <!-- ========================================================================= -->
    <script>
        // Tab Switcher Function for Mobile / Single Page Navigation
        function switchTab(tabId) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            // Show target tab
            const target = document.getElementById(tabId);
            if (target) {
                target.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // Update Bottom Nav Colors
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('text-emerald-700');
                btn.classList.add('text-slate-400');
            });
            const activeBtn = document.getElementById('btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-400');
                activeBtn.classList.add('text-emerald-700');
            }
        }

        // Virtual QR Modal
        function openQrModal() {
            document.getElementById('qrModal').classList.remove('hidden');
        }
        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }

        // Transaction Detail Modal
        function openDetailModal(type, station, items, weight, amount, co2) {
            document.getElementById('dt-type').innerText = type;
            document.getElementById('dt-station').innerText = station;
            document.getElementById('dt-items').innerText = items;
            document.getElementById('dt-weight').innerText = weight;
            document.getElementById('dt-amount').innerText = amount;
            document.getElementById('dt-co2').innerText = co2;
            document.getElementById('detailModal').classList.remove('hidden');
        }
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        function requestWithdrawal() {
            alert('ระบบเปิดรับคำขอถอนเงินผ่าน PromptPay โดยจะดำเนินการโอนเงินและแจ้งเตือนภายใน 24 ชม.');
        }

        // Live Search in Price List
        const searchInput = document.getElementById('priceSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                const items = document.querySelectorAll('#tab-prices .bg-white');
                items.forEach(item => {
                    const text = item.innerText.toLowerCase();
                    item.style.display = text.includes(query) ? 'flex' : 'none';
                });
            });
        }

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js')
                    .then(reg => console.log('[PWA] ServiceWorker registered with scope:', reg.scope))
                    .catch(err => console.log('[PWA] ServiceWorker registration failed:', err));
            });
        }

        // PWA Install Prompt Banner
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const banner = document.getElementById('pwa-install-banner');
            if (banner) banner.classList.remove('hidden');
        });

        const installBtn = document.getElementById('pwa-install-btn');
        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`[PWA] User response: ${outcome}`);
                    deferredPrompt = null;
                    document.getElementById('pwa-install-banner').classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
