<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
?>

<?
echo \mihaildev\elfinder\ElFinder::widget([
    'controller' => 'cms/admin-elfinder-full', // вставляем название контроллера, по умолчанию равен elfinder
    'callbackFunction' => new \yii\web\JsExpression('function(file, id){}'), // id - id виджета
    'frameOptions' => [
        'style' => 'width: 100%; height: 800px;'
    ]
]);
?>