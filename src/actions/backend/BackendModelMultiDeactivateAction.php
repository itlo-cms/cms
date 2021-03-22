<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */
namespace itlo\cms\actions\backend;

use itlo\cms\backend\actions\BackendModelMultiAction;
use itlo\cms\components\Cms;
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class BackendModelMultiDeactivateAction extends BackendModelMultiAction {
    public $attribute = 'active';
    public $value = Cms::BOOL_N;

    public function init()
    {
        if (!$this->icon)
        {
            $this->icon = "fas fa-eye-slash";
        }

        if (!$this->name)
        {
            $this->name = \Yii::t('skeeks/cms', "Deactivate");
        }

        parent::init();
    }

    /**
     * @param $model
     * @return bool
     */
    public function eachExecute($model)
    {
        try {
            $model->{$this->attribute} = $this->value;
            return $model->save(false);
        } catch (\Exception $e) {
            return false;
        }
    }
}
