<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\helpers;

use yii\base\Behavior;
use yii\base\Component;

/**
 * Class ComponentHelper
 * @package itlo\cms\helpers
 */
class ComponentHelper extends Component
{
    /**
     * Проверка наличия у компонента необходимого поведения
     *
     * @param Component $component
     * @param $behavior
     * @return bool
     */
    public static function hasBehavior($component, $behavior)
    {
        if ($behavior instanceof Behavior) {
            $behavior = (string)$behavior->className();
        } else {
            if (is_string($behavior)) {
                $behavior = (string)$behavior;
            }
        }

        if (!method_exists($component, 'getBehaviors')) {
            return false;
        }

        $hasBehaviors = $component->getBehaviors();

        foreach ($hasBehaviors as $hasBehavior) {
            if ($hasBehavior instanceof $behavior) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * Проверка есть ли у компонента хоть одно повдеение
     *
     * @param Component $component
     * @param array $behaviors
     * @return bool
     */
    public static function hasBehaviorsOr(Component $component, $behaviors = [])
    {
        foreach ($behaviors as $behavior) {
            if (static::hasBehavior($component, $behavior)) {
                return true;
            }
        }

        return false;
    }
}