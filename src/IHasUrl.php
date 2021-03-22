<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms;

/**
 * @property $url;
 *
 * Interface IHasUrl
 * @package itlo\cms
 */
interface IHasUrl
{
    /**
     * @return string
     */
    public function getUrl();
}