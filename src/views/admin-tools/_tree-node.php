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

$result = $model->name;
$additionalName = '';
if ($model->level == 0) {
    $site = \itlo\cms\models\CmsSite::findOne(['id' => $model->cms_site_id]);
    if ($site) {
        $additionalName = $site->name;
    }
} else {
    if ($model->name_hidden) {
        $additionalName = $model->name_hidden;
    }
}

if ($additionalName) {
    $result .= " [{$additionalName}]";
}

$controllElement = \Yii::$app->controller->renderNodeControll($model);
?>

<?= $controllElement; ?>
<div class="sx-label-node level-<?= $model->level; ?> status-<?= $model->active; ?>">
    <a href="<?= $widget->getOpenCloseLink($model); ?>">
        <?= $result; ?>
    </a>
</div>

<!-- Possible actions -->
<div class="sx-controll-node row">
    <div class="pull-left sx-controll-act">
        <a href="<?= $model->absoluteUrl; ?>" target="_blank"
           class="btn-tree-node-controll btn btn-default btn-sm show-at-site"
           title="<?= \Yii::t('itlo/cms', "Show at site"); ?>">
            <span class="fa fa-eye"></span>
        </a>
    </div>
</div>

<?php if ($model->treeType) : ?>
    <div class="pull-right sx-tree-type">
        <?= $model->treeType->name; ?>
    </div>
<?php endif; ?>

