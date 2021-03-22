<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\assets;

use itlo\cms\base\AssetBundle;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class GridViewAsset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/assets/src/grid-view';

    public $css = [
        'grid.css',
        'table.css',
    ];

    public $js = [];

    public $depends = [
        'yii\web\YiiAsset',
        'itlo\sx\assets\Custom',
    ];
}