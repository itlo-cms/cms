<?php
/**
 * ErrorController
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;


use itlo\cms\base\Component;
use itlo\cms\components\Cms;
use itlo\cms\helpers\RequestResponse;
use itlo\cms\models\CmsComponentSettings;
use itlo\sx\helpers\ResponseHelper;
use Yii;
use yii\web\Controller;
use yii\web\Response;


/**
 * Class ToolbarController
 * @package itlo\cms\controllers
 */
class ToolbarController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    public function actionTriggerEditWidgets()
    {
        $rr = new ResponseHelper();

        if (\Yii::$app->request->isPost && \Yii::$app->request->isAjax) {

            if (\Yii::$app->cmsToolbar->editWidgets == Cms::BOOL_Y) {
                $component = clone \Yii::$app->cmsToolbar;
                $component->setCmsUser(\Yii::$app->user->identity)->setOverride(Component::OVERRIDE_USER);
                $component->editWidgets = Cms::BOOL_N;

                if (!$component->save(true, ['editWidgets'])) {
                    $rr->message = 'Не удалось сохранить настройки';
                    $rr->success = false;
                    return $rr;
                }
            } else {
                $component = clone \Yii::$app->cmsToolbar;
                $component->setCmsUser(\Yii::$app->user->identity)->setOverride(Component::OVERRIDE_USER);
                $component->editWidgets = Cms::BOOL_Y;

                if (!$component->save(true, ['editWidgets'])) {
                    $rr->message = 'Не удалось сохранить настройки';
                    $rr->success = false;
                    return $rr;
                }
            }
            
            \Yii::$app->cmsToolbar->invalidateCache();

            return $rr;
        }
    }

    public function actionTriggerEditViewFiles()
    {
        $rr = new RequestResponse();

        if (\Yii::$app->request->isPost && \Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (\Yii::$app->cmsToolbar->editViewFiles == Cms::BOOL_Y) {
                $component = clone \Yii::$app->cmsToolbar;
                $component->setCmsUser(\Yii::$app->user->identity)->setOverride(Component::OVERRIDE_USER);
                $component->editViewFiles = Cms::BOOL_N;

                if (!$component->save(true, ['editViewFiles'])) {
                    $rr->message = 'Не удалось сохранить настройки';
                    $rr->success = false;
                    return $rr;
                }
            } else {
                $component = clone \Yii::$app->cmsToolbar;
                $component->setCmsUser(\Yii::$app->user->identity)->setOverride(Component::OVERRIDE_USER);
                $component->editViewFiles = Cms::BOOL_Y;

                if (!$component->save(true, ['editViewFiles'])) {
                    $rr->message = 'Не удалось сохранить настройки';
                    $rr->success = false;
                    return $rr;
                }
            }
            
            \Yii::$app->cmsToolbar->invalidateCache();
            
            return $rr;
        }
    }

    public function actionTriggerIsOpen()
    {
        if (\Yii::$app->request->isPost && \Yii::$app->request->isAjax) {
            $rr = new RequestResponse();

            if (\Yii::$app->request->post('isOpen') == "true") {
                $component = clone \Yii::$app->cmsToolbar;
                $component->setCmsUser(\Yii::$app->user->identity)->setOverride(Component::OVERRIDE_USER);
                $component->isOpen = Cms::BOOL_Y;

                if (!$component->save(true, ['isOpen'])) {
                    $rr->message = 'Не удалось сохранить настройки';
                    $rr->success = false;
                    return $rr;
                }

                $rr->message = 'Сохранено';
                $rr->success = true;
            } else {
                $component = clone \Yii::$app->cmsToolbar;
                $component->setCmsUser(\Yii::$app->user->identity)->setOverride(Component::OVERRIDE_USER);
                $component->isOpen = Cms::BOOL_N;

                if (!$component->save(true, ['isOpen'])) {
                    $rr->message = 'Не удалось сохранить настройки';
                    $rr->success = false;
                    return $rr;
                }

                \Yii::$app->cmsToolbar->invalidateCache();
                $rr->message = 'Сохранено';
                $rr->success = true;
            }
            
            return $rr;
        }
    }

}
