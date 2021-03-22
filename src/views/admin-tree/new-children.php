<?php
/**
 * new-children
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
?>

<?= $this->render('_form', [
    'model' => $model
]); ?>

    <hr/>
<?php /*= \yii\helpers\Html::a('Пересчитать приоритеты по алфавиту', '#', ['class' => 'btn btn-xs btn-primary']) ?> |
<?= \yii\helpers\Html::a('Пересчитать приоритеты по дате добавления', '#', ['class' => 'btn btn-xs btn-primary']) ?> |
<?= \yii\helpers\Html::a('Пересчитать приоритеты по дате обновления', '#', ['class' => 'btn btn-xs btn-primary']) */ ?>
<?= $this->render('_recalculate-children-priorities', [
    'model' => $model
]); ?>

<?= $this->render('list', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'controller' => $controller,
]); ?>