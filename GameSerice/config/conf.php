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
        300 =>'xx_nsb_record',
        400=>'xx_pdk_record'
        ],
];
