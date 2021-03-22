<?
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $widget \itlo\cms\widgets\formInputs\componentSettings\ComponentSettingsWidget */
/* @var $element string */

$options = $widget->clientOptions;
$clientOptions = \yii\helpers\Json::encode($options);
?>
<div id="<?= $widget->id; ?>">
    <div class="sx-select-controll">
        <?= $element; ?>
    </div>
    <a href="#" class="<?= $widget->buttonClasses; ?>">
        <i class="fa fa-cog"></i> <?= $widget->buttonText; ?>
    </a>
</div>

<?

$this->registerJs(<<<JS
(function(sx, $, _)
{
    new sx.classes.ComponentSettingsWidget({$clientOptions});
})(sx, sx.$, sx._);
JS
)
?>
