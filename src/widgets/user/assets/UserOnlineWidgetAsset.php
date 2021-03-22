<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\user\assets;

use itlo\cms\base\AssetBundle;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class UserOnlineWidgetAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/user/assets/src';

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'itlo\sx\assets\Custom',
    ];
}
