<?php
/**
 * UserColumnData
 *
 * TODO: доработать фильтр
 *
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\grid;

use itlo\cms\helpers\UrlHelper;
use itlo\cms\models\User;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class UserColumnData
 * @package itlo\cms\grid
 */
class UserColumnData extends DataColumn
{
    public function init()
    {
        parent::init();

        /*$this->filter = ArrayHelper::map(
            \Yii::$app->cms->findUser()->all(),
            'id',
            'displayName'
        );*/

        if ($this->grid->filterModel && $this->attribute) {
            $this->filter = false;
            /*$this->filter = \itlo\cms\backend\widgets\SelectModelDialogUserWidget::widget([
                'model'             => $this->grid->filterModel,
                'attribute'         => $this->attribute,
            ]);*/
        }

    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        /*if (!isset($model->{$this->attribute})){
            return '';
        }*/

        $userId = (int)$model->{$this->attribute};
        $user = User::findOne($userId);

        if ($user) {
            if (!$srcImage = $user->getAvatarSrc()) {
                $srcImage = \Yii::$app->cms->noImageUrl;
            }

            $this->grid->view->registerCss(<<<CSS
.sx-user-preview
{

}
.sx-user-preview .sx-user-preview-controll
{
    display: none;
}

.sx-user-preview:hover .sx-user-preview-controll
{
    display: inline;
}
CSS
            );
            return "<div class='sx-user-preview'>" . Html::img($srcImage, [
                    'width' => 25,
                    'style' => 'margin-right: 5px;'
                ]) . $user->getDisplayName() . "
                <div class='sx-user-preview-controll'>" . Html::a("<i class='fa fa-edit' title='Редактировать'></i>",
                    UrlHelper::construct(['/cms/admin-user/update', 'pk' => $user->id])->enableAdmin()->toString(),
                    [
                        'class' => 'btn btn-xs btn-default',
                        'data-pjax' => 0
                    ]) . '</div></div>';
        } else {
            return null;
        }
    }


}