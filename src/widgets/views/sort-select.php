<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $widget \itlo\cms\widgets\SortSelect */
/* @var $element string */
$widget = $this->context;
\yii\jui\Sortable::widget();

$hidden = $widget->items;
$visible = [];
$values = (array) $widget->model->{$widget->attribute};


\yii\jui\Sortable::widget();

$js = \yii\helpers\Json::encode($widget->jsOptions);
$this->registerJs(<<<JS
(function(sx, $, _)
{
    //new sx.classes.DualSelect({$js});
})(sx, sx.$, sx._);
JS
);
?>
<?= \yii\helpers\Html::beginTag('div', $widget->wrapperOptions); ?>
<div style="display: none;"><?= $element; ?></div>
<div class="row">
    <? $counter = 0; ?>
    <? foreach ($values as $key => $value) : ?>
        <div class="col-sm-4 col-sm-offset-1">
            <?= \yii\helpers\Html::listBox(\yii\helpers\Html::getInputName($widget->model, $widget->attribute) . "[{$counter}]", $key, $widget->items, [
                'class' => 'form-control',
                'size' => 1
            ]); ?>
        </div>
        <div class="col-sm-4 col-sm-offset-1">
            <?= \yii\helpers\Html::listBox(\yii\helpers\Html::getInputName($widget->model, $widget->attribute) . "[{$counter}]", $value, [
                SORT_ASC => "asc",
                SORT_DESC => "desc",
            ], [
                'class' => 'form-control',
                'size' => 1
            ]); ?>
        </div>
        <? $counter ++; ?>
    <? endforeach; ?>
</div>
<?= \yii\helpers\Html::endTag('div'); ?>
