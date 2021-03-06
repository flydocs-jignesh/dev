<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
		
		// DB settings
		'db' => [
            'driver' => 'mysql',
            'host' => '192.168.100.5',
            'database' => 'flymcc',
            'username' => 'admin',
            'password' => 'admin',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],		
		// ODB settings
        'odb' => [
            'host' => '192.168.100.5',
            'dbname' => 'oauth',
            'user' => 'admin',
			'pass' => 'admin'
        ],
        'cors' => null !== getenv('CORS_ALLOWED_ORIGINS') ? getenv('CORS_ALLOWED_ORIGINS') : '*',

        // App Settings
        'app'   => [
            'name' => "FLYdocs",
            'url'  => "http:/192.168.100.15"
        ],

        'ELK'   => [
            'hosts' => "http://192.168.101.154:9200"            
        ],
    ],
];
