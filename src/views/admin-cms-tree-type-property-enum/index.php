<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $searchModel \itlo\cms\models\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \itlo\cms\models\CmsContentElement */

$sortAttr = $dataProvider->getSort()->attributes;
$query = $dataProvider->query;
$query->joinWith('property as p');
$query->select([\itlo\cms\models\CmsTreeTypePropertyEnum::tableName() . '.*', 'p.name as p_name']);

$dataProvider->getSort()->attributes = \yii\helpers\ArrayHelper::merge($sortAttr, [
    'p.name' => [
        'asc' => ['p.name' => SORT_ASC],
        'desc' => ['p.name' => SORT_DESC],
        'label' => \Yii::t('itlo/shop/app', 'Property'),
        'default' => SORT_ASC
    ]
]);

?>
<?php $pjax = \yii\widgets\Pjax::begin(); ?>

<?php echo $this->render('_search', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
]); ?>

<?= \itlo\cms\modules\admin\widgets\GridViewStandart::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    //'autoColumns'       => false,
    'pjax' => $pjax,
    'adminController' => $controller,
    'columns' =>
        [
            'id',
            [
                'label' => \Yii::t('itlo/cms', 'Property'),
                'attribute' => 'p.name',
                'value' => function(\itlo\cms\models\CmsTreeTypePropertyEnum $cmsContentPropertyEnum) {
                    return $cmsContentPropertyEnum->property->name;
                }
            ],
            'value',

            'code',
            'priority',
        ]
]); ?>

<?php \yii\widgets\Pjax::end(); ?>

