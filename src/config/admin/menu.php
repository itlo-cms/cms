<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

/**
 * Меню контента
 * @return array
 */
function contentMenu()
{
    $result = [];

    if ($contentTypes = \itlo\cms\models\CmsContentType::find()->orderBy("priority ASC")->all()) {
        /**
         * @var $contentType \itlo\cms\models\CmsContentType
         */
        foreach ($contentTypes as $contentType) {
            $itemData = [
                'code'  => "content-block-".$contentType->id,
                'label' => $contentType->name,
                "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/icon.article.png'],
            ];

            $contents = $contentType->getCmsContents()->andWhere(['visible' => 'Y'])->all();

            if ($contents) {
                foreach ($contents as $content) {
                    $itemData['items'][] = [
                        'label'          => $content->name,
                        'url'            => ["cms/admin-cms-content-element", "content_id" => $content->id],
                        "activeCallback" => function ($adminMenuItem) use ($content) {
                            return (bool)($content->id == \Yii::$app->request->get("content_id") && \Yii::$app->controller->uniqueId == 'cms/admin-cms-content-element');
                        },

                        "accessCallback" => function ($adminMenuItem) use ($content) {
                            $controller = \Yii::$app->createController('cms/admin-cms-content-element')[0];
                            $controller->setContent($content);

                            foreach ([$controller->permissionName] as $permissionName) {
                                if ($permission = \Yii::$app->authManager->getPermission($permissionName)) {
                                    if (!\Yii::$app->user->can($permission->name)) {
                                        return false;
                                    }
                                }
                            }

                            return true;
                        },

                    ];
                }
            }

            if (isset($itemData['items'])) {
                $result[] = $itemData;
            }
        }
    }

    return $result;
}

;


/**
 * Меню контента
 * @return array
 */
function dashboardsMenu()
{
    $result = [];

    if ($dashboards = \itlo\cms\models\CmsDashboard::find()->orderBy("priority ASC")->all()) {
        /**
         * @var $dashboard \itlo\cms\models\CmsDashboard
         */
        foreach ($dashboards as $dashboard) {
            $itemData = [
                'label'          => $dashboard->name,
                "img"            => ['\itlo\cms\assets\CmsAsset', 'images/icons/dashboard.png'],
                'url'            => ["admin/admin-index/dashboard", "pk" => $dashboard->id],
                "activeCallback" => function ($adminMenuItem) {
                    return (bool)(\Yii::$app->controller->action->uniqueId == 'admin/admin-index/dashboard' && \yii\helpers\ArrayHelper::getValue($adminMenuItem->urlData,
                            'pk') == \Yii::$app->request->get('pk'));
                },
            ];

            $result[] = $itemData;
        }
    } else {
        $result[] = [
            "label" => \Yii::t('itlo/cms', "Рабочий стол 1"),
            "url"   => ["admin/admin-index"],
            "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/dashboard.png'],
        ];
    }

    return $result;
}

;

/**
 * Меню контента
 * @return array
 */
function contentEditMenu()
{
    $result = [];

    if ($contentTypes = \itlo\cms\models\CmsContentType::find()->orderBy("priority ASC")->all()) {
        /**
         * @var $contentType \itlo\cms\models\CmsContentType
         */
        foreach ($contentTypes as $contentType) {
            $itemData = [
                'code'           => "content-block-edit-".$contentType->id,
                'url'            => ["/cms/admin-cms-content-type/update", "pk" => $contentType->id],
                'label'          => $contentType->name,
                "img"            => ['\itlo\cms\assets\CmsAsset', 'images/icons/icon.article.png'],
                "activeCallback" => function ($adminMenuItem) {
                    return (bool)(\Yii::$app->controller->action->uniqueId == 'cms/admin-cms-content-type/update' && \yii\helpers\ArrayHelper::getValue($adminMenuItem->urlData,
                            'pk') == \Yii::$app->request->get('pk'));
                },
            ];

            if ($contents = $contentType->cmsContents) {
                foreach ($contents as $content) {
                    $itemData['items'][] =
                        [
                            'label'          => $content->name,
                            'url'            => ["cms/admin-cms-content/update", "pk" => $content->id],
                            "activeCallback" => function ($adminMenuItem) {
                                return (bool)(\Yii::$app->controller->action->uniqueId == 'cms/admin-cms-content/update' && \yii\helpers\ArrayHelper::getValue($adminMenuItem->urlData,
                                        'pk') == \Yii::$app->request->get('pk'));
                            },
                        ];
                }
            }


            $result[] = $itemData;
        }
    }

    return $result;
}

