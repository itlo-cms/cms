<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
return [

    'rules' => [
        [
            'class' => \itlo\cms\rbac\AuthorRule::class,
        ],
    ],

    'roles' => [

        [
            'name'        => \itlo\cms\rbac\CmsManager::ROLE_ROOT,
            'description' => ['itlo/cms', 'Superuser'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::ROLE_GUEST,
            'description' => ['itlo/cms', 'Unauthorized user'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::ROLE_ADMIN,
            'description' => ['itlo/cms', 'Admin'],

            'child' => [
                //Есть доступ к системе администрирования
                'permissions' => [
                    \itlo\cms\rbac\CmsManager::PERMISSION_ADMIN_ACCESS,
                    \itlo\cms\rbac\CmsManager::PERMISSION_CONTROLL_PANEL,

                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_USER_FILES,
                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_COMMON_PUBLIC_FILES,
                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_ADDITIONAL_FILES,

                    "cms/admin-settings",
                    "cms/admin-info",

                    "cms/admin-cms-site",
                    "cms/admin-cms-lang",

                    "cms/admin-tree",
                    "cms/admin-tree/new-children",
                    "cms/admin-tree/update",
                    "cms/admin-tree/delete",
                    "cms/admin-tree/delete-multi",
                    "cms/admin-tree/list",
                    "cms/admin-tree/move",
                    "cms/admin-tree/resort",

                    "cms/admin-storage-files",
                    "cms/admin-storage-files/upload",
                    "cms/admin-storage-files/index",
                    "cms/admin-storage-files/update",
                    "cms/admin-storage-files/delete",
                    "cms/admin-storage-files/delete-mult",
                    "cms/admin-storage-files/download",
                    "cms/admin-storage-files/delete-tmp-dir",


                    "cms/admin-user",
                    "cms/admin-user/create",
                    "cms/admin-user/update",
                    "cms/admin-user/update-advanced",
                    "cms/admin-user/delete",
                    "cms/admin-user/delete-multi",
                    "cms/admin-user/activate-multi",
                    "cms/admin-user/deactivate-multi",

                    "cms/admin-storage",
                    "cms/admin-cms-tree-type",
                    "cms/admin-cms-tree-type-property",
                    "cms/admin-cms-tree-type-property-enum",

                    "cms/admin-cms-content-property",
                    "cms/admin-cms-content-property-enum",

                    "cms/admin-cms-user-universal-property",
                    "cms/admin-cms-user-universal-property-enum",
                ],
            ],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::ROLE_MANGER,
            'description' => ['itlo/cms', 'Manager (access to the administration)'],

            'child' => [


                //Есть доступ к системе администрирования
                'permissions' => [
                    \itlo\cms\rbac\CmsManager::PERMISSION_ADMIN_ACCESS,
                    \itlo\cms\rbac\CmsManager::PERMISSION_CONTROLL_PANEL,

                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_USER_FILES,
                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_COMMON_PUBLIC_FILES,

                    "cms/admin-tree",
                    "cms/admin-tree/new-children",
                    "cms/admin-tree/update",
                    "cms/admin-tree/move",
                    "cms/admin-tree/resort",
                    "cms/admin-tree/delete/own",

                    "cms/admin-storage-files",
                    "cms/admin-storage-files/upload",
                    "cms/admin-storage-files/index",
                    "cms/admin-storage-files/update",
                    "cms/admin-storage-files/download",
                    "cms/admin-storage-files/delete/own",
                    "cms/admin-storage-files/delete-tmp-dir",
                ],
            ],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::ROLE_EDITOR,
            'description' => ['itlo/cms', 'Editor (access to the administration)'],

            'child' => [

                //Есть доступ к системе администрирования
                'permissions' => [
                    \itlo\cms\rbac\CmsManager::PERMISSION_ADMIN_ACCESS,
                    \itlo\cms\rbac\CmsManager::PERMISSION_CONTROLL_PANEL,

                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_USER_FILES,
                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_COMMON_PUBLIC_FILES,

                    "cms/admin-tree",
                    "cms/admin-tree/new-children",
                    "cms/admin-tree/update/own",
                    "cms/admin-tree/delete/own",
                    "cms/admin-tree/move/own",


                    "cms/admin-storage-files",
                    "cms/admin-storage-files/upload",
                    "cms/admin-storage-files/index/own",
                    "cms/admin-storage-files/delete-tmp-dir/own",
                    "cms/admin-storage-files/download/own",
                    "cms/admin-storage-files/delete/own",
                    "cms/admin-storage-files/update/own",
                ],
            ],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::ROLE_USER,
            'description' => ['itlo/cms', 'Registered user'],

            //Есть доступ к системе администрирования
            'child'       => [
                'permissions' => [
                    \itlo\cms\components\Cms::UPA_PERMISSION,
                    \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_USER_FILES,
                ],
            ],

        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::ROLE_APPROVED,
            'description' => ['itlo/cms', 'Confirmed user'],

            //Есть доступ к системе администрирования
            'permissions' => [
                \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_USER_FILES,
            ],
        ],
    ],

    'permissions' => [
        [
            'name'        =>\itlo\cms\rbac\CmsManager::PERMISSION_ROOT_ACCESS,
            'description' => ['itlo/cms', 'Возможности суперадминистратора'],
        ],

        [
            'name'        => \itlo\cms\components\Cms::UPA_PERMISSION,
            'description' => ['itlo/cms', 'Доступ к персональной части'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::PERMISSION_ADMIN_ACCESS,
            'description' => ['itlo/cms', 'Access to system administration'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::PERMISSION_CONTROLL_PANEL,
            'description' => ['itlo/cms', 'Access to the site control panel'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::PERMISSION_EDIT_VIEW_FILES,
            'description' => ['itlo/cms', 'The ability to edit view files'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_USER_FILES,
            'description' => ['itlo/cms', 'Access to personal files'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_COMMON_PUBLIC_FILES,
            'description' => ['itlo/cms', 'Access to the public files'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::PERMISSION_ELFINDER_ADDITIONAL_FILES,
            'description' => ['itlo/cms', 'Access to all files'],
        ],

        [
            'name'        => \itlo\cms\rbac\CmsManager::PERMISSION_ADMIN_DASHBOARDS_EDIT,
            'description' => ['itlo/cms', 'Access to edit dashboards'],
        ],


        [
            'name'        => 'cms/admin-cms-site',
            'description' => ['itlo/cms', 'Управление сайтами'],
        ],
        [
            'name'        => 'cms/admin-cms-lang',
            'description' => ['itlo/cms', 'Управление языками'],
        ],
        [
            'name'        => 'cms/admin-storage-files',
            'description' => ['itlo/cms', 'Управление языками'],
        ],
        [
            'name'        => 'cms/admin-storage-files/index',
            'description' => ['itlo/cms', 'Просмотр списка своих файлов'],
        ],
        [
            'name'        => 'cms/admin-storage-files/index/own',
            'description' => ['itlo/cms', 'Просмотр списка своих файлов'],
        ],
        [
            'name'        => 'cms/admin-tree/resort',
            'description' => ['itlo/cms', 'Сортировать подразделы'],
        ],
        [
            'name'        => 'cms/admin-tree/new-children',
            'description' => ['itlo/cms', 'Создать подраздел'],
        ],



        //Управление пользователями
        [
            'name'        => 'cms/admin-user',
            'description' => ['itlo/cms', 'Управление пользователями'],
        ],

        [
            'name'        => 'cms/admin-user/update',
            'description' => ['itlo/cms', 'Редактирование данных пользователя'],
        ],

        [
            'name'        => 'cms/admin-user/create',
            'description' => ['itlo/cms', 'Создать пользователя'],
        ],

        [
            'name'        => 'cms/admin-user/update-advanced',
            'description' => ['itlo/cms', 'Расширенное редактирование данных пользователя'],
        ],

        [
            'name'        => 'cms/admin-user/delete',
            'description' => ['itlo/cms', 'Удаление пользователя'],
        ],

        /*[
            'name'        => 'cms/admin-user/update/not-root',
            'description' => ['itlo/crm', 'Редактирование данных доступного пользователя'],
            'child' => [
                'permissions' => [
                    'cms/admin-user/update',
                ],
            ],
            'ruleName' => \itlo\cms\rbac\rules\CmsUserNotRootRule::class
        ],*/


    ],


];