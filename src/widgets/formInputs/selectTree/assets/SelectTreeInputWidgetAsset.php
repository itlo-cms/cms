<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\formInputs\selectTree\assets;

use yii\web\AssetBundle;

/**
 * Class SelectTreeInputWidgetAsset
 *
 * @package itlo\cms\widgets\formInputs\selectTree\assets
 */
class SelectTreeInputWidgetAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/formInputs/selectTree/assets/src';

    public $css = [
        'css/select-tree.css',
    ];

    public $js = [
        'js/select-tree.js',
    ];

    public $depends = [
        'itlo\sx\assets\Core',
    ];
}
