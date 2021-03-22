<?php
/**
 * Базовая модель содержит поведения пользователей, кто когда обновил, и создал сущьность
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models;

/**
 * @method string getTableCacheTag()
 *
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User    $createdBy
 * @property User    $updatedBy
 *
 * @deprecated
 */
abstract class Core extends \itlo\cms\base\ActiveRecord
{

}