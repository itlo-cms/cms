<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\validators;

use yii\validators\Validator;
use Exception;

/**
 * Class LoginValidator
 * @package itlo\cms\validators
 */
class LoginValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $string = $model->{$attribute};

        if (!preg_match('/^[a-z]{1}[a-z0-9_]+$/', $string)) {
            $this->addError($model, $attribute, \Yii::t('itlo/cms',
                'Use only letters (lowercase) and numbers. Must begin with a letter. Example {sample}',
                ['sample' => 'demo1']));
            return false;
        }
    }
}