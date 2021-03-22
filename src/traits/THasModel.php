<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\traits;

use yii\base\Model;

/**
 * @property Model $model;
 *
 * Class THasModel
 * @package itlo\cms\traits
 */
trait THasModel
{
    /**
     * @var string
     */
    protected $_model = '';

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

}