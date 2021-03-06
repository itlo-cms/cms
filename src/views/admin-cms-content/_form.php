<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $controller \itlo\cms\backend\controllers\BackendModelController */
/* @var $action \itlo\cms\backend\actions\BackendModelCreateAction|\itlo\cms\backend\actions\IHasActiveForm */
/* @var $model \itlo\cms\models\CmsLang */
$controller = $this->context;
$action = $controller->action;
?>
<?php $form = $action->beginActiveForm(); ?>

<?= $form->fieldSet(\Yii::t('itlo/cms', 'Main')); ?>

<?= \itlo\cms\modules\admin\widgets\BlockTitleWidget::widget([
    'content' => \Yii::t('itlo/cms', 'Main')
]); ?>

<?php if ($content_type = \Yii::$app->request->get('content_type')) : ?>
    <?= $form->field($model, 'content_type')->hiddenInput(['value' => $content_type])->label(false); ?>
<?php else : ?>
    <div style="display: none;">
        <?= $form->fieldSelect($model, 'content_type',
            \yii\helpers\ArrayHelper::map(\itlo\cms\models\CmsContentType::find()->all(), 'code', 'name'));
        ?>
    </div>
<?php endif; ?>

<?= $form->field($model, 'name')->textInput(); ?>
<?= $form->field($model, 'code')->textInput()
    ->hint(\Yii::t('itlo/cms',
        'The name of the template to draw the elements of this type will be the same as the name of the code.')); ?>

<?= $form->field($model, 'view_file')->textInput()
    ->hint(\Yii::t('itlo/cms', 'The path to the template. If not specified, the pattern will be the same code.')); ?>


<?= $form->fieldRadioListBoolean($model, 'active'); ?>
<?= $form->fieldRadioListBoolean($model, 'visible'); ?>


<?= \itlo\cms\modules\admin\widgets\BlockTitleWidget::widget([
    'content' => \Yii::t('itlo/cms', 'Link to section')
]); ?>

<div class="row">
    <div class="col-md-6">

        <?= $form->field($model, 'default_tree_id')->widget(
            \itlo\cms\backend\widgets\SelectModelDialogTreeWidget::class
        ); ?>
        <?php /*= $form->fieldSelect($model, 'default_tree_id', \itlo\cms\helpers\TreeOptions::getAllMultiOptions(), [
                    'allowDeselect' => true
                ]); */ ?>
    </div>
    <div class="col-md-6">
        <?= $form->fieldRadioListBoolean($model, 'is_allow_change_tree'); ?>
    </div>
</div>


<?= $form->field($model, 'root_tree_id')->widget(
    \itlo\cms\backend\widgets\SelectModelDialogTreeWidget::class
)->hint(\Yii::t('itlo/cms', 'If it is set to the root partition, the elements can be tied to him and his sub.')); ?>

<?php /*= $form->fieldSelect($model, 'root_tree_id', \itlo\cms\helpers\TreeOptions::getAllMultiOptions(), [
            'allowDeselect' => true
        ])->hint(\Yii::t('itlo/cms', 'If it is set to the root partition, the elements can be tied to him and his sub.')); */ ?>

<?= \itlo\cms\modules\admin\widgets\BlockTitleWidget::widget([
    'content' => \Yii::t('itlo/cms', 'Relationship to other content')
]); ?>

