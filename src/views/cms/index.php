<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
$this->title = 'Система управления сайтом: ITLO CMS (Yii2)';
?>

<div style="text-align: center; padding: 100px;">
    <p>Система управления сайтом: <?= \yii\helpers\Html::a("ITLO CMS (Yii2)", \Yii::$app->cms->descriptor->homepage, [
            'target' => '_blank'
        ]); ?></p>
    <p>@author <?= \yii\helpers\Html::a("itlo", "https://itlo.ru", [
            'target' => '_blank'
        ]); ?></p>
</div>

