<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 *
 * @var $component \itlo\cms\base\Component
 */
/* @var $this yii\web\View */
?>

<?= $this->render('_header', [
    'component' => $component
]); ?>


<? $alert = \yii\bootstrap\Alert::begin([
    'options' => [
        'class' => 'alert-default'
    ],
    'closeButton' => false,
]); ?>
    <p><?= \Yii::t('itlo/cms', 'To improve performance, configure each component of the site is cached.') ?></p>
    <button type="submit" class="btn btn-danger btn-xs" onclick="sx.ComponentSettings.Cache.clearAll(); return false;">
        <i class="fa fa-times"></i> Сбросить кэш для всех
    </button>
<? $alert::end(); ?>

<?= $this->render('_footer'); ?>



