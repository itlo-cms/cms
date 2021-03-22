<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\models\CmsTreeTypePropertyEnum;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminCmsTreeTypePropertyEnumController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', 'Managing partition property values');
        $this->modelShowAttribute = "value";
        $this->modelClassName = CmsTreeTypePropertyEnum::class;

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
                            'value'     => function (CmsTreeTypePropertyEnum $model) {
                                return Html::a($model->value, "#", [
                                    'class' => "sx-trigger-action",
                                ]);
                            },
                        ],
                    ]
                ],
            ],
        ]);
    }

}
