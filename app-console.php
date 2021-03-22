<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 * @date 22.03.2021
 */
// fcgi doesn't have STDIN and STDOUT defined by default
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

//Standard loader
require_once(__DIR__ . '/bootstrap.php');

\Yii::beginProfile('Load config app');

if (YII_ENV == 'dev') {
    \Yii::beginProfile('Rebuild config');
    \itlo\cms\composer\config\Builder::rebuild();
    \Yii::endProfile('Rebuild config');
}

$configFile = \itlo\cms\composer\config\Builder::path('console-' . ENV);
if (!file_exists($configFile)) {
    $configFile = \itlo\cms\composer\config\Builder::path('console');
}

$config = (array)require $configFile;
\Yii::endProfile('Load config app');

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);