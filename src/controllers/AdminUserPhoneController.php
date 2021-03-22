<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\backend\controllers\BackendModelStandartController;
use itlo\cms\models\CmsUserPhone;
use itlo\cms\modules\admin\controllers\AdminModelEditorController;

/**
 * Class AdminUserEmailController
 * @package itlo\cms\controllers
 */
class AdminUserPhoneController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = "Управление телефонами";
        $this->modelShowAttribute = "value";
        $this->modelClassName = CmsUserPhone::className();

        parent::init();

    }

}
