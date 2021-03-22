<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\assets;

use itlo\cms\base\AssetBundle;

/**
 * Class JsTaskManagerAsset
 * @package skeeks\cms\assets
 */
class JsTaskManagerAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/assets/src';

    public $css = [
    ];

    public $js = [
        'classes/tasks/Task.js',
        'classes/tasks/AjaxTask.js',
        'classes/tasks/ProgressBar.js',
        'classes/tasks/Manager.js',
    ];

    public $depends = [
        '\itlo\sx\assets\Custom',
    ];
}
