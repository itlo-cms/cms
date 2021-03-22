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
<?php $pjax = \itlo\cms\modules\admin\widgets\Pjax::begin(); ?>

<?

$search = new \itlo\cms\models\Search(\itlo\cms\models\StorageFile::className());
$dataProvider = $search->getDataProvider();

$dataProvider->sort->defaultOrder = [
    'created_at' => SORT_DESC
];

?>
<?
$id = $pjax->id;

echo \itlo\cms\widgets\StorageFileManager::widget([
    'clientOptions' =>
        [
            'completeUploadFile' => new \yii\web\JsExpression(<<<JS
                function(data)
                {
                    _.delay(function()
                    {
                        $.pjax.reload('#{$id}', {});
                    }, 500)

                }
JS
            )
        ],
]); ?>
<p></p>

<?php
$searchModel = new \itlo\cms\models\Search(\itlo\cms\models\CmsStorageFile::class);
$dataProvider   = $search->search(\Yii::$app->request->queryParams);
$searchModel    = $search->loadedModel;

/*echo $this->render('@itlo/cms/views/admin-storage-files/_search', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider
]); */?>

<?php $dataProvider->pagination->defaultPageSize = 10; ?>

<?= \itlo\cms\modules\admin\widgets\GridViewHasSettings::widget([

    'dataProvider' => $dataProvider,
    'filterModel' => $search->getLoadedModel(),

    'pjax' => $pjax,

    'columns' => [

        [
            'class' => \yii\grid\DataColumn::className(),
            'value' => function(\itlo\cms\models\StorageFile $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-circle-arrow-left"></i> ' . \Yii::t('itlo/cms',
                        'Choose file'), $model->src, [
                    'class' => 'btn btn-primary',
                    'onclick' => 'sx.SelectFile.submit("' . $model->src . '"); return false;',
                    'data-pjax' => 0
                ]);
            },
            'format' => 'raw'
        ],

        [
            'class' => \itlo\cms\modules\admin\grid\ActionColumn::className(),
            'controller' => \Yii::$app->createController('cms/admin-storage-files')[0],
            'isOpenNewWindow' => true
        ],

        [
            'class' => \yii\grid\DataColumn::className(),
            'value' => function(\itlo\cms\models\StorageFile $model) {
                if ($model->isImage()) {

                    $smallImage = \Yii::$app->imaging->getImagingUrl($model->src,
                        new \itlo\cms\components\imaging\filters\Thumbnail());
                    return "<a href='" . $model->src . "' data-pjax='0' class='sx-fancybox' title='" . \Yii::t('itlo/cms',
                            'Increase') . "'>
                                    <img src='" . $smallImage . "' />
                                </a>";
                }

                return \yii\helpers\Html::tag('span', $model->extension,
                    ['class' => 'label label-primary', 'style' => 'font-size: 18px;']);
            },
            'format' => 'raw'
        ],

        'name',

        /*[
            'class'     => \yii\grid\DataColumn::className(),
            'value'     => function(\itlo\cms\models\StorageFile $model)
            {
                return \yii\helpers\Html::tag('pre', $model->src);
            },

            'format' => 'html',
            'attribute' => 'src'
        ],*/

        [
            'class' => \yii\grid\DataColumn::className(),
            'value' => function(\itlo\cms\models\StorageFile $model) {
                $model->cluster_id;
                $cluster = \Yii::$app->storage->getCluster($model->cluster_id);
                return $cluster->name;
            },

            'filter' => \yii\helpers\ArrayHelper::map(\Yii::$app->storage->getClusters(), 'id', 'name'),
            'format' => 'html',
            'attribute' => 'cluster_id',
        ],

        [
            'attribute' => 'mime_type',
            'filter' => \yii\helpers\ArrayHelper::map(\itlo\cms\models\StorageFile::find()->groupBy(['mime_type'])->all(),
                'mime_type', 'mime_type'),
        ],

        [
            'attribute' => 'extension',
            'filter' => \yii\helpers\ArrayHelper::map(\itlo\cms\models\StorageFile::find()->groupBy(['extension'])->all(),
                'extension', 'extension'),
        ],

        [
            'class' => \itlo\cms\grid\FileSizeColumnData::className(),
            'attribute' => 'size'
        ],


        ['class' => \itlo\cms\grid\CreatedAtColumn::className()],
        //['class' => \itlo\cms\grid\UpdatedAtColumn::className()],

        ['class' => \itlo\cms\grid\CreatedByColumn::className()],
        //['class' => \itlo\cms\grid\UpdatedByColumn::className()],

    ],

]); ?>

<?php $pjax::end(); ?>
