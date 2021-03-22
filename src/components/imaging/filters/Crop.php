<?php
/**
 * Filter
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\components\imaging\filters;

use yii\base\Component;
use itlo\imagine\Image;

/**
 * Class Filter
 * @package itlo\cms\components\imaging
 */
class Crop extends \itlo\cms\components\imaging\Filter
{
    public $w = 0;
    public $h = 0;
    public $s = [0, 0];

    protected function _save()
    {
        Image::crop($this->_originalRootFilePath, $this->w, $this->h, $this->s)->save($this->_newRootFilePath);
        Image::thumbnail($this->_originalRootFilePath, $this->w, $this->h, $this->s)->save($this->_newRootFilePath);
    }
}