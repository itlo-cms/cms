<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\relatedProperties\propertyTypes;

use itlo\cms\relatedProperties\PropertyType;

/**
 * Class PropertyTypeFile
 * @package itlo\cms\relatedProperties\propertyTypes
 */
class PropertyTypeFile extends PropertyType
{
    public $code = self::CODE_FILE;

    public function init()
    {
        parent::init();

        if (!$this->name) {
            $this->name = \Yii::t('itlo/cms', 'File');
        }
    }
}