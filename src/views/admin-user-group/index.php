<?php
/**
 * index
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\Game */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?= \itlo\cms\modules\admin\widgets\GridViewHasSettings::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'class' => \itlo\cms\modules\admin\grid\ActionColumn::className(),
            'controller' => $controller
        ]

        /*['class' => \itlo\cms\grid\ImageColumn::className()]*/,

        'groupname',
        'description',


        ['class' => \itlo\cms\grid\CreatedAtColumn::className()],
        ['class' => \itlo\cms\grid\UpdatedAtColumn::className()],

        ['class' => \itlo\cms\grid\CreatedByColumn::className()],
        ['class' => \itlo\cms\grid\UpdatedByColumn::className()],

    ],
]); ?>
