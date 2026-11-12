<?php
/**
 * RBRU Recyclable Waste Bank Management System
 * Application Configuration
 */

// Application Info
define('APP_NAME', 'RBRU Recyclable Waste Bank Management');
define('APP_SHORT_NAME', 'RBRU Waste Bank');
define('APP_UNIVERSITY', 'มหาวิทยาลัยราชภัฏรำไพพรรณี');
define('APP_VERSION', '1.0.0');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'rbru_waste_bank');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Base URL
define('BASE_URL', '/rbru_waste_bank/');

// LINE Messaging API & LINE Notify Configuration
define('LINE_MESSAGING_API_URL', 'https://api.line.me/v2/bot/message/push');
define('LINE_CHANNEL_ACCESS_TOKEN', getenv('LINE_CHANNEL_ACCESS_TOKEN') ?: 'YOUR_LINE_CHANNEL_ACCESS_TOKEN_HERE');
define('LINE_CHANNEL_SECRET', getenv('LINE_CHANNEL_SECRET') ?: 'YOUR_LINE_CHANNEL_SECRET_HERE');
define('LINE_NOTIFY_API_URL', 'https://notify-api.line.me/api/notify');
define('LINE_NOTIFY_CLIENT_ID', getenv('LINE_NOTIFY_CLIENT_ID') ?: '');
define('LINE_NOTIFY_CLIENT_SECRET', getenv('LINE_NOTIFY_CLIENT_SECRET') ?: '');

// Hardware / Web Serial Scale Configuration
define('SCALE_DEFAULT_BAUD_RATE', 9600);
define('SCALE_DEFAULT_STATION', 'ศูนย์คัดแยกขยะกลาง (อาคาร 36 สำนักงานอธิการบดี)');

// UI Theme Colors
define('COLOR_PRIMARY', '#059669'); // Emerald 600
define('COLOR_SECONDARY', '#047857'); // Emerald 700
define('COLOR_ACCENT', '#f59e0b'); // Amber 500

