<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

/* @var $this yii\web\View */

use yii\helpers\Html;
use itlo\cms\base\widgets\ActiveFormAjaxSubmit as ActiveForm;
use \itlo\cms\helpers\UrlHelper;

$this->title = \Yii::t('itlo/cms', 'Getting a new password');
\Yii::$app->breadcrumbs->createBase()->append($this->title);
?>
<div class="row">
    <section id="sidebar-main" class="col-md-12">
        <div id="content">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <h1><?= $message; ?></h1>
                    <?= Html::a(\Yii::t('itlo/cms', 'Request recovery again'),
                        UrlHelper::constructCurrent()->setRoute('cms/auth/forget')->toString()) ?> |
                    <?= Html::a(\Yii::t('itlo/cms', 'Authorization'),
                        UrlHelper::constructCurrent()->setRoute('cms/auth/login')->toString()) ?> |
                    <?= Html::a(\Yii::t('itlo/cms', 'Registration'),
                        UrlHelper::constructCurrent()->setRoute('cms/auth/register')->toString()) ?>
                </div>
                <div class="col-lg-3"></div>
            </div>
        </div>
    </section>
</div>
