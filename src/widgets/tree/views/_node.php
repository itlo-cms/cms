<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $widget \itlo\cms\widgets\tree\CmsTreeWidget */
/* @var $model \itlo\cms\models\CmsTree */
$widget = $this->context;
?>
<?= \yii\helpers\Html::beginTag('li', [
    "class" => "sx-tree-node sx-tree-node-{$model->id} " . ($widget->isOpenNode($model) ? " open" : ""),
    "data-id" => $model->id,
    "title" => ""
]); ?>

<div class="row">
    <?php if ($model->children) : ?>
        <div class="sx-node-open-close">
            <?php if ($widget->isOpenNode($model)) : ?>
                <a href="<?= $widget->getOpenCloseLink($model); ?>" class="btn btn-sm btn-default">
                    <span class="fa fa-minus" title="<?= \Yii::t('itlo/cms', "Minimize"); ?>"></span>
                </a>
            <?php else
                : ?>
                <a href="<?= $widget->getOpenCloseLink($model);
                ?>" class="btn btn-sm btn-default">
                    <span class="fa fa-plus" title="<?= \Yii::t('itlo/cms', "Restore"); ?>"></span>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?= $widget->renderNodeContent($model); ?>

</div>

<!-- Construction of child elements -->
<?php if ($widget->isOpenNode($model) && $model->children) : ?>
    <?= $widget->renderNodes($model->children); ?>
<?php endif; ?>

<?= \yii\helpers\Html::endTag('li'); ?>

