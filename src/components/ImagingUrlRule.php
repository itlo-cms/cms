<?php
/**
 * ImagingUrlRule
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\components;

use itlo\cms\helpers\StringHelper;
use itlo\sx\File;

/**
 * Class Storage
 * @package itlo\cms\components
 */
class ImagingUrlRule
    extends \yii\web\UrlRule
{
    /**
     *
     * Добавлять слэш на конце или нет
     *
     * @var bool
     */
    public $useLastDelimetr = true;

    public function init()
    {
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }

    /**
     * @param \yii\web\UrlManager $manager
     * @param string $route
     * @param array $params
     * @return bool|string
     */
    public function createUrl($manager, $route, $params)
    {
        return false;
    }

    /**
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     * @return array|bool
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $params = $request->getQueryParams();

        $sourceOriginalFile = File::object($pathInfo);
        $extension = $sourceOriginalFile->getExtension();

        if (!$extension) {
            return false;
        }

        if (!in_array(StringHelper::strtolower($extension), (array)\Yii::$app->imaging->extensions)) {
            return false;
        }
        
        return ['cms/imaging/process', $params];
    }
}
