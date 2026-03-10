<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spm_log".
 *
 * @property integer $spm_log_id
 * @property string $reff_no
 * @property string $no_barcode
 * @property string $no_lap
 * @property string $no_grade
 * @property string $no_btg
 * @property string $no_produksi
 * @property integer $kayu_id
 * @property double $panjang
 * @property string $kode_potong
 * @property double $diameter_ujung1
 * @property double $diameter_ujung2
 * @property double $diameter_pangkal1
 * @property double $diameter_pangkal2
 * @property double $diameter_rata
 * @property double $cacat_panjang
 * @property double $cacat_gb
 * @property double $cacat_gr
 * @property double $volume
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * 
 */
class TSpmLog extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kayu_nama;
    public static function tableName()
    {
        return 't_spm_log';
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
            [['reff_no', 'no_barcode', 'no_lap', 'no_grade', 'no_btg', 'no_produksi', 'kayu_id'], 'required'],
            [['kayu_id', 'created_by', 'updated_by'], 'integer'],
            [['panjang', 'diameter_ujung1', 'diameter_ujung2', 'diameter_pangkal1', 'diameter_pangkal2', 'diameter_rata', 'cacat_panjang', 'cacat_gb', 'cacat_gr', 'volume'], 'number'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['reff_no'], 'string', 'max' => 25],
            [['no_barcode', 'no_produksi'], 'string', 'max' => 50],
            [['no_lap', 'no_grade', 'no_btg'], 'string', 'max' => 100],
            [['kode_potong'], 'string', 'max' => 4],
            [['reff_no'], 'exist', 'skipOnError' => true, 'targetClass' => TSpmKo::className(), 'targetAttribute' => ['reff_no' => 'kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spm_log_id' => 'Spm Log',
                'reff_no' => 'Reff No',
                'no_barcode' => 'No Barcode',
                'no_lap' => 'No Lap',
                'no_grade' => 'No Grade',
                'no_btg' => 'No Btg',
                'no_produksi' => 'No Produksi',
                'kayu_id' => 'Kayu',
                'panjang' => 'Panjang',
                'kode_potong' => 'Kode Potong',
                'diameter_ujung1' => 'Diameter Ujung1',
                'diameter_ujung2' => 'Diameter Ujung2',
                'diameter_pangkal1' => 'Diameter Pangkal1',
                'diameter_pangkal2' => 'Diameter Pangkal2',
                'diameter_rata' => 'Diameter Rata',
                'cacat_panjang' => 'Cacat Panjang',
                'cacat_gb' => 'Cacat Gb',
                'cacat_gr' => 'Cacat Gr',
                'volume' => 'Volume',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

}