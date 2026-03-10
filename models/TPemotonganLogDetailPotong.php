<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pemotongan_log_detail_potong".
 *
 * @property integer $pemotongan_log_detail_potong_id
 * @property integer $pemotongan_log_detail_id
 * @property string $no_barcode_lama
 * @property string $no_barcode_baru
 * @property double $panjang_baru
 * @property double $diameter_ujung1_baru
 * @property double $diameter_ujung2_baru
 * @property double $diameter_pangkal1_baru
 * @property double $diameter_pangkal2_baru
 * @property double $cacat_pjg_baru
 * @property double $cacat_gb_baru
 * @property double $cacat_gr_baru
 * @property string $reduksi_baru
 * @property double $volume_baru
 * @property string $alokasi
 * @property string $grading_rule
 * @property string $no_lap_baru
 * @property string $kode_pemotongan
 * @property boolean $status_penerimaan
 * @property string $tanggal_penerimaan
 * @property integer $penerima
 * @property string $catatan_penerimaan
 */
class TPemotonganLogDetailPotong extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kayu_id, $tgl_awal, $tgl_akhir;
    public static function tableName()
    {
        return 't_pemotongan_log_detail_potong';
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
            [['pemotongan_log_detail_id', 'no_barcode_lama', 'no_barcode_baru', 'alokasi'], 'required'],
            [['pemotongan_log_detail_id', 'penerima'], 'integer'],
            [['panjang_baru', 'diameter_ujung1_baru', 'diameter_ujung2_baru', 'diameter_pangkal1_baru', 'diameter_pangkal2_baru', 'cacat_pjg_baru', 'cacat_gb_baru', 'cacat_gr_baru', 'volume_baru'], 'number'],
            [['no_barcode_lama', 'no_barcode_baru', 'reduksi_baru', 'alokasi', 'no_lap_baru'], 'string', 'max' => 50],
            [['grading_rule'], 'string', 'max' => 20],
            [['status_penerimaan'], 'boolean'],
            [['tanggal_penerimaan'], 'safe'],
            [['catatan_penerimaan'], 'string'],
            [['kode_pemotongan'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pemotongan_log_detail_potong_id' => 'Pemotongan Log Detail Potong',
                'pemotongan_log_detail_id' => 'Pemotongan Log Detail',
                'no_barcode_lama' => 'No Barcode Lama',
                'no_barcode_baru' => 'No Barcode Baru',
                'panjang_baru' => 'Panjang Baru',
                'diameter_ujung1_baru' => 'Diameter Ujung1 Baru',
                'diameter_ujung2_baru' => 'Diameter Ujung2 Baru',
                'diameter_pangkal1_baru' => 'Diameter Pangkal1 Baru',
                'diameter_pangkal2_baru' => 'Diameter Pangkal2 Baru',
                'cacat_pjg_baru' => 'Cacat Pjg Baru',
                'cacat_gb_baru' => 'Cacat Gb Baru',
                'cacat_gr_baru' => 'Cacat Gr Baru',
                'reduksi_baru' => 'Reduksi Baru',
                'volume_baru' => 'Volume Baru',
                'alokasi' => 'Alokasi',
                'grading_rule' => 'Grade',
                'status_penerimaan' => 'Status Penerimaan',
                'tanggal_penerimaan' => 'Tanggal Penerimaan',
                'penerima' => 'Penerima',
                'catatan_penerimaan' => 'Catatan Penerimaan',
        ];
    }

    public static function getOptionListPanjang(){
        $res = Yii::$app->db->createCommand("select panjang_baru from t_pemotongan_log_detail_potong group by panjang_baru order by panjang_baru")->queryAll();
        return yii\helpers\ArrayHelper::map($res, 'panjang_baru', 'panjang_baru');
    }
} 