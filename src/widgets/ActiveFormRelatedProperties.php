<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets;

use itlo\cms\modules\admin\traits\ActiveFormTrait;
use itlo\cms\modules\admin\traits\AdminActiveFormTrait;
use itlo\cms\modules\admin\widgets\ActiveForm;
use itlo\cms\traits\ActiveFormAjaxSubmitTrait;
use itlo\modules\cms\form\models\Form;
use yii\base\Model;

/**
 * Class ActiveFormRelatedProperties
 * @package itlo\cms\widgets
 */
class ActiveFormRelatedProperties extends ActiveForm
{
    use AdminActiveFormTrait;
    use ActiveFormAjaxSubmitTrait;

    /**
     * @var Model
     */
    public $modelHasRelatedProperties;

    public function __construct($config = [])
    {
        $this->validationUrl = \itlo\cms\helpers\UrlHelper::construct('cms/model-properties/validate')->toString();
        $this->action = \itlo\cms\helpers\UrlHelper::construct('cms/model-properties/submit')->toString();

        $this->enableAjaxValidation = true;

        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        echo \yii\helpers\Html::hiddenInput("sx-model-value", $this->modelHasRelatedProperties->id);
        echo \yii\helpers\Html::hiddenInput("sx-model", $this->modelHasRelatedProperties->className());
    }
}
