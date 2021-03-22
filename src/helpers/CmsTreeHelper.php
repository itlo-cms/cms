<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\helpers;

use itlo\cms\models\CmsContentElement;
use itlo\cms\models\Tree;
use yii\base\Component;
use yii\helpers\ArrayHelper;


/**
 * Class CmsTreeHelper
 * @package itlo\cms\helpers
 */
abstract class CmsTreeHelper extends Component
{
    /**
     * @var array
     */
    static public $instances = [];

    /**
     * @var CmsContentElement
     */
    public $model;

    /**
     * @param Tree $model
     * @param $data
     */
    public function __construct($model, $data = [])
    {
        $data['model'] = $model;
        static::$instances[$model->id] = $this;

        parent::__construct($data);


    }

    /**
     * @param Tree $model
     * @param array $data
     * @return static
     */
    public static function instance($model, $data = [])
    {
        if ($package = ArrayHelper::getValue(static::$instances, $model->id)) {
            return $package;
        }

        return new static($model, $data);
    }
}