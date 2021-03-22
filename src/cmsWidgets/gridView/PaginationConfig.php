<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\cmsWidgets\gridView;

use itlo\cms\base\Component;
use yii\data\DataProviderInterface;

/**
 * @property string                $modelClassName; название класса модели с которой идет работа
 * @property DataProviderInterface $dataProvider; готовый датапровайдер с учетом настроек виджета
 * @property array                 $resultColumns; готовый конфиг для построения колонок
 *
 * Class ShopProductFiltersWidget
 * @package itlo\cms\cmsWidgets\filters
 */
class PaginationConfig extends Component
{
    /**
     * @var string name of the parameter storing the current page index.
     * @see params
     */
    public $pageParam = 'page';
    /**
     * @var string name of the parameter storing the page size.
     * @see params
     */
    public $pageSizeParam = 'per-page';

    /**
     * @var int the default page size. This property will be returned by [[pageSize]] when page size
     * cannot be determined by [[pageSizeParam]] from [[params]].
     */
    public $defaultPageSize = 20;
    /**
     * @var array|false the page size limits. The first array element stands for the minimal page size, and the second
     * the maximal page size. If this is false, it means [[pageSize]] should always return the value of [[defaultPageSize]].
     */
    public $pageSizeLimitMin = 1;

    /**
     * @var int
     */
    public $pageSizeLimitMax = 50;


    public function rules()
    {
        return [
            [['pageParam', 'pageSizeParam', 'defaultPageSize'], 'required'],
            [['pageParam', 'pageSizeParam'], 'string'],
            ['defaultPageSize', 'integer'],
            ['pageSizeLimitMin', 'integer'],
            ['pageSizeLimitMax', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'pageParam'        => \Yii::t('itlo/cms', 'Parameter name pages, pagination'),
            'defaultPageSize'  => \Yii::t('itlo/cms', 'Number of records on one page'),
            'pageSizeLimitMin' => \Yii::t('itlo/cms', 'The minimum allowable value for pagination'),
            'pageSizeLimitMax' => \Yii::t('itlo/cms', 'The maximum allowable value for pagination'),
            'pageSizeParam' => \Yii::t('itlo/cms', 'pageSizeParam'),
        ];
    }

    /**
     * @return array
     */
    public function getConfigFormFields()
    {
        return [
            'defaultPageSize'  => [
                'elementOptions' => [
                    'type' => 'number',
                ],
            ],
            'pageSizeLimitMin' => [
                'elementOptions' => [
                    'type' => 'number',
                ],
            ],
            'pageSizeLimitMax' => [
                'elementOptions' => [
                    'type' => 'number',
                ],
            ],
            'pageParam',
            'pageSizeParam',
        ];
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @return $this
     */
    public function initDataProvider(DataProviderInterface $dataProvider)
    {

        $dataProvider->getPagination()->defaultPageSize = $this->defaultPageSize;
        $dataProvider->getPagination()->pageParam = $this->pageParam;
        $dataProvider->getPagination()->pageSizeParam = $this->pageSizeParam;
        $dataProvider->getPagination()->pageSizeLimit = [
            (int)$this->pageSizeLimitMin,
            (int)$this->pageSizeLimitMax,
        ];

        return $this;
    }
}