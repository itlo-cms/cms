<?php
/**
 * The pseudo-only IDE tips
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010-2014 SkeekS (Sx)
 * @date 12.11.2014
 * @since 1.0.0
 */

namespace yii\web;

use itlo\cms\_ide\UserIde;
use itlo\cms\components\Breadcrumbs;
use itlo\cms\components\Cms;
use itlo\cms\components\CmsToolbar;
use itlo\cms\components\ConsoleComponent;
use itlo\cms\components\CurrentSite;
use itlo\cms\components\Imaging;
use itlo\cms\components\storage\Storage;
use itlo\cms\i18n\I18N;
use itlo\cms\models\CmsSite;

/**
 * @property Storage $storage
 * @property Cms $cms
 * @property Imaging $imaging
 * @property Breadcrumbs $breadcrumbs
 * @property CmsSite $currentSite
 * @property ConsoleComponent $console
 * @property I18N $i18n
 *
 * @property \yii\web\User|UserIde $user
 *
 * Class Application
 * @package yii\web
 */
class Application
{
}