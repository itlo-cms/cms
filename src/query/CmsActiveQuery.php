<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\query;

use itlo\cms\components\Cms;
use yii\db\ActiveQuery;

/**
 * Class CmsActiveQuery
 * @package itlo\cms\query
 */
class CmsActiveQuery extends ActiveQuery
{
    public $is_active = true;

    /**
     * @param bool $state
     * @return $this
     */
    public function active($state = true)
    {
        if ($this->is_active === true) {
            return $this->andWhere(['is_active' => $state]);
        }

        return $this->andWhere(['active' => ($state == true ? Cms::BOOL_Y : Cms::BOOL_N)]);
    }

    public function def($state = true)
    {
        return $this->andWhere(['def' => ($state == true ? Cms::BOOL_Y : Cms::BOOL_N)]);
    }
}
