<?php
/**
 * StorageEvent
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\components\storage;

use yii\base\Event;

/**
 * Class StorageEvent
 * @package itlo\cms\components\storage
 */
class StorageEvent extends Event
{
    /**
     * @var boolean if message was sent successfully.
     */
    public $isSuccessful;
    /**
     * @var boolean whether to continue sending an email. Event handlers of
     * [[\yii\mail\BaseMailer::EVENT_BEFORE_SEND]] may set this property to decide whether
     * to continue send or not.
     */
    public $isValid = true;
}
