<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\relatedProperties\propertyTypes;

use itlo\cms\backend\widgets\SelectModelDialogTreeWidget;
use itlo\cms\components\Cms;
use itlo\cms\models\CmsTree;
use itlo\cms\relatedProperties\PropertyType;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class PropertyTypeTree
 * @package itlo\cms\relatedProperties\propertyTypes
 */
class PropertyTypeTree extends PropertyType
{
    public $code = self::CODE_TREE;
    public $name = "Привязка к разделу";

    public $is_multiple = false;
    public $root_tree_id = null;


    const FIELD_ELEMENT_DEFAULT = "selectDefault";
    const FIELD_ELEMENT_SELECT_DIALOG = "selectDialog";

    public $fieldElement = self::FIELD_ELEMENT_DEFAULT;

    public static function fieldElements()
    {
        return [
            self::FIELD_ELEMENT_DEFAULT => \Yii::t('itlo/cms', 'Standard selection element'),
            self::FIELD_ELEMENT_SELECT_DIALOG => \Yii::t('itlo/cms', 'Selection in the dialog box'),
        ];
    }

    /**
     * Файл с формой настроек, по умолчанию лежит в той же папке где и компонент.
     *
     * @return string
     */
    public function renderConfigForm(ActiveForm $activeForm)
    {
        echo $activeForm->field($this, 'is_multiple')->checkbox(\Yii::$app->formatter->booleanFormat);
        echo $activeForm->fieldSelect($this, 'fieldElement', static::fieldElements());
        echo $activeForm->field($this, 'root_tree_id')->widget(
            ///\itlo\cms\widgets\formInputs\selectTree\SelectTreeInputWidget::class
            SelectModelDialogTreeWidget::class
        );
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                'is_multiple' => \Yii::t('itlo/cms', 'Multiple choice'),
                'fieldElement' => \Yii::t('itlo/cms', 'Form element type'),
                'root_tree_id' => \Yii::t('itlo/cms', 'Root partition'),
            ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                ['is_multiple', 'boolean'],
                ['fieldElement', 'in', 'range' => array_keys(static::fieldElements())],
                ['fieldElement', 'string'],
                ['root_tree_id', 'integer'],
            ]);
    }


    /**
     * @return bool
     */
    public function getIsMultiple()
    {
        return $this->is_multiple;
    }


    /**
     * @return \yii\widgets\ActiveField
     */
    public function renderForActiveForm()
    {
        $field = parent::renderForActiveForm();


        if ($this->fieldElement == static::FIELD_ELEMENT_SELECT_DIALOG) {

            $options = [
                "multiple" => $this->isMultiple ? true : false,
            ];

            if ($this->root_tree_id) {
                $options['dialogRoute'] = ['/cms/admin-tree', 'root_id' => $this->root_tree_id];
            }

            $field->widget(
                SelectModelDialogTreeWidget::className(),
                $options
            );
        } else {
            $rootTreeModels = [];
            if ($this->root_tree_id) {
                $rootTreeModels = CmsTree::findAll($this->root_tree_id);
            }
            $field->widget(
                \itlo\cms\widgets\formInputs\selectTree\SelectTreeInputWidget::className(),
                [
                    "multiple" => $this->isMultiple ? true : false,
                    'treeWidgetOptions' =>
                        [
                            'models' => $rootTreeModels
                        ]
                ]
            );
        }


        return $field;
    }
    
    /**
     * @return string
     */
    public function getAsText()
    {
        $value = $this->property->relatedPropertiesModel->getAttribute($this->property->code);

        if ($this->isMultiple) {
            $data = ArrayHelper::map(CmsTree::find()->where(['id' => $value])->all(), 'id', 'name');
            return implode(', ', $data);
        } else {
            if ($element = CmsTree::find()->where(['id' => $value])->one()) {
                return $element->name;
            }

            return "";
        }
    }
}