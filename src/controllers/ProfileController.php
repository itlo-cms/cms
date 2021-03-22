<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\filters\CmsAccessControl;
use yii\web\Controller;

/**
 * Class ProfileController
 * @package itlo\cms\controllers
 */
class ProfileController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return
            [
                //Closed all by default
                'access' =>
                    [
                        'class' => CmsAccessControl::className(),
                        'rules' =>
                            [
                                [
                                    'allow' => true,
                                    'roles' => ['@'],
                                    'actions' => ['index'],
                                ]
                            ]
                    ],
            ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(\Yii::$app->user->identity->profileUrl);
    }

}
