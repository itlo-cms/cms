<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models\behaviors\traits;

use itlo\cms\models\CmsContentElementTree;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @method string                               getAbsoluteUrl()
 * @method string                               getUrl()
 *
 * @property string absoluteUrl
 * @property string url
 */
trait HasUrlTrait
{
    /**
     * @return string
     */
    public function getAbsoluteUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return "";
    }
}