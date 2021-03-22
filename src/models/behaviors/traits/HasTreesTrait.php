<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models\behaviors\traits;

use itlo\cms\models\CmsContentElementTree;
use itlo\cms\models\CmsTree;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @method ActiveQuery getElementTrees()
 * @method ActiveQuery getCmsTrees()
 * @method int[] getTreeIds()
 *
 * @property CmsContentElementTree[] $elementTrees
 * @property int[] $treeIds
 * @property CmsTree[] $cmsTrees
 */
trait HasTreesTrait
{
}