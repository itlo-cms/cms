<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\cmsWidgets\breadcrumbs;

use itlo\cms\base\WidgetRenderable;

/**
 * Class breadcrumbs
 * @package skeeks\cms\cmsWidgets\Breadcrumbs
 */
class BreadcrumbsCmsWidget extends WidgetRenderable
{
    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/cms', 'Breadcrumbs')
        ]);
    }
}