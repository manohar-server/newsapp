<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=solapur_news_db',
            'username' => 'root',
            'password' => 'S0ftFee1s@1001',
            'charset' => 'utf8',
        ],
	'translate' => [
        	'class' => 'richweber\google\translate\Translation',
	        'key' => 'AIzaSyCJEH9QOq3rCXkuOP2f5uet9WPvoUl4EE4',
    	],
	'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
	    'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['softfeels1001@gmail.com' => 'SoftFeels Internet Pvt Ltd'],
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'softfeels1001@gmail.com',
                'password' => 'S0ftFee1s@1001',
                'port' => '587',
                'encryption' => 'tls',
            ],

        ],
    ],
];
