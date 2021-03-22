<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\base\widgets;

use itlo\cms\traits\ActiveFormAjaxSubmitTrait;

/**
*
<? $form = \itlo\cms\base\widgets\ActiveFormAjaxSubmit::begin([
    'clientCallback' => new \yii\web\JsExpression(<<<JS
    function (ActiveFormAjaxSubmit) {
        ActiveFormAjaxSubmit.on('success', function(e, response) {
            $("#sx-result").empty();

            if (response.data.html) {
                $("#sx-result").append(response.data.html);
            }
        });
    }
JS
)
]); ?>
 */
class ActiveFormAjaxSubmit extends ActiveForm
{
    use ActiveFormAjaxSubmitTrait;

    public $enableAjaxValidation = true;
    public $validateOnChange = false;
    public $validateOnBlur = false;
}