;

function componentsMenu()
{
    $result = [];

    if (\Yii::$app instanceof \yii\console\Application) {
        return $result;
    }

    foreach (\Yii::$app->getComponents(true) as $id => $data) {
        try {
            $loadedComponent = \Yii::$app->get($id);
            if ($loadedComponent instanceof \itlo\cms\base\Component) {
                $result[] = [
                    'label'          => $loadedComponent->descriptor->name,
                    'url'            => ["cms/admin-settings", "component" => $loadedComponent->className()],
                    "activeCallback" => function ($adminMenuItem) {
                        return (bool)(\Yii::$app->request->getUrl() == $adminMenuItem->getUrl());
                    },
                ];
            }
        } catch (\Exception $e) {

        }

    }

    return $result;
}

return
    [
        'dashboard' =>
            [
                'priority' => 90,
                'label'    => \Yii::t('itlo/cms', 'Dashboards'),
                "img"      => ['\itlo\cms\assets\CmsAsset', 'images/icons/dashboard.png'],

                'items' => dashboardsMenu(),
            ],

        'content' =>
            [
                'priority' => 200,
                'label'    => \Yii::t('itlo/cms', 'Content'),
                "img"      => ['\itlo\cms\assets\CmsAsset', 'images/icons/sections.png'],

                'items' => array_merge([

                    [
                        "label" => \Yii::t('itlo/cms', "Sections"),
                        "url"   => ["cms/admin-tree"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/sections.png'],
                    ],

                    [
                        "label" => \Yii::t('itlo/cms', "File manager"),
                        "url"   => ["cms/admin-file-manager"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/folder.png'],
                    ],

                    [
                        "label" => \Yii::t('itlo/cms', "File storage"),
                        "url"   => ["cms/admin-storage-files"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/storage_file.png'],
                    ],


                ], contentMenu()),
            ],

        'users' =>
            [
                'label'    => \Yii::t('itlo/cms', 'Users'),
                'priority' => 200,
                'enabled'  => true,
                "img"      => ['\itlo\cms\assets\CmsAsset', 'images/icons/user.png'],

                'items' =>
                    [
                        [
                            "label"    => \Yii::t('itlo/cms', "User management"),
                            "url"      => ["cms/admin-user"],
                            "img"      => ['\itlo\cms\assets\CmsAsset', 'images/icons/user.png'],
                            'priority' => 0,
                        ],

                        [
                            "label" => \Yii::t('itlo/cms', 'The base of {email} addresses', ['email' => 'email']),
                            "url"   => ["cms/admin-user-email"],
                            "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/email-2.png'],
                        ],

                        [
                            "label" => \Yii::t('itlo/cms', "Base phones"),
                            "url"   => ["cms/admin-user-phone"],
                            "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/phone.png'],
                        ],
                    ],
            ],


        'settings' => [
            'priority' => 300,
            'label'    => \Yii::t('itlo/cms', 'Settings'),
            "img"      => ['\itlo\cms\assets\CmsAsset', 'images/icons/settings-big.png'],

            'items' =>
                [
                    [
                        "label" => \Yii::t('itlo/cms', 'Sites'),
                        "url"   => ["/cms/admin-cms-site"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/www.png'],

                        /*'items' => [
                            [
                                "label" => \Yii::t('itlo/cms', 'Sites'),
                                "url"   => ["/cms/admin-cms-site"],
                                "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/www.png'],
                            ],
                            [
                                "label" => \Yii::t('itlo/cms', 'Domains'),
                                "url"   => ["/cms/admin-cms-site-domain"],
                                "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/www.png'],
                            ],
                        ],*/
                    ],


                    [
                        "label" => \Yii::t('itlo/cms', "Languages"),
                        "url"   => ["cms/admin-cms-lang"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/ru.png'],
                    ],

                    [
                        "label" => \Yii::t('itlo/cms', "Server file storage"),
                        "url"   => ["cms/admin-storage/index"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/servers.png'],
                    ],


                    [
                        "label" => \Yii::t('itlo/cms', "Settings sections"),
                        //"url"       => ["cms/admin-cms-tree-type"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/icon.tree.gif'],
                        "items" =>
                            [
                                [
                                    "label" => \Yii::t('itlo/cms', "Properties"),
                                    "url"   => ["cms/admin-cms-tree-type-property"],
                                    //"img"       => ['\itlo\cms\assets\CmsAsset', 'images/icons/icon.tree.gif'],
                                ],

                                [
                                    "label" => \Yii::t('itlo/cms', "Options"),
                                    "url"   => ["cms/admin-cms-tree-type-property-enum"],
                                    //"img"       => ['\itlo\cms\assets\CmsAsset', 'images/icons/icon.tree.gif'],
                                ],

                                [
                                    "label" => \Yii::t('itlo/cms', "Types"),
                                    "url"   => ["cms/admin-cms-tree-type"],
                                    "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/icon.tree.gif'],
                                ],
                            ],
                    ],

                    [
                        "label" => \Yii::t('itlo/cms', "Content settings"),
                        "url"   => ["cms/admin-cms-content-type"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/content.png'],

                        'items' => \yii\helpers\ArrayHelper::merge([
                            'contentSettings'     => [
                                'url'   => ["/cms/admin-cms-content-property"],
                                'label' => \Yii::t('itlo/cms', "Properties"),
                            ],
                            'contentSettingsEnum' => [
                                'url'   => ["/cms/admin-cms-content-property-enum"],
                                'label' => \Yii::t('itlo/cms', "Options"),
                            ],
                        ], contentEditMenu()),
                    ],


                    [
                        "label" => \Yii::t('itlo/cms', "User settings"),
                        "url"   => ["cms/admin-cms-user-universal-property"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/user.png'],
                        "items" =>
                            [
                                [
                                    "label" => \Yii::t('itlo/cms', "User properties"),
                                    "url"   => ["cms/admin-cms-user-universal-property"],
                                    "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/settings-big.png'],
                                ],

                                [
                                    "label" => \Yii::t('itlo/cms', "Options"),
                                    "url"   => ["cms/admin-cms-user-universal-property-enum"],
                                    "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/settings-big.png'],
                                ],
                            ],
                    ],


                    [
                        "label" => \Yii::t('itlo/cms', "Module settings"),
                        //"url"       => ["cms/admin-settings"],
                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/settings-big.png'],
                        'items' => componentsMenu(),
                    ],
                ],
        ],


        'other' =>
            [
                'priority' => 500,
                'label'    => \Yii::t('itlo/cms', 'Additionally'),
                "img"      => ['\itlo\cms\assets\CmsAsset', 'images/icons/other.png'],

                'items' =>
                    [

                        [
                            'label'    => \Yii::t('itlo/cms', 'Instruments'),
                            'priority' => 0,
                            'enabled'  => true,

                            "img" => ['\itlo\cms\assets\CmsAsset', 'images/icons/tools.png'],

                            'items' =>
                                [
                                    [
                                        "label" => \Yii::t('itlo/cms', "Information"),
                                        "url"   => ["cms/admin-info"],
                                        "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/icon.infoblock.png'],
                                    ],
                                ],
                        ],

                        [
                            "label" => \Yii::t('itlo/cms', "Clearing temporary data"),
                            "url"   => ["cms/admin-clear"],
                            "img"   => ['\itlo\cms\assets\CmsAsset', 'images/icons/clear.png'],
                        ],
                    ],
            ],
    ];