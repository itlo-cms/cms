<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\user;

use common\models\User;
use itlo\cms\widgets\user\assets\UserOnlineWidgetAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Виджет отображения индикатора онлайн пользователь или офлайн
 * @author Semenov Alexander <semenov@itlo.com>
 */
class UserOnlineWidget extends Widget
{
    /**
     * @var User
     */
    public $user = null;

    /**
     * @var null
     */
    public $options = null;

    public function run()
    {
        if (!$this->user) {
            return '';
        }
        $user = $this->user;

        if ($user->isOnline) {
            $options = ArrayHelper::merge($this->options, [
                'title' => \Yii::t('itlo/cms', 'Online'),
                'data-toggle' => 'tooltip',
            ]);

            $online = \yii\helpers\Html::img(UserOnlineWidgetAsset::getAssetUrl('icons/round_green.gif'), $options);
        } else {
            $options = ArrayHelper::merge($this->options, [
                'title' => \Yii::t('itlo/cms', 'Offline'),
                'data-toggle' => 'tooltip',
            ]);

            $online = \yii\helpers\Html::img(UserOnlineWidgetAsset::getAssetUrl('icons/round_red.gif'), $options);
        }

        return $online;
    }
}