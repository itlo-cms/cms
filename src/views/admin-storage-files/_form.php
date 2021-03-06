<?php

use yii\helpers\Html;
use itlo\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
use itlo\cms\models\Tree;

/* @var $this yii\web\View */
/* @var $model \itlo\cms\models\StorageFile */

?>

<?php $this->registerCss(<<<CSS
    .sx-image-controll .sx-image img
    {
        max-height: 200px;
        border: 2px solid silver;
    }
CSS
); ?>

<div class="sx-image-controll">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->fieldSet(\Yii::t('itlo/cms', 'Main')); ?>
    <?php if ($model->isImage()) : ?>
        <div class="sx-image">
            <img src="<?= $model->src; ?>"/>
        </div>
    <?php endif; ?>
    <div class="">

    </div>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 255])->hint(\Yii::t('itlo/cms',
        'This name is usually needed for SEO, so that the file was found in the search engines')) ?>
    <?= $form->field($model, 'name_to_save')->textInput(['maxlength' => 255])->hint(\Yii::t('itlo/cms',
        'Filename, when someone will be download it.')) ?>

    <?= $form->fieldSetEnd(); ?>




    <?= $form->fieldSet(\Yii::t('itlo/cms', 'Description')); ?>

    <?= $form->field($model, 'description_full')->widget(
        \itlo\cms\widgets\formInputs\ckeditor\Ckeditor::className(),
        [
            'options' => ['rows' => 20],
            'preset' => 'full',
            'relatedModel' => $model,
        ])
    ?>

    <?= $form->field($model, 'description_short')->widget(
        \itlo\cms\widgets\formInputs\ckeditor\Ckeditor::className(),
        [
            'options' => ['rows' => 6],
            'preset' => 'full',
            'relatedModel' => $model,
        ])
    ?>

    <?= $form->fieldSetEnd() ?>



    <?= $form->fieldSet(\Yii::t('itlo/cms', 'Additional Information')); ?>

    <?= $form->field($model, 'original_name')->textInput([
        'maxlength' => 255,
        'disabled' => 'disabled'
    ])->hint(\Yii::t('itlo/cms', 'Filename at upload time to the site')) ?>


    <div class="form-group field-storagefile-mime_type">
        <label>???????????? ??????????</label>
        <?= Html::textInput("file-size", \Yii::$app->formatter->asShortSize($model->size), [
            'disabled' => 'disabled',
            'class' => 'form-control',
        ]) ?>
    </div>
    <?php /*= $form->field($model, 'size')->textInput([
        'maxlength' => 255,
        'disabled' => 'disabled',
        'value' => \Yii::$app->formatter->asShortSize($model->size)
    ]); */ ?>

    <?= $form->field($model, 'mime_type')->textInput([
        'maxlength' => 255,
        'disabled' => 'disabled'
    ])->hint('Internet Media Types ??? ' . \Yii::t('itlo/cms',
            'types of data which can be transmitted via the Internet using standard MIME.')); ?>

    <?= $form->field($model, 'extension')->textInput([
        'maxlength' => 255,
        'disabled' => 'disabled'
    ]); ?>

    <?php if ($model->isImage()) : ?>
        <?php if (!$model->image_height || !$model->image_width) : ?>
            <?php $model->updateFileInfo(); ?>
        <?php endif; ?>
        <div class="col-md-12">
            <div class="col-md-2">
                <?= $form->field($model, 'image_width')->textInput([
                    'maxlength' => 255,
                    'disabled' => 'disabled'
                ]); ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($model, 'image_height')->textInput([
                    'maxlength' => 255,
                    'disabled' => 'disabled'
                ]); ?>
            </div>
        </div>
    <?php endif; ?>



    <?= $form->fieldSetEnd(); ?>


    <?php if ($model->isImage()) : ?>
        <?= $form->fieldSet(\Yii::t('itlo/cms', 'Thumbnails')); ?>
        <p><?= \Yii::t('itlo/cms',
                'This is an image in different places of the site displayed in different sizes.') ?></p>

        <?= $form->fieldSetEnd(); ?>

        <?= $form->fieldSet(\Yii::t('itlo/cms', 'Image editor')); ?>

        <?= $form->fieldSetEnd(); ?>
    <?php endif; ?>

    <?= $form->buttonsCreateOrUpdate($model); ?>
    <?php ActiveForm::end(); ?>
</div>