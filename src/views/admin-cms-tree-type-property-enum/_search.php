<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

$filter = new \yii\base\DynamicModel([
    'id',
]);
$filter->addRule('id', 'integer');

$filter->load(\Yii::$app->request->get());

if ($filter->id) {
    $dataProvider->query->andWhere(['id' => $filter->id]);
}

?>
<?php $form = \itlo\cms\modules\admin\widgets\filters\AdminFiltersForm::begin([
    'action' => '/' . \Yii::$app->request->pathInfo,
]); ?>

<?= $form->field($searchModel, 'value')->setVisible(true)->textInput([
    'placeholder' => \Yii::t('itlo/cms', 'Search by name')
]); ?>

<?= $form->field($searchModel, 'property_id')->label(\Yii::t('itlo/cms', 'Property'))->setVisible(true)->widget(
    \itlo\widget\chosen\Chosen::class,
    [
        'multiple' => true,
        'items' => \yii\helpers\ArrayHelper::map(
            \itlo\cms\models\CmsContentProperty::find()->all(), 'id', 'name'
        )
    ]
); ?>

<?php $form::end(); ?>
