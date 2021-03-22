<?php

use yii\helpers\Html;
use itlo\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord */
/* @var $console \itlo\cms\controllers\AdminUserController */
?>


<?php $form = ActiveForm::begin(); ?>
<?php ?>

<?= $form->fieldSet(\Yii::t('itlo/cms', 'General information')) ?>
<?= $form->field($model, 'name')->textInput(); ?>
<?= $form->field($model, 'code')->textInput(); ?>
<?= $form->fieldInputInt($model, 'priority')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>


<?= $form->fieldSet(\Yii::t('itlo/cms', 'Content')) ?>
<?= \itlo\cms\modules\admin\widgets\RelatedModelsGrid::widget([
    'label' => \Yii::t('itlo/cms', "Content"),
    'hint' => "",
    'parentModel' => $model,
    'relation' => [
        'content_type' => 'code'
    ],
    'controllerRoute' => '/cms/admin-cms-content',
    'gridViewOptions' => [
        'sortable' => true,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'name',
            'code',
            [
                'class' => \itlo\cms\grid\BooleanColumn::className(),
                'falseValue' => \itlo\cms\components\Cms::BOOL_N,
                'trueValue' => \itlo\cms\components\Cms::BOOL_Y,
                'attribute' => 'active'
            ],
        ],
    ],
]); ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->buttonsCreateOrUpdate($model); ?>
<?php ActiveForm::end(); ?>