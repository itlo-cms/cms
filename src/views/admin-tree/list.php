<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $searchModel \itlo\cms\models\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \itlo\cms\models\CmsTree */
?>
<?php $pjax = \yii\widgets\Pjax::begin(); ?>

<?php echo $this->render('_search', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
]); ?>

<?= \itlo\cms\modules\admin\widgets\GridViewStandart::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'autoColumns' => false,
    'pjax' => $pjax,
    'adminController' => $controller,
    'columns' =>
        [
            'name',
            'code',
            'treeType.name',
            'level',
            /*[
                'label' => \Yii::t('itlo/cms', 'Sections'),
                'value' => function(\itlo\cms\models\CmsTreeTypeProperty $cmsContentProperty)
                {
                    $contents = \yii\helpers\ArrayHelper::map($cmsContentProperty->cmsTreeTypes, 'id', 'name');
                    return implode(', ', $contents);
                }
            ],
            [
                'label' => \Yii::t('itlo/cms', 'Number of partitions where the property is filled'),
                'value' => function(\itlo\cms\models\CmsTreeTypeProperty $cmsContentProperty)
                {
                    return $cmsContentProperty->getElementProperties()->andWhere(['!=', 'value', ''])->count();
                }
            ],
            [
                'class'         => \itlo\cms\grid\BooleanColumn::className(),
                'attribute'     => "active"
            ],*/
        ]
]); ?>

<?php \yii\widgets\Pjax::end(); ?>