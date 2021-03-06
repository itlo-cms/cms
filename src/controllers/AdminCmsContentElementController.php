<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\actions\backend\BackendModelMultiActivateAction;
use itlo\cms\actions\backend\BackendModelMultiDeactivateAction;
use itlo\cms\backend\actions\BackendGridModelAction;
use itlo\cms\backend\actions\BackendModelMultiDialogEditAction;
use itlo\cms\backend\actions\BackendModelUpdateAction;
use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\backend\widgets\SelectModelDialogTreeWidget;
use itlo\cms\backend\widgets\SelectModelDialogUserWidget;
use itlo\cms\grid\DateTimeColumnData;
use itlo\cms\grid\ImageColumn2;
use itlo\cms\helpers\Image;
use itlo\cms\helpers\RequestResponse;
use itlo\cms\IHasUrl;
use itlo\cms\models\CmsContent;
use itlo\cms\models\CmsContentElement;
use itlo\cms\models\CmsContentElementProperty;
use itlo\cms\models\CmsContentPropertyEnum;
use itlo\cms\modules\admin\actions\AdminAction;
use itlo\cms\modules\admin\actions\modelEditor\AdminModelEditorAction;
use itlo\cms\modules\admin\widgets\GridViewStandart;
use itlo\cms\queryfilters\filters\modes\FilterModeEq;
use itlo\cms\queryfilters\QueryFiltersEvent;
use itlo\yii2\form\fields\BoolField;
use itlo\yii2\form\fields\SelectField;
use itlo\yii2\form\fields\TextField;
use itlo\yii2\form\fields\WidgetField;
use yii\base\DynamicModel;
use yii\base\Event;
use yii\base\Exception;
use yii\bootstrap\Alert;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\UnsetArrayValue;
use yii\helpers\Url;
use yii\web\Application;

/**
 * @property CmsContent|static $content
 *
 * Class AdminCmsContentTypeController
 * @package itlo\cms\controllers
 */
class AdminCmsContentElementController extends BackendModelStandartController
{
    public $notSubmitParam = 'sx-not-submit';

    public $modelClassName = CmsContentElement::class;
    public $modelShowAttribute = "name";
    /**
     * @var CmsContent
     */
    protected $_content = null;


