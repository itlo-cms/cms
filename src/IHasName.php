<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms;

/**
 * @property string $name;
 *
 * @author Semenov Alexander <semenov@itlo.com>
 */
interface IHasName
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string|array $name
     * @return mixed
     */
    public function setName($name);
}