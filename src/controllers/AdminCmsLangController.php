<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\actions\backend\BackendModelMultiActivateAction;
use itlo\cms\actions\backend\BackendModelMultiDeactivateAction;
use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\grid\BooleanColumn;
use itlo\cms\grid\ImageColumn2;
use itlo\cms\models\CmsLang;
use itlo\yii2\form\fields\BoolField;
use itlo\yii2\form\fields\WidgetField;
use yii\helpers\ArrayHelper;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminCmsLangController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', "Management of languages");
        $this->modelShowAttribute = "name";
        $this->modelClassName = CmsLang::class;

        $this->generateAccessActions = false;

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
                        'name',
                    ],
                ],
                'grid'    => [
                    'defaultOrder' => [
                        'is_active' => SORT_DESC,
                        'priority' => SORT_ASC,
                    ],
                    'visibleColumns' => [
                        'checkbox',
                        'actions',
                        'id',
                        'image_id',
                        'name',
                        'code',
                        'is_active',
                        'priority',
                    ],
                    'columns'        => [
                        'is_active'   => [
                            'class' => BooleanColumn::class,
                        ],

                        'image_id' => [
                            'class' => ImageColumn2::class,
                        ],
                    ],
                ],
            ],
            "create" => [
                'fields' => [$this, 'updateFields'],
            ],
            "update" => [
                'fields' => [$this, 'updateFields'],
            ],

            "activate-multi" => [
                'class' => BackendModelMultiActivateAction::class,
            ],

            "deactivate-multi" => [
                'class' => BackendModelMultiDeactivateAction::class,
            ],
        ]);
    }

    public function updateFields($action)
    {
        return [
            'image_id' => [
                'class'        => WidgetField::class,
                'widgetClass'  => \itlo\cms\widgets\AjaxFileUploadWidget::class,
                'widgetConfig' => [
                    'accept'   => 'image/*',
                    'multiple' => false,
                ],
            ],
            'code',
            'is_active'   => [
                'class'      => BoolField::class,
            ],
            'name',
            'description',
            'priority',
        ];
    }
}
