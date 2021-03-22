<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\actions\user\UserAction;
use itlo\cms\helpers\RequestResponse;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class OnlineController extends Controller
{
    /**
     * @return RequestResponse
     */
    public function actionTrigger()
    {
        $callback = \Yii::$app->request->get('callback');

        $rr = new RequestResponse();
        $rr->data['call'] = \Yii::$app->request->get('callback');
        $rr->success = true;

        $data = Json::encode($rr->toArray());
        return "{$callback}({$data})";
    }

}
