<?php
/**
 * CreatedAtColumn
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

/**
 * Class CreatedAtColumn
 * @package itlo\cms\grid
 */
class CreatedAtColumn extends DateTimeColumnData
{
    public $attribute = "created_at";
}