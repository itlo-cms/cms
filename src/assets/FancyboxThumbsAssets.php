<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\assets;

/**
 * Class AppAsset
 * @package backend\assets
 */
class FancyboxThumbsAssets extends FancyboxAssets
{
    public $js = [
        'helpers/jquery.fancybox-thumbs.js',
    ];

    public $css = [
        'helpers/jquery.fancybox-thumbs.css',
    ];

    public $depends = [
        '\skeeks\cms\assets\FancyboxAssets',
    ];
}
