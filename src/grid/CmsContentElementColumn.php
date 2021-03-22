<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\CmsContentElement;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class CmsContentElementColumn
 * @package itlo\cms\grid
 */
class CmsContentElementColumn extends DataColumn
{
    public $filter = false;

    public $attribute = "element_id";

    public $relation = "element";

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        /**
         * @var $element CmsContentElement
         */
        if ($this->relation) {
            $element = $model->{$this->relation};
            if (!$element) {
                return null;
            } else {
                return Html::a($element->name . " [$element->id]", $element->url, [
                        'target' => '_blank',
                        'data-pjax' => 0,
                        'title' => 'Посмотреть на сайте (откроется в новом окне)',
                    ]) . " " .
                    Html::a('<span class="fa fa-edit"></span>',
                        UrlHelper::construct('/cms/admin-cms-content-element/update', [
                            'content_id' => $element->content_id,
                            'pk' => $element->id,
                        ]), [
                            'data-pjax' => 0,
                            'class' => 'btn btn-xs btn-default',
                        ]);
            }
        }

        return null;
    }
}