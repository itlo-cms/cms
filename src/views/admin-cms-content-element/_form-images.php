<?php
/* @var $this yii\web\View */
/* @var $model \itlo\cms\models\CmsContentElement */
/* @var $relatedModel \itlo\cms\relatedProperties\models\RelatedPropertiesModel */
?>
<?= $form->fieldSet(\Yii::t('itlo/cms', 'Images/Files')); ?>

<?= $form->field($model, 'imageIds')->widget(
    \itlo\cms\widgets\AjaxFileUploadWidget::class,
    [
        'accept' => 'image/*',
        'multiple' => true
    ]
); ?>

<?= $form->field($model, 'fileIds')->widget(
    \itlo\cms\widgets\AjaxFileUploadWidget::class,
    [
        'multiple' => true
    ]
); ?>

<?= $form->fieldSetEnd() ?>
