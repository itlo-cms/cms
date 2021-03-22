<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\traits;

use itlo\cms\base\ComponentDescriptor;

/**
 *
 * @property ComponentDescriptor descriptor
 *
 * Class HasComponentDescriptorTrait
 * @package itlo\cms\traits
 */
trait HasComponentDescriptorTrait
{

    /**
     * @var ComponentDescriptor
     */
    protected $_descriptor = null;
    /**
     * @var string
     */
    static public $descriptorClassName = 'itlo\cms\base\ComponentDescriptor';

    /**
     * @return array
     */
    public static function descriptorConfig()
    {
        return [
            "name"        => "itlo CMS",
            "description" => "",
            "keywords"    => "itlo, cms",
        ];
    }

    /**
     * @return ComponentDescriptor
     */
    public function getDescriptor()
    {
        if ($this->_descriptor === null) {
            $classDescriptor = static::$descriptorClassName;
            if (class_exists($classDescriptor)) {
                $this->_descriptor = new $classDescriptor(static::descriptorConfig());
            } else {
                $this->_descriptor = new ComponentDescriptor(static::descriptorConfig());
            }
        }

        return $this->_descriptor;
    }
}