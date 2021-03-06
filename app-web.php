<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 * @date 22.03.2021
 */

$env = getenv('ENV');
if (!empty($env)) {
    defined('ENV') or define('ENV', $env);
}

require_once(__DIR__ . '/bootstrap.php');

\Yii::beginProfile('Load config app');

if (YII_ENV == 'dev') {
    \Yii::beginProfile('Rebuild config');
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    \itlo\cms\composer\config\Builder::rebuild();
    \Yii::endProfile('Rebuild config');
}

$configFile = \itlo\cms\composer\config\Builder::path('web-' . ENV);
if (!file_exists($configFile)) {
    $configFile = \itlo\cms\composer\config\Builder::path('web');
}
$config = (array)require $configFile;

\Yii::endProfile('Load config app');

$application = new yii\web\Application($config);
$application->run();
