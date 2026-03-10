<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_dokumen_revisi".
 *
 * @property integer $dokumen_revisi_id
 * @property integer $dokumen_id
 * @property string $nama_dokumen
 * @property integer $revisi_ke
 * @property string $tanggal_berlaku
 * @property string $catatan_revisi
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TDokumenDistribusi[] $tDokumenDistribusis
 * @property MDokumen $dokumen
 */
class TDokumenRevisi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $nomor_dokumen, $jenis_dokumen, $kategori_dokumen, $perubahan_nama;
    public static function tableName()
    {
        return 't_dokumen_revisi';
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
            [['dokumen_id', 'revisi_ke', 'created_by', 'updated_by'], 'integer'],
            [['nama_dokumen'], 'required'],
            [['tanggal_berlaku', 'created_at', 'updated_at'], 'safe'],
            [['catatan_revisi'], 'string'],
            [['nama_dokumen'], 'string', 'max' => 255],
            [['dokumen_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDokumen::className(), 'targetAttribute' => ['dokumen_id' => 'dokumen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'dokumen_revisi_id' => 'Dokumen Revisi',
                'dokumen_id' => 'Dokumen',
                'nama_dokumen' => 'Nama Dokumen',
                'revisi_ke' => 'Revisi Ke',
                'tanggal_berlaku' => 'Tanggal Berlaku',
                'catatan_revisi' => 'Catatan Revisi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTDokumenDistribusis()
    {
        return $this->hasMany(TDokumenDistribusi::className(), ['dokumen_revisi_id' => 'dokumen_revisi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumen()
    {
        return $this->hasOne(MDokumen::className(), ['dokumen_id' => 'dokumen_id']);
    }

    public static function getOptionList($id){
        $query = "SELECT * FROM t_dokumen_revisi JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id
                  WHERE dokumen_revisi_id NOT IN (SELECT dokumen_revisi_id FROM t_dokumen_distribusi)";
        if($id){
            // $query .= "OR dokumen_revisi_id IN (SELECT dokumen_revisi_id FROM t_dokumen_distribusi WHERE dokumen_distribusi_id = $id)";
            $query .= "OR dokumen_revisi_id = $id";
        }
        $res = Yii::$app->db->createCommand($query)->queryAll();
		$ret = [];
		foreach($res as $data){
			$ret[$data['dokumen_revisi_id']] = $data['nomor_dokumen'] . ' - REVISI ' . $data['revisi_ke'];
		}
        return $ret;
    }

    public function searchLaporan(){
        $query = self::find();
        $query->select("dokumen_revisi_id, 
                        nomor_dokumen, 
                        jenis_dokumen, 
                        m_dokumen.nama_dokumen, 
                        revisi_ke, 
                        catatan_revisi, 
                        t_dokumen_revisi.nama_dokumen as perubahan_nama");
        $query->join('JOIN','m_dokumen', 'm_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id');
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            'nomor_dokumen, revisi_ke');
        if(!empty($this->kategori_dokumen)){
            $query->andWhere("m_dokumen.kategori_dokumen = '" .$this->kategori_dokumen."'");
        }
        if(!empty($this->nomor_dokumen)){
            $query->andWhere("m_dokumen.nomor_dokumen ilike'%" .$this->nomor_dokumen."%'");
        }
        if(!empty($this->jenis_dokumen)){
            $query->andWhere("m_dokumen.jenis_dokumen = '" .$this->jenis_dokumen."'");
        }
        return $query;
    }

    public function searchLaporanDt(){
        $searchLaporan = $this->searchLaporan();
        $param['table'] = self::tableName();
        $param['pk'] = $param['table'] . '.' . self::primaryKey()[0];
        if (!empty($searchLaporan->groupBy)) {
            $param['column'] = ['GROUP BY ' . implode(", ", $searchLaporan->groupBy)];
        }
        if (!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if (!empty($searchLaporan->orderBy)) {
            foreach ($searchLaporan->orderBy as $i_order => $order) {
                $param['order'][] = $i_order . " " . (($order == 3) ? "DESC" : "ASC");
            }
        }
        if (!empty($searchLaporan->join)) {
            foreach ($searchLaporan->join as $join) {
                $param['join'][] = $join[0] . ' ' . $join[1] . " ON " . $join[2];
            }
        }
        $param['where'] = [];
        if(!empty($this->kategori_dokumen)){
            array_push($param['where'], "m_dokumen.kategori_dokumen = '".$this->kategori_dokumen."'");
        }
        if(!empty($this->nomor_dokumen)){
            array_push($param['where'], "m_dokumen.nomor_dokumen ilike '%".$this->nomor_dokumen."%'");
        }
        if(!empty($this->jenis_dokumen)){
            array_push($param['where'], "m_dokumen.jenis_dokumen = '".$this->jenis_dokumen."'");
        }
        return $param;
    }
} 