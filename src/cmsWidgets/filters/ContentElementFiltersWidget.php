<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\cmsWidgets\filters;

use itlo\cms\base\Widget;
use itlo\cms\base\WidgetRenderable;
use itlo\cms\cmsWidgets\filters\models\SearchProductsModel;
use itlo\cms\components\Cms;
use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\CmsContent;
use itlo\cms\models\CmsContentElement;
use itlo\cms\models\CmsContentElementTree;
use itlo\cms\models\Search;
use itlo\cms\models\Tree;
use itlo\cms\models\searchs\SearchRelatedPropertiesModel;
use itlo\cms\shop\models\ShopTypePrice;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * @property CmsContent $cmsContent;
 *
 * Class ShopProductFiltersWidget
 * @package itlo\cms\cmsWidgets\filters
 */
class ContentElementFiltersWidget extends WidgetRenderable
{
    //Навигация
    public $content_id;
    public $searchModelAttributes = [];

    public $realatedProperties = [];

    /**
     * @var bool Учитывать только доступные фильтры для текущей выборки
     */
    public $onlyExistsFilters = false;
    /**
     * @var array (Массив ids записей, для показа только нужных фильтров)
     */
    public $elementIds = [];


    /**
     * @var SearchProductsModel
     */
    public $searchModel = null;

    /**
     * @var SearchRelatedPropertiesModel
     */
    public $searchRelatedPropertiesModel = null;

    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => 'Фильтры',
        ]);
    }

    public function init()
    {
        parent::init();

        if (!$this->searchModelAttributes) {
            $this->searchModelAttributes = [];
        }

        if (!$this->searchModel) {
            $this->searchModel = new \itlo\cms\cmsWidgets\filters\models\SearchProductsModel();
        }

        if (!$this->searchRelatedPropertiesModel && $this->cmsContent) {
            $this->searchRelatedPropertiesModel = new SearchRelatedPropertiesModel();
            $this->searchRelatedPropertiesModel->initCmsContent($this->cmsContent);
        }

        $this->searchModel->load(\Yii::$app->request->get());

        if ($this->searchRelatedPropertiesModel) {
            $this->searchRelatedPropertiesModel->load(\Yii::$app->request->get());
        }
    }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                'content_id' => \Yii::t('itlo/cms', 'Content'),
                'searchModelAttributes' => \Yii::t('itlo/cms', 'Fields'),
                'realatedProperties' => \Yii::t('itlo/cms', 'Properties'),
            ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [['content_id'], 'integer'],
                [['searchModelAttributes'], 'safe'],
                [['realatedProperties'], 'safe'],
            ]);
    }

    public function renderConfigForm(ActiveForm $form)
    {
        echo \Yii::$app->view->renderFile(__DIR__ . '/_form.php', [
            'form' => $form,
            'model' => $this
        ], $this);
    }

    /**
     * @return CmsContent
     */
    public function getCmsContent()
    {
        return CmsContent::findOne($this->content_id);
    }

    /**
     * @param ActiveDataProvider $activeDataProvider
     */
    public function search(ActiveDataProvider $activeDataProvider)
    {
        if ($this->onlyExistsFilters) {
            /**
             * @var $query \yii\db\ActiveQuery
             */
            $query = clone $activeDataProvider->query;

            $query->with = [];
            $query->select(['cms_content_element.id as mainId', 'cms_content_element.id as id'])->indexBy('mainId');
            $ids = $query->asArray()->all();

            $this->elementIds = array_keys($ids);
        }

        $this->searchModel->search($activeDataProvider);

        if ($this->searchRelatedPropertiesModel) {
            $this->searchRelatedPropertiesModel->search($activeDataProvider);
        }
    }


    /**
     *
     * Получение доступных опций для свойства
     * @param CmsContentProperty $property
     * @return $this|array|\yii\db\ActiveRecord[]
     */
    public function getRelatedPropertyOptions($property)
    {
        $options = [];

        if ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_ELEMENT) {
            $propertyType = $property->handler;

            if ($this->elementIds) {
                $availables = \itlo\cms\models\CmsContentElementProperty::find()
                    ->select(['value_enum'])
                    ->indexBy('value_enum')
                    ->andWhere(['element_id' => $this->elementIds])
                    ->andWhere(['property_id' => $property->id])
                    ->asArray()
                    ->all();

                $availables = array_keys($availables);
            }

            $options = \itlo\cms\models\CmsContentElement::find()
                ->active()
                ->andWhere(['content_id' => $propertyType->content_id]);
            if ($this->elementIds) {
                $options->andWhere(['id' => $availables]);
            }

            $options = $options->select(['id', 'name'])->asArray()->all();

            $options = \yii\helpers\ArrayHelper::map(
                $options, 'id', 'name'
            );

        } elseif ($property->property_type == \itlo\cms\relatedProperties\PropertyType::CODE_LIST) {
            $options = $property->getEnums()->select(['id', 'value']);

            if ($this->elementIds) {
                $availables = \itlo\cms\models\CmsContentElementProperty::find()
                    ->select(['value_enum'])
                    ->indexBy('value_enum')
                    ->andWhere(['element_id' => $this->elementIds])
                    ->andWhere(['property_id' => $property->id])
                    ->asArray()
                    ->all();

                $availables = array_keys($availables);
                $options->andWhere(['id' => $availables]);
            }

            $options = $options->asArray()->all();

            $options = \yii\helpers\ArrayHelper::map(
                $options, 'id', 'value'
            );
        }

        return $options;
    }
}