<?php
/**
 * AdminFileManagerController
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\Comment;
use itlo\cms\modules\admin\actions\AdminAction;
use itlo\cms\modules\admin\controllers\AdminController;
use Yii;
use itlo\cms\models\User;
use itlo\cms\models\searchs\User as UserSearch;

/**
 * Class AdminFileManagerController
 * @package itlo\cms\controllers
 */
class AdminFileManagerController extends AdminController
{
    public function init()
    {
        if (!$this->name) {
            $this->name = "Файловый менеджер";
        }

        parent::init();
    }

    public function actionIndex()
    {
        return $this->render($this->action->id);
    }
}
