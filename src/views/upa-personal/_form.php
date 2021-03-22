<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $controller \itlo\cms\backend\controllers\BackendModelController */
/* @var $action \itlo\cms\backend\actions\BackendModelCreateAction|\itlo\cms\backend\actions\IHasActiveForm */
/* @var $model \itlo\cms\models\CmsLang */
$controller = $this->context;
$action = $controller->action;
?>
<?php $form = $action->beginActiveForm(); ?>
    <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'image_id')->widget(
            \itlo\cms\widgets\AjaxFileUploadWidget::class,
            [
                'accept' => 'image/*',
                'multiple' => false
            ]
        ); ?>
        <?= $form->field($model, 'username'); ?>
        <?= $form->field($model, 'first_name')->textInput(); ?>
        <?= $form->field($model, 'last_name')->textInput(); ?>
        <?= $form->field($model, 'patronymic')->textInput(); ?>
        <?= $form->field($model, 'email')->textInput(); ?>
        <?
        \itlo\cms\admin\assets\JqueryMaskInputAsset::register($this);
        $id = \yii\helpers\Html::getInputId($model, 'phone');
        $this->registerJs(<<<JS
$("#{$id}").mask("+7 999 999-99-99");
JS
        );
        ?>

        <?= $form->field($model, 'phone')->textInput([
            'placeholder' => '+7 903 722-28-73'
        ]); ?>

    <?= $form->buttonsCreateOrUpdate($model); ?>
    <?= $form->errorSummary($model); ?>
<?php $action->endActiveForm(); ?>
