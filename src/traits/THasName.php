<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\traits;

use yii\helpers\ArrayHelper;

/**
 * @property $name;
 *
 * @author Semenov Alexander <semenov@itlo.com>
 */
trait THasName
{
    /**
     * @var string
     */
    protected $_name = '';

    /**
     * @return string|bool
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string|array|bool $name
     * @return $this
     */
    public function setName($name)
    {
        if (is_array($name)) {
            $this->_name = \Yii::t(
                ArrayHelper::getValue($name, 0),
                ArrayHelper::getValue($name, 1, ''),
                ArrayHelper::getValue($name, 2, []),
                ArrayHelper::getValue($name, 3)
            );
        } else if (is_string($name)) {
            $this->_name = $name;
        } else if (is_bool($name)) {
            $this->_name = $name;
        } else if (is_null($name)) {
            $this->_name = false;
        }

        return $this;
    }
}