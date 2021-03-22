<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models;

use itlo\cms\components\Cms;
use itlo\cms\validators\PhoneValidator;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cms_user_phone".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $value
 * @property string $approved
 * @property string $def
 * @property string $approved_key
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CmsUser $user
 */
class CmsUserPhone extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_user_phone}}';
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            TimestampBehavior::className()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'required'],
            [['value'], PhoneValidator::className()],
            [['value', 'approved_key'], 'string', 'max' => 255],
            [['approved', 'def'], 'string', 'max' => 1],
            [['value'], 'unique'],
            [['approved'], 'default', 'value' => Cms::BOOL_N],
            [['def'], 'default', 'value' => Cms::BOOL_N],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('itlo/cms', 'ID'),
            'user_id' => Yii::t('itlo/cms', 'User'),
            'value' => \Yii::t('itlo/cms', "Phone Number"),
            'approved' => \Yii::t('itlo/cms', "Approved"),
            'created_at' => Yii::t('itlo/cms', 'Created At'),
            'updated_at' => Yii::t('itlo/cms', 'Updated At'),
            'def' => 'Def',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'user_id']);
    }
}
