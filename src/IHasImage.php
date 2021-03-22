<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms;

/**
 * @property string $image;
 *
 * @author Semenov Alexander <semenov@itlo.com>
 */
interface IHasImage
{
    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     * @return mixed
     */
    public function setImage($image);
}