<?php
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ความรู้ SDG & Climate Change กับการจัดการขยะ | <?= APP_NAME ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Kanit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .pixar-shadow {
            box-shadow: 0 24px 48px -12px rgba(6, 78, 59, 0.25);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="bg-emerald-900 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-3.5">
                    <a href="index.php" class="w-10 h-10 bg-emerald-800 hover:bg-emerald-700 rounded-xl flex items-center justify-center text-lg transition text-emerald-200">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <span class="text-xs text-emerald-300 font-light tracking-wider uppercase block">RBRU Green Learning Hub</span>
                        <h1 class="text-lg sm:text-xl font-bold font-heading text-white">
                            SDG & Climate Change กับการจัดการขยะ
                        </h1>
                    </div>
                </div>

                <div class="flex items-center space-x-3 text-xs">
                    <a href="index.php" class="bg-emerald-800 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-xl transition">
                        หน้าหลักธนาคารขยะ
                    </a>
                    <a href="station_pos.php" class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-4 py-2 rounded-xl transition flex items-center gap-1.5 font-heading">
                        <i class="fa-solid fa-scale-balanced"></i>
                        <span>จุดรับฝาก (อาคาร 36)</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow space-y-16 py-10">

        <!-- Hero Section: Featuring 3D Pixar Student Mascot -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-emerald-800 via-teal-800 to-emerald-950 rounded-[2.5rem] text-white p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-2xl">
                <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center relative z-10">
                    <div class="lg:col-span-7 space-y-5">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-950/70 border border-emerald-400/40 text-emerald-200 text-xs font-semibold">
                            <i class="fa-solid fa-seedling text-amber-300"></i>
                            <span>ขับเคลื่อนความยั่งยืน • มหาวิทยาลัยราชภัฏรำไพพรรณี</span>
                        </div>
                        <h2 class="text-3xl sm:text-5xl font-extrabold font-heading leading-tight tracking-tight">
                            ขยะรีไซเคิล กู้โลกอย่างไร? <br>
                            <span class="text-amber-300">ถอดรหัส UN SDGs สู่การหยุดยั้งโลกร้อน</span>
                        </h2>
                        <p class="text-base sm:text-lg text-emerald-100 font-light leading-relaxed">
                            ทุกครั้งที่คุณแยกขวดพลาสติก ลังกระดาษ หรือกระป๋องเครื่องดื่มมาฝากที่ธนาคารขยะ มรภ.รำไพพรรณี คุณไม่ได้เพียงแค่สะสมเงินในสมุดบัญชี แต่กำลังเป็นส่วนหนึ่งในการลดก๊าซเรือนกระจกและพิทักษ์ระบบนิเวศของโลกอย่างเป็นรูปธรรม
                        </p>
                        <div class="pt-2 flex flex-wrap gap-4">
                            <a href="#calculator" class="bg-amber-400 hover:bg-amber-300 active:scale-95 text-slate-900 font-bold px-7 py-3.5 rounded-2xl shadow-lg transition font-heading flex items-center gap-2">
                                <i class="fa-solid fa-calculator"></i>
                                <span>คำนวณคาร์บอนที่ฉันช่วยลด</span>
                            </a>
                            <a href="#sdgs" class="bg-emerald-900/80 hover:bg-emerald-900 text-white font-semibold px-6 py-3.5 rounded-2xl border border-emerald-400/40 shadow transition font-heading">
                                <span>เรียนรู้ 3 เป้าหมาย SDGs</span>
                            </a>
                        </div>
                    </div>

                    <!-- 3D Pixar Student Mascot Artwork Card -->
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 to-emerald-400 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                            <div class="relative bg-white rounded-3xl p-3 pixar-shadow overflow-hidden max-w-sm">
                                <img src="assets/images/pixar_student_recycle.jpg" 
                                     alt="3D Pixar Student Mascot Recycle" 
                                     class="w-full h-auto rounded-2xl object-cover transform group-hover:scale-105 transition duration-500">
                                <div class="p-3 text-center">
                                    <h4 class="font-bold text-slate-900 font-heading text-sm">น้องรำไพ • มาสคอตพิทักษ์สิ่งแวดล้อม 3D</h4>
                                    <p class="text-xs text-emerald-700">ชวนเพื่อนๆ นศ. แยกขยะสะสมเงิน ณ อาคาร 36</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 1: The Climate Crisis & Waste Link -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">Global Boiling Crisis</span>
                <h3 class="text-2xl sm:text-4xl font-bold font-heading text-slate-900 mt-1">ทำไม "ขยะ" ถึงเร่งให้โลกเดือด?</h3>
                <p class="text-slate-600 text-sm mt-2">วิกฤตการเปลี่ยนแปลงสภาพภูมิอากาศไม่ได้มาจากควันโรงงานเพียงอย่างเดียว แต่วงจรขยะส่งผลกระทบต่อชั้นบรรยากาศอย่างมหาศาล</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Methane from Landfills -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-200 hover:shadow-xl transition">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mb-5">
                        <i class="fa-solid fa-fire-flame-curved"></i>
                    </div>
                    <span class="text-[11px] font-bold text-rose-600 uppercase block">Landfill Methane (CH₄)</span>
                    <h4 class="text-xl font-bold text-slate-900 font-heading mt-1 mb-2">ก๊าซมีเทนจากหลุมฝังกลบ</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        เมื่อขยะอินทรีย์และขยะที่ไม่ถูกคัดแยกถูกทับถมในหลุมฝังกลบแบบไร้อากาศ จะปลดปล่อย <strong>ก๊าซมีเทน ($CH_4$)</strong> ซึ่งมีศักยภาพในการกักเก็บความร้อนสูงกว่าก๊าซคาร์บอนไดออกไซด์ ($CO_2$) ถึง <strong>28 เท่า</strong> ในรอบ 100 ปี
                    </p>
                </div>

                <!-- Card 2: Fossil Fuels in Plastic Production -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-200 hover:shadow-xl transition">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl mb-5">
                        <i class="fa-solid fa-oil-well"></i>
                    </div>
                    <span class="text-[11px] font-bold text-amber-600 uppercase block">Petroleum Footprint</span>
                    <h4 class="text-xl font-bold text-slate-900 font-heading mt-1 mb-2">พลาสติกเกิดจากปิโตรเลียม</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        พลาสติกบริสุทธิ์ (Virgin Plastic) ผลิตจากน้ำมันดิบและก๊าซธรรมชาติ กระบวนการกลั่นและขึ้นรูปขวด 1 ใบ ต้องเผาผลาญพลังงานมหาศาล การนำขวดพลาสติก PET กลับมารีไซเคิลจึงลดการขุดเจาะเชื้อเพลิงฟอสซิลได้ถึง <strong>70%</strong>
                    </p>
                </div>

                <!-- Card 3: Deforestation & Paper Waste -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-200 hover:shadow-xl transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mb-5">
                        <i class="fa-solid fa-tree"></i>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 uppercase block">Carbon Sinks Protection</span>
                    <h4 class="text-xl font-bold text-slate-900 font-heading mt-1 mb-2">ปกป้องแหล่งดูดซับคาร์บอน</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        การรีไซเคิลกระดาษ A4 และลังกระดาษลูกฟูก 1 ตัน ช่วยรักษาต้นไม้ใหญ่ได้ถึง <strong>17 ต้น</strong> ซึ่งต้นไม้เหล่านี้เป็นแหล่งดูดซับก๊าซเรือนกระจกตามธรรมชาติที่สำคัญที่สุดของโลก
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 2: 3 Pillars of UN SDGs (SDG 11, 12, 13) -->
        <section id="sdgs" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">United Nations Framework</span>
                <h3 class="text-2xl sm:text-4xl font-bold font-heading text-slate-900 mt-1">3 เป้าหมาย SDGs ที่เชื่อมโยงกับธนาคารขยะ</h3>
                <p class="text-slate-600 text-sm mt-2">มหาวิทยาลัยราชภัฏรำไพพรรณี ดำเนินโครงการธนาคารขยะรีไซเคิลเพื่อตอบสนองเป้าหมายสากลอย่างเป็นรูปธรรม</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- SDG 12 -->
                <div class="bg-gradient-to-b from-amber-500/10 to-transparent p-8 rounded-3xl border border-amber-200">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-600 text-white text-xs font-bold rounded-lg mb-4">
                        <span>SDG 12</span>
                    </div>
                    <h4 class="text-xl font-bold font-heading text-slate-900 mb-2">Responsible Consumption & Production</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        การสร้างรูปแบบการบริโภคและการผลิตที่ยั่งยืน เปลี่ยนจากระบบเส้นตรง (Take-Make-Waste) เข้าสู่ **เศรษฐกิจหมุนเวียน (Circular Economy)** โดยการหมุนเวียนทรัพยากรกลับมาใช้ใหม่ซ้ำๆ
                    </p>
                </div>

                <!-- SDG 13 -->
                <div class="bg-gradient-to-b from-emerald-500/10 to-transparent p-8 rounded-3xl border border-emerald-200">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-700 text-white text-xs font-bold rounded-lg mb-4">
                        <span>SDG 13</span>
                    </div>
                    <h4 class="text-xl font-bold font-heading text-slate-900 mb-2">Climate Action</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        ปฏิบัติการเร่งด่วนเพื่อรับมือการเปลี่ยนแปลงสภาพภูมิอากาศ การรีไซเคิลของธนาคารขยะช่วยลดการปล่อยคาร์บอนฟุตพริ้นท์ตามเกณฑ์ อบก. (TGO) ได้สะสมกว่า **35,000 kgCO₂eq ต่อปี**
                    </p>
                </div>

                <!-- SDG 11 -->
                <div class="bg-gradient-to-b from-blue-500/10 to-transparent p-8 rounded-3xl border border-blue-200">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-lg mb-4">
                        <span>SDG 11</span>
                    </div>
                    <h4 class="text-xl font-bold font-heading text-slate-900 mb-2">Sustainable Cities & Communities</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        การสร้างเมืองและชุมชนที่ยั่งยืน ยกระดับมหาวิทยาลัยราชภัฏรำไพพรรณีสู่การเป็น **Green University** และขับเคลื่อนสำนักงานสีเขียว (Green Office) ในทุกหน่วยงาน
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 3: Featuring 3D Pixar Earth Character & Material Emission Factors -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-sm border border-slate-200">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    
                    <!-- 3D Pixar Earth Mascot Artwork Card -->
                    <div class="lg:col-span-5 flex justify-center order-2 lg:order-1">
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-blue-400 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                            <div class="relative bg-white rounded-3xl p-3 pixar-shadow overflow-hidden max-w-sm">
                                <img src="assets/images/pixar_earth_climate.jpg" 
                                     alt="3D Pixar Earth Climate Mascot" 
                                     class="w-full h-auto rounded-2xl object-cover transform group-hover:scale-105 transition duration-500">
                                <div class="p-3 text-center">
                                    <h4 class="font-bold text-slate-900 font-heading text-sm">พี่โลกสดใส • Pixar Earth Mascot 3D</h4>
                                    <p class="text-xs text-teal-700">ขอบคุณทุกคนที่ช่วยแยกขยะใส่ถังอย่างถูกวิธี</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carbon Emission Metrics (อบก. TGO) -->
                    <div class="lg:col-span-7 space-y-5 order-1 lg:order-2">
                        <span class="text-xs font-bold text-teal-700 uppercase tracking-wider block">Emission Reduction Metrics</span>
                        <h3 class="text-2xl sm:text-4xl font-bold font-heading text-slate-900 leading-tight">
                            ขยะแต่ละ 1 กิโลกรัม <br>
                            <span class="text-emerald-700">ช่วยลดก๊าซเรือนกระจกได้เท่าไหร่?</span>
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-light">
                            อ้างอิงค่าสัมประสิทธิ์การปล่อยก๊าซเรือนกระจก (Emission Factors) จากองค์การบริหารจัดการก๊าซเรือนกระจก (องค์การมหาชน) - อบก. (TGO)
                        </p>

                        <div class="space-y-3">
                            <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-200 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg"><i class="fa-solid fa-can-food"></i></div>
                                    <div>
                                        <h5 class="text-sm font-bold text-slate-900 font-heading">กระป๋องอลูมิเนียม 1 กก.</h5>
                                        <p class="text-[11px] text-slate-500">ประหยัดพลังงาน 95% เมื่อเทียบกับถลุงแร่ใหม่</p>
                                    </div>
                                </div>
                                <span class="text-sm sm:text-base font-extrabold text-amber-800 font-heading">ลด 9.13 kgCO₂eq</span>
                            </div>

                            <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg"><i class="fa-solid fa-bottle-water"></i></div>
                                    <div>
                                        <h5 class="text-sm font-bold text-slate-900 font-heading">ขวดพลาสติกใส PET 1 กก.</h5>
                                        <p class="text-[11px] text-slate-500">ลดการใช้น้ำมันดิบและการเผาทำลาย</p>
                                    </div>
                                </div>
                                <span class="text-sm sm:text-base font-extrabold text-emerald-800 font-heading">ลด 1.55 kgCO₂eq</span>
                            </div>

                            <div class="p-3.5 bg-blue-50 rounded-2xl border border-blue-200 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg"><i class="fa-solid fa-file-alt"></i></div>
                                    <div>
                                        <h5 class="text-sm font-bold text-slate-900 font-heading">กระดาษขาวดำ A4 1 กก.</h5>
                                        <p class="text-[11px] text-slate-500">ลดการตัดไม้และลดน้ำเสียจากอุตสาหกรรมเยื่อกระดาษ</p>
                                    </div>
                                </div>
                                <span class="text-sm sm:text-base font-extrabold text-blue-800 font-heading">ลด 1.34 kgCO₂eq</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Section 4: Interactive Personal Carbon Calculator -->
        <section id="calculator" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-[2.5rem] p-8 sm:p-12 shadow-2xl border border-slate-800">
                <div class="text-center max-w-2xl mx-auto mb-8">
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block">Interactive Calculator</span>
                    <h3 class="text-2xl sm:text-4xl font-bold font-heading text-white mt-1">เครื่องคำนวณคาร์บอนฟุตพริ้นท์ส่วนบุคคล</h3>
                    <p class="text-slate-400 text-xs sm:text-sm mt-2">ลองเลื่อนแถบน้ำหนักขยะที่คุณสะสม เพื่อดูว่าคุณจะช่วยโลกและได้เงินออมเท่าไหร่</p>
                </div>

                <div class="space-y-6">
                    <!-- Plastic Slider -->
                    <div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-emerald-300 font-bold"><i class="fa-solid fa-bottle-water mr-1"></i>ขวดพลาสติก PET ใส (9 ฿/กก.):</span>
                            <span id="labelPlastic" class="font-bold text-white">5.0 กก.</span>
                        </div>
                        <input type="range" id="sliderPlastic" min="0" max="50" step="0.5" value="5.0" 
                               class="w-full accent-emerald-500 h-2 bg-slate-800 rounded-lg cursor-pointer"
                               oninput="calcFootprint()">
                    </div>

                    <!-- Paper Slider -->
                    <div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-blue-300 font-bold"><i class="fa-solid fa-box mr-1"></i>ลังกระดาษลูกฟูก (3 ฿/กก.):</span>
                            <span id="labelPaper" class="font-bold text-white">10.0 กก.</span>
                        </div>
                        <input type="range" id="sliderPaper" min="0" max="100" step="1" value="10.0" 
                               class="w-full accent-blue-500 h-2 bg-slate-800 rounded-lg cursor-pointer"
                               oninput="calcFootprint()">
                    </div>

                    <!-- Aluminum Slider -->
                    <div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-amber-300 font-bold"><i class="fa-solid fa-can-food mr-1"></i>กระป๋องอลูมิเนียม (35 ฿/กก.):</span>
                            <span id="labelMetal" class="font-bold text-white">1.5 กก.</span>
                        </div>
                        <input type="range" id="sliderMetal" min="0" max="20" step="0.1" value="1.5" 
                               class="w-full accent-amber-500 h-2 bg-slate-800 rounded-lg cursor-pointer"
                               oninput="calcFootprint()">
                    </div>
                </div>

                <!-- Calculator Result Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 pt-6 border-t border-slate-800">
                    <div class="bg-emerald-950/60 border border-emerald-500/40 rounded-2xl p-4 text-center">
                        <span class="text-[11px] text-emerald-300 block mb-1">ก๊าซเรือนกระจกที่ลดได้</span>
                        <span id="calcCo2" class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-400">32.65</span>
                        <span class="text-xs text-emerald-200 block">kgCO₂eq</span>
                    </div>

                    <div class="bg-amber-950/60 border border-amber-500/40 rounded-2xl p-4 text-center">
                        <span class="text-[11px] text-amber-300 block mb-1">มูลค่าเงินสะสมเข้าบัญชี</span>
                        <span id="calcMoney" class="text-2xl sm:text-3xl font-extrabold font-heading text-amber-400">127.50</span>
                        <span class="text-xs text-amber-200 block">บาท (THB)</span>
                    </div>

                    <div class="bg-teal-950/60 border border-teal-500/40 rounded-2xl p-4 text-center">
                        <span class="text-[11px] text-teal-300 block mb-1">เทียบเท่าการปลูกต้นไม้</span>
                        <span id="calcTrees" class="text-2xl sm:text-3xl font-extrabold font-heading text-teal-300">3.3</span>
                        <span class="text-xs text-teal-200 block">ต้น (Tree Equivalent)</span>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="station_pos.php" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-8 py-3 rounded-xl transition shadow-lg text-xs font-heading">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>นำขยะมารีไซเคิลได้ที่ อาคาร 36 สำนักงานอธิการบดี</span>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Include Modern Shared Footer -->
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- JAVASCRIPT: CARBON FOOTPRINT CALCULATOR -->
    <script>
        function calcFootprint() {
            const pet = parseFloat(document.getElementById('sliderPlastic').value);
            const paper = parseFloat(document.getElementById('sliderPaper').value);
            const metal = parseFloat(document.getElementById('sliderMetal').value);

            document.getElementById('labelPlastic').innerText = pet.toFixed(1) + ' กก.';
            document.getElementById('labelPaper').innerText = paper.toFixed(1) + ' กก.';
            document.getElementById('labelMetal').innerText = metal.toFixed(1) + ' กก.';

            // Calculation based on TGO emission factors
            const totalCo2 = (pet * 1.55) + (paper * 1.12) + (metal * 9.13);
            const totalMoney = (pet * 9.0) + (paper * 3.0) + (metal * 35.0);
            const trees = totalCo2 / 10.0; // ~10 kgCO2 per tree per year

            document.getElementById('calcCo2').innerText = totalCo2.toFixed(2);
            document.getElementById('calcMoney').innerText = totalMoney.toFixed(2);
            document.getElementById('calcTrees').innerText = trees.toFixed(1);
        }

        // Initialize on load
        calcFootprint();
    </script>
</body>
</html>
