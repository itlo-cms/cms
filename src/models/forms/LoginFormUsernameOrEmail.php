<?php
/**
 * Форма позволяющая авторизовываться использую логин или email
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models\forms;

use itlo\cms\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginFormUsernameOrEmail extends Model
{
    public $identifier;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['identifier', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],

            ['identifier', 'validateEmailIsApproved'],
        ];
    }



    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'identifier' => \Yii::t('itlo/cms', 'Username or Email'),
            'password' => \Yii::t('itlo/cms', 'Password'),
            'rememberMe' => \Yii::t('itlo/cms', 'Remember me'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmailIsApproved($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (\Yii::$app->cms->auth_only_email_is_approved && !$user->email_is_approved) {
                $this->addError($attribute, \Yii::t('itlo/cms', 'Вам необходимо подтвердить ваш email. Для этого перейдите по ссылке из письма.'));
            }
        }
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, \Yii::t('itlo/cms', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsernameOrEmail($this->identifier);
        }

        return $this->_user;
    }
}
