<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\models\CmsContentPropertyEnum;
use itlo\yii2\form\fields\SelectField;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminCmsContentPropertyEnumController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', 'Managing property values');
        $this->modelShowAttribute = "value";
        $this->modelClassName = CmsContentPropertyEnum::class;

        $this->generateAccessActions = false;

        parent::init();
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'filters' => [
                    'visibleFilters' => [
                        'value',
                        'property_id',
                    ],
                ],
                'grid'    => [
                    'visibleColumns' => [
                        'checkbox',
                        'actions',
                        'id',
                        'value',
                        'property_id',
                        'code',
                        'priority',
                    ],
                    'columns' => [
                        'value' => [
                            'attribute' => "value",
                            'format'    => "raw",
                            'value'     => function (CmsContentPropertyEnum $model) {
                                return Html::a($model->value, "#", [
                                    'class' => "sx-trigger-action",
                                ]);
                            },
                        ],
                    ]
                ],
            ],
            'create' => [
                'fields' => [$this, 'updateFields'],
            ],
            'update' => [
                'fields' => [$this, 'updateFields'],
            ],
        ]);
    }

    public function updateFields($action)
    {
        /**
         * @var $model CmsTreeTypeProperty
         */
        $model = $action->model;
        //$model->load(\Yii::$app->request->get());

        return [
            'property_id' => [
                'class' => SelectField::class,
                'items' => function() {
                    return \yii\helpers\ArrayHelper::map(
                        \itlo\cms\models\CmsContentProperty::find()->all(),
                        "id",
                        "name"
                    );
                }
            ],
            'value',
            'code',
            'priority',
        ];
    }
}
