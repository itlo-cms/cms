<?php
/**
 * Модель связанного свойства.
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\relatedProperties\models;

use itlo\cms\components\Cms;
use itlo\cms\models\Core;
use Yii;
use yii\db\BaseActiveRecord;
use yii\widgets\ActiveForm;

/**
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $property_id
 * @property integer $element_id
 * @property string $value
 * @property integer $value_enum
 * @property string $value_num
 * @property string $description
 *
 * @property RelatedPropertyModel $property
 * @property RelatedElementModel $element
 */
abstract class RelatedElementPropertyModel extends Core
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'id' => Yii::t('itlo/cms', 'ID'),
            'created_by' => Yii::t('itlo/cms', 'Created By'),
            'updated_by' => Yii::t('itlo/cms', 'Updated By'),
            'created_at' => Yii::t('itlo/cms', 'Created At'),
            'updated_at' => Yii::t('itlo/cms', 'Updated At'),
            'property_id' => Yii::t('itlo/cms', 'Property ID'),
            'element_id' => Yii::t('itlo/cms', 'Element ID'),
            'value' => Yii::t('itlo/cms', 'Value'),
            'value_enum' => Yii::t('itlo/cms', 'Value Enum'),
            'value_num' => Yii::t('itlo/cms', 'Value Num'),
            'description' => Yii::t('itlo/cms', 'Description'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'property_id', 'element_id'], 'integer'],
            [['description'], 'string', 'max' => 255],
            //[['value_string'], 'string', 'max' => 255],
            [['value', 'value_string'], 'string'],

            [
                ['value_enum', 'value_int2'],
                'filter',
                'filter' => function($value) {
                    $value = (int)$value;
                    $filter_options = [
                        'options' => [
                            'default' => 0,
                            'min_range' => -2147483648,
                            'max_range' => 2147483647
                        ]
                    ];
                    return filter_var($value, FILTER_VALIDATE_INT, $filter_options);
                }
            ],
            [['value_enum', 'value_int2'], 'integer'],


            [
                ['value_num', 'value_num2'],
                'filter',
                'filter' => function($value) {
                    $value = (float)$value;
                    $min_range = -1.0E+14;
                    $max_range = 1.0E+14;
                    if ($value <= $min_range || $value >= $max_range) {
                        return 0.0;
                    }
                    return $value;
                }
            ],
            [['value_num', 'value_num2'], 'number'],


            ['value_bool', 'boolean'],
            [
                'value_bool',
                'filter',
                'filter' => function($value) {
                    $value = (bool)$value;
                    return $value;
                }
            ],

        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    abstract public function getProperty();
    /*{
        return $this->hasOne(CmsContentProperty::className(), ['id' => 'property_id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    abstract public function getElement();
    /*{
        return $this->hasOne(CmsContentElement::className(), ['id' => 'element_id']);
    }*/
}
