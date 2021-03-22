<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\relatedProperties\propertyTypes;

use itlo\cms\backend\widgets\SelectModelDialogContentElementWidget;
use itlo\cms\components\Cms;
use itlo\cms\models\CmsContentElement;
use itlo\cms\relatedProperties\models\RelatedPropertiesModel;
use itlo\cms\relatedProperties\PropertyType;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class PropertyTypeElement
 * @package itlo\cms\relatedProperties\propertyTypes
 */
class PropertyTypeElement extends PropertyType
{
    public $code = self::CODE_ELEMENT;
    public $name = "";

    const FIELD_ELEMENT_SELECT = "select";
    const FIELD_ELEMENT_SELECT_MULTI = "selectMulti";
    const FIELD_ELEMENT_RADIO_LIST = "radioList";
    const FIELD_ELEMENT_CHECKBOX_LIST = "checkbox";
    const FIELD_ELEMENT_SELECT_DIALOG = "selectDialog";
    const FIELD_ELEMENT_SELECT_DIALOG_MULTIPLE = "selectDialogMulti";

    public $fieldElement = self::FIELD_ELEMENT_SELECT;
    public $content_id;

    public static function fieldElements()
    {
        return [
            self::FIELD_ELEMENT_SELECT => \Yii::t('itlo/cms', 'Combobox') . ' (select)',
            self::FIELD_ELEMENT_SELECT_MULTI => \Yii::t('itlo/cms', 'Combobox') . ' (select multiple)',
            self::FIELD_ELEMENT_RADIO_LIST => \Yii::t('itlo/cms', 'Radio Buttons (selecting one value)'),
            self::FIELD_ELEMENT_CHECKBOX_LIST => \Yii::t('itlo/cms', 'Checkbox List'),
            self::FIELD_ELEMENT_SELECT_DIALOG => \Yii::t('itlo/cms', 'Selection widget in the dialog box'),
            self::FIELD_ELEMENT_SELECT_DIALOG_MULTIPLE => \Yii::t('itlo/cms',
                'Selection widget in the dialog box (multiple choice)'),
        ];
    }

    public function init()
    {
        parent::init();

        if (!$this->name) {
            $this->name = \Yii::t('itlo/cms', 'Binding to an element');
        }
    }

    /**
     * @return bool
     */
    public function getIsMultiple()
    {
        if (in_array($this->fieldElement, [
            self::FIELD_ELEMENT_SELECT_MULTI
            ,
            self::FIELD_ELEMENT_CHECKBOX_LIST
            ,
            self::FIELD_ELEMENT_SELECT_DIALOG_MULTIPLE
        ])) {
            return true;
        }

        return false;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                'content_id' => \Yii::t('itlo/cms', 'Content'),
                'fieldElement' => \Yii::t('itlo/cms', 'Form element type'),
            ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                ['content_id', 'integer'],
                ['fieldElement', 'in', 'range' => array_keys(static::fieldElements())],
                ['fieldElement', 'string'],
            ]);
    }

    /**
     * @return \yii\widgets\ActiveField
     */
    public function renderForActiveForm()
    {
        $field = parent::renderForActiveForm();

        $find = CmsContentElement::find()->active();

        if ($this->content_id) {
            $find->andWhere(['content_id' => $this->content_id]);
        }


        if ($this->fieldElement == self::FIELD_ELEMENT_SELECT) {
            $config = [];
            if ($this->property->is_required == Cms::BOOL_Y) {
                $config['allowDeselect'] = false;
            } else {
                $config['allowDeselect'] = true;
            }

            $field = $this->activeForm->fieldSelect(
                $this->property->relatedPropertiesModel,
                $this->property->code,
                ArrayHelper::map($find->all(), 'id', 'name'),
                $config
            );
        } else {
            if ($this->fieldElement == self::FIELD_ELEMENT_SELECT_MULTI) {
                $field = $this->activeForm->fieldSelectMulti(
                    $this->property->relatedPropertiesModel,
                    $this->property->code,
                    ArrayHelper::map($find->all(), 'id', 'name'),
                    []
                );
            } else {
                if ($this->fieldElement == self::FIELD_ELEMENT_RADIO_LIST) {
                    $field = parent::renderForActiveForm();
                    $field->radioList(ArrayHelper::map($find->all(), 'id', 'name'));

                } else {
                    if ($this->fieldElement == self::FIELD_ELEMENT_CHECKBOX_LIST) {
                        $field = parent::renderForActiveForm();
                        $field->checkboxList(ArrayHelper::map($find->all(), 'id', 'name'));
                    } else {
                        if ($this->fieldElement == self::FIELD_ELEMENT_SELECT_DIALOG) {
                            $field = parent::renderForActiveForm();
                            $field->widget(
                                SelectModelDialogContentElementWidget::class,
                                [
                                    'content_id' => $this->content_id
                                ]
                            );
                        } else {
                            if ($this->fieldElement == self::FIELD_ELEMENT_SELECT_DIALOG_MULTIPLE) {
                                $field = parent::renderForActiveForm();
                                $field->widget(
                                    SelectModelDialogContentElementWidget::class,
                                    [
                                        'content_id' => $this->content_id,
                                        'multiple' => true
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }


        if (!$field) {
            return '';
        }


        return $field;
    }


    /**
     * @return string
     */
    public function renderConfigForm(ActiveForm $activeForm)
    {
        echo $activeForm->fieldSelect($this, 'fieldElement',
            \itlo\cms\relatedProperties\propertyTypes\PropertyTypeElement::fieldElements());
        echo $activeForm->fieldSelect($this, 'content_id', \itlo\cms\models\CmsContent::getDataForSelect());
    }

    /**
     * @varsion > 3.0.2
     *
     * @return $this
     */
    public function addRules()
    {
        if ($this->isMultiple) {
            $this->property->relatedPropertiesModel->addRule($this->property->code, 'safe');
        } else {
            $this->property->relatedPropertiesModel->addRule($this->property->code, 'integer');
        }

        if ($this->property->isRequired) {
            $this->property->relatedPropertiesModel->addRule($this->property->code, 'required');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getAsText()
    {
        $value = $this->property->relatedPropertiesModel->getAttribute($this->property->code);

        if ($this->isMultiple) {
            $data = ArrayHelper::map(CmsContentElement::find()->where(['id' => $value])->all(), 'id', 'name');
            return implode(', ', $data);
        } else {
            if ($element = CmsContentElement::find()->where(['id' => $value])->one()) {
                return $element->name;
            }

            return "";
        }
    }
}