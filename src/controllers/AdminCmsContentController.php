<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\controllers;

use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\CmsContent;
use itlo\cms\modules\admin\controllers\AdminModelEditorController;

/**
 * Class AdminCmsContentTypeController
 * @package itlo\cms\controllers
 */
class AdminCmsContentController extends AdminModelEditorController
{
    public function init()
    {
        $this->name = \Yii::t('itlo/cms', 'Content management');
        $this->modelShowAttribute = "name";
        $this->modelClassName = CmsContent::class;

        parent::init();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $contentTypePk = null;

        if ($this->model) {
            if ($contentType = $this->model->contentType) {
                $contentTypePk = $contentType->id;
            }
        }

        return UrlHelper::construct([
            "cms/admin-cms-content-type/update",
            'pk' => $contentTypePk
        ])->enableAdmin()->toString();
    }
}
