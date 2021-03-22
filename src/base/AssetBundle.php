<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright 2021 ITLO (Infomarket)
 */

namespace itlo\cms\base;

class AssetBundle extends \yii\web\AssetBundle
{
    /**
     * @param string $asset
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAssetUrl($asset)
    {
        return \Yii::$app->assetManager->getAssetUrl(\Yii::$app->assetManager->getBundle(static::className()), $asset);
    }
}