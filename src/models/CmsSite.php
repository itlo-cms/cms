<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models;

use itlo\cms\components\Cms;
use itlo\cms\models\behaviors\HasStorageFile;
use itlo\modules\cms\user\models\User;
use Yii;
use yii\base\Event;
use yii\base\Exception;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%cms_site}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $active
 * @property string $def
 * @property integer $priority
 * @property string $code
 * @property string $name
 * @property string $server_name
 * @property string $description
 * @property integer $image_id
 *
 * @property string $url
 *
 * @property CmsTree $rootCmsTree
 * @property CmsLang $cmsLang
 * @property CmsSiteDomain[] $cmsSiteDomains
 * @property CmsSiteDomain $cmsSiteMainDomain
 * @property CmsTree[] $cmsTrees
 * @property CmsStorageFile $image
 */
class CmsSite extends Core
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_site}}';
    }


    public function init()
    {
        parent::init();

        $this->on(BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'createTreeAfterInsert']);
        $this->on(BaseActiveRecord::EVENT_BEFORE_INSERT, [$this, 'beforeInsertChecks']);
        $this->on(BaseActiveRecord::EVENT_BEFORE_UPDATE, [$this, 'beforeUpdateChecks']);

        $this->on(BaseActiveRecord::EVENT_BEFORE_DELETE, [$this, 'beforeDeleteRemoveTree']);

    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function beforeDeleteRemoveTree()
    {
        //Before delete site delete all tree
        foreach ($this->cmsTrees as $tree) {
            //$tree->delete();
            /*if (!$tree->deleteWithChildren())
            {
                throw new Exception('Not deleted tree');
            }*/
        }
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            HasStorageFile::className() =>
                [
                    'class' => HasStorageFile::className(),
                    'fields' => ['image_id']
                ],
        ]);
    }

    /**
     * @param Event $e
     * @throws Exception
     */
    public function beforeUpdateChecks(Event $e)
    {
        //???????? ???????? ?????????????? ???? ?????????????????? ????????????, ???? ?????? ???????????????? ?????????? ????????????????.
        if ($this->def == Cms::BOOL_Y) {
            static::updateAll(
                [
                    'def' => Cms::BOOL_N
                ],
                ['!=', 'id', $this->id]
            );

            $this->active = Cms::BOOL_Y; //???????? ???? ?????????????????? ???????????? ????????????????
        }

    }

    /**
     * @param Event $e
     * @throws Exception
     */
    public function beforeInsertChecks(Event $e)
    {
        //???????? ???????? ?????????????? ???? ?????????????????? ????????????, ???? ?????? ???????????????? ?????????? ????????????????.
        if ($this->def == Cms::BOOL_Y) {
            static::updateAll([
                'def' => Cms::BOOL_N
            ]);

            $this->active = Cms::BOOL_Y; //???????? ???? ?????????????????? ???????????? ????????????????
        }

    }

    public function createTreeAfterInsert(Event $e)
    {
        $tree = new Tree([
            'name' => '?????????????? ????????????????',
        ]);

        $tree->makeRoot();
        $tree->cms_site_id = $this->id;

        try {
            if (!$tree->save()) {
                throw new Exception('Failed to create a section of the tree');
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
            throw $e;
        }

    }


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
            'active' => Yii::t('itlo/cms', 'Active'),
            'def' => Yii::t('itlo/cms', 'Default'),
            'priority' => Yii::t('itlo/cms', 'Priority'),
            'code' => Yii::t('itlo/cms', 'Code'),
            'name' => Yii::t('itlo/cms', 'Name'),
            'description' => Yii::t('itlo/cms', 'Description'),
            'image_id' => Yii::t('itlo/cms', 'Image'),
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'priority'], 'integer'],
            [['code', 'name'], 'required'],
            [['active', 'def'], 'string', 'max' => 1],
            [['code'], 'string', 'max' => 15],
            [['name', 'description'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['code'], 'validateCode'],
            ['priority', 'default', 'value' => 500],
            ['active', 'default', 'value' => Cms::BOOL_Y],
            ['def', 'default', 'value' => Cms::BOOL_N],
            [['image_id'], 'safe'],

            [
                ['image_id'],
                \itlo\cms\validators\FileValidator::class,
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'jpeg', 'gif', 'png'],
                'maxFiles' => 1,
                'maxSize' => 1024 * 1024 * 2,
                'minSize' => 1024,
            ],
        ]);
    }

    public function validateCode($attribute)
    {
        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9-]{1,255}$/', $this->$attribute)) {
            $this->addError($attribute, \Yii::t('itlo/cms',
                'Use only letters of the alphabet in lower or upper case and numbers, the first character of the letter (Example {code})',
                ['code' => 'code1']));
        }
    }


    static public $sites = [];

    /**
     * @param (integer) $id
     * @return static
     */
    public static function getById($id)
    {
        if (!array_key_exists($id, static::$sites)) {
            static::$sites[$id] = static::find()->where(['id' => (integer)$id])->one();
        }

        return static::$sites[$id];
    }

    static public $sites_by_code = [];

    /**
     * @param (integer) $id
     * @return static
     */
    public static function getByCode($code)
    {
        if (!array_key_exists($code, static::$sites_by_code)) {
            static::$sites_by_code[$code] = static::find()->where(['code' => (string)$code])->one();
        }

        return static::$sites_by_code[$code];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsSiteDomains()
    {
        return $this->hasMany(CmsSiteDomain::class, ['cms_site_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsSiteMainDomain()
    {
        $query = $this->getCmsSiteDomains()->andWhere(['is_main' => 1]);
        $query->multiple = false;
        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsTrees()
    {
        return $this->hasMany(CmsTree::class, ['cms_site_id' => 'id']);
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->cmsSiteMainDomain) {
            return (($this->cmsSiteMainDomain->is_https ? "https:" : "http:") . "//" . $this->cmsSiteMainDomain->domain);
        }

        return \Yii::$app->urlManager->hostInfo;
    }

    /**
     * @return CmsTree
     */
    public function getRootCmsTree()
    {
        return $this->getCmsTrees()->andWhere(['level' => 0])->limit(1)->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(CmsStorageFile::className(), ['id' => 'image_id']);
    }
}