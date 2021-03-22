<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 *
 * @var $component \itlo\cms\base\Component
 */
/* @var $this yii\web\View */
?>

<?= $this->render('_header', [
    'component' => $component
]); ?>


<div class="sx-box g-mb-10 g-pa-10">
    <p><?= \Yii::t('itlo/cms',
            'This component may have personal preferences. And it works differently depending on which of the sites is displayed.') ?></p>
    <p><?= \Yii::t('itlo/cms',
            'In that case, if the site not has personal settings will be used the default settings.') ?></p>
    <?php if ($settings = \itlo\cms\models\CmsComponentSettings::findByComponent($component)->andWhere([
        '>',
        'cms_site_id',
        0
    ])->count()) : ?>
        <p><b><?= \Yii::t('itlo/cms', 'Number of customized sites') ?>:</b> <?= $settings; ?></p>
        <button type="submit" class="btn btn-danger btn-xs"
                onclick="sx.ComponentSettings.Remove.removeSites(); return false;">
            <i class="fa fa-times"></i> <?= \Yii::t('itlo/cms', 'reset settings for all sites"') ?>
        </button>
        <small>.</small>
    <?php else
        : ?>
        <small><?= \Yii::t('itlo/cms', 'Neither site does not have personal settings for this component') ?></small>
    <?php endif;
    ?>
</div>

<?
$search = new \itlo\cms\models\Search(\itlo\cms\models\CmsSite::className());
$search->search(\Yii::$app->request->get());
$search->getDataProvider()->query->andWhere(['active' => \itlo\cms\components\Cms::BOOL_Y]);

?>
<?= \itlo\cms\modules\admin\widgets\GridViewHasSettings::widget([
    'dataProvider' => $search->getDataProvider(),
    'filterModel' => $search->getLoadedModel(),
    'columns' => [

        [
            'class' => \yii\grid\DataColumn::className(),
            'value' => function(\itlo\cms\models\CmsSite $model, $key, $index) {
                return \yii\helpers\Html::a('<i class="fa fa-cog"></i>',
                    \itlo\cms\helpers\UrlHelper::constructCurrent()->setRoute('cms/admin-component-settings/site')->set('site_id',
                        $model->id)->toString(),
                    [
                        'class' => 'btn btn-default btn-xs',
                        'title' => \Yii::t('itlo/cms', 'Customize')
                    ]);
            },

            'format' => 'raw',
        ],

        'name',
        'code',

        [
            'class' => \itlo\cms\grid\ComponentSettingsColumn::className(),
            'component' => $component,
        ],
    ]
]) ?>


<?= $this->render('_footer'); ?>
