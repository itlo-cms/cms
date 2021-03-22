<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms;

/**
 * @deprecated
 *
 * @author Semenov Alexander <semenov@itlo.com>
 */
interface IHasInfo
{
    /**
     * Name
     * @return string
     */
    public function getName();

    /**
     * Icon name
     * @return array
     */
    public function getIcon();

    /**
     * Image url
     * @return string
     */
    public function getImage();
}