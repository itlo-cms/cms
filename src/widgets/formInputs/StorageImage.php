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
 * @property CmsStorageFile $image
 * Class StorageImage
 * @package itlo\cms\widgets\formInputs
 */
class StorageImage extends InputWidget
{
    /**
     * @var array
     */
    public $clientOptions = [];

    public $viewItemTemplate = null;

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
                throw new Exception(\Yii::t('itlo/cms', "The image can be downloaded after you save the form data"));
            }

            echo $this->render('storage-image', [
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
     * @return null|StorageFile
     */
    public function getImage()
    {
        $imageId = $this->model->{$this->attribute};
        if (!$imageId) {
            return null;
        }

        return StorageFile::findOne($imageId);
    }

    public function getJsonString()
    {
        return Json::encode([
            'backendUrl' => UrlHelper::construct('cms/admin-storage-files/link-to-model')->enableAdmin()->toString(),
            'modelId' => $this->model->id,
            'modelClassName' => $this->model->className(),
            'modelAttribute' => $this->attribute,
        ]);
    }
}