    public function init()
    {
        $this->name = \Yii::t('itlo/cms', 'Elements');

        if ($this->content) {
            if ($this->permissionName === null) {
                $this->permissionName = $this->uniqueId."__".$this->content->id;
            }
        }

        $this->modelHeader = function () {
            /**
             * @var $model CmsContentElement
             */
            $model = $this->model;
            return Html::tag('h1', $model->name.Html::a('<i class="fas fa-external-link-alt"></i>', $model->url, [
                    'target' => "_blank",
                    'class'  => "g-ml-20",
                    'title'  => \Yii::t('itlo/cms', 'Watch to site (opens new window)'),
                ]));
        };

        parent::init();


    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $result = ArrayHelper::merge(parent::actions(), [
            'index'            => [
                'configKey'      => $this->uniqueId."-".($this->content ? $this->content->id : ""),
                'on afterRender' => [$this, 'contentEdit'],
                //'url' => [$this->uniqueId, 'content_id' => $this->content->id],
                'on init'        => function ($e) {
                    $action = $e->sender;
                    /**
                     * @var $action BackendGridModelAction
                     */
                    if ($this->content) {
                        $action->url = ["/".$action->uniqueId, 'content_id' => $this->content->id];
                        $this->initGridData($action, $this->content);
                    }

                },

                "filters" => [
                    'visibleFilters' => [
                        'q',
                        'id',
                        //'name',
                        'active',
                    ],
                    'filtersModel'   => [

                        'rules' => [
                            ['q', 'safe'],
                            ['has_image', 'safe'],
                        ],

                        'attributeDefines' => [
                            'q',
                            'has_image',
                        ],

                        'fields' => [

                            'active' => [
                                'field'             => [
                                    'class'      => BoolField::class,
                                    'trueValue'  => 'Y',
                                    'falseValue' => 'N',
                                ],
                                'defaultMode'       => FilterModeEq::ID,
                                'isAllowChangeMode' => false,
                            ],

                            'created_by' => [
                                /*'class' => WidgetField::class,
                                'widgetClass' => SelectModelDialogUserWidget::class,*/
                                'isAllowChangeMode' => false,
                                'field'             => [
                                    'class'       => WidgetField::class,
                                    'widgetClass' => SelectModelDialogUserWidget::class,
                                    'items'       => new UnsetArrayValue(),
                                    'multiple'    => new UnsetArrayValue(),
                                ],
                            ],

                            'updated_by' => [
                                /*'class' => WidgetField::class,
                                'widgetClass' => SelectModelDialogUserWidget::class,*/
                                'isAllowChangeMode' => false,
                                'field'             => [
                                    'class'       => WidgetField::class,
                                    'widgetClass' => SelectModelDialogUserWidget::class,
                                    'items'       => new UnsetArrayValue(),
                                    'multiple'    => new UnsetArrayValue(),
                                ],
                            ],

                            'tree_id' => [
                                /*'class' => WidgetField::class,
                                'widgetClass' => SelectModelDialogUserWidget::class,*/
                                'isAllowChangeMode' => false,
                                'field'             => [
                                    'class'       => WidgetField::class,
                                    'widgetClass' => SelectModelDialogTreeWidget::class,
                                    //'items'       => new UnsetArrayValue(),
                                    //'multiple'    => new UnsetArrayValue(),
                                ],
                            ],

                            'q' => [
                                'label'          => '??????????',
                                'elementOptions' => [
                                    'placeholder' => '?????????? (????????????????, ????????????????)',
                                ],
                                'on apply'       => function (QueryFiltersEvent $e) {
                                    /**
                                     * @var $query ActiveQuery
                                     */
                                    $query = $e->dataProvider->query;

                                    if ($e->field->value) {
                                        $query->andWhere([
                                            'or',
                                            ['like', CmsContentElement::tableName().'.name', $e->field->value],
                                            ['like', CmsContentElement::tableName().'.description_short', $e->field->value],
                                            ['like', CmsContentElement::tableName().'.description_full', $e->field->value],
                                        ]);
                                    }
                                },
                            ],

                            'has_image' => [
                                'class'      => BoolField::class,
                                'falseValue' => 'n',
                                'label'      => '?????????????? ??????????????????????',
                                'on apply'   => function (QueryFiltersEvent $e) {
                                    /**
                                     * @var $query ActiveQuery
                                     */
                                    $query = $e->dataProvider->query;

                                    if ($e->field->value) {
                                        if ($e->field->value == '1') {
                                            $query->andWhere(
                                                ['IS NOT', CmsContentElement::tableName().'.image_id', null]
                                            );
                                        } else if ($e->field->value == 'n') {
                                            $query->andWhere(
                                                [CmsContentElement::tableName().'.image_id' => null]
                                            );
                                        }
                                    }
                                },
                            ],

                            'has_full_image' => [
                                'class'      => BoolField::class,
                                'falseValue' => 'n',
                                'label'      => '?????????????? ???????????????????? ??????????????????????',
                                'on apply'   => function (QueryFiltersEvent $e) {
                                    /**
                                     * @var $query ActiveQuery
                                     */
                                    $query = $e->dataProvider->query;

                                    if ($e->field->value) {
                                        if ($e->field->value == '1') {
                                            $query->andWhere(
                                                ['IS NOT', CmsContentElement::tableName().'.image_full_id', null]
                                            );
                                        } else if ($e->field->value == 'n') {
                                            $query->andWhere(
                                                [CmsContentElement::tableName().'.image_full_id' => null]
                                            );
                                        }
                                    }
                                },
                            ],
                        ],
                    ],
                ],
                'grid'    => [
                    'on init'        => function (Event $event) {
                        /**
                         * @var $query ActiveQuery
                         */
                        $query = $event->sender->dataProvider->query;
                        if ($this->content) {
                            $query->andWhere(['content_id' => $this->content->id]);
                        }
                    },
                    'defaultOrder'   => [
                        'active'   => SORT_DESC,
                        'priority' => SORT_ASC,
                        'id'       => SORT_DESC,
                    ],
                    'visibleColumns' => [
                        'checkbox',
                        'actions',
                        'custom',

                        //'image_id',
                        //'name',

                        //'tree_id',
                        //'additionalSections',
                        //'published_at',
                        'priority',

                        'created_by',

                        'active',

                        'view',
                    ],
                    'columns'        => [
                        'test'         => [
                            'label' => "test",
                            'value' => 1,
                        ],
                        'active'       => [
                            //'class' => BooleanColumn::class,
                            'format'         => 'raw',
                            'headerOptions'  => [
                                'style' => '100px',
                            ],
                            'contentOptions' => [
                                'style' => '100px',
                            ],
                            'value'          => function (\itlo\cms\models\CmsContentElement $model) {
                                if ($model->active == "Y") {
                                    $time = \Yii::$app->formatter->asRelativeTime($model->published_at);
                                    $dateTime = \Yii::$app->formatter->asDatetime($model->published_at);
                                    return <<<HTML
<span class="fa fa-check text-success" title=""></span> <small title="{$dateTime}">{$time}</small>
HTML;

                                } else {
                                    return <<<HTML
<span class="fa fa-times text-danger" title=""></span>
HTML;
                                }
                            },
                        ],
                        'custom'       => [
                            'attribute' => 'id',
                            'format'    => 'raw',
                            'value'     => function (\itlo\cms\models\CmsContentElement $model) {

                                $data = [];
                                /*$data[] = "<div class='sx-trigger-action' style='width: 50px; float: left;'>".Html::a(
                                        Html::img($model->image ? $model->image->src : Image::getCapSrc(), [
                                            'style' => 'max-width: 50px; max-height: 50px; border-radius: 5px;',
                                        ])
                                        , "#", ['class' => 'sx-trigger-action', 'style' => 'width: 50px;'])."</div>";*/

                                $data[] = Html::a($model->asText, "#", ['class' => 'sx-trigger-action']);

                                if ($model->tree_id) {
                                    $data[] = Html::a($model->cmsTree->fullName, $model->cmsTree->url, [
                                        'data-pjax' => '0',
                                        'target'    => '_blank',
                                        'style'     => 'color: #333; max-width: 200px;',
                                    ]);
                                }

                                if ($model->cmsTrees) {
                                    foreach ($model->cmsTrees as $cmsTree) {
                                        $data[] = Html::a($cmsTree->fullName, $cmsTree->url, [
                                            'data-pjax' => '0',
                                            'target'    => '_blank',
                                            'style'     => 'color: #333; max-width: 200px; ',
                                        ]);
                                    }
                                }

                                $info = implode("<br />", $data);

                                return "<div class='row no-gutters'>
                                                <div style='margin-left: 5px;'>
                                                <div class='sx-trigger-action' style='width: 50px; margin-right: 10px; float: left;'>
                                                    <a href='#' style='text-decoration: none; border-bottom: 0;'>
                                                        <img src='".($model->image ? $model->image->src : Image::getCapSrc())."' style='max-width: 50px; max-height: 50px; border-radius: 5px;' />
                                                    </a>
                                                </div>".$info."</div></div>";;
                            },
                        ],
                        'image_id'     => [
                            'class' => ImageColumn2::class,
                        ],
                        'published_at' => [
                            'class' => DateTimeColumnData::class,
                        ],
                        'created_at'   => [
                            'class' => DateTimeColumnData::class,
                        ],
                        'updated_at'   => [
                            'class' => DateTimeColumnData::class,
                        ],
                        'priority'     => [
                            'headerOptions'  => [
                                'style' => 'max-width: 100px;',
                            ],
                            'contentOptions' => [
                                'style' => 'max-width: 100px;',
                            ],
                        ],

                        'tree_id' => [
                            'value'  => function (\itlo\cms\models\CmsContentElement $model) {
                                if (!$model->cmsTree) {
                                    return null;
                                }

                                $path = [];

                                if ($model->cmsTree->parents) {
                                    foreach ($model->cmsTree->parents as $parent) {
                                        if ($parent->isRoot()) {
                                            $path[] = "[".$parent->site->name."] ".$parent->name;
                                        } else {
                                            $path[] = $parent->name;
                                        }
                                    }
                                }
                                $path = implode(" / ", $path);
                                return "<small><a href='{$model->cmsTree->url}' target='_blank' data-pjax='0'>{$path} / {$model->cmsTree->name}</a></small>";
                            },
                            'format' => 'raw',
                        ],

                        'view' => [
                            'value'          => function (\itlo\cms\models\CmsContentElement $model) {
                                return \yii\helpers\Html::a('<i class="fas fa-external-link-alt"></i>', $model->absoluteUrl,
                                    [
                                        'target'    => '_blank',
                                        'title'     => \Yii::t('itlo/cms', 'Watch to site (opens new window)'),
                                        'data-pjax' => '0',
                                        'class'     => 'btn btn-sm',
                                    ]);
                            },
                            'format'         => 'raw',
                            /*'label'  => "????????????????",*/
                            'headerOptions'  => [
                                'style' => 'max-width: 40px;',
                            ],
                            'contentOptions' => [
                                'style' => 'max-width: 40px;',
                            ],
                        ],

                        'additionalSections' => [
                            'value'   => function (\itlo\cms\models\CmsContentElement $model) {
                                $result = [];

                                if ($model->cmsContentElementTrees) {
                                    foreach ($model->cmsContentElementTrees as $contentElementTree) {

                                        $site = $contentElementTree->tree->root->site;
                                        $result[] = "<small><a href='{$contentElementTree->tree->url}' target='_blank' data-pjax='0'>[{$site->name}]/.../{$contentElementTree->tree->name}</a></small>";

                                    }
                                }

                                return implode('<br />', $result);

                            },
                            'format'  => 'raw',
                            'label'   => \Yii::t('itlo/cms', 'Additional sections'),
                            'visible' => false,
                        ],


                    ],
                ],
            ],
            "create"           => [
                "callback" => [$this, 'create'],
            ],
            "update"           => [
                "callback" => [$this, 'update'],
            ],
            "activate-multi"   => [
                'class'   => BackendModelMultiActivateAction::class,
                'on init' => function ($e) {
                    $action = $e->sender;
                    /**
                     * @var BackendGridModelAction $action
                     */
                    if ($this->content) {
                        $action->url = ["/".$action->uniqueId, 'content_id' => $this->content->id];
                    }
                },

            ],
            "deactivate-multi" => [
                'class'   => BackendModelMultiDeactivateAction::class,
                'on init' => function ($e) {
                    $action = $e->sender;
                    /**
                     * @var BackendGridModelAction $action
                     */
                    if ($this->content) {
                        $action->url = ["/".$action->uniqueId, 'content_id' => $this->content->id];
                    }
                },

            ],


            "copy" => [
                'priority'       => 200,
                'class'          => BackendModelUpdateAction::class,
                "name"           => \Yii::t('itlo/cms', 'Copy'),
                "icon"           => "fas fa-copy",
                "beforeContent"  => "???????????????? ???????????????? ?????????? ???????????????? ????????????????. ?????????????? ?????????????????? ?????????????????????? ?? ?????????????? ??????????????????.",
                "successMessage" => "?????????????? ?????????????? ????????????????????",

                'on initFormModels' => function (Event $e) {
                    $model = $e->sender->model;
                    $dm = new DynamicModel(['is_copy_images', 'is_copy_files']);
                    $dm->addRule(['is_copy_images', 'is_copy_files'], 'boolean');

                    $dm->is_copy_images = true;
                    $dm->is_copy_files = true;

                    $e->sender->formModels['dm'] = $dm;
                },

                'on beforeSave' => function (Event $e) {
                    /**
                     * @var $action BackendModelUpdateAction;
                     */
                    $action = $e->sender;
                    $action->isSaveFormModels = false;
                    $dm = ArrayHelper::getValue($action->formModels, 'dm');

                    $newModel = $action->model->copy();

                    if ($newModel) {
                        $action->afterSaveUrl = Url::to(['update', 'pk' => $newModel->id, 'content_id' => $newModel->content_id]);
                    } else {
                        throw new Exception(print_r($newModel->errors, true));
                    }

                },

                'fields' => function () {
                    return [
                        'dm.is_copy_images' => [
                            'class' => BoolField::class,
                            'label' => ['itlo/cms', 'Copy images?'],
                        ],
                        'dm.is_copy_files'  => [
                            'class' => BoolField::class,
                            'label' => ['itlo/cms', 'Copy files?'],
                        ],
                    ];
                },
            ],


            "change-tree-multi" => [
                'class'        => BackendModelMultiDialogEditAction::class,
                "name"         => \Yii::t('itlo/cms', 'The main section'),
                "viewDialog"   => "@itlo/cms/views/admin-cms-content-element/change-tree-form",
                "eachCallback" => [$this, 'eachMultiChangeTree'],
                'on init'      => function ($e) {
                    /**
                     * @var BackendGridModelAction $action
                     */
                    $action = $e->sender;
                    if ($this->content) {
                        $action->url = ["/".$action->uniqueId, 'content_id' => $this->content->id];
                    }


                },
            ],

            "change-trees-multi" => [
                'class'        => BackendModelMultiDialogEditAction::class,
                "name"         => \Yii::t('itlo/cms', 'Related topics'),
                "viewDialog"   => "@itlo/cms/views/admin-cms-content-element/change-trees-form",
                "eachCallback" => [$this, 'eachMultiChangeTrees'],
                'on init'      => function ($e) {
                    $action = $e->sender;
                    /**
                     * @var BackendGridModelAction $action
                     */
                    if ($this->content) {
                        $action->url = ["/".$action->uniqueId, 'content_id' => $this->content->id];
                    }
                },
            ],

            "rp" => [
                'class'        => BackendModelMultiDialogEditAction::class,
                "name"         => \Yii::t('itlo/cms', 'Properties'),
                "viewDialog"   => "@itlo/cms/views/admin-cms-content-element/multi-rp",
                "eachCallback" => [$this, 'eachRelatedProperties'],
                'on init'      => function ($e) {
                    $action = $e->sender;
                    /**
                     * @var BackendGridModelAction $action
                     */
                    if ($this->content) {
                        $action->url = ["/".$action->uniqueId, 'content_id' => $this->content->id];
                    }
                },
            ],

        ]);

        //???????????????????????????? ????????????????
        return $result;
    }


