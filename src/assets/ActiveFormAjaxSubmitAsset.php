<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\assets;

use itlo\cms\base\AssetBundle;


class ActiveFormAjaxSubmitAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/assets/src';

    public $css = [
    ];

    public $js = [
        'classes/active-form/AjaxSubmit.js',
    ];

    public $depends = [
        '\itlo\sx\assets\Custom',
    ];
}
