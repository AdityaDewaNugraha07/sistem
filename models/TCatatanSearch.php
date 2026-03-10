<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TCatatan;
use app\models\MUser;
use app\models\MPegawai;

/**
 * This is the model class for table "t_catatan".
 *
 * @property integer $catatan_id
 * @property integer $user_id 
 * @property string $tanggal
 * @property string $keterangan
 * @property string $catatan_gambar
 * @property string $judul
 */
class TCatatanSearch extends TCatatan
{
    /**
     * @inheritdoc
     */
    // tambahan variable untuk upload file
    public $file;

    public static function tableName()
    {
        return 't_catatan';
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
            [['catatan_id'], 'integer'],
            [['tanggal','judul','keterangan','user_id','catatan_gambar'], 'safe'],
            //[['keterangan'], 'string'],
            //[['user_id'], 'integer'],
            //[['catatan_gambar', 'judul'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //->leftJoin('meta_data', 'meta_data.ID=product.meta_dataID')
        $query = TCatatan::find();
        $query->leftJoin('m_user', 'm_user.user_id = t_catatan.user_id');
        $query->leftJoin('m_pegawai', 'm_pegawai.pegawai_id = m_user.pegawai_id');
        $query->orderBy('t_catatan.tanggal DESC');
        $query->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
                
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'nama_kolom' => 'value_kolom',
        ]);
        //Select DATE_FORMAT(now(), '%b %e, %Y, %T')
        $query->andFilterWhere(["ilike", "to_char(t_catatan.tanggal, 'DD-MM-YYYY')", $this->tanggal])
            ->andFilterWhere(["ilike", "m_user.username", $this->user_id])
            ->andFilterWhere(["ilike", "t_catatan.judul", $this->judul])
            ->andFilterWhere(["ilike", "t_catatan.keterangan", $this->keterangan])
            ->andFilterWhere(["ilike", "t_catatan.catatan_gambar", $this->catatan_gambar]);            
        return $dataProvider;
    }
    
    public function searchAktif($params)
    {       
        //$array_driver = SuratJalan::find()->select('id_karyawan')->where(['status'=>2])->all();
        
        //echo var_dump($array_driver);
        
        $query = TCatatan::find();
        $query->leftJoin('m_user', 'm_user.user_id = t_catatan.user_id');
        $query->leftJoin('m_pegawai', 'm_pegawai.pegawai_id = m_user.user_id');
        $query->orderBy('opentiket_tgl DESC');
        $query->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
                
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'' => 0,
        ]);

        $query->andFilterWhere(["ilike", "to_char(t_catatan.tanggal, 'DD-MM-YYYY')", $this->tanggal])
            ->andFilterWhere(["ilike", "m_user.username", $this->user_id])
            ->andFilterWhere(["ilike", "t_catatan.judul", $this->judul])
            ->andFilterWhere(["ilike", "t_catatan.keterangan", $this->keterangan])
            ->andFilterWhere(["ilike", "t_catatan.catatan_gambar", $this->catatan_gambar]);            
        return $dataProvider;
    }
}
