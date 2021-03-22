<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */

namespace itlo\cms\models;

use Yii;

/**
 * This is the model class for table "{{%cms_content_element_tree}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $element_id
 * @property integer $tree_id
 *
 * @property CmsContentElement $element
 * @property CmsTree $tree
 */
class CmsContentElementTree extends Core
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_content_element_tree}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'id' => Yii::t('itlo/cms', 'ID'),
            'created_by' => Yii::t('itlo/cms', 'Created By'),
            'updated_by' => Yii::t('itlo/cms', 'Updated By'),
            'created_at' => Yii::t('itlo/cms', 'Created At'),
            'updated_at' => Yii::t('itlo/cms', 'Updated At'),
            'element_id' => Yii::t('itlo/cms', 'Element ID'),
            'tree_id' => Yii::t('itlo/cms', 'Tree ID'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'element_id', 'tree_id'], 'integer'],
            [['element_id', 'tree_id'], 'required'],
            [
                ['element_id', 'tree_id'],
                'unique',
                'targetAttribute' => ['element_id', 'tree_id'],
                'message' => \Yii::t('itlo/cms', 'The combination of Element ID and Tree ID has already been taken.')
            ]
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(CmsContentElement::className(), ['id' => 'element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTree()
    {
        return $this->hasOne(Tree::className(), ['id' => 'tree_id']);
    }
}