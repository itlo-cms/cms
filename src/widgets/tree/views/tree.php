<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $widget \itlo\cms\widgets\tree\CmsTreeWidget */
$widget = $this->context;
?>
<div class="row">
    <div class="sx-container-tree col-md-12">
        <?= \yii\helpers\Html::beginTag("div", $widget->options); ?>
        <?= $widget->renderNodes($widget->models); ?>
        <?= \yii\helpers\Html::endTag("div"); ?>
    </div>
</div>

