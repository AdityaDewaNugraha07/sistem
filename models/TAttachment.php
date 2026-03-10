<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_attachment".
 *
 * @property integer $attachment_id
 * @property string $file_name
 * @property string $file_ext
 * @property string $dir_path
 * @property integer $file_size
 * @property string $reff_no
 * @property integer $reff_no_detail
 * @property integer $seq
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $file,$file1,$file2,$file3,$file4,$file5,$file6,$file7,$file8,$file9,$file10,$file11,$file12,$file13,$file14,$file15,$lokasi_bongkar;
    public static function tableName()
    {
        return 't_attachment';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_name', 'file_type', 'file_ext', 'dir_path', 'file_size', 'reff_no', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['file_name', 'dir_path'], 'string'],
            [['file_size', 'reff_no_detail', 'seq', 'created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['file_type', 'file_ext', 'reff_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'attachment_id' => 'Attachment',
                'file_name' => 'File Name',
                'file_type' => 'File Type',
                'file_ext' => 'File Ext',
                'dir_path' => 'Dir Path',
                'file_size' => 'File Size',
                'reff_no' => 'Reff No',
                'reff_no_detail' => 'Reff No Detail',
                'seq' => 'Seq',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }
}
