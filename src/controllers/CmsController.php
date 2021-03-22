<?php
/**
 * CmsController
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\helpers\RequestResponse;
use itlo\cms\helpers\UrlHelper;
use yii\db\Exception;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class CmsController
 * @package itlo\cms\controllers
 */
class CmsController extends Controller
{
    public function actionIndex()
    {
        return $this->render($this->action->id);
    }
}