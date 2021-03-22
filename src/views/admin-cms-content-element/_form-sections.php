<?php
/* @var $this yii\web\View */
/* @var $model \itlo\cms\models\CmsContentElement */
/* @var $relatedModel \itlo\cms\relatedProperties\models\RelatedPropertiesModel */
?>
<?= $form->fieldSet(\Yii::t('itlo/cms', 'Sections')); ?>
<?php if ($contentModel->root_tree_id) : ?>
    <?php $rootTreeModels = \itlo\cms\models\CmsTree::findAll($contentModel->root_tree_id); ?>
<?php else
    : ?>
    <?php $rootTreeModels = \itlo\cms\models\CmsTree::findRoots()->joinWith('cmsSiteRelation')->orderBy([\itlo\cms\models\CmsSite::tableName() . ".priority" => SORT_ASC])->all();
    ?>
<?php endif; ?>

<?php /* if ($contentModel->is_allow_change_tree == \itlo\cms\components\Cms::BOOL_Y) : */ ?><!--
        <?php /* if ($rootTreeModels) : */ ?>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <?php /*= $form->field($model, 'tree_id')->widget(
                        \itlo\cms\widgets\formInputs\selectTree\SelectTreeInputWidget::class,
                        [
                            'options' => [
                                'data-form-reload' => 'true'
                            ],
                            'multiple' => false,
                            'treeWidgetOptions' =>
                            [
                                'models' => $rootTreeModels
                            ]
                        ]
                    ); */ ?>
                </div>
            </div>
        <?php /* endif; */ ?>
    --><?php /* endif; */ ?>

<?php if ($rootTreeModels) : ?>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <?= $form->field($model, 'treeIds')->widget(
                \itlo\cms\widgets\formInputs\selectTree\SelectTreeInputWidget::class,
                [
                    'options' => [
                        //'data-form-reload' => 'true'
                    ],
                    'multiple' => true,
                    'treeWidgetOptions' =>
                        [
                            'models' => $rootTreeModels
                        ]
                ]
            ); ?>
        </div>
    </div>
<?php endif; ?>

<?= $form->fieldSetEnd() ?>
