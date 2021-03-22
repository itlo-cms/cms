<?php
/**
 * FileSizeColumnData
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use yii\grid\DataColumn;

/**
 * Class FileSizeData
 * @package itlo\cms\grid
 */
class FileSizeColumnData extends DataColumn
{
    public $filter = false;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $size = $model->{$this->attribute};
        return \Yii::$app->formatter->asShortSize($size);
    }
}