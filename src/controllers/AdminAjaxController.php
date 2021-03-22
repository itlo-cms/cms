<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\base\Component;
use itlo\cms\components\marketplace\models\PackageModel;
use itlo\cms\helpers\RequestResponse;
use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\CmsComponentSettings;
use itlo\cms\models\CmsLang;
use itlo\cms\models\Comment;
use itlo\cms\modules\admin\actions\AdminAction;
use itlo\cms\modules\admin\controllers\AdminController;
use Yii;
use itlo\cms\models\User;
use itlo\cms\models\searchs\User as UserSearch;
use yii\helpers\ArrayHelper;

/**
 * Class AdminAjaxController
 * @package itlo\cms\controllers
 */
class AdminAjaxController extends AdminController
{
    public function getPermissionName()
    {
        return "";
    }

    public function actionSetLang()
    {
        $rr = new RequestResponse();

        $newLang = \Yii::$app->request->post('code');
        $cmsLang = CmsLang::find()->active()->andWhere(['code' => $newLang])->one();

        if (!$cmsLang) {
            $rr->message = 'Указанный язык отлючен или удален';
            $rr->success = false;
            return $rr;
        }

        $rr->success = true;

        $component = clone \Yii::$app->admin;
        $component->setCmsUser(\Yii::$app->user)->setOverride(Component::OVERRIDE_USER);
        $component->languageCode = $cmsLang->code;

        if (!$component->save(true, ['languageCode'])) {
            $rr->message = 'Не удалось сохранить настройки: ';
            $rr->success = false;
            return $rr;
        }

        return $rr;
    }
}
