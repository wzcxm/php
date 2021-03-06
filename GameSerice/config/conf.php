<?php

return [
	'APP_ID' => 'wx8236e25cf16a7c2e',
	'APP_SECRET' => 'cd3eeb089d60f5ed428488ac0d6282d9',

	'GAME_DOMAIN_APP' => [
        'gw1' => [
            'INDEX' => 1,
            'DOMAIN' => 'gw.wangqianhong.com',
            'PORT' => 10000,
            'STATUS' => 2,
        ],

        'gw2' => [
            'INDEX' => 2,
            'DOMAIN' => 'gw.wangqianhong.com',
            'PORT' => 10700,
            'STATUS' => 2,
        ],

        'gw3' => [
            'INDEX' => 3,
            'DOMAIN' => 'gw.wangqianhong.com',
            'PORT' => 10070,
            'STATUS' => 2,
        ],

        'gw4' => [
            'INDEX' => 4,
            'DOMAIN' => 'gw.wangqianhong.com',
            'PORT' => 12000,
            'STATUS' => 2,
        ],

        'gw5' => [
            'INDEX' => 5,
            'DOMAIN' => 'gw.wangqianhong.com',
            'PORT' => 10100,
            'STATUS' => 2,
        ],
    ],
    'GAME_DOMAIN_H5'=>[
        'h5_gw1' => [
            'INDEX' => 1,
            'DOMAIN' => 'gw.wangqianhong.com',
            'PORT' => 11000,
            'STATUS' => 2,
        ],

        'h5_gw2' => [
            'INDEX' => 2,
            'DOMAIN' => 'gw.wangqianhong.com',
            'PORT' => 13000,
            'STATUS' => 2,
        ],
    ],
    'GameType'=>[
        200 =>'xx_pdk_record',//跑得快
        300 =>'xx_nsb_record',//牛十别
        400=>'xx_dmz_record',  //打麻子
        500=>'xx_hzmj_record',  //红中麻将
        600=>'xx_zzmj_record',  //转转麻将
        700=>'xx_csmj_record'  //长沙麻将
        ],
];
