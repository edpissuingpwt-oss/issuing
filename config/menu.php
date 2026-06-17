<?php

return [

    'LAPORAN' => [
        ['label' => 'Laporan AWB OMI', 'route' => 'awb_ipp_omi'],
        ['label' => 'Laporan BKL OMI', 'route' => 'bkl_omi'],
        ['label' => 'Laporan Tolakan PB', 'route' => 'tolakan_pb'],
    ],

    'MASTER' => [
        'TOKO' => [
            ['label' => 'IDM', 'route' => 'list_idm'],
            ['label' => 'OMI', 'route' => 'list_omi'],
        ],
        'STOCK' => [
            ['label' => 'Stock Ekonomis', 'route' => 'stock_eko'],
            ['label' => 'Stock POT', 'route' => 'stock_pot'],
        ]
    ],

    'MONITORING' => [
        'PBSL TODAY' => [
            ['label' => 'IDM', 'route' => 'pb_idm'],
            ['label' => 'OMI', 'route' => 'pb_omi'],
        ],
        'PICKING' => [
            ['label' => 'Time Picking', 'route' => 'time_picking'],
            ['label' => 'Monitoring Picking', 'route' => 'm_picking'],
        ]
    ]

];