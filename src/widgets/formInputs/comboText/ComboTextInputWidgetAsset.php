<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\formInputs\comboText;

use Yii;
use yii\web\AssetBundle;

/**
 * Class ComboTextInputWidgetAsset
 * @package itlo\cms\widgets\formInputs\comboText
 */
class ComboTextInputWidgetAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/formInputs/comboText/assets';

    public $css = [];

    public $js =
        [
            'combo-widget.js',
        ];

    public $depends = [
        '\itlo\sx\assets\Core',
    ];
}

