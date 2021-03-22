<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\cmsWidgets\treeMenu;

use itlo\cms\base\WidgetRenderable;
use itlo\cms\components\Cms;
use itlo\cms\grid\BooleanColumn;
use itlo\cms\models\CmsSite;
use itlo\cms\models\CmsTree;
use itlo\cms\models\Tree;
use itlo\cms\widgets\formInputs\selectTree\SelectTreeInputWidget;
use itlo\yii2\form\fields\BoolField;
use itlo\yii2\form\fields\FieldSet;
use itlo\yii2\form\fields\FieldSetEnd;
use itlo\yii2\form\fields\SelectField;
use itlo\yii2\form\fields\WidgetField;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class TreeMenuCmsWidget
 *
 * @package itlo\cms\cmsWidgets\treeMenu
 */
class TreeMenuCmsWidget extends WidgetRenderable
{
    /**
     * Родительский раздел дерева
     * @var null
     */
    public $treePid = null;

    /**
     * @var null
     */
    public $treeParentCode = null;

    /**
     * Выбор только активных пунктов
     * @var string
     */
    public $active = Cms::BOOL_Y;

    /**
     * Добавить условие уровня раздела
     * @var null
     */
    public $level = null;

    /**
     * Название
     * @var null
     */
    public $label = null;

    /**
     * Условие выборки по сайтам
     * @var array
     */
    public $site_codes = [];

    /**
     * Сортировка по умолчанию
     * @var string
     */
    public $orderBy = "priority";
    public $order = SORT_ASC;

    /**
     * Установить лимит
     * @var int
     */
    public $limit = false;

    /**
     * Добавить условие выборки разделов, только текущего сайта
     * @var string
     */
    public $enabledCurrentSite = Cms::BOOL_Y;

    /**
     * Включить выключить кэш
     * @var string
     */
    public $enabledRunCache = Cms::BOOL_Y;
    public $runCacheDuration = 0;

    /**
     * Типы разделов
     * @var array
     */
    public $tree_type_ids = [];

    /**
     * Дополнительный activeQueryCallback
     * @var
     */
    public $activeQueryCallback;

    /**
     * @see (new ActiveQuery)->with
     * @var array
     */
    public $with = ['children'];


