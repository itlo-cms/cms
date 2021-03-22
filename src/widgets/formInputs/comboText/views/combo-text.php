<?
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $widget \itlo\cms\widgets\formInputs\comboText\ComboTextInputWidget */

$options = $widget->clientOptions;
$clientOptions = \yii\helpers\Json::encode($options);
?>
<div id="<?= $widget->id; ?>">
    <div class="sx-select-controll">
        <?php if ($widget->modelAttributeSaveType) : ?>
            <?= \yii\helpers\Html::activeRadioList($widget->model, $widget->modelAttributeSaveType,
                \itlo\cms\widgets\formInputs\comboText\ComboTextInputWidget::editors()) ?>
        <?php else
            : ?>
            <?= \yii\helpers\Html::radioList(
                $widget->id . '-radio',
                $widget->defaultEditor,
                \itlo\cms\widgets\formInputs\comboText\ComboTextInputWidget::editors()
            ) ?>
        <?php endif;
        ?>
    </div>
    <div class="sx-controll">
        <?= $textarea; ?>
    </div>
</div>

<?
//TODO: убрать в файл


$this->registerCss(<<<CSS
    .CodeMirror
    {
        height: 400px;
    }
CSS
);


$this->registerJs(<<<JS
(function(sx, $, _)
{
    new sx.classes.combotext.ComboTextInputWidget({$clientOptions});
})(sx, sx.$, sx._);
JS
)
?>
