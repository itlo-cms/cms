<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models;

use itlo\cms\relatedProperties\models\RelatedPropertyModel;

/**
 * This is the model class for table "{{%cms_content_property}}".
 *
 * @property CmsUserUniversalPropertyEnum[] $enums
 * @property CmsUserProperty[] $elementProperties
 */
class CmsUserUniversalProperty extends RelatedPropertyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_user_universal_property}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementProperties()
    {
        return $this->hasMany(CmsUserProperty::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnums()
    {
        return $this->hasMany(CmsUserUniversalPropertyEnum::className(), ['property_id' => 'id']);
    }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['code'], 'unique'],
        ]);
    }
}