<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\traits;

use yii\helpers\Url;

/**
 * @property string $url;
 * @property string|array $urlData;
 *
 * Class THasUrl
 * @package itlo\cms\traits
 */
trait THasUrl
{
    /**
     * @var string|array
     */
    protected $_url = null;

    /**
     * @return string
     */
    public function getUrl()
    {
        if (is_array($this->_url)) {
            return Url::to($this->_url);
        }

        return (string)$this->_url;
    }

    /**
     * @param string|array $url
     * @return $this
     */
    public function setUrl($url)
    {
        if (!is_array($url)) {
            \Yii::warning('bad url ' . $url, 'adminUrl');
        }
        $this->_url = $url;
        return $this;
    }

    /**
     * @return array|string|null
     */
    public function getUrlData()
    {
        return $this->_url;
    }
}