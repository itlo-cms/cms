<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\base;

use itlo\cms\IHasImage;
use itlo\cms\IHasName;
use itlo\cms\traits\THasImage;
use itlo\cms\traits\THasName;

class ComponentDescriptor extends \yii\base\Component implements IHasName, IHasImage
{
    use THasName;
    use THasImage;

    public $description = "";
    public $keywords = [];

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->name;
    }

}