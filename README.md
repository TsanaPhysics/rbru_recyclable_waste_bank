# RBRU Recyclable Waste Bank Management
## ระบบ RBRU Recyclable Waste Bank Management
### ระบบบริหารจัดการธนาคารขยะรีไซเคิล มหาวิทยาลัยราชภัฏรำไพพรรณี ออนไลน์

ระบบบริหารจัดการธนาคารขยะรีไซเคิลออนไลน์สำหรับประชาคมมหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU Recyclable Waste Bank Management System) พัฒนาขึ้นโดยอ้างอิงและถอดบทเรียนสถาปัตยกรรมจากระบบ **MU Recyclable Waste Bank Management System (มหาวิทยาลัยมหิดล)** ผสานการดำเนินงานเพื่อตอบสนองเกณฑ์มหาวิทยาลัยสีเขียวระดับสากล **UI GreenMetric World University Rankings (หมวด Waste)** และการประเมินการลดก๊าซเรือนกระจก (TGO GHG Reduction)

---

### เอกสารแผนแม่บทและสถาปัตยกรรมระบบ (Master Plan & Blueprint)
- เอกสารแผนแม่บทฉบับสมบูรณ์: [docs/RBRU_WASTE_BANK_MASTER_PLAN.md](file:///Applications/XAMPP/xamppfiles/htdocs/rbru_waste_bank/docs/RBRU_WASTE_BANK_MASTER_PLAN.md)
- สคริปต์ฐานข้อมูลพร้อมข้อมูลเริ่มต้น (Seed Data): [database/schema.sql](file:///Applications/XAMPP/xamppfiles/htdocs/rbru_waste_bank/database/schema.sql)

---

### โครงสร้างโมดูลหลักของระบบ (System Modules)
1. **Authentication & Member Portal:** รองรับสมาชิกบุคคล (นักศึกษา/อาจารย์/บุคลากร) และสมาชิกหน่วยงาน (คณะ/สำนัก/กอง/ชมรม) พร้อมรหัส Virtual QR Member Card
2. **Waste Catalog & Dynamic Pricing:** ทะเบียนขยะรีไซเคิล 5 หมวดหลัก (กระดาษ, พลาสติก, โลหะ/อลูมิเนียม, แก้ว, E-waste) พร้อมระบบคำนวณราคารับฝาก และ Emission Factor ($\text{kgCO}_2\text{eq/kg}$)
3. **Station POS & Weighing System:** บันทึกการชั่งรับฝากขยะ คำนวณมูลค่าเงิน + คะแนนสะสมกรีนพอยท์ (Green Coins) + คำนวณปริมาณคาร์บอนที่ลดได้ทันที พร้อมออก E-Slip
4. **Digital Passbook & Statement:** ตรวจสอบยอดเงินสะสม ประวัติการทำรายการแบบ Drill-down (ดับเบิลคลิกดูรายการขยะย่อยเหมือน MU Recyclable) และระบบขอเบิกถอนเงินสด/พร้อมเพย์
5. **UI GreenMetric & Carbon Analytics Dashboard:** สถิติน้ำหนักขยะสะสม, อันดับ Leaderboard (รายบุคคลและหน่วยงาน), และผลประเมินคาร์บอนฟุตพริ้นท์
6. **Stock Inventory & Outflow Sales:** บันทึกสต็อกขยะคงคลังและการจำหน่ายออกให้โรงงานรีไซเคิล พร้อมงบดุลกำไรสะสมกองทุนธนาคารขยะ

---

### สภาพแวดล้อมทางเทคนิค (Tech Stack)
- **Web Server & Environment:** XAMPP (Apache, PHP 8.2+, MySQL / MariaDB)
- **Frontend Framework:** TailwindCSS / Modern Glassmorphism & UI Green Theme, FontAwesome, Chart.js
- **Database Engine:** InnoDB, UTF-8mb4
