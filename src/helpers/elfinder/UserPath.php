<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\helpers\elfinder;

use itlo\cms\rbac\CmsManager;
use Yii;

class UserPath extends \mihaildev\elfinder\volume\UserPath
{
    public function isAvailable()
    {
        if (!\Yii::$app->user->can(CmsManager::PERMISSION_ELFINDER_USER_FILES)) {
            return false;
        }

        return parent::isAvailable();
    }
}