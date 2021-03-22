<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms;

/**
 * @property string $icon;
 *
 * @author Semenov Alexander <semenov@itlo.com>
 */
interface IHasIcon
{
    /**
     * @return string
     */
    public function getIcon();

    /**
     * @param string $icon
     * @return mixed
     */
    public function setIcon($icon);
}