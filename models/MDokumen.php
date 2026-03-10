<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_dokumen".
 *
 * @property integer $dokumen_id
 * @property string $nomor_dokumen
 * @property string $jenis_dokumen
 * @property string $kategori_dokumen
 * @property string $nama_dokumen
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TDokumenRevisi[] $tDokumenRevisis
 */
class MDokumen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kode1, $kode2, $kode3, $kode4;
    public $tanggal_berlaku, $revisi_ke;
    public static function tableName()
    {
        return 'm_dokumen';
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
            [['nomor_dokumen', 'jenis_dokumen', 'kategori_dokumen', 'nama_dokumen'], 'required'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['nomor_dokumen'], 'string', 'max' => 50],
            [['jenis_dokumen'], 'string', 'max' => 100],
            [['kategori_dokumen'], 'string', 'max' => 15],
            [['nama_dokumen'], 'string', 'max' => 255],
            [['nomor_dokumen'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'dokumen_id' => 'Dokumen',
                'nomor_dokumen' => 'Nomor Dokumen',
                'jenis_dokumen' => 'Jenis Dokumen',
                'kategori_dokumen' => 'Kategori Dokumen',
                'nama_dokumen' => 'Nama Dokumen',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTDokumenRevisis()
    {
        return $this->hasMany(TDokumenRevisi::className(), ['dokumen_id' => 'dokumen_id']);
    }

    public static function getOptionListJenis()
    {
        $res = Yii::$app->db->createCommand("SELECT kategori_dokumen FROM m_dokumen GROUP BY kategori_dokumen ORDER BY 1")->queryAll();
		$ret = [];
		foreach($res as $data){
			$ret[$data['kategori_dokumen']] = $data['kategori_dokumen'];
		}
        return $ret;
    }

    public static function getOptionList(){
        $res = self::find()->where(['active'=>true])->orderBy('dokumen_id ASC')->all();
		$ret = [];
		foreach($res as $data){
			$ret[$data['dokumen_id']] = $data['nomor_dokumen'];
		}
        return $ret;
    }

    public function searchLaporan(){
        $query = self::find();
        $query->select("dokumen_id, 
                        nomor_dokumen,
                        nama_dokumen,  
                        jenis_dokumen,
                        kategori_dokumen");
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            'dokumen_id ASC');
        $query->andWhere("active IS true");
        if (!empty($this->kategori_dokumen)) {
            $query->andWhere("kategori_dokumen = '" . $this->kategori_dokumen . "' ");
        }
        if (!empty($this->jenis_dokumen)) {
            $query->andWhere("jenis_dokumen = '" . $this->jenis_dokumen . "' ");
        }
        return $query;
    }

    public function searchLaporanDt(){
        $searchLaporan = $this->searchLaporan();
        $param['table'] = self::tableName();
        $param['pk'] = $param['table'] . '.' . self::primaryKey()[0];
        if (!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
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
        $param['where'] = ["active IS true"];
        if (!empty($this->kategori_dokumen)) {
            array_push($param['where'], "kategori_dokumen = '" . $this->kategori_dokumen . "'");
        }
        if (!empty($this->jenis_dokumen)) {
            array_push($param['where'], "jenis_dokumen = '" . $this->jenis_dokumen . "'");
        }
        return $param;
    }

    public function searchLaporanInduk(){
        $query = self::find();
        $pegawai = Yii::$app->user->identity->pegawai_id;
        $modPic = \app\models\MPicIso::findOne(['pegawai_id'=>$pegawai]);
        $query->select("m_dokumen.dokumen_id, 
                        nomor_dokumen, 
                        t_dokumen_revisi.nama_dokumen, 
                        tanggal_berlaku, 
                        t_dokumen_revisi.revisi_ke");
        $query->join("JOIN", "t_dokumen_revisi", "t_dokumen_revisi.dokumen_id = m_dokumen.dokumen_id");
        $query->join("JOIN", "t_dokumen_distribusi", "t_dokumen_distribusi.dokumen_revisi_id = t_dokumen_revisi.dokumen_revisi_id");
        $query->join("JOIN", "(select dokumen_id, max(revisi_ke) as revisi_ke from t_dokumen_revisi 
	                            join t_dokumen_distribusi ON t_dokumen_distribusi.dokumen_revisi_id = t_dokumen_revisi.dokumen_revisi_id 
                                where status_penerimaan IS true AND t_dokumen_distribusi.pic_iso_id = {$modPic->pic_iso_id}
                                group by dokumen_id) as a", 
                                "a.dokumen_id = t_dokumen_revisi.dokumen_id AND a.revisi_ke = t_dokumen_revisi.revisi_ke");
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            'nomor_dokumen ASC');
        $query->andWhere("m_dokumen.active IS true AND status_penerimaan IS true AND t_dokumen_distribusi.pic_iso_id = {$modPic->pic_iso_id}");
        return $query;
    }

    public function searchLaporanIndukDt(){
        $searchLaporan = $this->searchLaporanInduk();
        $param['table'] = self::tableName();
        $param['pk'] = $param['table'] . '.' . self::primaryKey()[0];
        if (!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
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
        $pegawai = Yii::$app->user->identity->pegawai_id;
        $modPic = \app\models\MPicIso::findOne(['pegawai_id'=>$pegawai]);
        $param['where'] = ["m_dokumen.active IS true AND status_penerimaan IS true AND t_dokumen_distribusi.pic_iso_id = {$modPic->pic_iso_id}"];
        return $param;
    }

    public function searchLaporanIndukAll(){
        $query = self::find();
        $query->select("m_dokumen.dokumen_id, 
                        nomor_dokumen, 
                        t_dokumen_revisi.nama_dokumen, 
                        tanggal_berlaku, 
                        t_dokumen_revisi.revisi_ke");
        $query->join("JOIN", "t_dokumen_revisi", "t_dokumen_revisi.dokumen_id = m_dokumen.dokumen_id");
        $query->join("JOIN", "t_dokumen_distribusi", "t_dokumen_distribusi.dokumen_revisi_id = t_dokumen_revisi.dokumen_revisi_id");
        $query->join("JOIN", "(select dokumen_id, max(revisi_ke) as revisi_ke from t_dokumen_revisi 
	                            join t_dokumen_distribusi ON t_dokumen_distribusi.dokumen_revisi_id = t_dokumen_revisi.dokumen_revisi_id 
                                group by dokumen_id) as a", 
                                "a.dokumen_id = t_dokumen_revisi.dokumen_id AND a.revisi_ke = t_dokumen_revisi.revisi_ke");
        $query->groupBy("m_dokumen.dokumen_id, t_dokumen_revisi.nama_dokumen, t_dokumen_revisi.tanggal_berlaku, t_dokumen_revisi.revisi_ke");
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            'nomor_dokumen ASC');
        $query->andWhere("m_dokumen.active IS true");
        return $query;
    }

    public function searchLaporanIndukAllDt(){
        $searchLaporan = $this->searchLaporanIndukAll();
        $param['table'] = self::tableName();
        $param['pk'] = $param['table'] . '.' . self::primaryKey()[0];
        if (!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
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
        $param['where'] = ["m_dokumen.active IS true"];
        return $param;
    }
} 