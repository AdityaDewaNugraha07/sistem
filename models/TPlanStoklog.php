<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_plan_stoklog".
 *
 * @property integer $plan_stoklog_id
 * @property string $jenis_alokasi
 * @property integer $kayu_id
 * @property string $no_barcode
 * @property double $kubikasi
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MKayu $kayu
 */
class TPlanStoklog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kayu_nama, $pcs;
    public static function tableName()
    {
        return 't_plan_stoklog';
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
            [['jenis_alokasi', 'kayu_id', 'no_barcode', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kayu_id', 'created_by', 'updated_by'], 'integer'],
            [['kubikasi'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['jenis_alokasi', 'no_barcode'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'plan_stoklog_id' => 'Plan Stoklog',
                'jenis_alokasi' => 'Jenis Alokasi',
                'kayu_id' => 'Kayu',
                'no_barcode' => 'No Barcode',
                'kubikasi' => 'Kubikasi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }

    public function searchLaporan(){
        $query = self::find();
        $query->select('jenis_alokasi, kayu_nama, count(*) as pcs, sum(kubikasi) as kubikasi, t_plan_stoklog.kayu_id');
        $query->join('JOIN', 'm_kayu', 'm_kayu.kayu_id = t_plan_stoklog.kayu_id');
        // $query->join('JOIN', 'h_persediaan_log', 'h_persediaan_log.no_barcode = t_plan_stoklog.no_barcode');
        $query->join('JOIN', "(SELECT no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS total_stock FROM h_persediaan_log
						GROUP BY no_barcode HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0) s", 't_plan_stoklog.no_barcode = s.no_barcode');
        $query->groupBy('jenis_alokasi, kayu_nama, t_plan_stoklog.kayu_id');
        // $query->having("SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0");
        $query->orderBy(!empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) :
            self::tableName() . '.jenis_alokasi ASC, kayu_nama');
        if (!empty($this->jenis_alokasi)) {
            $query->andWhere("jenis_alokasi = '" . $this->jenis_alokasi . "' ");
        }
        if (!empty($this->kayu_id)) {
            $query->andWhere("t_plan_stoklog.kayu_id = " . $this->kayu_id . " ");
        }
        return $query;
    }

    public function searchLaporanDt()
    {
        $searchLaporan = $this->searchLaporan();
        $param['table'] = self::tableName();
        $param['pk'] = $param['table'] . '.' . self::primaryKey()[0];
        if (!empty($searchLaporan->groupBy)) {
            $param['column'] = ['GROUP BY ' . implode(", ", $searchLaporan->groupBy)];
        }
        if (!empty($searchLaporan->select)) {
            $param['column'] = $searchLaporan->select;
        }
        if (!empty($searchLaporan->groupBy)) {
            $param['group'] = ['GROUP BY ' . implode(", ", $searchLaporan->groupBy)];
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
        if(!empty($searchLaporan->having)){
			$param['having'] = "HAVING ".$searchLaporan->having;
		}
        $param['where'] = [];
        if (!empty($this->jenis_alokasi)) {
            array_push($param['where'], "jenis_alokasi = '" . $this->jenis_alokasi . "'");
        }
        if (!empty($this->kayu_id)) {
            array_push($param['where'], "t_plan_stoklog.kayu_id = '" . $this->kayu_id . "'");
        }
        return $param;
    }
}