<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\models\CmsContentType;
use itlo\cms\modules\admin\controllers\AdminModelEditorController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class AdminCmsContentTypeController
 * @package itlo\cms\controllers
 */
class AdminCmsContentTypeController extends AdminModelEditorController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', 'Content management');
        $this->modelShowAttribute = "name";
        $this->modelClassName = CmsContentType::class;

        parent::init();
    }
}
