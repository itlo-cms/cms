<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\backend\BackendController;
use itlo\cms\base\Component;
use itlo\cms\components\Cms;
use itlo\cms\modules\admin\actions\AdminAction;
use itlo\cms\modules\admin\controllers\AdminController;
use itlo\yii2\config\ConfigBehavior;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AdminSettingsController
 * @package itlo\cms\controllers
 */
class AdminSettingsController extends BackendController
{
    public function init()
    {
        $this->name = "Управление настройками";
        $this->generateAccessActions = false;
        //$this->permissionName = "cms/admin-settings";
        parent::init();
    }

    public function actions()
    {
        return [
            "index" => [
                "class"    => AdminAction::className(),
                "name"     => "Настройки",
                "callback" => [$this, 'actionIndex'],
            ],
        ];
    }

    public function actionIndex()
    {
        $loadedComponents = [];
        $loadedForSelect = [];
        $component = '';
        $componentSelect = Cms::className();

        foreach (\Yii::$app->getComponents(true) as $id => $data) {
            $loadedComponent = \Yii::$app->get($id);
            if ($loadedComponent instanceof Component) {
                $loadedComponents[$loadedComponent->className()] = $loadedComponent;

                if ($name = $loadedComponent->descriptor->name) {
                    $loadedForSelect[$loadedComponent->className()] = $name;
                } else {
                    $loadedForSelect[$loadedComponent->className()] = $loadedComponent->className();
                }
            } elseif ($loadedComponent instanceof \yii\base\Component && $loadedComponent->getBehavior(ConfigBehavior::class)) {
                $loadedComponents[$loadedComponent->className()] = $loadedComponent;
                $loadedForSelect[$loadedComponent->className()] = $id;
            }
        }

        if (\Yii::$app->request->get("component")) {
            $componentSelect = \Yii::$app->request->get("component");
        }

        $component = ArrayHelper::getValue($loadedComponents, $componentSelect);

        if ($component && $component instanceof Component) {

            if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
                $component->load(\Yii::$app->request->post());
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($component);
            }

            if (\Yii::$app->request->isAjax) {
                if ($component->load(\Yii::$app->request->post())) {
                    $component->override = Component::OVERRIDE_DEFAULT;
                    if ($component->save()) {
                        \Yii::$app->getSession()->setFlash('success', 'Успешно сохранено');
                    } else {
                        \Yii::$app->getSession()->setFlash('error', 'Не удалось сохранить');
                    }

                } else {
                    \Yii::$app->getSession()->setFlash('error', 'Не удалось сохранить');
                }

            }
        }

        if ($component) {

        }


        return $this->render('index', [
            'loadedComponents' => $loadedComponents,
            'loadedForSelect'  => $loadedForSelect,
            'component'        => $component,
        ]);
    }

}
