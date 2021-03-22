<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\grid\BooleanColumn;
use itlo\cms\helpers\RequestResponse;
use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\CmsUserUniversalProperty;
use yii\helpers\ArrayHelper;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminCmsUserUniversalPropertyController extends BackendModelStandartController
{
    public $notSubmitParam = 'sx-not-submit';

    public function init()
    {
        $this->name = \Yii::t('itlo/cms', 'User control properties');
        $this->modelShowAttribute = "name";
        $this->modelClassName = CmsUserUniversalProperty::class;

        $this->generateAccessActions = false;

        parent::init();

    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [

            'index' => [
                'filters' => [
                    'visibleFilters' => [
                        'name',
                    ],
                ],

                'grid'    => [
                    'visibleColumns' => [
                        'checkbox',
                        'actions',
                        'id',
                        'name',
                        'code',
                        'priority',
                        'hint',
                        'active',
                    ],
                    'columns' => [
                        'active' => [
                            'class' => BooleanColumn::class,
                            'trueValue' => "Y",
                            'falseValue' => "N",
                        ]
                    ]
                ],
            ],

            'create' => [
                'callback' => [$this, 'create'],
            ],

            'update' => [
                'callback' => [$this, 'update'],
            ],
        ]);
    }


    public function create()
    {
        $rr = new RequestResponse();

        $modelClass = $this->modelClassName;
        $model = new $modelClass();
        $model->loadDefaultValues();

        if ($post = \Yii::$app->request->post()) {
            $model->load($post);
        }

        $handler = $model->handler;
        if ($handler) {
            if ($post = \Yii::$app->request->post()) {
                $handler->load($post);
            }
        }

        if ($rr->isRequestPjaxPost()) {
            if (!\Yii::$app->request->post($this->notSubmitParam)) {
                $model->component_settings = $handler->toArray();
                $handler->load(\Yii::$app->request->post());

                if ($model->load(\Yii::$app->request->post())
                    && $model->validate() && $handler->validate()
                ) {
                    $model->save();

                    \Yii::$app->getSession()->setFlash('success', \Yii::t('itlo/cms', 'Saved'));

                    return $this->redirect(
                        UrlHelper::constructCurrent()->setCurrentRef()->enableAdmin()->setRoute($this->modelDefaultAction)->normalizeCurrentRoute()
                            ->addData([$this->requestPkParamName => $model->{$this->modelPkAttribute}])
                            ->toString()
                    );
                } else {
                    \Yii::$app->getSession()->setFlash('error', \Yii::t('itlo/cms', 'Could not save'));
                }
            }
        }

        return $this->render('_form', [
            'model'   => $model,
            'handler' => $handler,
        ]);
    }


    public function update()
    {
        $rr = new RequestResponse();

        $model = $this->model;

        if ($post = \Yii::$app->request->post()) {
            $model->load($post);
        }

        $handler = $model->handler;
        if ($handler) {
            if ($post = \Yii::$app->request->post()) {
                $handler->load($post);
            }
        }

        if ($rr->isRequestPjaxPost()) {
            if (!\Yii::$app->request->post($this->notSubmitParam)) {
                if ($rr->isRequestPjaxPost()) {
                    $model->component_settings = $handler->toArray();
                    $handler->load(\Yii::$app->request->post());

                    if ($model->load(\Yii::$app->request->post())
                        && $model->validate() && $handler->validate()
                    ) {
                        $model->save();

                        \Yii::$app->getSession()->setFlash('success', \Yii::t('itlo/cms', 'Saved'));

                        if (\Yii::$app->request->post('submit-btn') == 'apply') {

                        } else {
                            return $this->redirect(
                                $this->url
                            );
                        }

                        $model->refresh();

                    }
                }
            }
        }

        return $this->render('_form', [
            'model'   => $model,
            'handler' => $handler,
        ]);
    }
}
