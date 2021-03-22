<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 * @date 22.03.2021
 */
namespace skeeks\cms\actions\backend;

use skeeks\cms\backend\actions\BackendModelMultiAction;
use skeeks\cms\components\Cms;

class BackendModelMultiActivateAction extends BackendModelMultiAction {

    public $attribute = 'active';
    public $value = Cms::BOOL_Y;

    public function init()
    {
        if (!$this->icon)
        {
            $this->icon = "fas fa-eye";
        }

        if (!$this->name)
        {
            $this->name = \Yii::t('itlo/cms', "Activate");
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
