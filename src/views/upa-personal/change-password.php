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
        <?= $form->field($model, 'new_password')->passwordInput() ?>
        <?= $form->field($model, 'new_password_confirm')->passwordInput() ?>

    <?= $form->buttonsCreateOrUpdate($model); ?>
    <?= $form->errorSummary($model); ?>
<?php $action->endActiveForm(); ?>