    public function initGridData($action, $content)
    {
        /**
         * @var $action BackendGridModelAction
         */

        $model = null;
        $autoFilters = [];
        $autoRules = [];
        $autoLabels = [];

        $autoColumns = [];

        if ($content) {
            $model = new CmsContentElement([
                'content_id' => $content->id,
            ]);
        }

        if ($model) {
            $relatedPropertiesModel = $model->relatedPropertiesModel;

            $relatedPropertiesModel->initAllProperties();

            foreach ($relatedPropertiesModel->toArray($relatedPropertiesModel->attributes()) as $name => $value) {

                $property = $relatedPropertiesModel->getRelatedProperty($name);
                $filter = '';

                $autoColumns["property{$property->id}"] = [
                    //'attribute' => $name,
                    'label'  => \yii\helpers\ArrayHelper::getValue($relatedPropertiesModel->attributeLabels(), $name)." [????????????????]",
                    'format' => 'raw',
                    'value'  => function ($model, $key, $index) use ($name, $relatedPropertiesModel) {
                        /**
                         * @var $model \itlo\cms\models\CmsContentElement
                         */
                        return $model->relatedPropertiesModel->getAttributeAsHtml($name);
                        /*if (is_array($value)) {
                            return implode(",", $value);
                        } else {
                            return $value;
                        }*/
                    },
                ];

                $autoRules[] = ["property{$property->id}", "safe"];
                $autoLabels["property{$property->id}"] = \yii\helpers\ArrayHelper::getValue($relatedPropertiesModel->attributeLabels(), $name)." [????????????????]";


                if ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_STRING) {
                    $autoFilters["property{$property->id}"] = [
                        'class'    => TextField::class,
                        'label'    => \yii\helpers\ArrayHelper::getValue($relatedPropertiesModel->attributeLabels(), $name)." [????????????????]",
                        'on apply' => function (QueryFiltersEvent $e) use ($property) {
                            /**
                             * @var $query ActiveQuery
                             */
                            $query = $e->dataProvider->query;


                            if ($e->field->value) {
                                $query1 = CmsContentElementProperty::find()->select(['element_id as id'])
                                    ->where([
                                        "property_id" => $property->id,
                                    ])
                                    ->andWhere([
                                        'like',
                                        'value',
                                        $e->field->value,
                                    ]);

                                $query->andWhere([
                                    CmsContentElement::tableName().".id" => $query1,
                                ]);
                            }
                        },
                    ];
                } elseif ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_BOOL) {

                    $autoFilters["property{$property->id}"] = [
                        'class'    => BoolField::class,
                        'label'    => \yii\helpers\ArrayHelper::getValue($relatedPropertiesModel->attributeLabels(), $name)." [????????????????]",
                        'on apply' => function (QueryFiltersEvent $e) use ($property) {
                            /**
                             * @var $query ActiveQuery
                             */
                            $query = $e->dataProvider->query;


                            if ($e->field->value) {
                                $query1 = CmsContentElementProperty::find()->select(['element_id as id'])
                                    ->where([
                                        "value_bool"  => $e->field->value,
                                        "property_id" => $property->id,
                                    ]);

                                $query->andWhere([
                                    CmsContentElement::tableName().".id" => $query1,
                                ]);
                            }
                        },
                    ];

                } elseif ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_NUMBER) {


                } elseif ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_LIST) {

                    $count = CmsContentPropertyEnum::find()->where(['property_id' => $property->id])->count();

                    if ($count > 100) {
                        $autoFilters["property{$property->id}"] = [
                            'class'        => WidgetField::class,
                            'widgetClass'  => \itlo\cms\backend\widgets\SelectModelDialogWidget::class,
                            'widgetConfig' => [
                                'modelClassName' => \itlo\cms\models\CmsContentPropertyEnum::class,
                                'dialogRoute'    => [
                                    '/cms/admin-cms-content-property-enum',
                                    'CmsContentPropertyEnum' => [
                                        'property_id' => $property->id,
                                    ],
                                ],
                            ],
                        ];
                    } else {
                        $autoFilters["property{$property->id}"] = [
                            'class'    => SelectField::class,
                            'items'    => ArrayHelper::map(CmsContentPropertyEnum::find()->where(['property_id' => $property->id])->all(), 'id', 'value'),
                            'multiple' => true,
                        ];
                    }

                    $autoFilters["property{$property->id}"]['label'] = \yii\helpers\ArrayHelper::getValue($relatedPropertiesModel->attributeLabels(), $name)." [????????????????]";
                    $autoFilters["property{$property->id}"]["on apply"] = function (QueryFiltersEvent $e) use ($property) {
                        /**
                         * @var $query ActiveQuery
                         */
                        $query = $e->dataProvider->query;


                        if ($e->field->value) {
                            $query1 = CmsContentElementProperty::find()->select(['element_id as id'])
                                ->where([
                                    "value_enum"  => $e->field->value,
                                    "property_id" => $property->id,
                                ]);

                            $query->andWhere([
                                CmsContentElement::tableName().".id" => $query1,
                            ]);
                        }
                    };


                } elseif ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_ELEMENT) {

                    $propertyType = $property->handler;

                    $count = CmsContentElement::find()->where(['content_id' => $propertyType->content_id])->count();

                    if ($count > 100) {
                        $autoFilters["property{$property->id}"] = [
                            'class'        => WidgetField::class,
                            'widgetClass'  => \itlo\cms\backend\widgets\SelectModelDialogContentElementWidget::class,
                            'widgetConfig' => [
                                'content_id' => $propertyType->content_id,
                            ],
                        ];
                    } else {
                        $autoFilters["property{$property->id}"] = [
                            'class'    => SelectField::class,
                            'items'    => ArrayHelper::map(CmsContentElement::find()->where(['content_id' => $propertyType->content_id])->all(), 'id', 'name'),
                            'multiple' => true,
                        ];
                    }

                    $autoFilters["property{$property->id}"]["label"] = \yii\helpers\ArrayHelper::getValue($relatedPropertiesModel->attributeLabels(), $name)." [????????????????]";
                    $autoFilters["property{$property->id}"]["on apply"] = function (QueryFiltersEvent $e) use ($property) {
                        /**
                         * @var $query ActiveQuery
                         */
                        $query = $e->dataProvider->query;


                        if ($e->field->value) {
                            $query1 = CmsContentElementProperty::find()->select(['element_id as id'])
                                ->where([
                                    "value_enum"  => $e->field->value,
                                    "property_id" => $property->id,
                                ]);

                            $query->andWhere([
                                CmsContentElement::tableName().".id" => $query1,
                            ]);
                        }
                    };


                } elseif ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_TREE) {
                    $propertyType = $property->handler;
                    $autoFilters["property{$property->id}"] = [
                        'class'       => WidgetField::class,
                        'widgetClass' => \itlo\cms\backend\widgets\SelectModelDialogTreeWidget::class,
                        'label'       => \yii\helpers\ArrayHelper::getValue($relatedPropertiesModel->attributeLabels(), $name)." [????????????????]",
                        'on apply'    => function (QueryFiltersEvent $e) use ($property) {
                            /**
                             * @var $query ActiveQuery
                             */
                            $query = $e->dataProvider->query;


                            if ($e->field->value) {
                                $query1 = CmsContentElementProperty::find()->select(['element_id as id'])
                                    ->where([
                                        "value_enum"  => $e->field->value,
                                        "property_id" => $property->id,
                                    ]);

                                $query->andWhere([
                                    CmsContentElement::tableName().".id" => $query1,
                                ]);
                            }
                        },
                    ];
                }


            }
        }

        if ($autoColumns) {
            //$result['index']['grid']['columns'] = ArrayHelper::merge($result['index']['grid']['columns'], $autoColumns);
            $action->grid['columns'] = ArrayHelper::merge($action->grid['columns'], $autoColumns);
        }

        if ($autoRules) {
            //$result['index']['filters']['filtersModel']['rules'] = ArrayHelper::merge((array)$result['index']['filters']['filtersModel']['rules'], $autoRules);
            $action->filters['filtersModel']['rules'] = ArrayHelper::merge($action->filters['filtersModel']['rules'], $autoRules);
        }

        if ($autoFilters) {
            //$result['index']['filters']['filtersModel']['fields'] = ArrayHelper::merge((array)ArrayHelper::getValue($result, ['index', 'filters', 'filtersModel', 'fields']), $autoFilters);
            //$result['index']['filters']['filtersModel']['attributeDefines'] = ArrayHelper::merge((array)ArrayHelper::getValue($result, ['index', 'filters', 'filtersModel', 'attributeDefines']), array_keys($autoFilters));
            //$result['index']['filters']['filtersModel']['attributeLabels'] = ArrayHelper::merge((array)ArrayHelper::getValue($result, ['index', 'filters', 'filtersModel', 'attributeLabels']), $autoLabels);

            $action->filters['filtersModel']['fields'] = ArrayHelper::merge($action->filters['filtersModel']['fields'], $autoFilters);
            $action->filters['filtersModel']['attributeDefines'] = ArrayHelper::merge($action->filters['filtersModel']['attributeDefines'], array_keys($autoFilters));
            $action->filters['filtersModel']['attributeLabels'] = ArrayHelper::merge(ArrayHelper::getValue($action->filters, ['filtersModel', 'attributeLabels']), $autoLabels);
        }

        return $this;
    }

    public function contentEdit(Event $e)
    {
        $href = \yii\helpers\Html::a('???????????????????? ????????????????',
            \itlo\cms\helpers\UrlHelper::construct([
                '/cms/admin-cms-content/update',
                'pk' => $this->content->id,
            ])->enableAdmin()->toString());

        $e->content = Alert::widget([
            'options' => [
                'class' => 'alert-info',
            ],

            'body' => <<<HTML
    ???????????????? ???????????????? ?? ?????????? ?????????????? ?? ?????????????????????????????? ?????????? ???? ???????????? ?? {$href}
HTML
            ,
        ]);
    }

    public function create($adminAction)
    {
        $is_saved = false;
        $redirect = "";

        $modelClassName = $this->modelClassName;
        $model = new $modelClassName;

        $model->loadDefaultValues();

        if ($content_id = \Yii::$app->request->get("content_id")) {
            $contentModel = \itlo\cms\models\CmsContent::findOne($content_id);
            $model->content_id = $content_id;
        }

        $relatedModel = $model->relatedPropertiesModel;
        $relatedModel->loadDefaultValues();

        $rr = new RequestResponse();

        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            $model->load(\Yii::$app->request->post());
            $relatedModel->load(\Yii::$app->request->post());

            return \yii\widgets\ActiveForm::validateMultiple([
                $model,
                $relatedModel,
            ]);
        }

        if ($post = \Yii::$app->request->post()) {
            $model->load(\Yii::$app->request->post());
            $relatedModel->load(\Yii::$app->request->post());
        }

        if ($rr->isRequestPjaxPost()) {
            if (!\Yii::$app->request->post($this->notSubmitParam)) {
                $model->load(\Yii::$app->request->post());
                $relatedModel->load(\Yii::$app->request->post());

                if ($model->save() && $relatedModel->save()) {
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('itlo/cms', 'Saved'));

                    $is_saved = true;

                    if (\Yii::$app->request->post('submit-btn') == 'apply') {
                        $url = '';
                        $this->model = $model;

                        if ($this->modelActions) {
                            if ($action = ArrayHelper::getValue($this->modelActions, $this->modelDefaultAction)) {
                                $url = $action->url;
                            }
                        }

                        if (!$url) {
                            $url = $this->url;
                        }

                        $redirect = $url;
                    } else {
                        $redirect = $this->url;
                    }
                }
            }

        }

        return $this->render('_form', [
            'model'        => $model,
            'relatedModel' => $relatedModel,

            'is_saved'  => $is_saved,
            'submitBtn' => \Yii::$app->request->post('submit-btn'),
            'redirect'  => $redirect,
        ]);
    }
    public function update($adminAction)
    {
        $is_saved = false;
        $redirect = "";

        /**
         * @var $model CmsContentElement
         */
        $model = $this->model;
        $relatedModel = $model->relatedPropertiesModel;

        $rr = new RequestResponse();

        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            $model->load(\Yii::$app->request->post());
            $relatedModel->load(\Yii::$app->request->post());
            return \yii\widgets\ActiveForm::validateMultiple([
                $model,
                $relatedModel,
            ]);
        }

        if ($post = \Yii::$app->request->post()) {
            $model->load(\Yii::$app->request->post());
            $relatedModel->load(\Yii::$app->request->post());
        }

        if ($rr->isRequestPjaxPost()) {
            if (!\Yii::$app->request->post($this->notSubmitParam)) {
                $model->load(\Yii::$app->request->post());
                $relatedModel->load(\Yii::$app->request->post());

                if ($model->save() && $relatedModel->save()) {
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('itlo/cms', 'Saved'));

                    $is_saved = true;

                    if (\Yii::$app->request->post('submit-btn') == 'apply') {
                    } else {
                        $redirect = $this->url;
                    }

                    $model->refresh();
                    $relatedModel = $model->relatedPropertiesModel;
                }
            }

        }

        return $this->render('_form', [
            'model'        => $model,
            'relatedModel' => $relatedModel,
            'is_saved'     => $is_saved,
            'submitBtn'    => \Yii::$app->request->post('submit-btn'),
            'redirect'     => $redirect,
        ]);
    }
    /**
     * @param CmsContentElement $model
     * @param                   $action
     * @return bool
     */
    public function eachMultiChangeTree($model, $action)
    {
        //try {
        $formData = [];
        parse_str(\Yii::$app->request->post('formData'), $formData);
        $tmpModel = new CmsContentElement();
        $tmpModel->load($formData);
        if ($tmpModel->tree_id && $tmpModel->tree_id != $model->tree_id) {
            $model->tree_id = $tmpModel->tree_id;
            if (!$model->save(false)) {
                throw new Exception("???? ??????????????????????: ".print_r($model->errors, true));
            }
        } else {
            throw new Exception('???????????? ???? ??????????????????');
        }

        return true;
        //} catch (\Exception $e) {
        //    return false;
        //}
    }
    public function eachRelatedProperties($model, $action)
    {
        try {
            $formData = [];
            parse_str(\Yii::$app->request->post('formData'), $formData);

            if (!$formData) {
                return false;
            }

            if (!$content_id = ArrayHelper::getValue($formData, 'content_id')) {
                return false;
            }

            if (!$fields = ArrayHelper::getValue($formData, 'fields')) {
                return false;
            }


            /**
             * @var CmsContent $content
             */
            $content = CmsContent::findOne($content_id);
            if (!$content) {
                return false;
            }


            $element = $content->createElement();
            $relatedProperties = $element->relatedPropertiesModel;
            $relatedProperties->load($formData);
            /**
             * @var $model CmsContentElement
             */
            $rpForSave = $model->relatedPropertiesModel;

            foreach ((array)ArrayHelper::getValue($formData, 'fields') as $code) {
                if ($rpForSave->hasAttribute($code)) {
                    $rpForSave->setAttribute($code,
                        ArrayHelper::getValue($formData, 'RelatedPropertiesModel.'.$code));
                }
            }

            return $rpForSave->save(false);
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * @param CmsContentElement $model
     * @param                   $action
     * @return bool
     */
    public function eachMultiChangeTrees($model, $action)
    {
        try {
            $formData = [];
            parse_str(\Yii::$app->request->post('formData'), $formData);
            $tmpModel = new CmsContentElement();
            $tmpModel->load($formData);

            if (ArrayHelper::getValue($formData, 'removeCurrent')) {
                $model->treeIds = [];
            }

            if ($tmpModel->treeIds) {
                $model->treeIds = array_merge($model->treeIds, $tmpModel->treeIds);
                $model->treeIds = array_unique($model->treeIds);
            }

            return $model->save(false);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return CmsContent|static
     */
    public function getContent()
    {
        if ($this->_content === null) {
            if ($this->model) {
                $this->_content = $this->model->cmsContent;
                return $this->_content;
            }

            if (\Yii::$app instanceof Application && \Yii::$app->request->get('content_id')) {
                $content_id = \Yii::$app->request->get('content_id');

                $dependency = new TagDependency([
                    'tags' =>
                        [
                            (new CmsContent())->getTableCacheTag(),
                        ],
                ]);

                $this->_content = CmsContent::getDb()->cache(function ($db) use ($content_id) {
                    return CmsContent::find()->where([
                        "id" => $content_id,
                    ])->one();
                }, null, $dependency);

                return $this->_content;
            }
        }

        return $this->_content;
    }
    /**
     * @param $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }
    public function getModelActions()
    {
        /**
         * @var AdminAction $action
         */
        $actions = parent::getModelActions();
        if ($actions) {
            foreach ($actions as $action) {
                $action->url = ArrayHelper::merge($action->urlData,
                    ['content_id' => $this->content ? $this->content->id : ""]);
            }
        }

        return $actions;
    }
    public function beforeAction($action)
    {
        if ($this->content) {
            if ($this->content->name_meny) {
                $this->name = $this->content->name_meny;
            } else {
                $this->name = $this->content->name;
            }
        }

        return parent::beforeAction($action);
    }
    /**
     * @return string
     */
    public function getUrl()
    {
        $actions = $this->getActions();
        $index = ArrayHelper::getValue($actions, 'index');
        if ($index && $index instanceof IHasUrl) {
            return $index->url;
        }

        return '';
    }
    public function getActions()
    {
        /**
         * @var AdminAction $action
         */
        $actions = parent::getActions();
        if ($actions) {
            foreach ($actions as $action) {
                if ($this->content) {
                    $action->url = ArrayHelper::merge($action->urlData, ['content_id' => $this->content->id]);
                }
            }
        }

        return $actions;
    }


    /**
     * @param CmsContent $model
     * @return array
     */
    public static function getColumns($cmsContent = null, $dataProvider = null)
    {
        return \yii\helpers\ArrayHelper::merge(
            static::getDefaultColumns($cmsContent),
            static::getColumnsByContent($cmsContent, $dataProvider)
        );
    }
    /**
     * @param CmsContent $cmsContent
     * @return array
     */
    public static function getDefaultColumns($cmsContent = null)
    {
        $columns = [
            [
                'class' => \itlo\cms\grid\ImageColumn2::class,
            ],
            'name',
            ['class' => \itlo\cms\grid\CreatedAtColumn::class],
            [
                'class'   => \itlo\cms\grid\UpdatedAtColumn::class,
                'visible' => false,
            ],
            [
                'class'   => \itlo\cms\grid\PublishedAtColumn::class,
                'visible' => false,
            ],
            [
                'class'     => \itlo\cms\grid\DateTimeColumnData::class,
                'attribute' => "published_to",
                'visible'   => false,
            ],
            ['class' => \itlo\cms\grid\CreatedByColumn::class],
            //['class' => \itlo\cms\grid\UpdatedByColumn::class],
            [
                'class'     => \yii\grid\DataColumn::class,
                'value'     => function (\itlo\cms\models\CmsContentElement $model) {
                    if (!$model->cmsTree) {
                        return null;
                    }
                    $path = [];
                    if ($model->cmsTree->parents) {
                        foreach ($model->cmsTree->parents as $parent) {
                            if ($parent->isRoot()) {
                                $path[] = "[".$parent->site->name."] ".$parent->name;
                            } else {
                                $path[] = $parent->name;
                            }
                        }
                    }
                    $path = implode(" / ", $path);
                    return "<small><a href='{$model->cmsTree->url}' target='_blank' data-pjax='0'>{$path} / {$model->cmsTree->name}</a></small>";
                },
                'format'    => 'raw',
                'filter'    => false,
                //'filter' => \itlo\cms\helpers\TreeOptions::getAllMultiOptions(),
                'attribute' => 'tree_id',
            ],
            'additionalSections' => [
                'class'   => \yii\grid\DataColumn::class,
                'value'   => function (\itlo\cms\models\CmsContentElement $model) {
                    $result = [];
                    if ($model->cmsContentElementTrees) {
                        foreach ($model->cmsContentElementTrees as $contentElementTree) {
                            $site = $contentElementTree->tree->root->site;
                            $result[] = "<small><a href='{$contentElementTree->tree->url}' target='_blank' data-pjax='0'>[{$site->name}]/.../{$contentElementTree->tree->name}</a></small>";
                        }
                    }
                    return implode('<br />', $result);
                },
                'format'  => 'raw',
                'label'   => \Yii::t('itlo/cms', 'Additional sections'),
                'visible' => false,
            ],
            [
                'attribute' => 'active',
                'class'     => \itlo\cms\grid\BooleanColumn::class,
            ],
            [
                'class'  => \yii\grid\DataColumn::class,
                'label'  => "????????????????",
                'value'  => function (\itlo\cms\models\CmsContentElement $model) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-arrow-right"></i>', $model->absoluteUrl,
                        [
                            'target'    => '_blank',
                            'title'     => \Yii::t('itlo/cms', 'Watch to site (opens new window)'),
                            'data-pjax' => '0',
                            'class'     => 'btn btn-default btn-sm',
                        ]);
                },
                'format' => 'raw',
            ],
        ];
        return $columns;
    }
    /**
     * @param CmsContent $cmsContent
     * @return array
     */
    public static function getColumnsByContent($cmsContent = null, $dataProvider = null)
    {
        $autoColumns = [];
        if (!$cmsContent) {
            return [];
        }
        $model = null;
        //$model = CmsContentElement::find()->where(['content_id' => $cmsContent->id])->one();
        if (!$model) {
            $model = new CmsContentElement([
                'content_id' => $cmsContent->id,
            ]);
        }
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                $autoColumns[] = [
                    'attribute' => $name,
                    'visible'   => false,
                    'format'    => 'raw',
                    'class'     => \yii\grid\DataColumn::class,
                    'value'     => function ($model, $key, $index) use ($name) {
                        if (is_array($model->{$name})) {
                            return implode(",", $model->{$name});
                        } else {
                            return $model->{$name};
                        }
                    },
                ];
            }
            $searchRelatedPropertiesModel = new \itlo\cms\models\searchs\SearchRelatedPropertiesModel();
            $searchRelatedPropertiesModel->initProperties($cmsContent->cmsContentProperties);
            $searchRelatedPropertiesModel->load(\Yii::$app->request->get());
            if ($dataProvider) {
                $searchRelatedPropertiesModel->search($dataProvider);
            }
            /**
             * @var $model \itlo\cms\models\CmsContentElement
             */
            if ($model->relatedPropertiesModel) {
                $autoColumns = ArrayHelper::merge($autoColumns,
                    GridViewStandart::getColumnsByRelatedPropertiesModel($model->relatedPropertiesModel,
                        $searchRelatedPropertiesModel));
            }
        }
        return $autoColumns;
    }
}
