<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
/* @var $action \itlo\cms\modules\admin\actions\modelEditor\AdminMultiDialogModelEditAction */
/* @var $content \itlo\cms\models\CmsContent */

$model = new \itlo\cms\models\CmsContentElement();

$jsData = \yii\helpers\Json::encode([
    'id' => $action->id
]);

$this->registerJs(<<<JS
(function(sx, $, _)
{
    sx.classes.MultiRP = sx.classes.Component.extend({

        _onDomReady: function()
        {
            var self = this;
            this.jWrapper = $("#" + this.get('id'));
            this.jForm = $('form', this.jWrapper);
            this.jSelect = $('.sx-select', this.jWrapper);

            this.jSelect.on('change', function()
            {
                $(".sx-multi", self.jForm).slideUp();

                if (self.jSelect.val())
                {
                    self.jForm.show();
                } else
                {
                    self.jForm.hide();
                }

                _.each(self.jSelect.val(), function(element)
                {
                    $(".sx-multi-" + element, self.jForm).slideDown();

                });
            });
        }
    });

    new sx.classes.MultiRP({$jsData});
})(sx, sx.$, sx._);
JS
);
?>
<div id="<?= $action->id; ?>">
    <?php if ($action->controller && $action->controller->content) : ?>

        <?php $content = $action->controller->content; ?>
        <?php $element = $content->createElement(); ?>
        <?php $element->loadDefaultValues(); ?>

        <?
            $rpm = $element->relatedPropertiesModel;
        ?>

        <?php if ($element && $rpm) : ?>

            <? $rpm->initAllProperties(); ?>
            <?php $form = \itlo\cms\modules\admin\widgets\ActiveForm::begin([
                'options' => [
                    'class' => 'sx-form',
                ]
            ]); ?>
            <?= \itlo\widget\chosen\Chosen::widget([
                'multiple' => true,
                'name' => 'fields',
                'options' => [
                    'class' => 'sx-select'
                ],
                'items' => $rpm->attributeLabels()
            ]); ?>

            <?= \yii\helpers\Html::hiddenInput('content_id', $content->id); ?>


            <?php foreach ($rpm->getProperties() as $property) : ?>
                <div class="sx-multi sx-multi-<?= $property->code; ?>" style="display: none;">
                    <?php if ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_ELEMENT) : ?>

                        <?php if ($property->handler->fieldElement == \itlo\cms\relatedProperties\propertyTypes\PropertyTypeElement::FIELD_ELEMENT_SELECT) : ?>
                            <?
                            echo $form->field($rpm, $property->code)->widget(
                                \itlo\cms\backend\widgets\SelectModelDialogContentElementWidget::class,
                                [
                                    'content_id' => $property->handler->content_id
                                ]
                            );
                            ?>
                        <?php else
                            : ?>
                            <?
                            echo $form->field($rpm, $property->code)->widget(
                                \itlo\cms\backend\widgets\SelectModelDialogContentElementWidget::class,
                                [
                                    'content_id' => $property->handler->content_id,
                                    'multiple' => true
                                ]
                            );
                            ?>
                        <?php endif; ?>
                    <?php else
                        : ?>
                        <?= $property->renderActiveForm($form);
                        ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?= $form->buttonsStandart($model, ['save']); ?>
            <?php $form::end(); ?>
        <?php else
            : ?>
            Not found properties
        <?php endif;
        ?>
    <?php endif; ?>
</div>



