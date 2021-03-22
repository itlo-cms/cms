<?php
/* @var $this yii\web\View */
/* @var $model \itlo\cms\models\CmsContentElement */
/* @var $relatedModel \itlo\cms\relatedProperties\models\RelatedPropertiesModel */
?>
<?= $form->fieldSet(\Yii::t('itlo/cms', 'In detal')); ?>

<?= $form->field($model, 'image_full_id')->widget(
    \itlo\cms\widgets\AjaxFileUploadWidget::class,
    [
        'accept' => 'image/*',
        'multiple' => false
    ]
); ?>

<?= $form->field($model, 'description_full')->widget(
    \itlo\cms\widgets\formInputs\comboText\ComboTextInputWidget::className(),
    [
        'modelAttributeSaveType' => 'description_full_type',
    ]);
?>

<?= $form->fieldSetEnd() ?>
