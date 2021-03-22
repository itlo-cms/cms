<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\assets;

use itlo\cms\base\AssetBundle;

/**
 * Class DualSelectAsset
 * @package itlo\cms\assets
 */
class DualSelectAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/assets/src/dual-select';

    public $css = [
        'dual-select.css'
    ];

    public $js = [
        'dual-select.js',
    ];

    public $depends = [
        'itlo\sx\assets\Custom',
    ];
}
