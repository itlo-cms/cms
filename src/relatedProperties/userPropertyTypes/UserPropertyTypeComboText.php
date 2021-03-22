<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\relatedProperties\userPropertyTypes;

use itlo\cms\components\Cms;
use itlo\cms\models\CmsContentElement;
use itlo\cms\relatedProperties\models\RelatedPropertiesModel;
use itlo\cms\relatedProperties\PropertyType;
use yii\helpers\ArrayHelper;

/**
 * Class UserPropertyTypeComboText
 * @package itlo\cms\relatedProperties\userPropertyTypes
 */
class UserPropertyTypeComboText extends PropertyType
{
    public $code = self::CODE_STRING;
    public $name = "Текст/CKEditor/HTML";


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                //'type'  => 'Тип',
            ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                //['type', 'string'],
                //['type', 'in', 'range' => array_keys(self::$types)],
            ]);
    }

    /**
     * @return \yii\widgets\ActiveField
     */
    public function renderForActiveForm()
    {
        $field = parent::renderForActiveForm();

        $field->widget(\itlo\cms\widgets\formInputs\comboText\ComboTextInputWidget::className(),
            [
                'ckeditorOptions' =>
                    [
                        'relatedModel' => $this->property->relatedPropertiesModel->relatedElementModel
                    ]
            ]);

        return $field;
    }


    /**
     * Файл с формой настроек, по умолчанию лежит в той же папке где и компонент.
     *
     * @return string
     */
    public function getConfigFormFile()
    {
        $class = new \ReflectionClass($this->className());
        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'views/_formUserPropertyTypeComboText.php';
    }

    /**
     * @varsion > 3.0.2
     *
     * @return $this
     */
    public function addRules()
    {
        $this->property->relatedPropertiesModel->addRule($this->property->code, 'string');

        return $this;
    }
}