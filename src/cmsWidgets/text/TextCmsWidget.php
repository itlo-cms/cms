<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\cmsWidgets\text;

use itlo\cms\base\Widget;
use itlo\cms\widgets\formInputs\comboText\ComboTextInputWidget;
use itlo\yii2\form\fields\WidgetField;
use yii\helpers\ArrayHelper;

/**
 * Class TextCmsWidget
 * @package itlo\cms\cmsWidgets\text
 */
class TextCmsWidget extends Widget
{
    public $text = '';
    protected $_obContent = '';
    protected $_is_begin = false;

    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => 'Текст',
        ]);
    }

    public static function begin($config = [])
    {
        $widget = parent::begin($config);
        $widget->_is_begin = true;

        ob_start();
        ob_implicit_flush(false);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'text' => 'Текст',
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['text', 'string'],
        ]);
    }

    /**
     * @return array
     */
    public function getConfigFormFields()
    {
        return [
            'text' => [
                'class'       => WidgetField::class,
                'widgetClass' => ComboTextInputWidget::class,
            ],
        ];
    }

    public function run()
    {
        if ($this->_is_begin) {
            $this->_obContent = ob_get_clean();
            if (!$this->text) {
                $this->text = $this->_obContent;
            }
        }

        return $this->text;
    }

    /**
     * @return array
     */
    public function getCallableData()
    {
        $attributes = parent::getCallableData();
        if (!$attributes['attributes']['text']) {
            $attributes['attributes']['text'] = $this->_obContent;
        }
        return $attributes;
    }
}