<div class="row">
    <div class="col-md-3">
        <?= $form->fieldSelect($model, 'parent_content_id', \itlo\cms\models\CmsContent::getDataForSelect(true,
            function(\yii\db\ActiveQuery $activeQuery) use ($model) {
                if (!$model->isNewRecord) {
                    //$activeQuery->andWhere(['!=', 'id', $model->id]);
                }
            }),
            [
                'allowDeselect' => true
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->fieldRadioListBoolean($model, 'parent_content_is_required'); ?>
    </div>
    <div class="col-md-3">
        <?= $form->fieldSelect($model, 'parent_content_on_delete',
            \itlo\cms\models\CmsContent::getOnDeleteOptions()); ?>
    </div>
</div>


<?php if ($model->childrenContents) : ?>
    <p><b><?= \Yii::t('itlo/cms', 'Children content') ?></b></p>
    <?php foreach ($model->childrenContents as $contentChildren) : ?>
        <p><?= \yii\helpers\Html::a($contentChildren->name, \itlo\cms\helpers\UrlHelper::construct([
                '/cms/admin-cms-content/update',
                'pk' => $contentChildren->id
            ])->enableAdmin()->toString()) ?></p>
    <?php endforeach; ?>

<?php endif; ?>


<?= $form->fieldSetEnd(); ?>


<?php if (!$model->isNewRecord) : ?>
    <?php if ($controllerProperty = \Yii::$app->createController('cms/admin-cms-content-property')[0]) : ?>
        <?
        /**
         * @var \itlo\cms\backend\BackendAction $actionIndex
         * @var \itlo\cms\backend\BackendAction $actionCreate
         */
        $actionCreate = \yii\helpers\ArrayHelper::getValue($controllerProperty->actions, 'create');
        $actionIndex = \yii\helpers\ArrayHelper::getValue($controllerProperty->actions, 'index');
        ?>

        <?php if ($actionIndex) : ?>
            <?= $form->fieldSet(\Yii::t('itlo/cms', 'Properties')) ?>

            <?php $pjax = \yii\widgets\Pjax::begin(); ?>
            <?
            $query = \itlo\cms\models\CmsContentProperty::find()->orderBy(['priority' => SORT_ASC]);
            $query->joinWith('cmsContentProperty2contents map');
            $query->andWhere(['map.cms_content_id' => $model->id]);
            ?>
            <?
            if ($actionCreate) {
                $actionCreate->url = \yii\helpers\ArrayHelper::merge($actionCreate->urlData, [
                    'content_id' => $model->id
                ]);

                $actionCreate->name = \Yii::t("itlo/cms", "Create");

                /*echo \itlo\cms\backend\widgets\DropdownControllerActionsWidget::widget([
                    'actions' => ['create' => $actionCreate],
                    'isOpenNewWindow' => true
                ]);*/

                echo \itlo\cms\backend\widgets\ControllerActionsWidget::widget([
                    'actions' => ['create' => $actionCreate],
                    'clientOptions' => ['pjax-id' => $pjax->id],
                    'isOpenNewWindow' => true,
                    'tag' => 'div',
                    'itemWrapperTag' => 'span',
                    'itemTag' => 'button',
                    'itemOptions' => ['class' => 'btn btn-default'],
                    'options' => ['class' => 'sx-controll-actions'],
                ]);
            }
            ?>
            <?= \itlo\cms\modules\admin\widgets\GridViewStandart::widget([
                'dataProvider' => new \yii\data\ActiveDataProvider([
                    'query' => $query
                ]),
                'settingsData' =>
                    [
                        'namespace' => \Yii::$app->controller->uniqueId . "__" . $model->id
                    ],
                'adminController' => $controllerProperty,
                'isOpenNewWindow' => true,
                //'filterModel'       => $searchModel,
                'autoColumns' => false,
                'pjax' => $pjax,
                'columns' =>
                    [
                        'name',

                        [
                            'label' => \Yii::t('itlo/cms', 'Type'),
                            'format' => 'raw',
                            'value' => function(\itlo\cms\models\CmsContentProperty $cmsContentProperty) {
                                return $cmsContentProperty->handler->name;
                            }
                        ],

                        [
                            'label' => \Yii::t('itlo/cms', 'Content'),
                            'value' => function(\itlo\cms\models\CmsContentProperty $cmsContentProperty) {
                                $contents = \yii\helpers\ArrayHelper::map($cmsContentProperty->cmsContents, 'id',
                                    'name');
                                return implode(', ', $contents);
                            }
                        ],

                        [
                            'label' => \Yii::t('itlo/cms', 'Sections'),
                            'format' => 'raw',
                            'value' => function(\itlo\cms\models\CmsContentProperty $cmsContentProperty) {
                                if ($cmsContentProperty->cmsTrees) {
                                    $contents = \yii\helpers\ArrayHelper::map($cmsContentProperty->cmsTrees, 'id',
                                        function($cmsTree) {
                                            $path = [];

                                            if ($cmsTree->parents) {
                                                foreach ($cmsTree->parents as $parent) {
                                                    if ($parent->isRoot()) {
                                                        $path[] = "[" . $parent->site->name . "] " . $parent->name;
                                                    } else {
                                                        $path[] = $parent->name;
                                                    }
                                                }
                                            }
                                            $path = implode(" / ", $path);
                                            return "<small><a href='{$cmsTree->url}' target='_blank' data-pjax='0'>{$path} / {$cmsTree->name}</a></small>";

                                        });


                                    return '<b>' . \Yii::t('itlo/cms',
                                            'Only shown in sections') . ':</b><br />' . implode('<br />', $contents);
                                } else {
                                    return '<b>' . \Yii::t('itlo/cms', 'Always shown') . '</b>';
                                }
                            }
                        ],
                        [
                            'class' => \itlo\cms\grid\BooleanColumn::className(),
                            'attribute' => "active"
                        ],
                        'code',
                        'priority',
                    ]
            ]); ?>

            <?php \yii\widgets\Pjax::end(); ?>

            <?= $form->fieldSetEnd(); ?>
        <?php endif; ?>
    <?php endif; ?>





    <?= $form->fieldSet(\Yii::t('itlo/cms', 'Seo')); ?>
    <?= $form->field($model, 'meta_title_template')->textarea()->hint("?????????????????????? ?????????????????????? ???????? {=model.name}"); ?>
    <?= $form->field($model, 'meta_description_template')->textarea(); ?>
    <?= $form->field($model, 'meta_keywords_template')->textarea(); ?>
    <?= $form->fieldSetEnd(); ?>

<?php endif; ?>


<?= $form->fieldSet(\Yii::t('itlo/cms', 'Captions')); ?>
<?= $form->field($model, 'name_one')->textInput(); ?>
<?= $form->field($model, 'name_meny')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('itlo/cms', 'Additionally')); ?>

<?= \itlo\cms\modules\admin\widgets\BlockTitleWidget::widget([
    'content' => \Yii::t('itlo/cms', 'Access')
]); ?>
<?= $form->fieldRadioListBoolean($model, 'access_check_element'); ?>

<?= \itlo\cms\modules\admin\widgets\BlockTitleWidget::widget([
    'content' => \Yii::t('itlo/cms', 'Additionally')
]); ?>
<?= $form->fieldInputInt($model, 'priority'); ?>
<?= $form->field($model, 'is_count_views')->checkbox(); ?>
<?php /*= $form->fieldRadioListBoolean($model, 'index_for_search'); */ ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->buttonsCreateOrUpdate($model); ?>
<?php $action->endActiveForm(); ?>
