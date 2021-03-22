<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets;

/**
 * Class Pjax
 *
 * @package itlo\cms\widgets
 */
class Pjax extends \yii\widgets\Pjax
{
    /**
     * Block container Pjax
     * @var bool
     */
    public $isBlock = true;

    /**
     * @var bool
     */
    public $isShowError = false;

    /**
     * Block other container
     * @var string
     */
    public $blockContainer = '';

    /**
     * @var int
     */
    public $timeout = 30000;


    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        parent::registerClientScript();

        $errorMessage = \Yii::t('itlo/admin', 'An unexpected error occurred. Refer to the developers.');

        $errorNotify = '';
        if ($this->isShowError) {
            $errorNotify = "sx.notify.error('{$errorMessage}');";
        }

        if ($this->isBlock === true) {
            $this->getView()->registerJs(<<<JS
                (function(sx, $, _)
                {
                    var blockerPanel = new sx.classes.Blocker('#{$this->id}');

                    $(document).on('pjax:send', function(e)
                    {
                        if ('{$this->id}' == e.target.id) {
                            blockerPanel = new sx.classes.Blocker($(e.target));
                            blockerPanel.block();
                        }
                        
                    });

                    $(document).on('pjax:complete', function(e) {
                        if ('{$this->id}' == e.target.id) {
                            blockerPanel.unblock();
                        }
                    });

                    $(document).on('pjax:error', function(e, data) {
                        if ('{$this->id}' == e.target.id) {
                            {$errorNotify}
                            blockerPanel.unblock();
                            e.preventDefault();
                        }
                    });

                })(sx, sx.$, sx._);
JS
            );
        }

        if ($this->blockContainer) {
            $this->getView()->registerJs(<<<JS
                (function(sx, $, _)
                {
                    var blockerPanel = new sx.classes.Blocker($("{$this->blockContainer}"));

                    $(document).on('pjax:send', function(e)
                    {
                        if ('{$this->id}' == e.target.id) {
                            var blockerPanel = new sx.classes.Blocker($("{$this->blockContainer}"));
                            blockerPanel.block();
                        }
                        
                    });

                    $(document).on('pjax:complete', function(e) {
                        if ('{$this->id}' == e.target.id) {
                            blockerPanel.unblock();
                        }  
                        
                    });

                    $(document).on('pjax:error', function(e, data) {
                        if ('{$this->id}' == e.target.id) {
                            {$errorNotify}
                            blockerPanel.unblock();
                            e.preventDefault();
                        }
                        
                    });

                })(sx, sx.$, sx._);
JS
            );
        }

    }
}