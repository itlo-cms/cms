<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $searchModel \itlo\cms\models\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php $pjax = \itlo\cms\modules\admin\widgets\Pjax::begin(); ?>

<?php echo $this->render('_search', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider
]); ?>

<?= \itlo\cms\modules\admin\widgets\GridViewStandart::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax' => $pjax,
    'adminController' => \Yii::$app->controller,
    'settingsData' =>
        [
            'order' => SORT_ASC,
            'orderBy' => "priority",
        ],
    'columns' =>
        [
            'name',
            'code',

            [
                'value' => function(\itlo\cms\models\CmsContentType $model) {
                    $contents = \yii\helpers\ArrayHelper::map($model->cmsContents, 'id', 'name');
                    return implode(', ', $contents);
                },

                'label' => 'Контент',
            ],

            'priority',
        ]
]); ?>

<?php $pjax::end(); ?>
