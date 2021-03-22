<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\widgets\formInputs;

use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\CmsStorageFile;
use itlo\cms\models\StorageFile;
use yii\base\Exception;
use yii\bootstrap\Alert;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 *
 *
 *
 * <?= $form->field($model, 'images')->widget(
 * \itlo\cms\widgets\formInputs\ModelStorageFiles::className(),
 * [
 * 'backendUrl' => \yii\helpers\Url::to(['/cms/storage-files/link-to-models']),
 * 'viewItemTemplate' => '',
 * 'controllWidgetOptions' => [
 * 'backendSimpleUploadUrl' => \yii\helpers\Url::to(['/cms/storage-files/upload']),
 * 'backendRemoteUploadUrl' => \yii\helpers\Url::to(['/cms/storage-files/remote-upload']),
 * ],
 * ]
 * ); ?>
 *
 *
 * @property StorageFile[] $files
 * Class StorageImages
 * @package itlo\cms\widgets\formInputs
 */
class ModelStorageFiles extends InputWidget
{
    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var null
     */
    public $viewItemTemplate = null;

    /**
     * @var string url to communicate with the model pictures
     * cms/storage-files/link-to-models
     * cms/admin-storage-files/link-to-models
     */
    public $backendUrl = null;

    /**
     * @var array
     */
    public $controllWidgetOptions = [];

    public function init()
    {
        parent::init();

        if ($this->backendUrl === null) {
            $this->backendUrl = UrlHelper::construct('cms/admin-storage-files/link-to-models')->enableAdmin()->toString();
        }
    }

    /**
     * @param $cmsStorageFile
     * @return string
     */
    public function renderItem($cmsStorageFile)
    {
        return $this->render($this->viewItemTemplate, [
            'model' => $cmsStorageFile
        ]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        try {
            if (!$this->hasModel()) {
                throw new Exception(\Yii::t('itlo/cms', "Current widget works only in form with model"));
            }

            if ($this->model->isNewRecord) {
                throw new Exception(\Yii::t('itlo/cms', "Images can be downloaded after you save the form data"));
            }


            if (!$this->model->hasProperty($this->attribute)) {
                throw new Exception("Relation {$this->attribute} не найдена");
            }

            echo $this->render('model-storage-files', [
                'model' => $this->model,
                'widget' => $this,
            ]);

        } catch (\Exception $e) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-warning',
                ],
                'body' => $e->getMessage()
            ]);
        }
    }

    /**
     * @return null|StorageFile[]
     */
    public function getFiles()
    {
        return $this->model->{$this->attribute};
    }

    public function getJsonString()
    {
        return Json::encode([
            'backendUrl' => $this->backendUrl,
            'modelId' => $this->model->id,
            'modelClassName' => $this->model->className(),
            'modelRelation' => $this->attribute,
        ]);
    }
}
