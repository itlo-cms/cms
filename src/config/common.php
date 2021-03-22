<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
return [
    "name" => "ITLO CMS",
    'id' => 'itlo-cms-app',

    'vendorPath' => '@vendor',

    'language' => 'ru',

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'timeZone' => 'UTC',

    'components' => [
        'formatter' => [
            'defaultTimeZone' => 'UTC',
            'timeZone'        => 'Europe/Moscow',
        ],

        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=mysql.itlo.ru;dbname=itlo-db',
            //'username' => 'itlo_user',
            //'password' => 'itlo_pass',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'cms' => [
            'class' => '\itlo\cms\components\Cms',
        ],

        'storage' => [
            'class' => 'itlo\cms\components\Storage',
            'components' => [
                'local' => [
                    'class' => 'itlo\cms\components\storage\ClusterLocal',
                    'priority' => 100,
                ],
            ],
        ],

        'currentSite' => [
            'class' => '\itlo\cms\components\CurrentSite',
        ],

        'imaging' => [
            'class' => '\itlo\cms\components\Imaging',
        ],

        'console' => [
            'class' => 'itlo\cms\components\ConsoleComponent',
        ],

        'i18n' => [
            'translations' => [
                'itlo/cms' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@itlo/cms/messages',
                    'fileMap' => [
                        'itlo/cms' => 'main.php',
                    ],
                ],

                'itlo/cms/user' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@itlo/cms/messages',
                    'fileMap' => [
                        'itlo/cms/user' => 'user.php',
                    ],
                ],
            ],
        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '',
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
                'normalizeTrailingSlash' => true,
                'action' => \yii\web\UrlNormalizer::ACTION_REDIRECT_PERMANENT,
            ],
            'rules' => [
                'u' => 'cms/user/index',
                'u/<username>' => 'cms/user/view',
                'u/<username>/<action>' => 'cms/user/<action>',

                '~<_a:(login|logout|register|forget|reset-password)>' => 'cms/auth/<_a>',

                'itlo-cms' => 'cms/cms/index',
                'itlo-cms/<action>' => 'cms/cms/<action>',

                "cms-imaging" => ["class" => 'itlo\cms\components\ImagingUrlRule'],
                //Resize image on request
            ],
        ],

        'cmsAgent' => [
            'commands' => [

                'cms/cache/flush-all' => [
                    'class' => \itlo\cms\agent\CmsAgent::class,
                    'name' => ['itlo/cms', 'Clearing the cache'],
                    'interval' => 3600 * 24,
                ],

                'ajaxfileupload/cleanup' => [
                    'class' => \itlo\cms\agent\CmsAgent::class,
                    'name' => ['itlo/cms', 'Cleaning temporarily downloaded files'],
                    'interval' => 3600 * 24,
                ],

            ],
        ],

        'authManager' => [
            'config' => require __DIR__ . '/_permissions.php'
        ],
    ],

    'modules' => [

        'cms' => [
            'class' => '\itlo\cms\Module',
        ],

        'ajaxfileupload' => [
            'class' => '\itlo\yii2\ajaxfileupload\AjaxFileUploadModule',
        ],
    ],
];