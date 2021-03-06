<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\filters;

use itlo\cms\helpers\RequestResponse;
use itlo\cms\helpers\UrlHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\User;

/**
 * Class AdminAccessControl
 * @package itlo\cms\modules\admin\filters
 */
class CmsAccessControl extends \yii\filters\AccessControl
{
    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        $rr = new RequestResponse();

        if ($user->getIsGuest()) {
            $authUrl = UrlHelper::construct(["/cms/auth/login"])->setCurrentRef()->createAbsoluteUrl();

            if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
                \Yii::$app->getResponse()->redirect($authUrl);
                $rr->redirect = $authUrl;
                return (array)$rr;
            } else {
                \Yii::$app->response->redirect($authUrl);
                \Yii::$app->end();
            }

        } else {
            throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
}
