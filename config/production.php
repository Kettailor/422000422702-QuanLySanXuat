<?php

return [
    'profiles' => [
        'keyboard' => [
            'product_ids' => ['SPKB87', 'SPKB108', 'SPKBCUSTOM'],
            'components' => [
                [
                    'name' => 'Dập phim tiêu âm',
                    'quantity_per_unit' => 54,
                    'unit' => 'tấm phim',
                    'workshop' => 'XU001',
                    'logistics_key' => 'film',
                ],
                [
                    'name' => 'Gia công vỏ & plate',
                    'quantity_per_unit' => 1,
                    'unit' => 'bộ vỏ',
                    'workshop' => 'XU001',
                    'logistics_key' => 'case',
                ],
                [
                    'name' => 'Lắp ráp main/PCB',
                    'quantity_per_unit' => 1,
                    'unit' => 'bo mạch',
                    'workshop' => 'XU001',
                    'logistics_key' => 'pcb',
                    'materials' => [
                        [
                            'id' => 'NL002',
                            'quantity_per_unit' => 1,
                            'label' => 'PCB SV5TOT R3',
                            'unit' => 'PCB',
                        ],
                    ],
                ],
                [
                    'name' => 'QA & đóng gói bàn phím',
                    'quantity_per_unit' => 1,
                    'unit' => 'bộ',
                    'workshop' => 'XU002',
                    'logistics_key' => 'packaging',
                    'include_request' => true,
                ],
            ],
        ],
        'keycap' => [
            'product_ids' => ['SPCOMP01', 'SPCOMP02', 'SPCOMP03'],
            'components' => [
                [
                    'name' => 'Ép phím theo yêu cầu',
                    'quantity_per_unit' => 1,
                    'unit' => 'phím',
                    'workshop' => 'XU001',
                    'logistics_key' => 'keycap',
                    'materials' => [
                        [
                            'id' => 'NL003',
                            'quantity_per_unit' => 1,
                            'label' => 'Keycap PBT Glacier',
                            'unit' => 'keycap',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'defaults' => [
        'status' => 'Đang chờ xưởng xác nhận',
        'workshop' => 'XU001',
        'unit' => 'thành phẩm',
        'component_name' => 'Công đoạn sản xuất',
        'profile' => [
            'components' => [
                [
                    'name' => 'Gia công sản phẩm theo yêu cầu',
                    'quantity_per_unit' => 1,
                ],
            ],
        ],
    ],
    'notifications' => [
        'channels' => [
            'workshop' => 'workshop',
            'warehouse' => 'warehouse',
        ],
        'warehouse_roles' => ['VT_NHANVIEN_KHO', 'VT_KHO_TRUONG'],
    ],
];
