<?php
/**
 * Thumbnail
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\components\imaging\filters;

use yii\base\Component;
use itlo\imagine\Image;
use Imagine\Image\ManipulatorInterface;
use yii\base\Exception;

/**
 * Class Thumbnail
 * @package itlo\cms\components\imaging\filters
 */
class Thumbnail extends \itlo\cms\components\imaging\Filter
{
    public $w = 50;
    public $h = 50;
    public $m = ManipulatorInterface::THUMBNAIL_OUTBOUND;

    public function init()
    {
        parent::init();

        if (!$this->w && !$this->h) {
            throw new Exception("Необходимо указать ширину или высоту");
        }

    }

    protected function _save()
    {
        if (!$this->w) {
            $size = Image::getImagine()->open($this->_originalRootFilePath)->getSize();
            $width = ($size->getWidth() * $this->h) / $size->getHeight();
            Image::thumbnail($this->_originalRootFilePath, (int)round($width), $this->h,
                $this->m)->save($this->_newRootFilePath);

        } else {
            if (!$this->h) {
                $size = Image::getImagine()->open($this->_originalRootFilePath)->getSize();
                $height = ($size->getHeight() * $this->w) / $size->getWidth();
                Image::thumbnail($this->_originalRootFilePath, $this->w, (int)round($height),
                    $this->m)->save($this->_newRootFilePath);
            } else {
                Image::thumbnail($this->_originalRootFilePath, $this->w, $this->h,
                    $this->m)->save($this->_newRootFilePath);
            }
        }
    }
}