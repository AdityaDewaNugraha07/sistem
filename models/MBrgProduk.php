<?php

namespace app\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "m_brg_produk".
 *
 * @property integer $produk_id
 * @property string $produk_kode
 * @property string $produk_group
 * @property string $produk_nama
 * @property string $produk_dimensi
 * @property double $produk_p
 * @property string $produk_p_satuan
 * @property double $produk_l
 * @property string $produk_l_satuan
 * @property double $produk_t
 * @property string $produk_t_satuan
 * @property string $produk_satuan_besar
 * @property string $produk_satuan_kecil
 * @property double $produk_qty_satuan_kecil
 * @property string $produk_gbr
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $produk_harga_distributor
 * @property integer $produk_harga_agent
 * @property integer $produk_harga_enduser
 * @property integer $produk_harga_hpp
 * @property double $produk_stock
 * @property double $kapasitas_kubikasi
 * @property string $jenis_kayu
 * @property string $grade
 * @property string $warna_kayu
 * @property string $glue
 * @property string $profil_kayu
 * @property string $kondisi_kayu
 * @property integer $kayu_id
 * @property string $diameter_range
 *
 * @property HPersediaanProduk[] $hPersediaanProduks
 * @property MHargaProduk[] $mHargaProduks
 * @property TDokumenPenjualanDetail[] $tDokumenPenjualanDetails
 * @property TNotaPenjualanDetail[] $tNotaPenjualanDetails
 * @property TOpKoDetail[] $tOpKoDetails
 * @property TProdukKeluar[] $tProdukKeluars
 * @property TProduksi[] $tProduksis
 * @property TSpmKoDetail[] $tSpmKoDetails
 * @property TSuratPengantarDetail[] $tSuratPengantarDetails
 * @property TTerimaKo[] $tTerimaKos
 */
