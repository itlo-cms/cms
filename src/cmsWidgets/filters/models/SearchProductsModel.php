<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace skeeks\cms\cmsWidgets\filters\models;

use itlo\cms\base\Widget;
use itlo\cms\base\WidgetRenderable;
use itlo\cms\components\Cms;
use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\CmsContentElement;
use itlo\cms\models\CmsContentElementTree;
use itlo\cms\models\Search;
use itlo\cms\models\Tree;
use itlo\cms\shop\cmsWidgets\filters\ShopProductFiltersWidget;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class SearchProductsModel
 * @package itlo\cms\shop\cmsWidgets\filters\models
 */
class SearchProductsModel extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => \Yii::t('itlo/cms', 'With photo'),
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search(ActiveDataProvider $dataProvider)
    {
        $query = $dataProvider->query;

        if ($this->image == Cms::BOOL_Y) {
            $query->andWhere([
                'or',
                ['!=', 'cms_content_element.image_id', null],
                ['!=', 'cms_content_element.image_id', ""],
            ]);
        } else {
            if ($this->image == Cms::BOOL_N) {
                $query->andWhere([
                    'or',
                    ['cms_content_element.image_id' => null],
                    ['cms_content_element.image_id' => ""],
                ]);
            }
        }


        return $dataProvider;
    }
}