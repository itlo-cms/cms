<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */

/* @var $model \itlo\cms\models\forms\LoginFormUsernameOrEmail */

use yii\helpers\Html;
use itlo\cms\base\widgets\ActiveFormAjaxSubmit as ActiveForm;
use \itlo\cms\helpers\UrlHelper;

$this->title = \Yii::t('itlo/cms', 'Authorization');
\Yii::$app->breadcrumbs->createBase()->append($this->title);
?>
<div class="row">
    <section id="sidebar-main" class="col-md-12">
        <div id="content">
            <div class="row">
                <div class="col-lg-3">
                </div>
                <div class="col-lg-6">

                    <?php $form = ActiveForm::begin([
                        'validationUrl' => UrlHelper::construct('cms/auth/login')->setSystemParam(\itlo\cms\helpers\RequestResponse::VALIDATION_AJAX_FORM_SYSTEM_NAME)->toString()
                    ]); ?>
                    <?= $form->field($model, 'identifier') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="form-group">
                        <?= Html::submitButton("<i class=\"glyphicon glyphicon-off\"></i> " . \Yii::t('itlo/cms',
                                'Log in'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                    <?= Html::a(\Yii::t('itlo/cms', 'Forgot your password?'),
                        UrlHelper::constructCurrent()->setRoute('cms/auth/forget')->toString()) ?> |
                    <?= Html::a(\Yii::t('itlo/cms', 'Registration'),
                        UrlHelper::constructCurrent()->setRoute('cms/auth/register')->toString()) ?>
                </div>

                <div class="col-lg-3">

                </div>
                <!--Или социальные сети
                --><?php /*= yii\authclient\widgets\AuthChoice::widget([
                     'baseAuthUrl' => ['site/auth']
                ]) */ ?>
            </div>
        </div>
    </section>
</div>
