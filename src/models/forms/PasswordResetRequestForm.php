<?php
/**
 * PasswordResetRequestForm
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class PasswordResetRequestForm
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $identityClassName = \Yii::$app->user->identityClass;
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'exist',
                'targetClass' => $identityClassName,
                'filter' => ['status' => $identityClassName::STATUS_ACTIVE],
                'message' => \Yii::t('itlo/cms', 'There is no user with such email.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {


                \Yii::$app->mailer->view->theme->pathMap = ArrayHelper::merge(\Yii::$app->mailer->view->theme->pathMap,
                    [
                        '@app/mail' =>
                            [
                                '@itlo/cms/mail-templates'
                            ]
                    ]);

                return \Yii::$app->mailer->compose('@app/mail/password-reset-token', ['user' => $user])
                    ->setFrom([\Yii::$app->cms->adminEmail => \Yii::$app->cms->appName . ' robot'])
                    ->setTo($this->email)
                    ->setSubject(\Yii::t('itlo/cms', 'Password reset for ') . \Yii::$app->cms->appName)
                    ->send();
            }
        }

        return false;
    }
}
