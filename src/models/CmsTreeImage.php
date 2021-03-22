<?php

namespace itlo\cms\models;

use Yii;

/**
 * This is the model class for table "{{%cms_tree_image}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $storage_file_id
 * @property integer $tree_id
 * @property integer $priority
 *
 * @property CmsTree $tree
 * @property CmsStorageFile $storageFile
 */
class CmsTreeImage extends \itlo\cms\models\Core
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_tree_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['created_by', 'updated_by', 'created_at', 'updated_at', 'storage_file_id', 'tree_id', 'priority'],
                'integer'
            ],
            [['storage_file_id', 'tree_id'], 'required'],
            [
                ['storage_file_id', 'tree_id'],
                'unique',
                'targetAttribute' => ['storage_file_id', 'tree_id'],
                'message' => \Yii::t('itlo/cms',
                    'The combination of Storage File ID and Tree ID has already been taken.')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('itlo/cms', 'ID'),
            'created_by' => Yii::t('itlo/cms', 'Created By'),
            'updated_by' => Yii::t('itlo/cms', 'Updated By'),
            'created_at' => Yii::t('itlo/cms', 'Created At'),
            'updated_at' => Yii::t('itlo/cms', 'Updated At'),
            'storage_file_id' => Yii::t('itlo/cms', 'Storage File ID'),
            'tree_id' => Yii::t('itlo/cms', 'Tree ID'),
            'priority' => Yii::t('itlo/cms', 'Priority'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTree()
    {
        return $this->hasOne(CmsTree::className(), ['id' => 'tree_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageFile()
    {
        return $this->hasOne(CmsStorageFile::className(), ['id' => 'storage_file_id']);
    }
}