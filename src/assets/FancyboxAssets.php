<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace skeeks\cms\assets;

use itlo\cms\base\AssetBundle;

/**
 * Class AppAsset
 * @package backend\assets
 */
class FancyboxAssets extends AssetBundle
{
    public $sourcePath = '@bower/fancybox/dist';

    public $js = [
        'jquery.fancybox.js',
    ];

    public $css = [
        'jquery.fancybox.css',
    ];
}
