<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\relatedProperties\propertyTypes;

use itlo\cms\relatedProperties\models\RelatedPropertiesModel;
use itlo\cms\relatedProperties\PropertyType;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class PropertyTypeTextarea
 * @package itlo\cms\relatedProperties\propertyTypes
 */
class PropertyTypeBool extends PropertyType
{
    public $code = self::CODE_BOOL;
    public $name = "";

    public $default_value = null;

    public $fieldElement = 'radioInput';
    public $rows = 5;

    public static function fieldElements()
    {
        return [
            'radioList' => \Yii::t('itlo/cms', 'Radio'),
            'checkbox' => \Yii::t('itlo/cms', 'Checkbox'),
            'listBox' => \Yii::t('itlo/cms', 'listBox'),
        ];
    }

    public function init()
    {
        parent::init();

        if (!$this->name) {
            $this->name = \Yii::t('itlo/cms', 'Yes/No');
        }
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                'fieldElement' => \Yii::t('itlo/cms', 'Element form'),
                'default_value' => \Yii::t('itlo/cms', 'Default Value'),
            ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                ['fieldElement', 'string'],
                ['default_value', 'boolean'],
            ]);
    }

    /**
     * @return string
     */
    public function renderConfigForm(ActiveForm $activeForm)
    {
        echo $activeForm->fieldSelect($this, 'fieldElement', self::fieldElements());
        echo $activeForm->field($this, 'default_value')->radioList(\Yii::$app->formatter->booleanFormat);
    }

    /**
     * @return \yii\widgets\ActiveField
     */
    public function renderForActiveForm()
    {
        $field = parent::renderForActiveForm();

        if (in_array($this->fieldElement, array_keys(self::fieldElements()))) {
            $fieldElement = $this->fieldElement;

            if ($fieldElement == 'radioList' || $fieldElement == 'listBox') {
                $field->{$fieldElement}(\Yii::$app->formatter->booleanFormat);
            } else {
                $field->{$fieldElement}();
            }

        } else {
            $field->textInput([]);
        }

        return $field;
    }

    /**
     * @varsion > 3.0.2
     *
     * @return $this
     */
    public function addRules()
    {


        if ($this->property->isRequired) {
            $this->property->relatedPropertiesModel->addRule($this->property->code, 'required', [
                'requiredValue' => '1',
                'message' => \Yii::t('yii', '{attribute} cannot be blank.'),
            ]);
        } else {
            $this->property->relatedPropertiesModel->addRule($this->property->code, 'boolean', [
                'trueValue' => '1',
                'falseValue' => '0'
            ]);
        }

        return $this;
    }

    /**
     * @varsion > 3.0.2
     *
     * @return null
     */
    public function getDefaultValue()
    {
        if ($this->default_value !== null) {
            return $this->default_value;
        }

        return;
    }

    /**
     * @return string
     */
    public function getAsText()
    {
        $value = $this->property->relatedPropertiesModel->getAttribute($this->property->code);
        return (string)\Yii::$app->formatter->asBoolean($value);
    }

}