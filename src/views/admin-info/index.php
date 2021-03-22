<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

/* @var $this yii\web\View */

use itlo\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;


$autoEnvFile = '';
if (file_exists(APP_ENV_GLOBAL_FILE)) {
    $autoEnvFile = \Yii::t('itlo/cms', 'Yes') . ' ';
    $autoEnvFile .= "<a class='btn btn-xs btn-primary' href='" . \itlo\cms\helpers\UrlHelper::construct('cms/admin-info/remove-env-global-file')->enableAdmin()->toString() . "'>" . \Yii::t('itlo/cms',
            'Delete') . "</a>  ";
} else {
    $autoEnvFile = \Yii::t('itlo/cms', 'No') . ' ';
}
$autoEnvFile .= "<a class='btn btn-xs btn-primary' href='" . \itlo\cms\helpers\UrlHelper::construct('cms/admin-info/write-env-global-file',
        ['env' => 'dev'])->enableAdmin()->toString() . "'>" . \Yii::t('itlo/cms', 'To record') . " dev</a>  ";
$autoEnvFile .= "<a class='btn btn-xs btn-primary' href='" . \itlo\cms\helpers\UrlHelper::construct('cms/admin-info/write-env-global-file',
        ['env' => 'prod'])->enableAdmin()->toString() . "'>" . \Yii::t('itlo/cms', 'To record') . " prod</a>";

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->fieldSet(\Yii::t('itlo/cms', 'Project configuration')); ?>
<?php
echo $this->render('table', [
    'values' => [
        'itlo CMS' => \Yii::$app->cms->version,

        \Yii::t('itlo/cms', '{yii} Version', ['yii' => 'Yii']) => $application['yii'],
        \Yii::t('itlo/cms',
            'Project name') => $application['name'] . " (<a href='" . \itlo\cms\helpers\UrlHelper::construct('cms/admin-settings')->enableAdmin()->toString() . "'>" . \Yii::t('itlo/cms',
                'edit') . "</a>)",
        \Yii::t('itlo/cms', 'Environment ({yii_env})', ['yii_env' => 'YII_ENV']) => $application['env'],
        \Yii::t('itlo/cms', 'Development mode ({yii_debug})',
            ['yii_debug' => 'YII_DEBUG']) => $application['debug'] ? \Yii::t('itlo/cms', 'Yes') : \Yii::t('itlo/cms', 'No'),
    ],
]);
?>
<?= $form->fieldSetEnd(); ?>


<?= $form->fieldSet(\Yii::t('itlo/cms', 'All extensions and modules {yii}', ['yii' => 'Yii'])); ?>
<?php if (!empty($extensions)) {
    echo $this->render('table', [
        'values' => $extensions,
    ]);
} ?>
<?= $form->fieldSetEnd(); ?>


<?= $form->fieldSet(\Yii::t('itlo/cms', '{php} configuration', ['php' => "PHP"])); ?>
<?
echo $this->render('table', [
    'values' => [
        'PHP Version' => $php['version'],
        'Xdebug' => $php['xdebug'] ? 'Enabled' : 'Disabled',
        'APC' => $php['apc'] ? 'Enabled' : 'Disabled',
        'Memcache' => $php['memcache'] ? 'Enabled' : 'Disabled',
        'Xcache' => $php['xcache'] ? 'Enabled' : 'Disabled',
        'Gd' => $php['gd'] ? 'Enabled' : 'Disabled',
        'Imagick' => $php['imagick'] ? 'Enabled' : 'Disabled',
        'Sendmail Path' => ini_get('sendmail_path'),
        'Sendmail From' => ini_get('sendmail_from'),
        'open_basedir' => ini_get('open_basedir'),
        'realpath_cache_size' => ini_get('realpath_cache_size'),
        'xcache.cacher' => ini_get('xcache.cacher'),
        'xcache.ttl' => ini_get('xcache.ttl'),
        'xcache.stat' => ini_get('xcache.stat'),
        'xcache.size' => ini_get('xcache.size'),
    ],
]);
?>
<?= $form->fieldSetEnd(); ?>


<?= $form->fieldSet('PHP info'); ?>
<iframe id="php-info"
        src='<?= \itlo\cms\helpers\UrlHelper::construct('/cms/admin-info/php')->enableAdmin()->toString(); ?>'
        width='100%' height='1000'></iframe>;
<?= $form->fieldSetEnd(); ?>

<?php ActiveForm::end(); ?>




