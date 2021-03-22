<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
$imageFile = $model;
?>
<a href="<?= $imageFile->src; ?>" class="sx-fancybox" data-pjax="0">
    <img src="<?= \Yii::$app->imaging->getImagingUrl($imageFile->src,
        new \itlo\cms\components\imaging\filters\Thumbnail()); ?>"/>
</a>
<div class="sx-controlls">
    <?= \yii\helpers\Html::a('<i class="glyphicon glyphicon-circle-arrow-left"></i> ' . \Yii::t('itlo/cms',
            'Choose file'), $model->src, [
        'class' => 'btn btn-primary btn-xs',
        'onclick' => 'sx.SelectFile.submit("' . $model->src . '"); return false;',
        'data-pjax' => 0
    ]); ?>
</div>