<?php
/**
 * RBRU Recyclable Waste Bank Management
 * LINE Messaging API & LINE Notify Service
 * 
 * ให้บริการส่ง Digital E-Slip ใบเสร็จการฝากขยะ และสรุปการลดคาร์บอนฟุตพริ้นท์เข้า LINE สมาชิก
 */

require_once __DIR__ . '/../config/config.php';

class LineService {

    /**
     * ส่ง Digital E-Slip ใบเสร็จรับฝากขยะผ่าน LINE Messaging API (Flex Message)
     * 
     * @param string $lineUserId LINE User ID ของสมาชิก (Uxxxxxxxx)
     * @param array $receipt ข้อมูลรายละเอียดธุรกรรม
     * @return array [success => bool, message => string, payload => array]
     */
    public static function sendDepositReceiptFlex(string $lineUserId, array $receipt): array {
        // หากยังไม่ได้ตั้งค่า Token ให้รันในโหมด Simulation
        if (empty(LINE_CHANNEL_ACCESS_TOKEN) || LINE_CHANNEL_ACCESS_TOKEN === 'YOUR_LINE_CHANNEL_ACCESS_TOKEN_HERE') {
            return [
                'success' => true,
                'simulated' => true,
                'message' => 'จำลองการส่ง LINE E-Slip สำเร็จ (Simulation Mode: ยังไม่ได้ใส่ Channel Access Token)',
                'preview_data' => $receipt
            ];
        }

        $flexMessage = self::buildReceiptFlexJson($receipt);

        $payload = [
            'to' => $lineUserId,
            'messages' => [
                [
                    'type' => 'flex',
                    'altText' => '🌱 ใบเสร็จรับฝากขยะรีไซเคิล มรภ.รำไพพรรณี ยอดเงิน +' . number_format($receipt['total_amount'], 2) . ' ฿',
                    'contents' => $flexMessage
                ]
            ]
        ];

        return self::callLineMessagingApi($payload);
    }

    /**
     * Fallback: ส่งการแจ้งเตือนสลิปผ่าน LINE Notify
     */
    public static function sendDepositNotify(string $notifyToken, array $receipt): array {
        if (empty($notifyToken)) {
            return ['success' => false, 'message' => 'ไม่มี LINE Notify Token'];
        }

        $msg = "\n🌱 [RBRU Waste Bank - ใบเสร็จรับฝาก]" .
               "\nผู้ฝาก: " . $receipt['member_name'] . " (" . $receipt['member_code'] . ")" .
               "\nจุดบริการ: " . $receipt['station_name'] .
               "\nวัน-เวลา: " . $receipt['transaction_date'] .
               "\n---------------------------" .
               "\nขยะรวม: " . number_format($receipt['total_weight'], 2) . " กก." .
               "\nยอดเงินรอบนี้: +" . number_format($receipt['total_amount'], 2) . " บาท" .
               "\nยอดคงเหลือสะสม: " . number_format($receipt['current_balance'], 2) . " บาท" .
               "\nลด CO2: " . number_format($receipt['total_co2'], 2) . " kgCO2eq" .
               "\n---------------------------" .
               "\nตรวจสอบสมุดบัญชีออนไลน์ได้ที่ระบบ RBRU Waste Bank";

        $headers = [
            'Authorization: Bearer ' . $notifyToken,
            'Content-Type: application/x-www-form-urlencoded'
        ];

        $ch = curl_init(LINE_NOTIFY_API_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['message' => $msg]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'success' => ($httpCode === 200),
            'http_code' => $httpCode,
            'response' => json_decode($res, true)
        ];
    }

