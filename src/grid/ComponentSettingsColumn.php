<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use itlo\cms\base\Component;
use itlo\cms\components\Cms;
use itlo\cms\models\CmsSite;
use itlo\cms\models\User;

/**
 * Class LongTextColumnData
 * @package itlo\cms\grid
 */
class ComponentSettingsColumn extends BooleanColumn
{
    /**
     * @var Component
     */
    public $component = null;

    public $label = 'Наличие настроек';

    /**
     * @inheritdoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        $settings = null;

        if ($this->component === null) {
            return $this->_result(Cms::BOOL_N);
        }

        if ($model instanceof CmsSite) {
            $settings = \itlo\cms\models\CmsComponentSettings::findByComponentSite($this->component, $model)->one();
        }

        if ($model instanceof User) {
            $settings = \itlo\cms\models\CmsComponentSettings::findByComponentUser($this->component, $model)->one();
        }

        if ($settings) {
            return $this->_result(Cms::BOOL_Y);
        }

        return $this->_result(Cms::BOOL_N);
    }

    /**
     * @inheritdoc
     */
    protected function _result($value)
    {
        if ($this->trueValue !== true) {
            if ($value == $this->falseValue) {
                return $this->falseIcon;
            } else {
                return $this->trueIcon;
            }
        } else {
            if ($value !== null) {
                return $value ? $this->trueIcon : $this->falseIcon;
            }
            return $this->showNullAsFalse ? $this->falseIcon : $value;
        }

    }
}