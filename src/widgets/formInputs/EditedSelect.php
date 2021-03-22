<?php
/**
 * Селект в который можно добавлять записи
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\formInputs;

use itlo\cms\Exception;
use itlo\cms\models\Publication;
use itlo\cms\modules\admin\widgets\Pjax;
use itlo\widget\chosen\Chosen;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

/**
 * @deprecated
 * Class EditedSelect
 * @package itlo\cms\widgets\formInputs
 */
class EditedSelect extends Chosen
{
    protected $_pjaxId = '';

    /**
     * @var string
     */
    public $createAction = 'create';
    public $updateAction = 'update';

    public $controllerRoute = '';

    public $additionalData = [];

    public function init()
    {
        $this->_pjaxId = 'pjax-' . $this->id;

        Pjax::begin([
            'id' => $this->_pjaxId
        ]);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {

        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        if ($this->hasModel()) {
            echo Html::activeListBox($this->model, $this->attribute, $this->items, $this->options);
        } else {
            echo Html::listBox($this->name, $this->value, $this->items, $this->options);
        }
        echo "</div>";

        echo "<div class='col-md-6'>";


        $createUrl = (string)\itlo\cms\backend\helpers\BackendUrlHelper::createByParams([$this->controllerRoute . '/' . $this->createAction])
            ->merge($this->additionalData)
            ->enableEmptyLayout()
            ->enableNoActions()
            ->url;

        $updateUrl = (string)\itlo\cms\backend\helpers\BackendUrlHelper::createByParams([$this->controllerRoute . '/' . $this->updateAction])
            ->merge($this->additionalData)
            ->enableEmptyLayout()
            ->url;


        $create_w = \Yii::t('itlo/cms', 'Create');
        $edit_w = \Yii::t('itlo/cms', 'Edit');
        echo <<<HTML
            <a href="{$createUrl}" class="btn btn-default sx-btn-create sx-btn-controll" ><span class="fa fa-plus"></span> {$create_w}</a>
            <a href="{$updateUrl}" class="btn btn-default sx-btn-update sx-btn-controll" ><span class="fa fa-edit"></span> {$edit_w}</a>
HTML;


        echo "</div>";


        echo "</div>";


        Pjax::end();

        $options = [
            'multiple' => (int)$this->multiple,
        ];

        $optionsString = Json::encode($options);

        $this->view->registerJs(<<<JS
        (function(sx, $, _)
        {
            sx.classes.FormElementEditedSelect = sx.classes.Widget.extend({

                _init: function()
                {},

                getWrapper: function()
                {
                    return $(this._wrapper);
                },

                _onDomReady: function()
                {
                    var self = this;

                    $(this.getWrapper()).on('change', 'select', function()
                    {
                        self.updateButtons();
                    });

                    $(this.getWrapper()).on('click', '.sx-btn-create', function()
                    {
                        var windowWidget = new sx.classes.Window($(this).attr('href'));

                        windowWidget.bind('close', function(e, data)
                        {
                            self.reload();
                        });

                        windowWidget.open();

                        return false;
                    });

                    $(this.getWrapper()).on('click', '.sx-btn-update', function()
                    {
                        var windowWidget = new sx.classes.Window($(this).attr('href') + '&pk=' + $('select', self.getWrapper()).val());

                        windowWidget.bind('close', function(e, data)
                        {
                            self.reload();
                        });

                        windowWidget.open();

                        return false;
                    });

                    self.updateButtons();
                },

                _onWindowReady: function()
                {},


                updateButtons: function()
                {
                    var self = this;

                    if (!self.get('multiple'))
                    {
                        if ($('select', this.getWrapper()).val())
                        {
                            self.showUpdateControll();
                        } else
                        {
                            self.hideUpdateControll();
                        }
                    } else
                    {
                        self.hideUpdateControll();
                    }

                    return this;

                },

                /**
                *
                * @returns {sx.classes.FormElementEditedSelect}
                */
                hideUpdateControll: function()
                {
                    $('.sx-btn-update', this.getWrapper()).hide();
                    return this;
                },

                /**
                *
                * @returns {sx.classes.FormElementEditedSelect}
                */
                showUpdateControll: function()
                {
                    $('.sx-btn-update', this.getWrapper()).show();
                    return this;
                },

                reload: function()
                {
                    var self = this;

                    $.pjax.reload("#" + this.getWrapper().attr('id'), {});

                    _.delay(function()
                    {
                        self.updateButtons();
                    }, 500);
                }
            });

            new sx.classes.FormElementEditedSelect('#{$this->_pjaxId}', {$optionsString});
        })(sx, sx.$, sx._);
JS
        );


    }

}
