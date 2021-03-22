<?php
/**
 * AdminStorageController
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\modules\admin\actions\AdminAction;
use itlo\cms\modules\admin\controllers\AdminController;

/**
 * Class AdminStorageFilesController
 * @package itlo\cms\controllers
 */
class AdminStorageController extends AdminController
{
    public function init()
    {
        $this->name = "Управление серверами";
        $this->generateAccessActions = false;
        parent::init();
    }

    public function actions()
    {
        return [
            "index" => [
                "class"    => AdminAction::className(),
                "name"     => "Управление серверами",
                "callback" => [$this, 'actionIndex'],
            ],
        ];
    }

    public function actionIndex()
    {
        $clusters = \Yii::$app->storage->getClusters();

        return $this->render($this->action->id);
    }

}
