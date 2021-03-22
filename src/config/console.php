<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
$config =
    [
        'id' => 'app-itlo-console',

        'modules' => [

            'cms' => [
                'controllerNamespace' => 'itlo\cms\console\controllers'
            ],

            'ajaxfileupload' => [
                'controllerNamespace' => 'itlo\yii2\ajaxfileupload\console\controllers',
                'private_tmp_dir' => '@frontend/runtime/ajaxfileupload'
            ]
        ],

        'components' => [

            'urlManager' => [
                'baseUrl' => '',
                //'hostInfo' => 'https://demo.ru'
            ]
        ],

        'controllerMap' => [
            'migrate' => [
                'class'         => 'yii\console\controllers\MigrateController',
                'migrationPath' => [
                    '@app/migrations',
                    '@itlo/cms/migrations',
                ],
            ],
        ]
    ];

return $config;