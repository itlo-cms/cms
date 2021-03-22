<?php
/**
 * LongTextColumnData
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use itlo\cms\helpers\StringHelper;
use yii\grid\DataColumn;

/**
 * Class LongTextColumnData
 * @package itlo\cms\grid
 */
class LongTextColumnData extends DataColumn
{
    public $maxLength = 200;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $text = $model->{$this->attribute};
        return "<small>" . StringHelper::substr($text, 0,
                $this->maxLength) . ((StringHelper::strlen($text) > $this->maxLength) ? " ..." : "") . "</small>";

    }
}