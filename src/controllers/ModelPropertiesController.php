<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\models\forms\PasswordChangeForm;
use itlo\cms\models\User;
use itlo\cms\relatedProperties\models\RelatedElementModel;
use Yii;
use itlo\cms\models\searchs\User as UserSearch;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ModelPropertiesController
 * @package itlo\cms\controllers
 */
class ModelPropertiesController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'validate' => ['post'],
                    'submit' => ['post'],
                ],
            ],
        ]);
    }


    /**
     * Процесс отправки формы
     * @return array
     */
    public function actionSubmit()
    {
        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $response = [
                'success' => false,
                'message' => 'Произошла ошибка',
            ];

            if (\Yii::$app->request->post('sx-model') && \Yii::$app->request->post('sx-model-value')) {
                $modelClass = \Yii::$app->request->post('sx-model');
                $modelValue = \Yii::$app->request->post('sx-model-value');
                /**
                 * @var RelatedElementModel $modelForm
                 */
                $modelForm = $modelClass::find()->where(['id' => $modelValue])->one();

                if (method_exists($modelForm, "createPropertiesValidateModel")) {
                    $validateModel = $modelForm->createPropertiesValidateModel();
                } else {
                    $validateModel = $modelForm->getRelatedPropertiesModel();
                }

                if ($validateModel->load(\Yii::$app->request->post()) && $validateModel->validate()) {
                    $validateModel->save();
                    $response['success'] = true;
                    $response['message'] = 'Успешно отправлена';

                } else {
                    $response['message'] = 'Форма заполнена неправильно';
                }

                return $response;
            }
        }
    }

    /**
     * Валидация данных с формы
     * @return array
     */
    public function actionValidate()
    {
        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            if (\Yii::$app->request->post('sx-model') && \Yii::$app->request->post('sx-model-value')) {
                $modelClass = \Yii::$app->request->post('sx-model');
                $modelValue = \Yii::$app->request->post('sx-model-value');

                /**
                 * @var $modelForm Form
                 */
                $modelForm = $modelClass::find()->where(['id' => $modelValue])->one();

                if (method_exists($modelForm, "createPropertiesValidateModel")) {
                    $model = $modelForm->createPropertiesValidateModel();
                } else {
                    $model = $modelForm->getRelatedPropertiesModel();
                }

                $model->load(\Yii::$app->request->post());

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
    }
}