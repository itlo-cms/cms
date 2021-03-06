<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\helpers\RequestResponse;
use itlo\cms\helpers\StringHelper;
use itlo\cms\modules\admin\controllers\AdminController;
use itlo\cms\modules\admin\controllers\AdminModelEditorController;
use yii\helpers\Json;

/**
 * Class AdminUniversalComponentSettingsController
 * @package itlo\cms\controllers
 */
class AdminUniversalComponentSettingsController extends AdminController
{
    public function init()
    {
        $this->name = "Управление настройками компонента";
        parent::init();
    }

    public function actionIndex()
    {
        $rr = new RequestResponse();

        $classComponent = \Yii::$app->request->get('component');
        $classComponentSettings = (string)\Yii::$app->request->get('settings');
        if ($classComponentSettings) {
            $classComponentSettings = unserialize(StringHelper::base64DecodeUrl($classComponentSettings));
        }

        /**
         * @var $component \itlo\cms\relatedProperties\PropertyType;
         */
        $component = new $classComponent();
        try {
            $component->attributes = $classComponentSettings;
        } catch (\Exception $e) {
        }

        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            return $rr->ajaxValidateForm($component);
        }


        $forSave = "";
        if ($rr->isRequestPjaxPost()) {
            if ($component->load(\Yii::$app->request->post())) {
                \Yii::$app->session->setFlash('success', 'Сохранено');
                $forSave = StringHelper::base64EncodeUrl(serialize($component->attributes));

            } else {
                \Yii::$app->session->setFlash('error', 'Ошибка');
            };
        }

        return $this->render($this->action->id, [
            "component" => $component,
            "forSave" => $forSave,
        ]);
    }


    public function actionSave()
    {
        $rr = new RequestResponse();

        $classComponent = \Yii::$app->request->get('component');

        /**
         * @var $component \itlo\cms\relatedProperties\PropertyType;
         */
        $component = new $classComponent();

        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            $component->load(\Yii::$app->request->post());
            $forSave = StringHelper::base64EncodeUrl(serialize($component->attributes));

            $rr->success = true;
            $rr->message;

            $rr->data =
                [
                    'forSave' => $forSave
                ];

            return $rr;
        }

    }

}
