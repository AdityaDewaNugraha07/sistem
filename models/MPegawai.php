<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "m_pegawai".
 *
 * @property integer $pegawai_id
 * @property integer $departement_id
 * @property integer $pegawai_nik
 * @property string $pegawai_nama
 * @property string $pegawai_jk
 * @property string $pegawai_alamat
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $jabatan_id
 *
 * @property TSpb[] $tSpbs
 * @property TSpb[] $tSpbs0
 */
class MPegawai extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $departement_nama,$jabatan_nama;
    public static function tableName()
    {
        return 'm_pegawai';
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
            [['departement_id', 'pegawai_nama', 'pegawai_jk', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['departement_id', 'pegawai_nik', 'jabatan_id', 'created_by', 'updated_by'], 'integer'],
            [['pegawai_alamat'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['pegawai_nama'], 'string', 'max' => 100],
            [['pegawai_jk'], 'string', 'max' => 20],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MJabatan::className(), 'targetAttribute' => ['jabatan_id' => 'jabatan_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pegawai_id' => Yii::t('app', 'Pegawai'),
                'departement_id' => Yii::t('app', 'Departement'),
                'pegawai_nik' => Yii::t('app', 'Nik Pegawai'),
                'pegawai_nama' => Yii::t('app', 'Nama Pegawai'),
                'pegawai_jk' => Yii::t('app', 'Jenis Kelamin'),
                'pegawai_alamat' => Yii::t('app', 'Pegawai Alamat'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'jabatan_id' => Yii::t('app', 'Jabatan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJabatan()
    {
        return $this->hasOne(MJabatan::className(), ['jabatan_id' => 'jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMUsers()
    {
        return $this->hasMany(MUser::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTBpbs()
    {
        return $this->hasMany(TBpb::className(), ['bpb_dikeluarkan' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTBpbs0()
    {
        return $this->hasMany(TBpb::className(), ['bpb_diterima' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpbs()
    {
        return $this->hasMany(TSpb::className(), ['spb_diminta' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpbs0()
    {
        return $this->hasMany(TSpb::className(), ['spb_disetujui' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpos()
    {
        return $this->hasMany(TSpo::className(), ['spo_disetujui' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSpps()
    {
        return $this->hasMany(TSpp::className(), ['spp_disetujui' => 'pegawai_id']);
    } 
    
    public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('pegawai_nama ASC')->all();
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }
	
    public static function getOptionListWithDeptName()
    {
        $mod = self::find()->where(['active'=>true])
                            // ->andWhere(['not exists', (new \yii\db\Query())
                            //     ->select('*')
                            //     ->from('t_jobdesc')
                            //     ->where('t_jobdesc.pegawai_id = m_pegawai.pegawai_id')])
                            ->orderBy('pegawai_nama ASC')->all();
		$res = [];
		if(count($mod)>0){
			foreach($mod as $i => $data){
				$res[$data->pegawai_id] = $data->pegawai_nama.' ('.$data->departement->departement_nama.')';
			}
		}
//        return \yii\helpers\ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
        return $res;
    }
	
	public static function getOptionListAtasan()
    {
        $res = self::find()->where(['active'=>true])->andWhere("jabatan_id <= 7 OR pegawai_id = 75")->orderBy('jabatan_id,pegawai_nama ASC')->all(); //OR jabatan_id IN (9) //jika dibutuhkan untuk level kanit
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }
	
	public static function getOptionMenyetujuiSPB($dept_id=null)
    {
		if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) ){
			$res = self::find()->where(['active'=>true])
								->andWhere("jabatan_id <= 7")
								->orWhere('jabatan_id <= 3')
								->orWhere('jabatan_id = 9')
								->orderBy('pegawai_nama ASC')
								->all();
		}else{
			if(empty($dept_id)){
				$dept_id = Yii::$app->user->identity->pegawai->departement_id;
			}
			$res = self::find()->where(['active'=>true])
                                            ->andWhere(" (departement_id={$dept_id} AND jabatan_id <= 7) OR (departement_id={$dept_id} AND jabatan_id = 9) ")
                                            ->all();
                        //special case untuk QC Plymill
                        if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_QC_PLYMILL){
                            $res = self::find()->where(['active'=>true])
                                                ->andWhere(" (departement_id={$dept_id} AND jabatan_id <= 7) OR (departement_id={$dept_id} AND jabatan_id = 9) OR (pegawai_id = ".\app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM.")")
                                                ->all();
//                                                ->createCommand()->rawSql;
                        }
                        //spesial case untuk PPIC
                        if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PPIC){
                            $res = self::find()->where(['active'=>true])
                                                ->andWhere(" (departement_id ={$dept_id} AND jabatan_id <= 7)  OR (pegawai_id = ".\app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM.")")
                                                ->all(); //OR (departement_id={$dept_id} AND jabatan_id = 9)
                        }
                        //spesial case untuk R&D
                        if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_RND){
                            $res = self::find()->where(['active'=>true])
                                                ->andWhere(" (departement_id ={$dept_id} AND jabatan_id <= 7)  OR (pegawai_id = ".\app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM.")")
                                                ->all();
                        }
                        //spesial case untuk Plymill
                        if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PLYMILL){
                            $res = self::find()->where(['active'=>true])
                                                ->andWhere(" (departement_id ={$dept_id} AND jabatan_id <= 7)  OR (pegawai_id = ".\app\components\Params::DEFAULT_PEGAWAI_ID_ILHAM.")")
                                                ->all(); //OR (departement_id={$dept_id} AND jabatan_id = 9)
                        }
		}
        
        if( Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_TOP_MANAGEMENT  ){
            $res = self::find()->where(['active'=>true])
								->andWhere("jabatan_id <= 7")
								->orderBy('pegawai_nama ASC')
								->all();
        }
        if( Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_SECURITY  ){
                    $res = self::find()->where(['active'=>true])
                                                                        ->andWhere("jabatan_id <= 7 or departement_id=105")
                                                                        ->orderBy('pegawai_nama ASC')
                                                                        ->all();
                }
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }
	
	public static function getOptionListByDept($dept_id=null)
    {
		if(!empty($dept_id)){
			$res = self::find()->where(['active'=>true])->andWhere("departement_id = ".$dept_id)->orderBy('pegawai_nama ASC')->all();
		}else{
			$res = self::find()->where(['active'=>true])->orderBy('pegawai_nama ASC')->all();
		}
        
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }
	
	public static function getOptionListCheckerSecurity()
    {
		$res = self::find()->where('active = TRUE AND departement_id = '.\app\components\Params::DEPARTEMENT_ID_SECURITY.' OR departement_id = '.\app\components\Params::DEPARTEMENT_ID_GA)->orderBy('pegawai_nama ASC')->all();
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }

	public static function getOptionListMarketing()
    {
		$res = self::find()->where('active = TRUE AND departement_id = '.\app\components\Params::DEPARTEMENT_ID_MARKETING)->orderBy('pegawai_nama ASC')->all();
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }
    public static function getOptionListXArray($array)
    {
        $array = implode(",",$array);
		$res = self::find()->where('active = TRUE and pegawai_id in ('.$array.')')->orderBy('pegawai_nama ASC')->all();
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }
    public static function getOptionListPemeriksaProformaPackinglist()
    {
        $res = self::find()->where('active = TRUE and pegawai_id in (4954, 137)')->orderBy('pegawai_nama ASC')->all(); // MIFTAHUL KHOIRIYAH & MUSTAGHFIRIN
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }

    public static function getOptionListMonitoringProduksi($assigned)
    {
        $query = self::find();
        if ($assigned === 'diperiksa') {
            $query
                ->where(['active' => true])
                ->andWhere(['in', 'pegawai_id', [4804,3258,4954,5199,2363]]); //[5199,546,543,2363, 544]
        }else if($assigned === 'disetujui') {
            $query
                ->where(['active' => true])
                ->andWhere(['in', 'pegawai_id', [3281,2471,2749]]); //[3281,356,1446]
        }else if($assigned === 'diketahui') {
            $query
                ->where(['active' => true])
                ->andWhere(['in', 'pegawai_id', [3267,2749]]); //[218,2749]
        }else {
            $query->where(['active' => true]);
        }

        return ArrayHelper::map($query->orderBy('pegawai_nama')->all(), 'pegawai_id', 'pegawai_nama');
    }

    public static function getOptionListPicUkur()
    {
		$res = MDefaultValue::find()->where(['active' => true, 'type' => 'pic-ukur-log'])->all();
        return \yii\helpers\ArrayHelper::map($res, 'value', 'name');
    }
    
    // public static function getOptionListCustomPegawaiById(array $data, $from, $to)
    // {
    //     return ArrayHelper::map(MPegawai::find()->where(['IN', 'pegawai_id', $data])->all(), $from, $to);
    // }
    public static function getOptionListDirektur() //direktur & direktur utama
    {
        $res = self::find()->where(['active'=>true])->andWhere("pegawai_id in (".\app\components\params::DEFAULT_PEGAWAI_ID_ASENG.", ".\app\components\params::DEFAULT_PEGAWAI_ID_DIREKTUR_UTAMA .")")->orderBy('pegawai_nama ASC')->all(); 
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }

    public static function getOptionListPPIC(){
        $res = self::find()->where(['active'=>true])->andWhere("departement_id = 110")->orderBy('pegawai_nama ASC')->all();
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }

    public static function getOptionListCheckerSecurityOnly()
    {
		$res = self::find()->where('active = TRUE AND departement_id = '.\app\components\Params::DEPARTEMENT_ID_SECURITY)->orderBy('pegawai_nama ASC')->all();
        return ArrayHelper::map($res, 'pegawai_id', 'pegawai_nama');
    }
}
