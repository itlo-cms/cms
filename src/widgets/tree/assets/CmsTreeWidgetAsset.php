<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\tree\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package itlo\cms\modules\admin
 */
class CmsTreeWidgetAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/tree/assets/src';

    public $css = [
        'css/style.css',
    ];
    public $js = [
    ];
    public $depends = [
        'itlo\sx\assets\Core',
    ];
}
