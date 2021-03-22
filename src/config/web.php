<?php
/**
 * Самый базовый конфиг приложения на базе itlo cms
 * По умолчанию конфигурирование всех базовых используемых компонентов и админки
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
$config = [
    'bootstrap' => ['cms'],

    'components' => [

        'errorHandler' => [
            'errorAction' => 'cms/error/error',
        ],

        'user' => [
            'class' => '\yii\web\User',
            'identityClass' => 'itlo\cms\models\CmsUser',
            'enableAutoLogin' => true,
            'loginUrl' => ['cms/auth/login'],
        ],

        'assetManager' => [
            'appendTimestamp' => true,

            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'jquery.min.js',
                    ]
                ],

                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ]
                ],

                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css',
                    ]
                ]
            ],
        ],

        'breadcrumbs' => [
            'class' => '\itlo\cms\components\Breadcrumbs',
        ],

        'upaBackend' => [
            'menu' => [
                'data' => [
                    'personal' => [
                        'name' => ['itlo/cms', 'Personal data'],
                        'icon' => 'fa fa-user',
                        'items' => [
                            [
                                'name' => ['itlo/cms', 'Personal data'],
                                'url' => ['/cms/upa-personal/update'],
                                'icon' => 'fa fa-user',
                            ],
                            [
                                'name' => ['itlo/cms', 'Change password'],
                                'url' => ['/cms/upa-personal/change-password'],
                                'icon' => 'fa fa-key',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'modules' => [
        'datecontrol' => [
            'class' => 'itlo\cms\modules\datecontrol\Module',
        ],
    ],
];

return $config;