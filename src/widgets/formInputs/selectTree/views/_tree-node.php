<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $widget \itlo\cms\widgets\tree\CmsTreeWidget */
/* @var $model \itlo\cms\models\CmsTree */
/* @var $selectTreeInputWidget \itlo\cms\widgets\formInputs\selectTree\SelectTreeInputWidget */
$widget = $this->context;
$selectTreeInputWidget = \yii\helpers\ArrayHelper::getValue($widget->contextData, 'selectTreeInputWidget');
?>

<?= $selectTreeInputWidget->renderNodeControll($model); ?>
<div class="sx-label-node level-<?= $model->level; ?> status-<?= $model->active; ?>">
    <a href="<?= $widget->getOpenCloseLink($model); ?>"><?= $selectTreeInputWidget->renderNodeName($model); ?></a>
</div>

<!-- Possible actions -->
<!--<div class="sx-controll-node row">
    <div class="pull-left sx-controll-act">
        <a href="<?php /*= $model->absoluteUrl; */ ?>" target="_blank" class="btn-tree-node-controll btn btn-default btn-sm show-at-site" title="<?php /*= \Yii::t('itlo/cms',"Show at site"); */ ?>">
            <span class="fa fa-eye"></span>
        </a>
    </div>
</div>-->

<?php /* if ($model->treeType) : */ ?><!--
    <div class="pull-right sx-tree-type">
        <?php /*= $model->treeType->name; */ ?>
    </div>
--><?php /* endif; */ ?>

