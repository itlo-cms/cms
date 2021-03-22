<?php
/**
 * Модель значения связанного свойства.
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\relatedProperties\models;

use itlo\cms\models\Core;
use Yii;

/**
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $property_id
 * @property string $value
 * @property string $def
 * @property string $code
 * @property integer $priority
 *
 * @property RelatedPropertyModel $property
 */
abstract class RelatedPropertyEnumModel extends Core
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
            'property_id' => Yii::t('itlo/cms', 'Property'),
            'value' => Yii::t('itlo/cms', 'Value'),
            'def' => Yii::t('itlo/cms', 'Default'),
            'code' => Yii::t('itlo/cms', 'Code'),
            'priority' => Yii::t('itlo/cms', 'Priority'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'property_id', 'priority'], 'integer'],
            [['value', 'property_id'], 'required'],
            [['value'], 'string', 'max' => 255],
            [['def'], 'string', 'max' => 1],
            [['code'], 'string', 'max' => 32],
            [
                'code',
                'default',
                'value' => function($model, $attribute) {
                    return md5(rand(1, 10) . time());
                }
            ],
            [
                'priority',
                'default',
                'value' => function($model, $attribute) {
                    return 500;
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
}