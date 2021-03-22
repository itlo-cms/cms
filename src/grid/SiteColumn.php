<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use itlo\cms\models\CmsSite;
use yii\grid\DataColumn;

/**
 * Class SiteColumn
 * @package itlo\cms\grid
 */
class SiteColumn extends DataColumn
{
    public $attribute = 'site_code';

    public function init()
    {
        parent::init();

        if (!$this->filter) {
            $this->filter = \yii\helpers\ArrayHelper::map(
                \itlo\cms\models\CmsSite::find()->all(),
                'code',
                'name'
            );
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($model->site && $model->site instanceof CmsSite) {
            $site = $model->site;
        } else {

        }

        if ($site) {
            return $site->name;
        }

        return null;
    }
}