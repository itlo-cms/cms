<?php

use itlo\cms\models\Tree;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Tree */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>

<?= \Yii::t('itlo/cms', 'Recalculate the priorities of childs') ?><br/>
    По полю: <?= Html::dropDownList('column', null, [
    'name' => \Yii::t('itlo/cms', 'Name'),
    'created_at' => \Yii::t('itlo/cms', 'Created At'),
    'updated_at' => \Yii::t('itlo/cms', 'Updated At')
]) ?>
    <br/>
    Порядок: <?= Html::dropDownList('sort', null,
    ['desc' => \Yii::t('itlo/cms', 'Descending'), 'asc' => \Yii::t('itlo/cms', 'Ascending')]) ?>
    <br/>
<?= Html::submitButton(\Yii::t('itlo/cms', 'Recalculate'),
    ['class' => 'btn btn-xs btn-primary', 'name' => 'recalculate_children_priorities', 'value' => '1']) ?>
    <br/><br/>

<?php ActiveForm::end(); ?>