<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
$model = new \itlo\cms\models\CmsContentElement();
?>
<?php $form = \itlo\cms\modules\admin\widgets\ActiveForm::begin([
        'usePjax' => false
]); ?>

<?php /*= $form->field($model, 'treeIds')->widget(
        \itlo\cms\widgets\formInputs\selectTree\SelectTreeInputWidget::class,
        [
            'multiple' => true
        ]
    ); */ ?>

<?= $form->field($model, 'treeIds')->widget(
    \itlo\cms\backend\widgets\SelectModelDialogTreeWidget::class,
    [
        'multiple' => true
    ]
); ?>


<?= \yii\helpers\Html::checkbox('removeCurrent', false); ?> <label><?= \Yii::t('itlo/cms',
        'Get rid of the already linked (in this case, the selected records bind only to the selected section)') ?></label>
<?= $form->buttonsStandart($model, ['save']); ?>

<?php \itlo\cms\modules\admin\widgets\ActiveForm::end(); ?>


<?php $alert = \yii\bootstrap\Alert::begin([
    'options' => [
        'class' => 'alert-info',
        'style' => 'margin-top: 20px;',
    ],
]) ?>
    <p><?= \Yii::t('itlo/cms', 'You can specify some additional sections that will show your records.') ?></p>
    <p><?= \Yii::t('itlo/cms', 'This does not affect the final address of the page, and hence safe.') ?></p>
<?php $alert::end(); ?>