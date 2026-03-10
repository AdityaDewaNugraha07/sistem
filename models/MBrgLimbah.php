<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_brg_limbah".
 *
 * @property integer $limbah_id
 * @property string $limbah_kelompok
 * @property string $limbah_produk_jenis
 * @property string $limbah_grade
 * @property string $limbah_kode
 * @property string $limbah_nama
 * @property string $limbah_satuan_jual
 * @property string $limbah_satuan_muat
 * @property string $limbah_keterangan
 * @property string $limbah_gambar
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $seq
 */
class MBrgLimbah extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file1,$harga_enduser,$harga_keterangan,$produk_id;
    public static function tableName()
    {
        return 'm_brg_limbah';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::class];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['limbah_kelompok', 'limbah_produk_jenis', 'limbah_kode', 'limbah_nama', 'limbah_satuan_jual', 'limbah_satuan_muat', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['limbah_keterangan', 'limbah_gambar'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'seq'], 'integer'],
            [['limbah_kelompok', 'limbah_produk_jenis', 'limbah_kode'], 'string', 'max' => 50],
            [['limbah_grade'], 'string', 'max' => 200],
            [['limbah_nama', 'limbah_satuan_jual', 'limbah_satuan_muat'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'limbah_id' => Yii::t('app', 'Limbah'),
                'limbah_kelompok' => Yii::t('app', 'Kelompok'),
                'limbah_produk_jenis' => Yii::t('app', 'Jenis Limbah'),
                'limbah_grade' => Yii::t('app', 'Grade'),
                'limbah_kode' => Yii::t('app', 'Kode Limbah'),
                'limbah_nama' => Yii::t('app', 'Nama Limbah'),
                'limbah_satuan_jual' => Yii::t('app', 'Satuan Jual'),
                'limbah_satuan_muat' => Yii::t('app', 'Satuan Muat'),
                'limbah_keterangan' => Yii::t('app', 'Keterangan'),
                'limbah_gambar' => Yii::t('app', 'Gambar'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    public function getHargaLimbah() {
        return $this->hasMany(MHargaLimbah::class, ['limbah_id' => 'limbah_id']);
    }
    
    public static function getOptionList()
    {
        $mod = self::find()->where(['active'=>true])->orderBy('created_at DESC')->all();
        $return = [];
        if(count($mod)>0){
            foreach($mod as $i => $produk){
                $return[$produk->limbah_id] = $produk->limbah_id;
            }
        }
        return $return;
    }
}
