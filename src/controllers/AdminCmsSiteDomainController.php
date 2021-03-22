<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\grid\BooleanColumn;
use itlo\cms\models\CmsSite;
use itlo\cms\models\CmsSiteDomain;
use itlo\yii2\form\fields\BoolField;
use itlo\yii2\form\fields\HiddenField;
use itlo\yii2\form\fields\SelectField;
use yii\helpers\ArrayHelper;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminCmsSiteDomainController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', "Managing domains");
        $this->modelShowAttribute = "domain";
        $this->modelClassName = CmsSiteDomain::class;

        $this->generateAccessActions = false;
        $this->permissionName = 'cms/admin-cms-site';

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index'  => [
                "filters" => [
                    'visibleFilters' => [
                        'id',
                        'domain',
                        'cms_site_id',
                    ],
                ],
                'grid'    => [
                    'visibleColumns' => [
                        'checkbox',
                        'actions',
                        'id',
                        'domain',
                        'cms_site_id',
                        'is_main',
                        'is_https',
                    ],
                    'columns' => [
                        'is_main' => [
                            'class' => BooleanColumn::class,
                            'trueValue' => 1,
                            'falseValue' => 0,
                        ],
                        'is_https' => [
                            'class' => BooleanColumn::class,
                            'trueValue' => 1,
                            'falseValue' => 0,
                        ],
                    ]
                ],
            ],
            "create" => [
                'fields' => [$this, 'updateFields'],
            ],
            "update" => [
                'fields' => [$this, 'updateFields'],
            ],
        ]);
    }

    public function updateFields($action)
    {
        /**
         * @var $model CmsSiteDomain
         */
        $model = $action->model;
        $model->load(\Yii::$app->request->get());

        if ($code = \Yii::$app->request->get('cms_site_id'))
        {
            $model->cms_site_id = $code;
            $field = [
                'class' => HiddenField::class,
                'label' => false
            ];
        } else {
            $field = [
                'class' => SelectField::class,
                'items' => function() {
                    return ArrayHelper::map(CmsSite::find()->all(), 'id', 'asText');
                }
            ];
        }
        $updateFields = [
            'domain',
            'is_main' => [
                'class' => BoolField::class,
                'allowNull' => false,
                'formElement' => BoolField::ELEMENT_CHECKBOX,
            ],
            'is_https' => [
                'class' => BoolField::class,
                'allowNull' => false,
                'formElement' => BoolField::ELEMENT_CHECKBOX,
            ],
            'cms_site_id' => $field,
        ];

        return $updateFields;
    }
}
