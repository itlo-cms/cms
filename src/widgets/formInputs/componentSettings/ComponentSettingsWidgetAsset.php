<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\formInputs\componentSettings;

use Yii;
use yii\web\AssetBundle;

/**
 * Class ComponentSettingsWidgetAsset
 * @package itlo\cms\widgets\formInputs\componentSettings
 */
class ComponentSettingsWidgetAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/formInputs/componentSettings/assets';

    public $css = [];

    public $js =
        [
            'component-settings.js',
        ];

    public $depends = [
        '\itlo\sx\assets\Core',
    ];
}

