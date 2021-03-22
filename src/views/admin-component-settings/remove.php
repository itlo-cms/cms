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
    <p><?= \Yii::t('itlo/cms', 'Erase all the settings from the database for this component.') ?></p>
    <?php if ($settingsAllCount = \itlo\cms\models\CmsComponentSettings::findByComponent($component)->count()) : ?>
        <p><b><?= \Yii::t('itlo/cms', 'Total found') ?>:</b> <?= $settingsAllCount; ?></p>
        <button type="submit" class="btn btn-danger btn-xs"
                onclick="sx.ComponentSettings.Remove.removeAll(); return false;">
            <i class="fa fa-times"></i> <?= \Yii::t('itlo/cms', 'reset all settings') ?>
        </button>
    <?php else
        : ?>
        <small><?= \Yii::t('itlo/cms', 'The database no settings for this component.') ?></small>
    <?php endif;
    ?>
<? $alert::end(); ?>


<?= $this->render('_footer'); ?>



