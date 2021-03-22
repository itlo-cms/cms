<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 *
 * @var $component \itlo\cms\base\Component
 * @var $user \itlo\cms\models\User
 */
/* @var $this yii\web\View */
$controller = $this->context;
?>

<?= $this->render('_header', [
    'component' => $component
]); ?>


<h2><?= \Yii::t('itlo/cms', 'User settings') ?>: <?= $user->getDisplayName() ?></h2>
<div class="sx-box g-mb-10">
    <? $alert = \yii\bootstrap\Alert::begin([
        'options' => [
            'class' => 'alert-default'
        ],
        'closeButton' => false,
    ]); ?>
    <?php if ($settings = \itlo\cms\models\CmsComponentSettings::findByComponentUser($component, $user)->one()) : ?>
        <button type="submit" class="btn btn-danger btn-xs"
                onclick="sx.ComponentSettings.Remove.removeByUser('<?= $user->id; ?>'); return false;">
            <i class="fa fa-times"></i> <?= \Yii::t('itlo/cms', 'Reset settings for this user') ?>
        </button>
        <small><?= \Yii::t('itlo/cms',
                'The settings for this component are stored in the database. This option will erase them from the database, but the component, restore the default values. As they have in the code the developer.') ?></small>
    <?php else
        : ?>
        <small><?= \Yii::t('itlo/cms', 'These settings not yet saved in the database') ?></small>
    <?php endif;
    ?>
    <? $alert::end(); ?>
</div>

<?php $form = \itlo\cms\modules\admin\widgets\form\ActiveFormUseTab::begin([
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
]); ?>

<?php $this->registerJs(<<<JS

(function(sx, $, _)
{
    sx.classes.DynamicForm = sx.classes.Component.extend({

        _onDomReady: function()
        {
            var self = this;

            $("[" + this.get('formreload') + "=true]").on('change', function()
            {
                self.update();
            });
        },

        update: function()
        {
            var self = this;
            
            _.delay(function()
            {
                var jForm = $("#" + self.get('id'));
                jForm.append($('<input>', {'type': 'hidden', 'name' : self.get('nosubmit'), 'value': 'true'}));
                jForm.submit();
            }, 200);
        }
    });

    sx.DynamicForm = new sx.classes.DynamicForm({
        'id' : '{$form->id}',
        'formreload' : '{$controller->reloadFieldParam}',
        'nosubmit' : '{$controller->reloadFormParam}',
    });
})(sx, sx.$, sx._);


JS
); ?>


<?= $form->errorSummary(\yii\helpers\ArrayHelper::merge(
        [$component], $component->getConfigFormModels()
)); ?>

<? if ($fields = $component->getConfigFormFields()) : ?>
    <? echo (new \itlo\yii2\form\Builder([
        'models'     => $component->getConfigFormModels(),
        'model'      => $component,
        'activeForm' => $form,
        'fields'     => $fields,
    ]))->render(); ?>
<? elseif ($formContent = $component->renderConfigForm($form)) : ?>
    <?= $formContent; ?>
<? else : ?>
    Нет редактируемых настроек для данного компонента
<? endif; ?>

<?= $form->buttonsStandart($component); ?>
<?= $form->errorSummary(\yii\helpers\ArrayHelper::merge(
        [$component], $component->getConfigFormModels()
)); ?>

<?php \itlo\cms\modules\admin\widgets\form\ActiveFormUseTab::end(); ?>


<?= $this->render('_footer'); ?>
