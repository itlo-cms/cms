<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 *
 * @var $loadedComponents
 * @var $component \itlo\cms\base\Component
 */
/* @var $this yii\web\View */
$r = new ReflectionClass($component);
?>

<?php /* \itlo\cms\modules\admin\widgets\Pjax::begin([
    'id' => 'widget-select-component'
]) */ ?>
<form id="selector-component" action="" method="get" data-pjax>
    <label><?= \Yii::t('itlo/cms', 'Component settings') ?></label>

    <?=
    \itlo\widget\chosen\Chosen::widget([
        'name'          => 'component',
        'items'         => $loadedForSelect,
        'allowDeselect' => false,
        'value'         => $r->getName(),
    ])
    ?>
    <?php if (\Yii::$app->admin->isEmptyLayout()) : ?>
        <input type="hidden"
               name="<?= \itlo\cms\backend\helpers\BackendUrlHelper::BACKEND_PARAM_NAME; ?>[<?= \itlo\cms\backend\helpers\BackendUrlHelper::BACKEND_PARAM_NAME_EMPTY_LAYOUT; ?>]"
               value="true"/>
    <?php endif; ?>
</form>
<hr/>

<? if (method_exists($component, 'getEditUrl')) : ?>
    <iframe data-src="<?= $component->getEditUrl(); ?>" width="100%;" height="200px;" id="sx-test"></iframe>
<? else : ?>
    <?
    $url = \itlo\cms\backend\helpers\BackendUrlHelper::createByParams(['/cms/admin-component-settings/index'])
            ->merge([
                'componentClassName' => $component->className(),
                'attributes'         => $component->callAttributes,
            ])
            ->enableEmptyLayout()
            ->url
    ?>
    <iframe data-src="<?= $url; ?>" width="100%;" height="200px;" id="sx-test"></iframe>
<? endif; ?>


<?
\itlo\cms\themes\unify\admin\assets\UnifyAdminIframeAsset::register($this);
$this->registerJs(<<<JS
(function(sx, $, _)
{
    sx.classes.SelectorComponent = sx.classes.Component.extend({

        _init: function()
        {
            this.Iframe = new sx.classes.Iframe('sx-test', {
                'autoHeight'        : true,
                'heightSelector'    : 'body',
                'minHeight'         : 800
            });
        },

        _onDomReady: function()
        {
            $("#selector-component select").on('change', function()
            {
                $("#selector-component").submit();
            });

            _.delay(function()
            {
                $('#sx-test').attr('src', $('#sx-test').data('src'));
            }, 200);
        },

        _onWindowReady: function()
        {}
    });

    new sx.classes.SelectorComponent();
})(sx, sx.$, sx._);
JS
)
?>

<?php /* \itlo\cms\modules\admin\widgets\Pjax::end(); */ ?>
