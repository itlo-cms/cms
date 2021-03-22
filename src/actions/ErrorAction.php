<?php
/**
 * ErrorAction
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\actions;

use itlo\cms\helpers\RequestResponse;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ErrorAction
 * @package skeeks\cms\actions
 */
class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * Runs the action
     *
     * @return string result content
     */
    public function run()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return '';
        }

        if ($exception instanceof \HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }

        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }

        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
        }


        if (Yii::$app->getRequest()->getIsAjax()) {
            $rr = new RequestResponse();

            $rr->success = false;
            $rr->message = "$name: $message";

            return (array)$rr;
        } else {
            //All requests are to our backend
            //TODO::Add image processing
            $info = pathinfo(\Yii::$app->request->pathInfo);
            if ($extension = ArrayHelper::getValue($info, 'extension')) {
                $extension = \skeeks\cms\helpers\StringHelper::strtolower($extension);
                if (in_array($extension, ['js', 'css'])) {
                    \Yii::$app->response->format = Response::FORMAT_RAW;
                    if ($extension == 'js') {
                        \Yii::$app->response->headers->set('Content-Type', 'application/javascript');
                    }
                    if ($extension == 'css') {
                        \Yii::$app->response->headers->set('Content-Type', 'text/css');
                    }

                    $url = \Yii::$app->request->absoluteUrl;
                    return "/* File: '{$url}' not found */";
                }
            }

            return $this->controller->render($this->view ?: $this->id, [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }
}
