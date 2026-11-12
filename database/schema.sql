-- =====================================================================
-- RBRU Recyclable Waste Bank Management System
-- ระบบบริหารจัดการธนาคารขยะรีไซเคิล มหาวิทยาลัยราชภัฏรำไพพรรณี
-- Database Schema & Initial Seed Data
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `rbru_waste_bank` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rbru_waste_bank`;

-- ---------------------------------------------------------------------
-- 1. Table: users (ผู้ใช้งานและสมาชิก)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_code` VARCHAR(50) NOT NULL UNIQUE COMMENT 'รหัสนักศึกษา / รหัสบุคลากร / รหัสหน่วยงาน',
    `full_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(30) NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `user_type` ENUM('STUDENT', 'STAFF', 'DEPARTMENT', 'STATION_OFFICER', 'ADMIN') NOT NULL DEFAULT 'STUDENT',
    `faculty_department` VARCHAR(150) NULL COMMENT 'คณะ/สำนัก/กอง/สาขาวิชา',
    `qr_token` VARCHAR(100) NOT NULL UNIQUE COMMENT 'โทเค็นสำหรับสแกน Virtual QR Member Card',
    `line_user_id` VARCHAR(100) NULL COMMENT 'LINE User ID (Uxxxxxxxx) สำหรับส่ง Flex Message',
    `line_notify_token` VARCHAR(150) NULL COMMENT 'LINE Notify Token สำหรับส่งสลิปผ่านกลุ่ม/บุคคล',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. Table: accounts (สมุดบัญชีเงินฝากขยะรีไซเคิล)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `accounts` (
    `account_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `account_number` VARCHAR(30) NOT NULL UNIQUE COMMENT 'เลขที่บัญชี เช่น RB-2026-0001',
    `current_balance` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'ยอดเงินคงเหลือสุทธิ (บาท)',
    `total_earned` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'ยอดเงินสะสมทั้งหมดที่เคยฝาก',
    `total_withdrawn` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'ยอดเงินที่ถอนออกไปแล้ว',
    `total_weight_kg` DECIMAL(12, 3) NOT NULL DEFAULT 0.000 COMMENT 'น้ำหนักขยะสะสมรวม (กิโลกรัม)',
    `total_co2_reduced` DECIMAL(12, 3) NOT NULL DEFAULT 0.000 COMMENT 'ปริมาณ CO2 สะสมที่ช่วยลด (kgCO2eq)',
    `eco_points` INT NOT NULL DEFAULT 0 COMMENT 'คะแนนกรีนพอยท์สะสม',
    `status` ENUM('ACTIVE', 'SUSPENDED', 'CLOSED') NOT NULL DEFAULT 'ACTIVE',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Table: waste_categories (หมวดหมู่ขยะรีไซเคิล)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_categories` (
    `category_id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(100) NOT NULL,
    `category_code` VARCHAR(20) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `icon_class` VARCHAR(50) NOT NULL DEFAULT 'fa-recycle',
    `color_hex` VARCHAR(10) NOT NULL DEFAULT '#10b981',
    `display_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. Table: waste_items (รายการขยะและราคาต่อหน่วย)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `waste_items` (
    `item_id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `item_name` VARCHAR(120) NOT NULL,
    `item_code` VARCHAR(30) NOT NULL UNIQUE,
    `unit` VARCHAR(20) NOT NULL DEFAULT 'กก.',
    `buy_price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'ราคารับซื้อจากสมาชิก (บาท/หน่วย)',
    `sell_price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'ราคาจำหน่ายออกให้โรงงาน (บาท/หน่วย)',
    `emission_factor` DECIMAL(8, 4) NOT NULL DEFAULT 1.0000 COMMENT 'ค่าสัมประสิทธิ์ลดก๊าซเรือนกระจก (kgCO2eq per unit)',
    `current_stock_qty` DECIMAL(12, 3) NOT NULL DEFAULT 0.000 COMMENT 'สต็อกคงเหลือในคลัง (หน่วย)',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `waste_categories`(`category_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. Table: stations (จุดบริการรับฝากในมหาวิทยาลัย)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stations` (
    `station_id` INT AUTO_INCREMENT PRIMARY KEY,
    `station_name` VARCHAR(120) NOT NULL,
    `location_detail` VARCHAR(255) NULL,
    `operating_hours` VARCHAR(150) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. Table: transactions (ธุรกรรมการฝากและถอน)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions` (
    `transaction_id` INT AUTO_INCREMENT PRIMARY KEY,
    `transaction_no` VARCHAR(40) NOT NULL UNIQUE COMMENT 'เลขที่ธุรกรรม เช่น TX-20261112-0001',
    `user_id` INT NOT NULL,
    `account_id` INT NOT NULL,
    `station_id` INT NULL,
    `officer_id` INT NULL COMMENT 'เจ้าหน้าที่ผู้บันทึกรายการ',
    `transaction_type` ENUM('DEPOSIT', 'WITHDRAW', 'REWARD_REDEEM') NOT NULL,
    `total_amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'มูลค่าเงินรวม',
    `total_weight` DECIMAL(12, 3) NOT NULL DEFAULT 0.000 COMMENT 'น้ำหนักรวม (กก.)',
    `total_co2_kg` DECIMAL(12, 3) NOT NULL DEFAULT 0.000 COMMENT 'CO2 ที่ลดได้ (kgCO2eq)',
    `points_earned` INT NOT NULL DEFAULT 0 COMMENT 'คะแนนที่ได้รับในรอบนี้',
    `payment_method` ENUM('PASSBOOK_CREDIT', 'CASH', 'PROMPTPAY', 'POINTS') NOT NULL DEFAULT 'PASSBOOK_CREDIT',
    `payment_status` ENUM('PENDING', 'COMPLETED', 'REJECTED') NOT NULL DEFAULT 'COMPLETED',
    `notes` TEXT NULL,
    `transaction_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`account_id`) ON DELETE CASCADE,
    FOREIGN KEY (`station_id`) REFERENCES `stations`(`station_id`) ON DELETE SET NULL,
    FOREIGN KEY (`officer_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. Table: transaction_details (รายละเอียดขยะในแต่ละรายการฝาก)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaction_details` (
    `detail_id` INT AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` INT NOT NULL,
    `item_id` INT NOT NULL,
    `quantity` DECIMAL(10, 3) NOT NULL COMMENT 'น้ำหนักหรือจำนวนที่ฝาก',
    `unit_price` DECIMAL(10, 2) NOT NULL COMMENT 'ราคาต่อหน่วย ณ เวลาที่ทำธุรกรรม',
    `subtotal_price` DECIMAL(10, 2) NOT NULL COMMENT 'ราคารวม = quantity * unit_price',
    `emission_subtotal` DECIMAL(10, 3) NOT NULL COMMENT 'CO2 ที่ลดได้ในรายการนี้',
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions`(`transaction_id`) ON DELETE CASCADE,
    FOREIGN KEY (`item_id`) REFERENCES `waste_items`(`item_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 8. Table: stock_sales (การจำหน่ายขยะรีไซเคิลออกจากคลังสู่โรงงาน)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stock_sales` (
    `sale_id` INT AUTO_INCREMENT PRIMARY KEY,
    `sale_no` VARCHAR(40) NOT NULL UNIQUE,
    `buyer_name` VARCHAR(150) NOT NULL COMMENT 'ชื่อร้านรับซื้อของเก่า/โรงงานรีไซเคิล',
    `officer_id` INT NOT NULL,
    `total_weight` DECIMAL(12, 3) NOT NULL DEFAULT 0.000,
    `total_revenue` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_cost` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `fund_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'กำไรสุทธิเข้ากองทุนธนาคารขยะ RBRU',
    `sale_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`officer_id`) REFERENCES `users`(`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- INITIAL SEED DATA
-- =====================================================================

-- 1. หมวดหมู่ขยะ
INSERT INTO `waste_categories` (`category_id`, `category_name`, `category_code`, `description`, `icon_class`, `color_hex`, `display_order`) VALUES
(1, 'กลุ่มกระดาษ (Paper)', 'CAT-PAPER', 'กระดาษขาวดำ หนังสือพิมพ์ ลังกระดาษ และกระดาษย่อย', 'fa-file-alt', '#3b82f6', 1),
(2, 'กลุ่มพลาสติก (Plastic)', 'CAT-PLASTIC', 'ขวดพลาสติกใส PET ขวดขุ่น HDPE พลาสติกรวม', 'fa-bottle-water', '#10b981', 2),
(3, 'กลุ่มโลหะและอลูมิเนียม (Metal)', 'CAT-METAL', 'กระป๋องเครื่องดื่มอลูมิเนียม สังกะสี และเศษเหล็ก', 'fa-can-food', '#f59e0b', 3),
(4, 'กลุ่มแก้ว (Glass)', 'CAT-GLASS', 'ขวดแก้วสุรา/เบียร์ ขวดแก้วรวม และเศษแก้วแตก', 'fa-wine-bottle', '#8b5cf6', 4),
(5, 'กลุ่มขยะอิเล็กทรอนิกส์ (E-Waste)', 'CAT-EWASTE', 'สายไฟ ตลับหมึก อุปกรณ์คอมพิวเตอร์ และแบตเตอรี่', 'fa-laptop-code', '#ef4444', 5)
ON DUPLICATE KEY UPDATE `category_name` = VALUES(`category_name`);

-- 2. รายการขยะ ราคารับซื้อ และ Emission Factor (อิง อบก. TGO)
INSERT INTO `waste_items` (`item_id`, `category_id`, `item_name`, `item_code`, `unit`, `buy_price`, `sell_price`, `emission_factor`, `current_stock_qty`) VALUES
(1, 1, 'กระดาษขาวดำ (A4)', 'PAP-01', 'กก.', 4.50, 6.00, 1.3400, 120.500),
(2, 1, 'ลังกระดาษลูกฟูก (กล่องน้ำตาล)', 'PAP-02', 'กก.', 3.00, 4.50, 1.1200, 245.000),
(3, 1, 'กระดาษหนังสือพิมพ์/สมุด', 'PAP-03', 'กก.', 2.50, 3.80, 0.9500, 60.000),
(4, 2, 'ขวดพลาสติกใส (PET สะอาด)', 'PLA-01', 'กก.', 9.00, 12.50, 1.5500, 310.200),
(5, 2, 'ขวดพลาสติกขุ่น (HDPE แกลลอน/ขวดนม)', 'PLA-02', 'กก.', 8.00, 11.00, 1.4200, 85.000),
(6, 2, 'พลาสติกรวม/ถุงแกงสะอาด', 'PLA-03', 'กก.', 2.00, 3.50, 0.8800, 50.000),
(7, 3, 'กระป๋องอลูมิเนียม (น้ำอัดลม)', 'MET-01', 'กก.', 35.00, 45.00, 9.1300, 42.500),
(8, 3, 'เศษเหล็กหนา/เหล็กเส้น', 'MET-02', 'กก.', 6.50, 9.00, 1.8500, 150.000),
(9, 3, 'กระป๋องสังกะสี/เหล็กบาง', 'MET-03', 'กก.', 3.50, 5.00, 1.2000, 35.000),
(10, 4, 'ขวดเบียร์กล่อง (ขวดสมบูรณ์)', 'GLA-01', 'กล่อง', 12.00, 16.00, 0.6500, 80.000),
(11, 4, 'ขวดแก้วรวม/ขวดน้ำปลา', 'GLA-02', 'กก.', 1.00, 1.80, 0.5200, 115.000),
(12, 5, 'เมนบอร์ด/การ์ดคอมพิวเตอร์', 'EWA-01', 'กก.', 80.00, 120.00, 4.5000, 15.000),
(13, 5, 'สายไฟเก่าทองแดง', 'EWA-02', 'กก.', 40.00, 65.00, 3.2000, 22.000)
ON DUPLICATE KEY UPDATE `item_name` = VALUES(`item_name`), `buy_price` = VALUES(`buy_price`);

-- 3. จุดบริการรับฝากในมหาวิทยาลัยราชภัฏรำไพพรรณี
INSERT INTO `stations` (`station_id`, `station_name`, `location_detail`, `operating_hours`) VALUES
(1, 'ศูนย์คัดแยกขยะกลาง มรภ.รำไพพรรณี', 'อาคารบริหารจัดการสิ่งแวดล้อมและพัสดุ (ใกล้อาคาร 28)', 'วันจันทร์ - วันศุกร์ เวลา 08.30 - 16.30 น.'),
(2, 'จุดบริการกองพัฒนานักศึกษา', 'อาคารกิจกรรมนักศึกษา ชั้น 1', 'วันพุธ และ วันพฤหัสบดี เวลา 11.30 - 15.30 น.'),
(3, 'จุดบริการคณะวิทยาศาสตร์และเทคโนโลยี', 'ลานหน้าอาคาร 36', 'วันศุกร์ เวลา 09.00 - 14.00 น.')
ON DUPLICATE KEY UPDATE `station_name` = VALUES(`station_name`);

-- 4. บัญชีผู้ใช้เริ่มต้น (Admin, Officer, Sample Student, Sample Department)
-- Password เริ่มต้นคือ: rbru1234 (hash แบบ bcrypt)
INSERT INTO `users` (`user_id`, `user_code`, `full_name`, `email`, `phone`, `password_hash`, `user_type`, `faculty_department`, `qr_token`) VALUES
(1, 'ADMIN-001', 'ผู้ดูแลระบบ ธนาคารขยะ มรภ.รำไพพรรณี', 'admin.wastebank@rbru.ac.th', '039-319111', '$2y$10$wTqS8l18c5R1yD6vE6OQ9.y1R4H3K2N1M0L9P8Q7R6S5T4U3V2W1X', 'ADMIN', 'กองอาคารสถานที่และสิ่งแวดล้อม', 'RBRU-QR-ADM001'),
(2, 'OFFICER-01', 'นายสมคิด สิ่งแวดล้อมดี (เจ้าหน้าที่จุดรับฝาก)', 'somkid.s@rbru.ac.th', '081-234-5678', '$2y$10$wTqS8l18c5R1yD6vE6OQ9.y1R4H3K2N1M0L9P8Q7R6S5T4U3V2W1X', 'STATION_OFFICER', 'จุดบริการศูนย์คัดแยกกลาง', 'RBRU-QR-OFF001'),
(3, '6614401001', 'นางสาวรักดี มีวินัย (นักศึกษา)', 'rakdee.m@rbru.ac.th', '089-876-5432', '$2y$10$wTqS8l18c5R1yD6vE6OQ9.y1R4H3K2N1M0L9P8Q7R6S5T4U3V2W1X', 'STUDENT', 'คณะวิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'RBRU-QR-6614401001'),
(4, 'DEPT-SCI01', 'คณะวิทยาศาสตร์และเทคโนโลยี (Green Office)', 'sci.office@rbru.ac.th', '039-319111-200', '$2y$10$wTqS8l18c5R1yD6vE6OQ9.y1R4H3K2N1M0L9P8Q7R6S5T4U3V2W1X', 'DEPARTMENT', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'RBRU-QR-DEPTSCI01')
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);

-- 5. บัญชีสมุดเงินฝากเริ่มต้น
INSERT INTO `accounts` (`account_id`, `user_id`, `account_number`, `current_balance`, `total_earned`, `total_weight_kg`, `total_co2_reduced`, `eco_points`) VALUES
(1, 3, 'RB-2026-0001', 345.50, 420.00, 38.600, 56.420, 380),
(2, 4, 'RB-2026-0002', 1580.00, 2150.00, 210.500, 289.150, 2100)
ON DUPLICATE KEY UPDATE `account_number` = VALUES(`account_number`);

