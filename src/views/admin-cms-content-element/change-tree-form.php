<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
$model = new \itlo\cms\models\CmsContentElement();
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>

<?= $form->field($model, 'tree_id')->widget(
    \itlo\cms\backend\widgets\SelectModelDialogTreeWidget::class
); ?>
    <button type="submit" class="btn btn-primary">Сохранить</button>
<?php $form::end(); ?>

<?php $alert = \yii\bootstrap\Alert::begin([
    'options' => [
        'class' => 'alert-warning',
        'style' => 'margin-top: 20px;',
    ],
]) ?>
    <p><?= \Yii::t('itlo/cms', 'Attention! For checked items will be given a new primary section.') ?></p>
    <p><?= \Yii::t('itlo/cms',
            'This will alter the page record, and it will cease to be available at the old address.') ?></p>
<?php $alert::end(); ?>