    /**
     * @var ActiveQuery
     */
    public $activeQuery = null;
    public $text = '';
    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => 'Меню разделов',
        ]);
    }
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                'treePid'            => \Yii::t('itlo/cms', 'The parent section'),
                'active'             => \Yii::t('itlo/cms', 'Activity'),
                'level'              => \Yii::t('itlo/cms', 'The nesting level'),
                'label'              => \Yii::t('itlo/cms', 'Header'),
                'site_codes'         => \Yii::t('itlo/cms', 'Linking to sites'),
                'orderBy'            => \Yii::t('itlo/cms', 'Sorting'),
                'order'              => \Yii::t('itlo/cms', 'Sorting direction'),
                'enabledCurrentSite' => \Yii::t('itlo/cms', 'Consider the current site'),
                'enabledRunCache'    => \Yii::t('itlo/cms', 'Enable caching'),
                'runCacheDuration'   => \Yii::t('itlo/cms', 'Cache lifetime'),
                'tree_type_ids'      => \Yii::t('itlo/cms', 'Section types'),
                'limit'              => \Yii::t('itlo/cms', 'The maximum number of entries in the sample ({limit})',
                                                            ['limit' => 'limit']),
            ]);
    }

    public function attributeHints()
    {
        return array_merge(parent::attributeHints(),
            [
                'enabledCurrentSite' => \Yii::t('itlo/cms',
                    'If you select "yes", then the sample section, add the filter condition, sections of the site, which is called the widget'),
                'level'              => \Yii::t('itlo/cms',
                    'Adds the sample sections, the condition of nesting choice. 0 - will not use this condition at all.'),
            ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                ['text', 'string'],
                [['viewFile', 'label', 'active', 'orderBy', 'enabledCurrentSite', 'enabledRunCache'], 'string'],
                [['treePid', 'level', 'runCacheDuration', 'limit'], 'integer'],
                [['order'], 'integer'],
                [['site_codes'], 'safe'],
                [['tree_type_ids'], 'safe'],
            ]);
    }

    /**
     * @return array
     */
    public function getConfigFormFields()
    {
        return [
            'template' => [
                'class'  => FieldSet::class,
                'name'   => \Yii::t('itlo/cms', 'Template'),
                'fields' => [
                    'viewFile',
                ],
            ],

            'filtration' => [
                'class'  => FieldSet::class,
                'name'   => \Yii::t('itlo/cms', 'Filtration'),
                'fields' => [
                    'enabledCurrentSite' => [
                        'class'      => BoolField::class,
                        'trueValue'  => 'Y',
                        'falseValue' => 'N',
                    ],
                    'active'             => [
                        'class'      => BoolField::class,
                        'trueValue'  => 'Y',
                        'falseValue' => 'N',
                    ],
                    'tree_type_ids'      => [
                        'class'    => SelectField::class,
                        'items'    => \yii\helpers\ArrayHelper::map(
                            \itlo\cms\models\CmsTreeType::find()->all(), 'id', 'name'
                        ),
                        'multiple' => true,
                    ],
                    'level'              => [
                        'elementOptions' => [
                            'type' => 'number',
                        ],
                    ],
                    'site_codes'         => [
                        'class'    => SelectField::class,
                        'items'    => \yii\helpers\ArrayHelper::map(
                            \itlo\cms\models\CmsSite::find()->active()->all(),
                            'code',
                            'name'
                        ),
                        'multiple' => true,
                    ],
                    'treePid'            => [
                        'class'       => WidgetField::class,
                        'widgetClass' => SelectTreeInputWidget::class,
                    ],
                ],
            ],
            'sort'       => [
                'class'  => FieldSet::class,
                'name'   => \Yii::t('itlo/cms', 'Sorting'),
                'fields' => [
                    'orderBy' => [
                        'class' => SelectField::class,
                        'items' => (new \itlo\cms\models\Tree())->attributeLabels(),
                    ],
                    'order'   => [
                        'class' => SelectField::class,
                        'items' => [
                            SORT_ASC  => \Yii::t('itlo/cms', 'ASC (from lowest to highest)'),
                            SORT_DESC => \Yii::t('itlo/cms', 'DESC (from highest to lowest)'),
                        ],
                    ],
                    'limit'
                ],
            ],
            'additionally'       => [
                'class'  => FieldSet::class,
                'name'   => \Yii::t('itlo/cms', 'Additionally'),
                'fields' => [
                    'label'
                ],
            ],
            'cache'       => [
                'class'  => FieldSet::class,
                'name'   => \Yii::t('itlo/cms', 'Cache settings'),
                'fields' => [
                    'enabledRunCache' => [
                        'class' => BoolField::class,
                        'trueValue'  => 'Y',
                        'falseValue' => 'N',
                        'allowNull' => false,
                    ],
                    'runCacheDuration' => [
                        'elementOptions' => [
                            'type' => 'number',
                        ],
                    ],
                ],
            ],
        ];
    }


    public function init()
    {
        parent::init();

        $this->initActiveQuery();
    }

    /**
     * Инициализация acitveQuery
     * @return $this
     */
    public function initActiveQuery()
    {
        $this->activeQuery = Tree::find();

        if ($this->treePid) {
            $this->activeQuery->andWhere(['pid' => $this->treePid]);
        } elseif ($this->treeParentCode) {
            $tree = CmsTree::find()->where(['code' => $this->treeParentCode])->one();
            if ($tree) {
                $this->activeQuery->andWhere(['pid' => $tree->id]);
            }
        }

        if ($this->level) {
            $this->activeQuery->andWhere(['level' => $this->level]);
        }

        if ($this->active) {
            $this->activeQuery->andWhere(['active' => $this->active]);
        }

        if ($this->site_codes) {
            $cmsSites = CmsSite::find()->where(['code' => $this->site_codes])->all();
            if ($cmsSites) {
                $ids = ArrayHelper::map($cmsSites, 'id', 'id');
                $this->activeQuery->andWhere(['cms_site_id' => $ids]);
            }
        }

        if ($this->enabledCurrentSite == Cms::BOOL_Y && \Yii::$app->cms->site) {
            $this->activeQuery->andWhere(['cms_site_id' => \Yii::$app->cms->site->id]);
        }

        if ($this->orderBy) {
            $this->activeQuery->orderBy([$this->orderBy => (int)$this->order]);
        }

        if ($this->tree_type_ids) {
            $this->activeQuery->andWhere(['tree_type_id' => $this->tree_type_ids]);
        }


        if ($this->with) {
            $this->activeQuery->with($this->with);
        }
        if (!$this->limit) {
            $this->limit = 200;
        }
        if ($this->limit) {
            $this->activeQuery->limit($this->limit);
        }


        if ($this->activeQueryCallback && is_callable($this->activeQueryCallback)) {
            $callback = $this->activeQueryCallback;
            $callback($this->activeQuery);
        }

        return $this;
    }

    public function run()
    {
        $key = $this->getCacheKey().'run';

        $dependency = new TagDependency([
            'tags' =>
                [
                    $this->className().(string)$this->namespace,
                    (new Tree())->getTableCacheTag(),
                ],
        ]);

        $result = \Yii::$app->cache->get($key);
        if ($result === false || $this->enabledRunCache == Cms::BOOL_N) {
            $result = parent::run();
            \Yii::$app->cache->set($key, $result, (int)$this->runCacheDuration, $dependency);
        }

        return $result;
    }

}