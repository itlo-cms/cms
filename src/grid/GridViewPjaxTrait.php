<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/**
 * Class GridViewSortableTrait
 * @package itlo\cms\modules\admin\traits
 */
trait GridViewPjaxTrait
{
    public $pjaxClassName = 'yii\widgets\Pjax';
    /**
     * @var bool влючить/выключить pjax навигацию
     */
    public $enabledPjax = true;
    /**
     * @var array
     */
    public $pjaxOptions = [];
    /**
     * @var Pjax для того чтобы потом можно было обратиться к объекту pjax.
     */
    public $pjax;

    protected $_pjaxCreated = false;

    public function pjaxBegin()
    {
        if ($this->enabledPjax) {
            if (!$this->pjax) {
                $this->_pjaxCreated = true;

                $pjaxClassName = $this->pjaxClassName;

                $this->pjax = $pjaxClassName::begin(ArrayHelper::merge([
                    'id' => 'sx-pjax-grid-' . $this->id,
                ], $this->pjaxOptions));
            }
        }
    }

    public function pjaxEnd()
    {
        if ($this->enabledPjax && $this->_pjaxCreated) {
            $pjaxClassName = $this->pjax->className();
            $pjaxClassName::end();
        }
    }
}
