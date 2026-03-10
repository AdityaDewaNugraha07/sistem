<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_dokumen_distribusi".
 *
 * @property integer $dokumen_distribusi_id
 * @property integer $dokumen_revisi_id
 * @property integer $pic_iso_id
 * @property string $tanggal_dikirim
 * @property integer $dikirim_oleh
 * @property boolean $status_penerimaan
 * @property integer $diterima_oleh
 * @property string $catatan_penerimaan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $tanggal_penerimaan
 *
 * @property MPicIso $picIso
 * @property TDokumenRevisi $dokumenRevisi
 */
class TDokumenDistribusi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal, $tgl_akhir, $nomor_dokumen, $kategori_dokumen, $jenis_dokumen;
    public $nama_dokumen, $revisi_ke, $pic, $status_pengiriman, $pengirim;
    public static function tableName()
    {
        return 't_dokumen_distribusi';
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
            [['dokumen_revisi_id', 'pic_iso_id', 'dikirim_oleh', 'diterima_oleh', 'created_by', 'updated_by'], 'integer'],
            [['tanggal_dikirim', 'created_at', 'updated_at', 'tanggal_penerimaan'], 'safe'],
            [['dikirim_oleh'], 'required'],
            [['status_penerimaan'], 'boolean'],
            [['catatan_penerimaan'], 'string'],
            [['pic_iso_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPicIso::className(), 'targetAttribute' => ['pic_iso_id' => 'pic_iso_id']],
            [['dokumen_revisi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TDokumenRevisi::className(), 'targetAttribute' => ['dokumen_revisi_id' => 'dokumen_revisi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'dokumen_distribusi_id' => 'Dokumen Distribusi',
                'dokumen_revisi_id' => 'Dokumen Revisi',
                'pic_iso_id' => 'Pic Iso',
                'tanggal_dikirim' => 'Tanggal Dikirim',
                'dikirim_oleh' => 'Dikirim Oleh',
                'status_penerimaan' => 'Status Penerimaan',
                'diterima_oleh' => 'Diterima Oleh',
                'catatan_penerimaan' => 'Catatan Penerimaan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicIso()
    {
        return $this->hasOne(MPicIso::className(), ['pic_iso_id' => 'pic_iso_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenRevisi()
    {
        return $this->hasOne(TDokumenRevisi::className(), ['dokumen_revisi_id' => 'dokumen_revisi_id']);
    }

    public static function getOptionList(){
        $res = Yii::$app->db->createCommand("SELECT * FROM t_dokumen_revisi WHERE dokumen_revisi_id NOT IN (SELECT dokumen_revisi_id FROM t_dokumen_distribusi)")->queryAll();
		$ret = [];
		foreach($res as $data){
			$ret[$data['dokumen_revisi_id']] = $data['nama_dokumen'] . '-' . $data['revisi_ke'];
		}
        return $ret;
    }

    public function searchLaporan(){
        $query = self::find();
        $query->select("dokumen_distribusi_id, 
                        nomor_dokumen, 
                        t_dokumen_revisi.nama_dokumen, 
                        revisi_ke, 
                        tanggal_dikirim, 
                        b.pegawai_nama as pengirim, 
                        a.pegawai_nama as pic,
                        status_penerimaan,
                        tanggal_penerimaan");
        $query->join('JOIN','t_dokumen_revisi', 't_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id');
        $query->join('JOIN','m_dokumen', 'm_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id');
        $query->join('JOIN','m_pic_iso', 'm_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id');
        $query->join('LEFT JOIN','m_pegawai a', 'a.pegawai_id = m_pic_iso.pegawai_id');
        $query->join('LEFT JOIN','m_pegawai b', 'b.pegawai_id = t_dokumen_distribusi.dikirim_oleh');
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            'tanggal_dikirim, nomor_dokumen, revisi_ke');
        if(!empty($this->tgl_awal) && !empty($this->tgl_akhir)){
            $query->andWhere("DATE(tanggal_dikirim) BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."'");
        }
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
        if(!empty($this->tgl_awal) && !empty($this->tgl_akhir)){
            array_push($param['where'], "DATE(tanggal_dikirim) BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."'");
        }
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