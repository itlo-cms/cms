<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets;

use itlo\cms\base\widgets\ActiveFormAjaxSubmit;
use itlo\modules\cms\form\models\Form;
use itlo\widget\chosen\Chosen;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveForm
 * @package itlo\modules\cms\form\widgets
 */
class ActiveFormModelPropertyValues extends ActiveFormAjaxSubmit
{
    /**
     * @var Model
     */
    public $modelWithProperties;

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

        echo \yii\helpers\Html::hiddenInput("sx-model-value", $this->modelWithProperties->id);
        echo \yii\helpers\Html::hiddenInput("sx-model", $this->modelWithProperties->className());
    }


    /**
     *
     * TODO: Вынести в трейт, используется для админки
     * Стилизованный селект админки
     *
     * @param $model
     * @param $attribute
     * @param $items
     * @param array $config
     * @param array $fieldOptions
     * @return \itlo\cms\base\widgets\ActiveField
     */
    public function fieldSelect($model, $attribute, $items, $config = [], $fieldOptions = [])
    {
        $config = ArrayHelper::merge(
            ['allowDeselect' => false],
            $config,
            [
                'items' => $items,
            ]
        );

        foreach ($config as $key => $value) {
            if (property_exists(Chosen::className(), $key) === false) {
                unset($config[$key]);
            }
        }

        return $this->field($model, $attribute, $fieldOptions)->widget(
            Chosen::className(),
            $config
        );
    }
}
