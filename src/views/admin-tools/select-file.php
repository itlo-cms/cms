<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */

/* @var $model \yii\db\ActiveRecord */

use itlo\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;

?>

<?
$this->registerJs(<<<JS
(function(sx, $, _)
{
    sx.classes.SelectFile = sx.classes.Component.extend({
        _init: function()
        {},

        _onDomReady: function()
        {
            this.GetParams              = sx.helpers.Request.getParams();
        },

        submit: function(file)
        {
            if (this.GetParams['CKEditorFuncNum'])
            {
                if (window.opener)
                {
                    if (window.opener.CKEDITOR)
                    {
                        window.opener.CKEDITOR.tools.callFunction(this.GetParams['CKEditorFuncNum'], file);
                        window.close();
                        return this;
                    }
                }
            }

            if (this.GetParams['callbackEvent'])
            {
                if (window.opener)
                {
                    if (window.opener.sx)
                    {
                        window.opener.sx.EventManager.trigger(this.GetParams['callbackEvent'], {
                            'file' : file
                        });

                        window.close();
                        return this;
                    }
                }
            }

            if (sx.Window.openerWidget()) {
                sx.Window.openerWidgetTriggerEvent('selectFile', {
                    'file': file
                });
                
                return this;
            }
            
            sx.alert(file);
            
            return this;
        }
    });

    sx.SelectFile = new sx.classes.SelectFile();

})(sx, sx.$, sx._);
JS
);
?>
<? echo
\yii\bootstrap\Tabs::widget([
    'itemOptions' => [
        'class' => 'nav-item',
    ],
    'linkOptions' => [
        'class' => 'nav-link',
    ],
    'items'       => [
        [
            'label'   => \Yii::t('itlo/cms', 'File storage'),
            'content' => $this->render('_file-storage-select-file'),
            'active'  => true,
            'class' => 'active'
        ],
        [
            'label'   => \Yii::t('itlo/cms', 'File manager'),
            'content' => $this->render('_file-manager-select-file'),
        ],
    ],
]);
?>

<!--
<hr/>
<? /*= \yii\helpers\Html::a("<i class='glyphicon glyphicon-question-sign'></i>", "#", [
    'class' => 'btn btn-default',
    'onclick' => "sx.dialog({'title' : '" . \Yii::t('itlo/cms', 'Help') . "', 'content' : '#sx-help'}); return false;"
]); */ ?>
<div style="display: none;" id="sx-help">
    <?php /*\Yii::t('itlo/cms', 'Help in the process of writing ...') */ ?>
</div>-->

