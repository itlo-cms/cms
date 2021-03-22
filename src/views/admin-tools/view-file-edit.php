<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $rootViewFile string */
/* @var $model \itlo\cms\models\forms\ViewFileEditModel */
$this->registerCss(<<<CSS
.CodeMirror
{
    height: auto;
}
CSS
)
?>


<?php $form = \itlo\cms\modules\admin\widgets\form\ActiveFormStyled::begin([
    'useAjaxSubmit' => true,
    'usePjax' => false,
    'enableAjaxValidation' => false
]); ?>

<?= $form->field($model, 'source')->label($model->rootViewFile)->widget(
    \itlo\widget\codemirror\CodemirrorWidget::className(),
    [
        'preset' => 'htmlmixed',
        'assets' =>
            [
                \itlo\widget\codemirror\CodemirrorAsset::THEME_NIGHT
            ],
        'clientOptions' =>
            [
                'theme' => 'night'
            ],
        'options' => ['rows' => 40],
    ]
); ?>

<?= $form->buttonsStandart($model); ?>

<?php \itlo\cms\modules\admin\widgets\form\ActiveFormStyled::end(); ?>
