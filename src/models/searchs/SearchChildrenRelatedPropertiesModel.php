<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models\searchs;

use itlo\cms\models\CmsContent;
use itlo\cms\models\CmsContentElement;
use itlo\cms\models\CmsContentElementProperty;
use itlo\cms\models\CmsContentProperty;
use itlo\cms\relatedProperties\models\RelatedPropertyModel;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;

/**
 * Class SearchRelatedPropertiesModel
 * @package itlo\cms\models\searchs
 */
class SearchChildrenRelatedPropertiesModel extends SearchRelatedPropertiesModel
{
    /**
     * @param ActiveDataProvider $activeDataProvider
     */
    public function search(ActiveDataProvider $activeDataProvider, $tableName = 'cms_content_element')
    {
        $classSearch = $this->propertyElementClassName;

        /**
         * @var $activeQuery ActiveQuery
         */
        $activeQuery = $activeDataProvider->query;
        $elementIdsGlobal = [];
        $applyFilters = false;

        foreach ($this->toArray() as $propertyCode => $value) {
            //TODO: add to validator related properties
            if ($propertyCode == 'properties') {
                continue;
            }

            if ($property = $this->getProperty($propertyCode)) {
                if ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_NUMBER) {
                    $elementIds = [];

                    $query = $classSearch::find()->select(['element_id'])->where([
                        "property_id" => $property->id
                    ])->indexBy('element_id');

                    if ($fromValue = $this->{$this->getAttributeNameRangeFrom($propertyCode)}) {
                        $applyFilters = true;

                        $query->andWhere(['>=', 'value_num', (float)$fromValue]);
                    }

                    if ($toValue = $this->{$this->getAttributeNameRangeTo($propertyCode)}) {

                        $applyFilters = true;

                        $query->andWhere(['<=', 'value_num', (float)$toValue]);
                    }

                    if (!$fromValue && !$toValue) {
                        continue;
                    }

                    $elementIds = $query->all();

                } else {
                    if (!$value) {
                        continue;
                    }

                    $applyFilters = true;

                    $elementIds = $classSearch::find()->select(['element_id'])->where([
                        "value" => $value,
                        "property_id" => $property->id
                    ])->indexBy('element_id')->all();
                }

                $elementIds = array_keys($elementIds);

                if ($elementIds) {
                    $realElements = CmsContentElement::find()->where(['id' => $elementIds])->select([
                        'id',
                        'parent_content_element_id'
                    ])->indexBy('parent_content_element_id')->groupBy(['parent_content_element_id'])->asArray()->all();
                    $elementIds = array_keys($realElements);
                }

                if (!$elementIds) {
                    $elementIdsGlobal = [];
                }

                if ($elementIdsGlobal) {
                    $elementIdsGlobal = array_intersect($elementIds, $elementIdsGlobal);
                } else {
                    $elementIdsGlobal = $elementIds;
                }
            }
        }


        if ($applyFilters) {
            //$activeQuery->andWhere(['cms_content_element.id' => $elementIdsGlobal]);
            $activeQuery->andWhere([$tableName . '.id' => $elementIdsGlobal]);
        }

    }
}