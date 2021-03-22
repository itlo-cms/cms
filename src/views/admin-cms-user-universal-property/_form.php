<?php

use yii\helpers\Html;
use itlo\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
use itlo\cms\models\Tree;
use itlo\cms\modules\admin\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model Tree */
if ($model->isNewRecord) {
    $model->loadDefaultValues();
}

?>


<?php $form = ActiveForm::begin([
    'id' => 'sx-dynamic-form',
    'enableAjaxValidation' => false,
]); ?>

<?php $this->registerJs(<<<JS

(function(sx, $, _)
{
    sx.classes.DynamicForm = sx.classes.Component.extend({

        _onDomReady: function()
        {
            var self = this;

            $("[data-form-reload=true]").on('change', function()
            {
                self.update();
            });
        },

        update: function()
        {
            _.delay(function()
            {
                var jForm = $("#sx-dynamic-form");
                jForm.append($('<input>', {'type': 'hidden', 'name' : 'sx-not-submit', 'value': 'true'}));
                jForm.submit();
            }, 200);
        }
    });

    sx.DynamicForm = new sx.classes.DynamicForm();
})(sx, sx.$, sx._);


JS
); ?>

<?= $form->fieldSet(\Yii::t('itlo/cms', 'Basic settings')) ?>

<?= $form->fieldRadioListBoolean($model, 'active') ?>
<?= $form->fieldRadioListBoolean($model, 'is_required') ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'code')->textInput() ?>

<?= $form->field($model, 'component')->listBox(array_merge(['' => ' — '],
    \Yii::$app->cms->relatedHandlersDataForSelect), [
    'size' => 1,
    'data-form-reload' => 'true'
])
    ->label(\Yii::t('itlo/cms', "Property type"));
?>

<?php if ($handler) : ?>
    <?= \itlo\cms\modules\admin\widgets\BlockTitleWidget::widget(['content' => \Yii::t('itlo/cms', 'Settings')]); ?>
    <?php if ($handler instanceof \itlo\cms\relatedProperties\propertyTypes\PropertyTypeList) : ?>
        <?php $handler->enumRoute = 'cms/admin-cms-user-universal-property-enum'; ?>
    <?php endif; ?>
    <?= $handler->renderConfigForm($form); ?>
<?php endif; ?>



<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('itlo/cms', 'Additionally')) ?>
<?= $form->field($model, 'hint')->textInput() ?>
<?= $form->fieldInputInt($model, 'priority') ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->buttonsStandart($model); ?>

<?php ActiveForm::end(); ?>