class MBrgProduk extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file1, $plymill_shift, $sawmill_line, $produk_qty_satuan_besar, $nama;

    public static function tableName()
    {
        return 'm_brg_produk';
    }

    public function behaviors()
    {
        return [\app\components\DeltaGeneralBehavior::className()];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['produk_kode', 'produk_group', 'produk_nama', 'produk_dimensi', 'produk_p', 'produk_p_satuan', 'produk_l', 'produk_l_satuan', 'produk_t', 'produk_t_satuan', 'produk_qty_satuan_kecil', 'created_at', 'created_by', 'updated_at', 'updated_by', 'kapasitas_kubikasi'], 'required'],
            [['created_by', 'updated_by', 'kayu_id'], 'integer'],
            [['produk_stock'], 'number'],
            [['produk_gbr'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['produk_group', 'produk_dimensi', 'produk_p_satuan', 'produk_l_satuan', 'produk_t_satuan', 'produk_satuan_besar', 'produk_satuan_kecil'], 'string', 'max' => 50],
            [['produk_kode', 'produk_nama', 'jenis_kayu', 'grade', 'warna_kayu', 'glue', 'profil_kayu', 'kondisi_kayu', 'diameter_range'], 'string', 'max' => 100],
            [['produk_nama'], 'string', 'max' => 200],
            [['produk_kode', 'produk_nama'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'produk_id' => Yii::t('app', 'Produk'),
            'produk_kode' => Yii::t('app', 'Kode Produk'),
            'produk_group' => Yii::t('app', 'Jenis Produk'),
            'produk_nama' => Yii::t('app', 'Nama Produk'),
            'produk_dimensi' => Yii::t('app', 'Dimensi'),
            'produk_p' => Yii::t('app', 'Panjang'),
            'produk_p_satuan' => Yii::t('app', 'Satuan Panjang'),
            'produk_l' => Yii::t('app', 'Lebar'),
            'produk_l_satuan' => Yii::t('app', 'Satuan Lebar'),
            'produk_t' => Yii::t('app', 'Tinggi / Tebal'),
            'produk_t_satuan' => Yii::t('app', 'Satuan Tinggi / Tebal'),
            'produk_satuan_besar' => Yii::t('app', 'Satuan Besar'),
            'produk_satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
            'produk_qty_satuan_kecil' => Yii::t('app', 'Qty Satuan Kecil'),
            'produk_gbr' => Yii::t('app', 'Gambar'),
            'active' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
            'produk_harga_distributor' => Yii::t('app', 'Harga Distributor'),
            'produk_harga_agent' => Yii::t('app', 'Harga Agent'),
            'produk_harga_enduser' => Yii::t('app', 'Harga End User'),
            'produk_harga_hpp' => Yii::t('app', 'HPP'),
            'kapasitas_kubikasi' => Yii::t('app', 'Kubikasi'),
            'plymill_shift' => Yii::t('app', 'Shift'),
            'sawmill_line' => Yii::t('app', 'Line'),
            'jenis_kayu' => Yii::t('app', 'Jenis Kayu'),
            'grade' => Yii::t('app', 'Grade'),
            'warna_kayu' => Yii::t('app', 'Warna Kayu'),
            'glue' => Yii::t('app', 'Glue'),
            'profil_kayu' => Yii::t('app', 'Profil Kayu'),
            'kondisi_kayu' => Yii::t('app', 'Kondisi Kayu'),
            'kayu_id' => Yii::t('app', 'Kayu'),
            'diameter_range' => Yii::t('app', 'Diameter'),
        ];
    }

    public static function getOptionList()
    {
        $mod = self::find()->where(['active' => true])->orderBy('created_at DESC')->all();
        foreach ($mod as $i => $produk) {
            $return[$produk->produk_id] = $produk->produk_kode;
        }
        return $return;
    }

    public static function generateProductName()
    {
        return $return;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHPersediaanProduks()
    {
        return $this->hasMany(HPersediaanProduk::className(), ['produk_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMHargaProduks()
    {
        return $this->hasMany(MHargaProduk::className(), ['produk_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaKos()
    {
        return $this->hasMany(TTerimaKo::className(), ['produk_id' => 'produk_id']);
    }

    public function getNamaProduk()
    {
        $ret = "";
        if (!empty($this)) {
            $ret = explode("/", $this->produk_nama);
            if ($this->produk_group == "Plywood" || $this->produk_group == "Lamineboard" || $this->produk_group == "Platform") {
                $ret = $ret[1] . "(" . $ret[2] . ") " . $ret[3];
            } else if ($this->produk_group == "Veneer") {
                $ret = $ret[0];
            } else if ($this->produk_group == "Sawntimber" || $this->produk_group == "Moulding" || $this->produk_group == "FingerJointLamineBoard" || $this->produk_group == "FingerJointStick" || $this->produk_group == "Flooring") {
                $ret = $ret[0] . "(" . $ret[1] . ") " . $ret[2];
            }
        }
        return $ret;
    }

    public static function createNewByPackinglist($condition)
    {
        $ret = false;
        $model = new MBrgProduk();
        $model->attributes = $condition;
        $model->produk_kode = self::setProdukKodeNama($condition)['produk_kode'];
        $model->produk_nama = self::setProdukKodeNama($condition)['produk_nama'];
        $satuan_t = $condition['produk_t_satuan'] === 'feet' ? "'" : $condition['produk_t_satuan'];
        $satuan_l = $condition['produk_l_satuan'] === 'feet' ? "'" : $condition['produk_l_satuan'];
        $satuan_p = $condition['produk_p_satuan'] === 'feet' ? "'" : $condition['produk_p_satuan'];
        $model->produk_dimensi = $condition['produk_t']." ".$satuan_t." x ".$condition['produk_l']." ".$satuan_l." x ".$condition['produk_p']." ".$satuan_p;
        $model->produk_satuan_besar = "Palet";
        $model->produk_satuan_kecil = "Pcs";
        $model->produk_qty_satuan_kecil = 0;
        $model->kapasitas_kubikasi = 0;
        $model->active = TRUE;
        $model->created_at = \app\components\DeltaFormatter::formatDateTimeForDb(date('Y-m-d H:i:s'));
        $model->created_by = \Yii::$app->user->id;
        $model->updated_at = \app\components\DeltaFormatter::formatDateTimeForDb(date('Y-m-d H:i:s'));
        $model->updated_by = \Yii::$app->user->id;
        if ($model->validate()) {
            if ($model->save()) {
                $ret = $model->attributes;
            }
        }
        return $ret;
    }

    public static function setProdukKodeNama($condition)
    {
        $data = "";
        $jenis_produk = isset($condition['produk_group']) ? $condition['produk_group'] : "";
        $jenis_kayu = isset($condition['jenis_kayu']) ? $condition['jenis_kayu'] : "";
        $grade = isset($condition['grade']) ? $condition['grade'] : "";
        $warna_kayu = isset($condition['warna_kayu']) ? $condition['warna_kayu'] : "";
        $glue = isset($condition['glue']) ? $condition['glue'] : "";
        $profil_kayu = isset($condition['profil_kayu']) ? $condition['profil_kayu'] : "";
        $kondisi_kayu = isset($condition['kondisi_kayu']) ? $condition['kondisi_kayu'] : "";
        $t = isset($condition['produk_t']) ? $condition['produk_t'] : "";
        $l = isset($condition['produk_l']) ? $condition['produk_l'] : "";
        $p = isset($condition['produk_p']) ? $condition['produk_p'] : "";
        $data['produk_kode'] = "";
        $data['produk_nama'] = "";
        $kode_jenis_produk = \app\models\MDefaultValue::find()->where(['active' => true, 'type' => "jenis-produk", 'value' => $jenis_produk])->one();
        if (!empty($kode_jenis_produk)) {
            $kode_jenis_produk = $kode_jenis_produk->name_en;
        }
        $kode_jenis_kayu = \app\models\MJenisKayu::find()->where(['active' => true, 'nama' => $jenis_kayu])->one();
        if (!empty($kode_jenis_kayu)) {
            $kode_jenis_kayu = $kode_jenis_kayu->kode;
        }
        $kode_grade = \app\models\MGrade::find()->where(['active' => true, 'nama' => $grade])->one();
        if (!empty($kode_grade)) {
            $kode_grade = $kode_grade->kode;
        }
        $kode_warna_kayu = \app\models\MWarnaKayu::find()->where(['active' => true, 'nama' => $warna_kayu])->one();
        if (!empty($kode_warna_kayu)) {
            $kode_warna_kayu = $kode_warna_kayu->kode;
        }
        $kode_glue = \app\models\MGlue::find()->where(['active' => true, 'nama' => $glue])->one();
        if (!empty($kode_glue)) {
            $kode_glue = $kode_glue->kode;
        }
        $kode_profil_kayu = \app\models\MProfilKayu::find()->where(['active' => true, 'nama' => $profil_kayu])->one();
        if (!empty($kode_profil_kayu)) {
            $kode_profil_kayu = $kode_profil_kayu->kode;
        }
        $kode_kondisi_kayu = \app\models\MKondisiKayu::find()->where(['active' => true, 'nama' => $kondisi_kayu])->one();
        if (!empty($kode_kondisi_kayu)) {
            $kode_kondisi_kayu = $kode_kondisi_kayu->kode;
        }
        switch ($jenis_produk) {
            case "Plywood": // CPWDFM/A2/01/11.512202440
                //$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_warna_kayu."/".$kode_glue."/".$t.$l.$p;
                //$data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$warna_kayu."/".$glue."/".$t.$l.$p);
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_glue."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$glue."/".$t.$l.$p);
                break;
            case "Sawntimber": // CSTMBKR/A/01/3015516
                //$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_warna_kayu."/".$kode_kondisi_kayu."/".$t.$l.$p;
                //$data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$warna_kayu."/".$kondisi_kayu."/".$t.$l.$p);
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_kondisi_kayu."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$kondisi_kayu."/".$t.$l.$p);
                break;
            case "Moulding": // CMLDBKR/A/01/2514516
                //$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_warna_kayu."/".$kode_profil_kayu."/".$t.$l.$p;
                //$data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$warna_kayu."/".$profil_kayu."/".$t.$l.$p);
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_profil_kayu."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$profil_kayu."/".$t.$l.$p);
                break;
            case "Veneer": // CVNR/A/0,512202440 // Pertanggal 03/02/2025 ada perubahan format : ditambahkan jenis_kayu
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_warna_kayu."/".$kode_grade."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$warna_kayu."/".$t.$l.$p);
                break;
            case "Lamineboard": //CLBDMM/A2/01/11512202440
                //$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_warna_kayu."/".$kode_glue."/".$t.$l.$p;
                //$data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$warna_kayu."/".$glue."/".$t.$l.$p);
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_glue."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$glue."/".$t.$l.$p);
                break;
            case "Platform": //CPMFMM/A2/01/11512202440
                //$data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_warna_kayu."/".$kode_glue."/".$t.$l.$p;
                //$data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$warna_kayu."/".$glue."/".$t.$l.$p);
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_glue."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_produk."/".$jenis_kayu."/".$grade."/".$glue."/".$t.$l.$p);
                break;
            case "FingerJointLamineBoard": // CFJBMRM/A2/01/2514526
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_profil_kayu."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$profil_kayu."/".$t.$l.$p);
                break;
            case "Flooring": // CFLRMBU/A/TNG/181773
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_profil_kayu."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$profil_kayu."/".$t.$l.$p);
                break;
            case "FingerJointStick": // CFJSMRM/A/A/26735900
                $data['produk_kode'] = "C".$kode_jenis_produk.$kode_jenis_kayu."/".$kode_grade."/".$kode_profil_kayu."/".$t.$l.$p;
                $data['produk_nama'] = str_replace(' ', '',$jenis_kayu."/".$grade."/".$profil_kayu."/".$t.$l.$p);
                break;    
        }
        return $data;
    }

    /**
     * @param $jenis_produk
     * @param $container
     * @param $packinglist_id
     * @param $bundle_partition
     * @return array
     * @throws Exception
     */
    public static function getMasterProduk($jenis_produk, $container, $packinglist_id, $bundle_partition)
    {
        $condition = [];
        $produkorder = "";
        $garing = "";
        if ($jenis_produk === "Plywood" || $jenis_produk === "Lamineboard" || $jenis_produk === "Platform") {
            $produkorder = $jenis_produk;
            $garing = "/";
        }
        $condition['produk_group'] = $jenis_produk;
        if (!empty($container['jenis_kayu'])) {
            $condition['jenis_kayu'] = $container['jenis_kayu'];
            $produkorder .= $garing . $container['jenis_kayu'];
        }
        if (!empty($container['grade'])) {
            $condition['grade'] = $container['grade'];
            $produkorder .= "/" . $container['grade'];
        }
        if (!empty($container['glue'])) {
            $condition['glue'] = $container['glue'];
            $produkorder .= "/" . $container['glue'];
        }
        if (!empty($container['profil_kayu'])) {
            $condition['profil_kayu'] = $container['profil_kayu'];
            $produkorder .= "/" . $container['profil_kayu'];
        }
        if (!empty($container['kondisi_kayu'])) {
            $condition['kondisi_kayu'] = $container['kondisi_kayu'];
            $produkorder .= "/" . $container['kondisi_kayu'];
        }
        if (!empty($container['thick'])) {
            $condition['produk_t'] = $container['thick'];
            $produkorder .= "/" . $container['thick'];
        }
        if (!empty($container['thick_unit'])) {
            $condition['produk_t_satuan'] = $container['thick_unit'];
        }
        if (!empty($container['width'])) {
            $condition['produk_l'] = $container['width'];
            $produkorder .= $container['width'];
        }
        if (!empty($container['width_unit'])) {
            $condition['produk_l_satuan'] = $container['width_unit'];
        }
        if (!empty($container['length'])) {
            $condition['produk_p'] = $container['length'];
            $produkorder .= $container['length'];
        }
        if (!empty($container['length_unit'])) {
            $condition['produk_p_satuan'] = $container['length_unit'];
        }
        if ($bundle_partition) {
            $modUkuranThick = Yii::$app->db->createCommand("
                SELECT thick, thick_unit
                FROM t_packinglist_container
                WHERE packinglist_id = $packinglist_id
                GROUP by 1,2
                ORDER by 1,2
            ")->queryAll();
            $modUkuranWidth = Yii::$app->db->createCommand("
                SELECT width, width_unit
                FROM t_packinglist_container
                WHERE packinglist_id = $packinglist_id
                GROUP by 1,2
                ORDER by 1,2
            ")->queryAll();
            $modUkuranLength = Yii::$app->db->createCommand("
                SELECT length, length_unit
                FROM t_packinglist_container
                WHERE packinglist_id = $packinglist_id
                GROUP by 1,2
                ORDER by 1,2
            ")->queryAll();
            if (count($modUkuranThick) > 1) {
                $produkorder .= "/0";
                $condition['produk_t'] = "0";
            } else {
                $produkorder .= "/" . $modUkuranThick[0]['thick'];
                $condition['produk_t'] = $modUkuranThick[0]['thick'];
            }
            if (count($modUkuranWidth) > 1) {
                $produkorder .= "0";
                $condition['produk_l'] = "0";
            } else {
                $produkorder .= $modUkuranWidth[0]['width'];
                $condition['produk_l'] = $modUkuranWidth[0]['width'];
            }
            if (count($modUkuranLength) > 1) {
                $produkorder .= "0";
                $condition['produk_p'] = "0";
            } else {
                $produkorder .= $modUkuranLength[0]['length'];
                $condition['produk_p'] = $modUkuranLength[0]['length'];
            }
            $condition['produk_t_satuan'] = $modUkuranThick[0]['thick_unit'];
            $condition['produk_l_satuan'] = $modUkuranWidth[0]['width_unit'];
            $condition['produk_p_satuan'] = $modUkuranLength[0]['length_unit'];
        }
        str_replace(" ", "", $produkorder);
        return $condition;
    }

    public static function getOptionListDimensi($jenis_produk=null)
    {
		if(!empty($jenis_produk)){
			$res = self::find()->select(['produk_group'])->where(['active'=>true,'produk_group'=>$jenis_produk])->groupBy('produk_group')->all(); //->orderBy(['produk_dimensi' => SORT_ASC])
		}else{
			$res = self::find()->select(['produk_group'])->where(['active'=>true])->groupBy('produk_group')->all(); //->orderBy(['produk_dimensi' => SORT_ASC])
		}
        return \yii\helpers\ArrayHelper::map($res, 'nama', 'nama');
    }
}
