<?php

// TODO have more "basic" starter values, override in project
return array(
    'checks' => array(
        // true will test default db, connection name(s) will test that conn
        'database' => true,
        // enter the key names of any of your configured filesystems
        'filesystem' => array('cdn'),
        // string or array of strings will use configuration from
        // config/filesystems
        // array or array of arrays has all configuration details here
        'flysystem' => [
            'cdn',
            'cloudfiles' => [
                'driver'    => 'rackspace',
                'username'  => 'your-username',
                'key'       => 'your-key',
                'container' => 'your-container',
                'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
                'region'    => 'IAD',
                'url_type'  => 'publicURL'
            ],
        ],
        'framework' => true,
        'webservice' => [
            'mma' => [
                'url' => 'http://api.endpoint.com/',
                'check' => function($response) { // some callback to verify response
                    $json = $response->json();
                    return ($json && !empty($json['elements']));
                }
            ]
        ],
        'mail' => array(
            'email' => 'test@example.com',
            'method' => 'queue',
        ),
    ),
);