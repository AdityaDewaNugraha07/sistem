<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tmp_analisa_spb".
 *
 * @property integer $nour
 * @property string $spb_kode
 * @property string $spb_tanggal
 * @property string $spb_pegawai_nama
 * @property string $spb_approval_status
 * @property string $spp_kode
 * @property string $spl_spo_attr
 * @property string $tpb_attr
 * @property integer $user_id
 * @property string $departement_nama
 * @property string $bpb_attr
 * @property string $json_approval
 * @property string $json_spp
 */
class TmpAnalisaSpb extends ActiveRecord
{
    public $tgl_awal, $tgl_akhir, $nama_departement;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tmp_analisa_spb';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nour', 'spb_kode', 'spb_tanggal', 'spb_pegawai_nama', 'user_id', 'departement_nama'], 'required'],
            [['nour', 'user_id'], 'integer'],
            [['spb_tanggal'], 'safe'],
            [['spl_spo_attr', 'tpb_attr', 'bpb_attr', 'json_approval', 'json_spp'], 'string'],
            [['spb_kode', 'spp_kode'], 'string', 'max' => 20],
            [['spb_pegawai_nama'], 'string', 'max' => 100],
            [['spb_approval_status'], 'string', 'max' => 15],
            [['departement_nama'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'nour' => 'Nour',
                'spb_kode' => 'Spb Kode',
                'spb_tanggal' => 'Spb Tanggal',
                'spb_pegawai_nama' => 'Spb Pegawai Nama',
                'spb_approval_status' => 'Spb Approval Status',
                'spp_kode' => 'Spp Kode',
                'spl_spo_attr' => 'Spl Spo Attr',
                'tpb_attr' => 'Tpb Attr',
                'user_id' => 'User',
                'departement_nama' => 'Departement Nama',
                'bpb_attr' => 'Bpb Attr',
                'json_approval' => 'Json Approval',
                'json_spp' => 'Json Spp',
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function searchLaporanDt()
    {
        $param['table'] = self::tableName();
        $param['pk'] = self::tableName() . '.nour';
        $param['column'] = self::getTableSchema()->columnNames;
        $param['where'] = [];
        if (!empty($this->tgl_awal) || !empty($this->tgl_akhir)) {
            $param['where'][] = "{$param['table']}.spb_tanggal BETWEEN '$this->tgl_awal' AND '$this->tgl_akhir'";
        }
        if (!empty($this->nama_departement)) {
            $param['where'][] = "{$param['table']}.departement_nama = '$this->nama_departement'";
        }
        $param['where'][] = "{$param['table']}.user_id = " . Yii::$app->user->id;

        return $param;
    }
}