    /**
     * สร้างโครงสร้าง LINE Flex Message Bubble JSON ตามมาตรฐาน UI Green Theme
     */
    public static function buildReceiptFlexJson(array $receipt): array {
        $itemRows = [];
        if (!empty($receipt['items'])) {
            foreach ($receipt['items'] as $item) {
                $itemRows[] = [
                    'type' => 'box',
                    'layout' => 'horizontal',
                    'contents' => [
                        [
                            'type' => 'text',
                            'text' => '• ' . $item['name'] . ' (' . number_format($item['weight'], 2) . ' กก.)',
                            'size' => 'xs',
                            'color' => '#334155',
                            'flex' => 4,
                            'wrap' => true
                        ],
                        [
                            'type' => 'text',
                            'text' => number_format($item['amount'], 2) . ' ฿',
                            'size' => 'xs',
                            'color' => '#064e3b',
                            'align' => 'end',
                            'weight' => 'bold',
                            'flex' => 2
                        ]
                    ],
                    'margin' => 'sm'
                ];
            }
        }

        return [
            'type' => 'bubble',
            'size' => 'mega',
            'header' => [
                'type' => 'box',
                'layout' => 'vertical',
                'backgroundColor' => '#064e3b',
                'paddingTop' => '16px',
                'paddingBottom' => '16px',
                'contents' => [
                    [
                        'type' => 'text',
                        'text' => 'RBRU RECYCLABLE WASTE BANK',
                        'size' => 'xxs',
                        'color' => '#a7f3d0',
                        'weight' => 'bold',
                        'letterSpacing' => '1px'
                    ],
                    [
                        'type' => 'text',
                        'text' => 'ใบเสร็จรับฝากขยะรีไซเคิล',
                        'size' => 'lg',
                        'color' => '#ffffff',
                        'weight' => 'bold',
                        'margin' => 'xs'
                    ],
                    [
                        'type' => 'text',
                        'text' => 'มหาวิทยาลัยราชภัฏรำไพพรรณี',
                        'size' => 'xs',
                        'color' => '#6ee7b7'
                    ]
                ]
            ],
            'body' => [
                'type' => 'box',
                'layout' => 'vertical',
                'contents' => [
                    // Member Info
                    [
                        'type' => 'box',
                        'layout' => 'horizontal',
                        'contents' => [
                            ['type' => 'text', 'text' => 'ผู้ฝาก:', 'size' => 'xs', 'color' => '#64748b', 'flex' => 2],
                            ['type' => 'text', 'text' => $receipt['member_name'], 'size' => 'xs', 'weight' => 'bold', 'color' => '#0f172a', 'flex' => 5]
                        ]
                    ],
                    [
                        'type' => 'box',
                        'layout' => 'horizontal',
                        'contents' => [
                            ['type' => 'text', 'text' => 'รหัสสมาชิก:', 'size' => 'xs', 'color' => '#64748b', 'flex' => 2],
                            ['type' => 'text', 'text' => $receipt['member_code'], 'size' => 'xs', 'color' => '#0f172a', 'flex' => 5]
                        ],
                        'margin' => 'xs'
                    ],
                    [
                        'type' => 'box',
                        'layout' => 'horizontal',
                        'contents' => [
                            ['type' => 'text', 'text' => 'จุดบริการ:', 'size' => 'xs', 'color' => '#64748b', 'flex' => 2],
                            ['type' => 'text', 'text' => $receipt['station_name'], 'size' => 'xs', 'color' => '#047857', 'weight' => 'bold', 'flex' => 5]
                        ],
                        'margin' => 'xs'
                    ],
                    [
                        'type' => 'box',
                        'layout' => 'horizontal',
                        'contents' => [
                            ['type' => 'text', 'text' => 'วัน-เวลา:', 'size' => 'xs', 'color' => '#64748b', 'flex' => 2],
                            ['type' => 'text', 'text' => $receipt['transaction_date'], 'size' => 'xs', 'color' => '#64748b', 'flex' => 5]
                        ],
                        'margin' => 'xs'
                    ],
                    ['type' => 'separator', 'margin' => 'md'],

                    // Items Header
                    [
                        'type' => 'text',
                        'text' => 'รายการขยะที่นำฝากในรอบนี้',
                        'size' => 'xs',
                        'color' => '#475569',
                        'weight' => 'bold',
                        'margin' => 'md'
                    ],
                    // Item rows box
                    [
                        'type' => 'box',
                        'layout' => 'vertical',
                        'contents' => $itemRows,
                        'margin' => 'sm'
                    ],
                    ['type' => 'separator', 'margin' => 'md'],

                    // Total Deposit Amount Card
                    [
                        'type' => 'box',
                        'layout' => 'horizontal',
                        'contents' => [
                            ['type' => 'text', 'text' => 'ยอดเงินรอบนี้', 'size' => 'sm', 'weight' => 'bold', 'color' => '#0f172a'],
                            ['type' => 'text', 'text' => '+' . number_format($receipt['total_amount'], 2) . ' ฿', 'size' => 'lg', 'weight' => 'bold', 'color' => '#047857', 'align' => 'end']
                        ],
                        'margin' => 'md'
                    ],
                    [
                        'type' => 'box',
                        'layout' => 'horizontal',
                        'contents' => [
                            ['type' => 'text', 'text' => 'ยอดเงินคงเหลือสะสม', 'size' => 'xs', 'color' => '#64748b'],
                            ['type' => 'text', 'text' => '฿ ' . number_format($receipt['current_balance'], 2), 'size' => 'sm', 'weight' => 'bold', 'color' => '#065f46', 'align' => 'end']
                        ],
                        'margin' => 'xs'
                    ],

                    // Carbon Metric Box
                    [
                        'type' => 'box',
                        'layout' => 'vertical',
                        'backgroundColor' => '#ecfdf5',
                        'cornerRadius' => 'md',
                        'paddingAll' => '10px',
                        'margin' => 'lg',
                        'contents' => [
                            [
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'contents' => [
                                    ['type' => 'text', 'text' => '🌱 ลดก๊าซเรือนกระจก:', 'size' => 'xs', 'color' => '#065f46', 'weight' => 'bold'],
                                    ['type' => 'text', 'text' => '+' . number_format($receipt['total_co2'], 2) . ' kgCO2eq', 'size' => 'xs', 'color' => '#047857', 'weight' => 'bold', 'align' => 'end']
                                ]
                            ],
                            [
                                'type' => 'text',
                                'text' => 'ขอบคุณที่ร่วมขับเคลื่อน RBRU Green Campus 🌳',
                                'size' => 'xxs',
                                'color' => '#047857',
                                'margin' => 'xs'
                            ]
                        ]
                    ]
                ]
            ],
            'footer' => [
                'type' => 'box',
                'layout' => 'vertical',
                'contents' => [
                    [
                        'type' => 'button',
                        'action' => [
                            'type' => 'uri',
                            'label' => 'เปิดสมุดบัญชีเงินฝากออนไลน์',
                            'uri' => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . BASE_URL
                        ],
                        'style' => 'primary',
                        'color' => '#064e3b',
                        'height' => 'sm'
                    ]
                ]
            ]
        ];
    }

    private static function callLineMessagingApi(array $payload): array {
        $headers = [
            'Authorization: Bearer ' . LINE_CHANNEL_ACCESS_TOKEN,
            'Content-Type: application/json'
        ];

        $ch = curl_init(LINE_MESSAGING_API_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'success' => ($httpCode === 200),
            'http_code' => $httpCode,
            'response' => json_decode($res, true)
        ];
    }
}

