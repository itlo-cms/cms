<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\formInputs\ckeditor;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package backend\assets
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@itlo/cms/widgets/formInputs/ckeditor/assets';
    public $css = [];
    public $js = [
        'imageselect.png'
    ];
    public $depends = [];
}
