<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 * @date 22.03.2021
 */
require(__DIR__ . '/global.php');

require(VENDOR_DIR . '/autoload.php');
require(VENDOR_DIR . '/yiisoft/yii2/Yii.php');

\Yii::setAlias('@root', ROOT_DIR);
\Yii::setAlias('@vendor', VENDOR_DIR);