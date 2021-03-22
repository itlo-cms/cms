<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */

/* @var $model \yii\db\ActiveRecord */

use itlo\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
?>
<?
echo \mihaildev\elfinder\ElFinder::widget([
    'controller' => 'cms/admin-elfinder-full',
    // вставляем название контроллера, по умолчанию равен elfinder
    //'filter'           => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
    'callbackFunction' => new \yii\web\JsExpression('function(file, id){
                sx.SelectFile.submit(file.url);
            }'),
    // id - id виджета
    'frameOptions' => [
        'style' => 'width: 100%; height: 800px;'
    ]
]);
?>