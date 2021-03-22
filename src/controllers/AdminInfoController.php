<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\admin\AdminController;
use itlo\cms\backend\BackendAction;
use itlo\cms\backend\BackendController;
use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\Search;
use itlo\cms\modules\admin\actions\AdminAction;
use itlo\cms\modules\admin\controllers\helpers\rules\NoModel;
use itlo\sx\Dir;
use itlo\sx\File;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use Yii;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class AdminInfoController extends BackendController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', "Information about the system");
        $this->generateAccessActions = false;

        parent::init();
    }

    public function actions()
    {
        return
            [
                'index' =>
                    [
                        'class' => BackendAction::className(),
                        'name' => \Yii::t('itlo/cms', 'General information'),
                        'callback' => [$this, 'actionIndex'],
                    ]
            ];
    }

    public function actionIndex()
    {
        return $this->render($this->action->id,
            [
                'phpVersion' => PHP_VERSION,
                'yiiVersion' => \Yii::getVersion(),
                'application' => [
                    'yii' => \Yii::getVersion(),
                    'name' => \Yii::$app->cms->appName,
                    'env' => YII_ENV,
                    'debug' => YII_DEBUG,
                ],
                'php' => [
                    'version' => PHP_VERSION,
                    'xdebug' => extension_loaded('xdebug'),
                    'apc' => extension_loaded('apc'),
                    'memcache' => extension_loaded('memcache'),
                    'xcache' => extension_loaded('xcache'),
                    'imagick' => extension_loaded('imagick'),
                    'gd' => extension_loaded('gd'),
                ],
                'extensions' => $this->getExtensions(),
            ]);
    }


    public function actionPhp()
    {
        phpinfo();
        die;
    }

    /**
     * Перегенерация файла модулей.
     * @return \yii\web\Response
     */
    public function actionWriteEnvGlobalFile()
    {
        $env = (string)\Yii::$app->request->get('env');
        if (!$env) {
            \Yii::$app->session->setFlash('error', \Yii::t('itlo/cms', 'Not Specified Places to record'));
            return $this->redirect(\Yii::$app->request->getReferrer());
        }

        $content = <<<PHP
<?php
defined('YII_ENV') or define('YII_ENV', '{$env}');
PHP;

        $file = new File(APP_ENV_GLOBAL_FILE);
        if ($file->write($content)) {
            \Yii::$app->session->setFlash('success', \Yii::t('itlo/cms', 'File successfully created and written'));
        } else {
            \Yii::$app->session->setFlash('error', \Yii::t('itlo/cms', 'Failed to write file'));
        }

        return $this->redirect(\Yii::$app->request->getReferrer());
    }

    public function actionRemoveEnvGlobalFile()
    {
        $file = new File(APP_ENV_GLOBAL_FILE);
        if ($file->remove()) {
            \Yii::$app->session->setFlash('success', \Yii::t('itlo/cms', 'File deleted successfully'));
        } else {
            \Yii::$app->session->setFlash('error', \Yii::t('itlo/cms', 'Could not delete the file'));
        }

        return $this->redirect(\Yii::$app->request->getReferrer());
    }


    /**
     * Returns data about extensions
     *
     * @return array
     */
    public function getExtensions()
    {
        $data = [];
        foreach (\Yii::$app->extensions as $extension) {
            $data[$extension['name']] = $extension['version'];
        }

        return $data;
    }


}