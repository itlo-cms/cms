<?
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */

$this->registerJs(<<<JS

$("body").on("dblclick", ".sx-tree-node", function() {
    $(".sx-first-action", $(this)).click();
    return false;
});

$("body").on("click", ".sx-first-action-trigger", function() {
    /*console.log($(".sx-first-action", $(this).closest('.sx-tree-node')));*/
    $(".sx-first-action", $(this).closest('.sx-tree-node')).first().click();
    return false;
});

JS
);

$this->registerCss(<<<CSS

.sx-tree ul li.sx-tree-node .row .sx-controll-node
{
    display: none;
    float: left;
}

.sx-tree ul li.sx-tree-node .row .sx-controll-node .sx-btn-caret-action
{
    width: 21px;
    height: 22px;
}
.sx-tree ul li.sx-tree-node .row .sx-controll-node .btn
{
    height: 22px;
}


.sx-tree ul li.sx-tree-node .row:hover .sx-controll-node
{
    display: block;
}

.btn-tree-node-controll
{
    font-size: 8px;
}

    .sx-tree ul li.sx-tree-node .sx-controll-node
    {
        width: auto;
        float: left;
        margin-left: 10px;
        padding-top: 0px;
    }

        .sx-tree ul li.sx-tree-node .sx-controll-node > .dropdown button
        {
            font-size: 6px;
            color: #000000;
            background: white;
            padding: 2px 4px;
        }

.sx-tree-move
{
    cursor: move;
}
CSS
);
?>
<div class="col-md-12">
    <?php $widget = \itlo\cms\widgets\tree\CmsTreeWidget::begin([
        "models" => $models,
        "viewNodeContentFile" => '@itlo/cms/views/admin-tree/_tree-node',

        'pjaxClass' => \itlo\cms\modules\admin\widgets\Pjax::class,
        /*'pjaxOptions' =>
            [
                'blockPjaxContainer' => false,
                'blockContainer' => '.sx-panel',
            ]*/
    ]); ?>

    <?
    \yii\jui\Sortable::widget();

    $options = \yii\helpers\Json::encode([
        'id' => $widget->id,
        'pjaxid' => $widget->pjax->id,
        'backendNewChild' => \itlo\cms\helpers\UrlHelper::construct(['/cms/admin-tree/new-children'])->enableAdmin()->toString(),
        'backendResort' => \itlo\cms\helpers\UrlHelper::construct(['/cms/admin-tree/resort'])->enableAdmin()->toString()
    ]);


    $this->registerJs(<<<JS
        (function(window, sx, $, _)
        {
            sx.createNamespace('classes.tree.admin', sx);

            sx.classes.tree.admin.CmsTreeWidget = sx.classes.Component.extend({

                _init: function()
                {
                    var self = this;
                },

                _onDomReady: function()
                {
                    var self = this;
                    /*$('.sx-tree-node').on('dblclick', function(event)
                    {
                        event.stopPropagation();
                        $(this).find(".sx-row-action:first").click();
                    });*/

                    $(".sx-tree ul").find("ul").sortable(
                    {
                        cursor: "move",
                        handle: ".sx-tree-move",
                        forceHelperSize: true,
                        forcePlaceholderSize: true,
                        opacity: 0.5,
                        placeholder: "ui-state-highlight",

                        out: function( event, ui )
                        {
                            var Jul = $(ui.item).closest("ul");
                            var newSort = [];
                            Jul.children("li").each(function(i, element)
                            {
                                newSort.push($(this).data("id"));
                            });

                            var blocker = sx.block(Jul);

                            var ajax = sx.ajax.preparePostQuery(
                                self.get('backendResort'),
                                {
                                    "ids" : newSort,
                                    "changeId" : $(ui.item).data("id")
                                }
                            );

                            //new sx.classes.AjaxHandlerNoLoader(ajax); //???????????????????? ?????????????????????? ????????????????????
                            new sx.classes.AjaxHandlerNotify(ajax, {
                                'error': "?????????????????? ???? ??????????????????????",
                                'success': "?????????????????? ??????????????????",
                            }); //???????????????????? ?????????????????????? ????????????????????

                            ajax.onError(function(e, data)
                            {
                                sx.notify.info("?????????????????? ???????????? ???????????????? ?????????? ??????????????????????????");
                                _.delay(function()
                                {
                                    window.location.reload();
                                }, 2000);
                            })
                            .onSuccess(function(e, data)
                            {
                                blocker.unblock();
                            })
                            .execute();
                        }
                    });

                    var self = this;

                    $('.add-tree-child').on('click', function()
                    {
                        var jNode = $(this);
                        sx.prompt("?????????????? ???????????????? ???????????? ??????????????", {
                            'yes' : function(e, result)
                            {
                                var blocker = sx.block(jNode);

                                var ajax = sx.ajax.preparePostQuery(
                                        self.get('backendNewChild'),
                                        {
                                            "pid" : jNode.data('id'),
                                            "Tree" : {"name" : result},
                                            "no_redirect": true
                                        }
                                );

                                //new sx.classes.AjaxHandlerNoLoader(ajax); //???????????????????? ?????????????????????? ????????????????????

                                new sx.classes.AjaxHandlerNotify(ajax, {
                                    'error': "???? ?????????????? ???????????????? ?????????? ????????????",
                                    'success': "?????????? ???????????? ????????????????"
                                }); //???????????????????? ?????????????????????? ????????????????????

                                ajax.onError(function(e, data)
                                {
                                    $.pjax.reload('#' + self.get('pjaxid'), {});
                                    /*sx.notify.info("?????????????????? ???????????? ???????????????? ?????????? ??????????????????????????");
                                    _.delay(function()
                                    {
                                        window.location.reload();
                                    }, 2000);*/
                                })
                                .onSuccess(function(e, data)
                                {
                                    blocker.unblock();

                                    $.pjax.reload('#' + self.get('pjaxid'), {});
                                    /*sx.notify.info("?????????????????? ???????????? ???????????????? ?????????? ??????????????????????????");
                                    _.delay(function()
                                    {
                                        window.location.reload();
                                    }, 2000);*/
                                })
                                .execute();
                            }
                        });

                        return false;
                    });

                    $('.show-at-site').on('click', function()
                    {
                        window.open($(this).attr('href'));

                        return false;
                    });
                },
            });

            new sx.classes.tree.admin.CmsTreeWidget({$options});

        })(window, sx, sx.$, sx._);
JS
    );
    ?>
    <?php $widget::end(); ?>

</div>
