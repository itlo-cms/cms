<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
/* @var $this yii\web\View */

echo $this->render('@itlo/cms/views/admin-user/_form', [
    'model' => $model,
    'relatedModel' => $relatedModel,
    'passwordChange' => $passwordChange,
]);
