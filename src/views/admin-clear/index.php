<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */
$url = \yii\helpers\Url::to(['/cms/admin-clear/index']);
$data = \yii\helpers\Json::encode([
    'backend' => $url
])
?>

    <div class="sx-box g-pa-10 sx-bg-primary">
        <?= \yii\helpers\Html::a(\Yii::t('itlo/cms', 'Delete temporary files'), $url, [
            'class' => 'btn btn-primary',
            'onclick' => 'new sx.classes.Clear(' . $data . '); return false;'
        ]); ?>
        <hr/>

        <?= \itlo\cms\modules\admin\widgets\GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $clearDirs
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'label',
                [
                    'class' => \yii\grid\DataColumn::className(),
                    'value' => function($data) {
                        /**
                         * @var $dir \itlo\sx\Dir
                         */
                        $dir = $data['dir'];
                        return $dir->getPath();
                    }
                ],

                [
                    'class' => \yii\grid\DataColumn::className(),
                    'value' => function($data) {
                        /**
                         * @var $dir \itlo\sx\Dir
                         */
                        $dir = $data['dir'];
                        return $dir->getSize()->formatedShortSize();
                    }
                ],
            ]
        ]);
        ?>
    </div>
<?
$this->registerJs(<<<JS
    (function(sx, $, _)
    {
        sx.classes.Clear = sx.classes.Component.extend({

            _init: function()
            {
                var ajax = sx.ajax.preparePostQuery(this.get("backend"));

                new sx.classes.AjaxHandlerStandartRespose(ajax);
                //new sx.classes.AjaxHandlerNoLoader(ajax);

                new sx.classes.AjaxHandlerBlocker(ajax, {
                    'wrapper': '.sx-panel .panel-content'
                });

                /*ajax.onError(function(e, data)
                {
                    sx.notify.info("Подождите сейчас страница будет перезагружена");
                    _.delay(function()
                    {
                        window.location.reload();
                    }, 2000);
                })*/

                ajax.execute();
            },

            _onDomReady: function()
            {},

            _onWindowReady: function()
            {}
        });

    })(sx, sx.$, sx._);
JS
)
?>