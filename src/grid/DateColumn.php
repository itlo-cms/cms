<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use yii\grid\DataColumn;

/**
 * @author Semenov Alexander <semenov@itlo.com>
 */
class DateColumn extends DataColumn
{
    public $filter = false;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $timestamp = $model->{$this->attribute};
        return \Yii::$app->formatter->asDate($timestamp);
    }
}