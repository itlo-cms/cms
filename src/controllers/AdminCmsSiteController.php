<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\actions\backend\BackendModelMultiActivateAction;
use itlo\cms\actions\backend\BackendModelMultiDeactivateAction;
use itlo\cms\backend\actions\BackendGridModelRelatedAction;
use itlo\cms\backend\actions\BackendModelAction;
use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\grid\BooleanColumn;
use itlo\cms\grid\ImageColumn2;
use itlo\cms\helpers\Image;
use itlo\cms\models\CmsSite;
use itlo\cms\queryfilters\filters\modes\FilterModeEmpty;
use itlo\cms\queryfilters\filters\modes\FilterModeNotEmpty;
use itlo\cms\queryfilters\QueryFiltersEvent;
use itlo\yii2\form\fields\BoolField;
use itlo\yii2\form\fields\FieldSet;
use itlo\yii2\form\fields\HiddenField;
use itlo\yii2\form\fields\HtmlBlock;
use itlo\yii2\form\fields\SelectField;
use itlo\yii2\form\fields\TextareaField;
use itlo\yii2\form\fields\WidgetField;
use yii\base\Event;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminCmsSiteController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', "Site management");
        $this->modelShowAttribute = "name";
        $this->modelClassName = CmsSite::class;

        $this->generateAccessActions = false;

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $bool = [
            'isAllowChangeMode' => false,
            'field'             => [
                'class' => SelectField::class,
                'items' => [
                    'Y' => \Yii::t('yii', 'Yes'),
                    'N' => \Yii::t('yii', 'No'),
                ],
            ],
        ];


        $actions = ArrayHelper::merge(parent::actions(), [

            'index' => [
                "filters" => [
                    'visibleFilters' => [
                        'q',
                    ],

                    'filtersModel' => [
                        'rules' => [
                            ['q', 'safe'],
                            ['has_image', 'safe'],
                        ],

                        'attributeDefines' => [
                            'q',
                            'has_image',
                        ],


                        'fields' => [
                            'name'     => [
                                'isAllowChangeMode' => false,
                            ],
                            'code'     => [
                                'isAllowChangeMode' => false,
                            ],
                            'active'   => $bool,
                            'def'      => $bool,
                            'image_id' => [
                                'isAllowChangeMode' => true,
                                'modes'             => [
                                    FilterModeNotEmpty::class,
                                    FilterModeEmpty::class,
                                ],
                            ],

                            'q' => [
                                'label'          => 'Поиск',
                                'elementOptions' => [
                                    'placeholder' => 'Поиск',
                                ],
                                'on apply'       => function (QueryFiltersEvent $e) {
                                    /**
                                     * @var $query ActiveQuery
                                     */
                                    $query = $e->dataProvider->query;

                                    if ($e->field->value) {
                                        $query->andWhere([
                                            'or',
                                            ['like', CmsSite::tableName().'.name', $e->field->value],
                                            ['like', CmsSite::tableName().'.id', $e->field->value],
                                            ['like', 'cmsSiteDomains.domain', $e->field->value],
                                        ]);

                                        $query->groupBy([CmsSite::tableName().'.id']);
                                    }
                                },
                            ],
                        ],
                    ],
                ],

                "grid" => [
                    'on init'       => function (Event $e) {
                        /**
                         * @var $dataProvider ActiveDataProvider
                         * @var $query ActiveQuery
                         */
                        $query = $e->sender->dataProvider->query;
                        $dataProvider = $e->sender->dataProvider;

                        $query->joinWith('cmsSiteDomains as cmsSiteDomains');
                        $query->groupBy(CmsSite::tableName() . ".id");
                        $query->select([
                            CmsSite::tableName() . '.*',
                            'countDomains' => new Expression("count(*)")
                        ]);
                    },

                    'sortAttributes' => [
                        'countDomains' => [
                            'asc' => ['countDomains' => SORT_ASC],
                            'desc' => ['countDomains' => SORT_DESC],
                            'label' => 'Количество доменов',
                            'default' => SORT_ASC
                        ]
                    ],
                    'defaultOrder' => [
                        //'def' => SORT_DESC,
                        'priority' => SORT_ASC
                    ],
                    'visibleColumns' => [
                        'checkbox',
                        'actions',
                        'custom',
                        //'id',
                        //'image_id',
                        'def',
                        'active',
                        'priority',
                        //'name',
                        'countDomains',
                        //'domains',
                    ],
                    'columns'        => [
                        'custom'       => [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function (CmsSite $model) {

                                $data = [];
                                $data[] = Html::a($model->asText, "#", ['class' => 'sx-trigger-action']);

                                if ($model->cmsSiteDomains) {
                                    foreach ($model->cmsSiteDomains as $cmsSiteDomain)
                                    {
                                        $data[] = Html::a($cmsSiteDomain->domain, $cmsSiteDomain->url, [
                                            'data-pjax' => '0',
                                            'target' => '_blank',
                                            'style' => 'color: #333; max-width: 200px;'
                                        ]);
                                    }

                                }

                                $info = implode("<br />", $data);

                                return "<div class='row no-gutters'>
                                                <div class='sx-trigger-action' style='width: 50px;'>
                                                <a href='#' style='text-decoration: none; border-bottom: 0;'>
                                                    <img src='". ($model->image ? $model->image->src : Image::getCapSrc()) ."' style='max-width: 50px; max-height: 50px; border-radius: 5px;' />
                                                </a>
                                                </div>
                                                <div style='margin-left: 5px;'>" . $info . "</div></div>";

                                            ;
                            }
                        ],

                        'active'   => [
                            'class' => BooleanColumn::class,
                        ],
                        'def'      => [
                            'class' => BooleanColumn::class,
                        ],
                        'image_id' => [
                            'class' => ImageColumn2::class,
                        ],
                        'countDomains' => [
                            'value' => function(CmsSite $cmsSite) {
                                return $cmsSite->raw_row['countDomains'];
                            },
                            'attribute' => 'countDomains',
                            'label' => 'Количество доменов'
                        ],
                        'domains' => [
                            'value' => function(CmsSite $cmsSite) {
                                $result = ArrayHelper::map($cmsSite->cmsSiteDomains, "id", function($domain) {
                                    return Html::a($domain->domain, $domain->url, [
                                        'target' => '_blank',
                                        'data-pjax' => 0
                                    ]);
                                });

                                return implode("<br />", $result);
                            },
                            'attribute' => 'countDomains',
                            'format' => 'raw',
                            'label' => 'Домены'
                        ],
                    ],
                ],
            ],

            "create" => [
                'fields' => [$this, 'updateFields'],
            ],

            "domains" => [
                'class' => BackendGridModelRelatedAction::class,
                'accessCallback' => true,
                'name'            => "Домены",
                'icon'            => 'fa fa-list',
                'controllerRoute' => "/cms/admin-cms-site-domain",
                'relation'        => ['cms_site_id' => 'id'],
                'priority'        => 600,
                'on gridInit'        => function($e) {
                    /**
                     * @var $action BackendGridModelRelatedAction
                     */
                    $action = $e->sender;
                    $visibleColumns = $action->relatedIndexAction->grid['visibleColumns'];

                    ArrayHelper::removeValue($visibleColumns, 'cms_site_id');
                    $action->relatedIndexAction->grid['visibleColumns'] = $visibleColumns;

                },
            ],


            "update" => [
                'fields' => [$this, 'updateFields'],
            ],

            "activate-multi" => [
                'class' => BackendModelMultiActivateAction::class,
                'accessCallback' => true,
            ],

            "deactivate-multi" => [
                'class' => BackendModelMultiDeactivateAction::class,
                'accessCallback' => true,
            ],
        ]);

        return $actions;
    }

    public function updateFields($action)
    {
        $active = [
            'class'       => BoolField::class,
            'formElement' => BoolField::ELEMENT_RADIO_LIST,
            'allowNull'   => false,
            'trueValue'   => 'Y',
            'falseValue'  => 'N',
        ];
        $def = [
            'class'       => BoolField::class,
            'formElement' => BoolField::ELEMENT_RADIO_LIST,
            'allowNull'   => false,
            'trueValue'   => 'Y',
            'falseValue'  => 'N',
        ];

        if ($action->model->def == 'Y') {
            $active = [
                'class'     => HiddenField::class,
                'hint'      => \Yii::t('itlo/cms', 'Site selected by default always active')
            ];
            $def = [
                'class'     => HiddenField::class,
                'hint'      => \Yii::t('itlo/cms', 'This site is the site selected by default. If you want to change it, you need to choose a different site, the default site.')
            ];
        }

        $result = [
            'image_id'    => [
                'class'        => WidgetField::class,
                'widgetClass'  => \itlo\cms\widgets\AjaxFileUploadWidget::class,
                'widgetConfig' => [
                    'accept'   => 'image/*',
                    'multiple' => false,
                ],
            ],
            'name',
            'code',
            'active'      => $active,
            'def'         => $def,
            'description' => [
                'class' => TextareaField::class,
            ],
            'priority',

        ];

        /*if (!$action->model->isNewRecord) {
            $result['domains'] = [
                'class'  => FieldSet::class,
                'name'   => \Yii::t('itlo/cms', "Domains"),
                'fields' => [
                    'domains' => [
                        'class' => HtmlBlock::class,
                        'content' => \itlo\cms\modules\admin\widgets\RelatedModelsGrid::widget([
                            'label' => "",
                            'hint' => "",
                            'parentModel' => $action->model,
                            'relation' => [
                                'cms_site_id' => 'id'
                            ],

                            'controllerRoute' => '/cms/admin-cms-site-domain',
                            'gridViewOptions' => [
                                'columns' => [
                                    //['class' => 'yii\grid\SerialColumn'],
                                    'domain',
                                    'is_main' => [
                                        'class' => BooleanColumn::class,
                                        'attribute' => 'is_main',
                                        'trueValue' => 1,
                                        'falseValue' => 0,
                                    ],
                                    'is_https' => [
                                        'class' => BooleanColumn::class,
                                        'attribute' => 'is_https',
                                        'trueValue' => 1,
                                        'falseValue' => 0,
                                    ],
                                ],
                            ],
                        ])
                    ]
                ],
            ];
        }*/

        return $result;
    }

}
