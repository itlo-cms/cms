<?php
/**
 * Controller
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace skeeks\cms\base;

use yii\helpers\ArrayHelper;
use yii\web\Application;
use yii\web\Controller as YiiWebController;

/**
 * Class Controller
 * @package itlo\cms\base
 */
class Controller extends YiiWebController
{
    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        if ($this->module instanceof Application) {
            return parent::render($view, $params);
        }

        if (strpos($view, '/') && !strpos($view, '@app/views')) {
            return parent::render($view, $params);
        }

        $viewDir = "@app/views/modules/" . $this->module->id . '/' . $this->id;
        $viewApp = $viewDir . '/' . $view;

        if (isset(\Yii::$app->view->theme->pathMap['@app/views'])) {
            $tmpPaths = [];
            foreach (\Yii::$app->view->theme->pathMap['@app/views'] as $path) {
                $tmpPaths[] = $path . "/modules/" . $this->module->id . '/' . $this->id;
            }

            $tmpPaths[] = $this->viewPath;

            \Yii::$app->view->theme->pathMap = ArrayHelper::merge(\Yii::$app->view->theme->pathMap, [
                $viewDir => $tmpPaths
            ]);
        }

        return parent::render($viewApp, $params);
    }


}