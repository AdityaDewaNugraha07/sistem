<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Zloggerz;
use app\models\MUser;
use app\models\MPegawai;

/**
 * This is the model class for table "zloggerz".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class ZloggerzSearch extends Zloggerz
{
    /**
     * @inheritdoc
     */
    // tambahan variable untuk upload file

    public static function tableName()
    {
        return 'zloggerz';
    }
    
    public static function primaryKey()
    {
        return ["id"];
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
            [['level','category','log_time','prefix','message'], 'safe'],
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
        $query = Zloggerz::find();
        $query->leftJoin('m_user', 'm_user.username = zloggerz.prefix');
        $query->leftJoin('m_pegawai', 'm_pegawai.pegawai_id = m_user.pegawai_id');
        $query->orderBy('zloggerz.log_time DESC');
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
        
        //select log_time, to_timestamp(log_time) from zloggerz where cast(to_timestamp(log_time) as varchar) ilike '%86%' order by id desc
        $query->andFilterWhere(["ilike", "cast(to_timestamp(log_time) as varchar)", $this->log_time])
            ->andFilterWhere(["ilike", "zloggerz.level", $this->level])
            ->andFilterWhere(["ilike", "zloggerz.category", $this->category])
            ->andFilterWhere(["ilike", "zloggerz.prefix", $this->prefix])
            ->andFilterWhere(["ilike", "zloggerz.message", $this->message]);
        return $dataProvider;
    }
    
    public function searchAktif($params)
    {       
        //$array_driver = SuratJalan::find()->select('id_karyawan')->where(['status'=>2])->all();
        
        //echo var_dump($array_driver);
        
        $query = Zloggerz::find();
        $query->leftJoin('m_user', 'm_user.username = zloggerz.prefix');
        $query->leftJoin('m_pegawai', 'm_pegawai.pegawai_id = m_user.pegawai_id');
        $query->orderBy('zloggerz.log_time DESC');
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

        $query->andFilterWhere(["ilike", "cast(to_timestamp(log_time) as varchar)", $this->log_time])
            ->andFilterWhere(["ilike", "zloggerz.level", $this->level])
            ->andFilterWhere(["ilike", "zloggerz.category", $this->category])
            ->andFilterWhere(["ilike", "zloggerz.prefix", $this->prefix])
            ->andFilterWhere(["ilike", "zloggerz.message", $this->message]);            
        return $dataProvider;
    }
}
