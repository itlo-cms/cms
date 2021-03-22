<?php
/**
 * Breadcrumbs
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\components;

use itlo\cms\base\components\Descriptor;
use itlo\cms\models\Site;
use itlo\cms\models\Tree;
use itlo\cms\models\TreeType;
use yii\base\Component;

/**
 * Class Cms
 * @package itlo\cms\components
 */
class Breadcrumbs extends Component
{
    /**
     * @var array
     */
    public $parts = [];

    public function init()
    {
        parent::init();
    }

    /**
     * @param array $data
     * @return $this
     */
    public function append($data)
    {
        if (is_array($data)) {
            $this->parts[] = $data;
        } else {
            if (is_string($data)) {
                $this->parts[] = [
                    'name' => $data
                ];
            }
        }

        return $this;
    }

    /**
     * @param Tree $tree
     * @return $this
     */
    public function setPartsByTree(Tree $tree)
    {
        $parents = $tree->parents;
        $parents[] = $tree;

        foreach ($parents as $tree) {
            $this->append([
                'name' => $tree->name,
                'url' => $tree->url,
                'data' => [
                    'model' => $tree
                ],
            ]);
        }

        return $this;
    }

    public function createBase($baseData = [])
    {
        if (!$baseData) {
            $baseData = [
                'name' => \Yii::t('yii', 'Home'),
                'url' => '/'
            ];
        }

        $this->parts = [];

        $this->append($baseData);

        return $this;
    }

}