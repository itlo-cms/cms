<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models;

use itlo\cms\base\Component;
use itlo\cms\models\behaviors\HasJsonFieldsBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%cms_component_settings}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $component
 * @property string $value
 * @property integer $cms_site_id
 * @property integer $user_id
 * @property string $namespace
 *
 * @property CmsLang $lang
 * @property CmsSite $site
 * @property User $user
 */
class CmsComponentSettings extends Core
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_component_settings}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            HasJsonFieldsBehavior::className() =>
                [
                    'class' => HasJsonFieldsBehavior::className(),
                    'fields' => ['value']
                ]
        ]);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'user_id'], 'integer'],
            [['value'], 'safe'],
            [['component'], 'string', 'max' => 255],
            [['cms_site_id'], 'integer'],
            [['namespace'], 'string', 'max' => 50]
        ]);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'id' => Yii::t('itlo/cms', 'ID'),
            'value' => Yii::t('itlo/cms', 'Value'),
            'component' => Yii::t('itlo/cms', 'Component'),

            'cms_site_id' => Yii::t('itlo/cms', 'Site Code'),
            'user_id' => Yii::t('itlo/cms', 'User ID'),
            'namespace' => Yii::t('itlo/cms', 'Namespace'),
        ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(CmsSite::className(), ['id' => 'cms_site_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'user_id']);
    }


    /**
     * @param $component
     * @return ActiveQuery
     */
    public static function findByComponent(Component $component)
    {
        $query = static::find()->where([
            'component' => $component->className(),
        ]);

        if ($component->namespace) {
            $query->andWhere(['namespace' => $component->namespace]);
        }

        return $query;
    }



    /**
     * Overrides
     */
    /**
     * @param Component $component
     * @return ActiveQuery
     */
    public static function findByComponentDefault(Component $component)
    {
        return static::findByComponent($component)
            ->andWhere(['cms_site_id' => null])
            ->andWhere(['user_id' => null]);
    }


    /**
     * @param Component $component
     * @param CmsUser $user
     * @return ActiveQuery
     */
    public static function findByComponentUser(Component $component, $user)
    {
        return static::findByComponent($component)->andWhere(['user_id' => (int)$user->id]);
    }

    /**
     * @param Component $component
     * @param CmsUser $user
     * @return ActiveQuery
     */
    public static function findByComponentSite(Component $component, CmsSite $cmsSite)
    {
        return static::findByComponent($component)->andWhere(['cms_site_id' => (int)$cmsSite->id]);
    }
}
