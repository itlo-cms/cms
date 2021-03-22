<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\base;

use yii\widgets\ActiveForm;

/**
 *
 * Класс который умеет стоить форму для себя
 *
 * Interface ConfigFormInterface
 * @package yii\base
 */
interface ConfigFormInterface
{
    /**
     * @deprecated
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function renderConfigForm(ActiveForm $form);
}
