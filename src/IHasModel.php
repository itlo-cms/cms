<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms;

use yii\base\BaseObject;
use yii\base\Component;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * @property $model;
 *
 * Interface IHasModel
 * @package itlo\cms
 */
interface IHasModel
{
    /**
     * @return Model|ActiveRecord
     */
    public function getModel();

    /**
     * @param Model|ActiveRecord|Component|Object $model
     * @return mixed
     */
    public function setModel($model);

}