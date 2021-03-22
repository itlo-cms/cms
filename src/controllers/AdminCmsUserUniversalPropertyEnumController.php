<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\models\CmsUserUniversalPropertyEnum;
use yii\helpers\ArrayHelper;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminCmsUserUniversalPropertyEnumController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = "Управление значениями свойств пользователя";
        $this->modelShowAttribute = "value";
        $this->modelClassName = CmsUserUniversalPropertyEnum::class;

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
                        'property_id'
                    ]
                ],
                'grid' => [
                    'visibleColumns' => [
                        'checkbox',
                        'actions',
                        'id',
                        'property_id',
                        'value',
                        'code',
                        'priority',
                    ]
                ]
            ]
        ]);
    }

}
