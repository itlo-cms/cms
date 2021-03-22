<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\base;

use itlo\cms\traits\TWidget;
use yii\base\ViewContextInterface;
use yii\helpers\ArrayHelper;

/**
 * Class Widget
 * @package itlo\cms\base
 */
abstract class Widget extends Component implements ViewContextInterface
{
    //Умеет все что умеет \yii\base\Widget
    use TWidget;

    /**
     * @var array
     */
    public $contextData = [];

    /**
     * @param string $namespace Unique code, which is attached to the settings in the database
     * @param array  $config Standard widget settings
     *
     * @return static
     */
    public static function beginWidget($namespace, $config = [])
    {
        $config = ArrayHelper::merge(['namespace' => $namespace], $config);
        return static::begin($config);
    }
}