<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms;

/**
 * @property $permissionNames;
 * @property $permissionName;
 * @property bool $isAllow;
 *
 * Interface IHasPermissions
 * @package itlo\cms
 */
interface IHasPermissions
{
    /**
     * @return string
     */
    public function getPermissionName();

    /**
     * @return array
     */
    public function getPermissionNames();

    /**
     * @return bool
     */
    public function getIsAllow();
}