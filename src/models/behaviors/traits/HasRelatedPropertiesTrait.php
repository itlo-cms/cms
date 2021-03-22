<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models\behaviors\traits;

use itlo\cms\relatedProperties\models\RelatedElementPropertyModel;
use itlo\cms\relatedProperties\models\RelatedPropertiesModel;
use itlo\cms\relatedProperties\models\RelatedPropertyModel;
use itlo\cms\relatedProperties\PropertyType;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @method ActiveQuery getRelatedElementProperties()
 * @method ActiveQuery getRelatedProperties()
 * @method RelatedPropertiesModel getRelatedPropertiesModel()
 *
 * @property RelatedElementPropertyModel[] relatedElementProperties
 * @property RelatedPropertyModel[] relatedProperties
 * @property RelatedPropertiesModel relatedPropertiesModel
 */
trait HasRelatedPropertiesTrait
{
    /**
     *
     * @param ActiveQuery $activeQuery
     * @param RelatedPropertyModel|null $relatedPropertyModel
     * @param $value
     * @return null
     */
    public static function filterByProperty(
        ActiveQuery $activeQuery,
        RelatedPropertyModel $relatedPropertyModel = null,
        $value
    ) {
        if (!$relatedPropertyModel) {
            return null;
        }

        $activeQuery->joinWith('relatedElementProperties map');

        if (in_array($relatedPropertyModel->property_type, [PropertyType::CODE_STRING])) {
            $activeQuery
                ->andWhere(['map.property_id' => $relatedPropertyModel->id])
                ->andWhere(['map.value_string' => $value]);
        } else {
            if (in_array($relatedPropertyModel->property_type, [
                PropertyType::CODE_ELEMENT
                ,
                PropertyType::CODE_TREE
                ,
                PropertyType::CODE_LIST
                ,
                PropertyType::CODE_NUMBER
            ])) {
                $activeQuery
                    ->andWhere(['map.property_id' => $relatedPropertyModel->id])
                    ->andWhere(['map.value_enum' => $value]);
            } else {
                //TODO: медленно
                $activeQuery
                    ->andWhere(['map.property_id' => $relatedPropertyModel->id])
                    ->andWhere(['map.value' => $value]);
            }
        }
    }
}