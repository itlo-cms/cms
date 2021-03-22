<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $contentType \itlo\cms\models\CmsContentType */
/* @var $model \itlo\cms\shop\cmsWidgets\filters\ShopProductFiltersWidget */

?>
<?= $form->fieldSet(\Yii::t('itlo/cms', 'Showing')); ?>
<?= $form->field($model, 'viewFile')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('itlo/cms', 'Data source')); ?>
<?= $form->fieldSelect($model, 'content_id', \itlo\cms\models\CmsContent::getDataForSelect()); ?>

<?php /*= $form->fieldSelectMulti($model, 'searchModelAttributes', [
        'image' => \Yii::t('itlo/cms', 'Filter by photo'),
        'hasQuantity' => \Yii::t('itlo/cms', 'Filter by availability')
    ]); */ ?>

<?php /*= $form->field($model, 'searchModelAttributes')->dropDownList([
        'image' => \Yii::t('itlo/cms', 'Filter by photo'),
        'hasQuantity' => \Yii::t('itlo/cms', 'Filter by availability')
    ], [
'multiple' => true,
'size' => 4
]); */ ?>

<?php if ($model->cmsContent) : ?>
    <?= $form->fieldSelectMulti($model, 'realatedProperties',
        \yii\helpers\ArrayHelper::map($model->cmsContent->cmsContentProperties, 'code', 'name')); ?>
<?php else : ?>
    Дополнительные свойства появятся после сохранения настроек
<?php endif; ?>



<?= $form->fieldSetEnd(); ?>

