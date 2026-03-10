<?php

namespace app\modules\purchasinglog\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class LaporanController extends DeltaBaseController
{

    public function actionSpmlog() {
		$model = new \app\models\TSpkShipping();
        $model->tgl_awal = date("01/m/Y");
        $model->tgl_akhir = date("d/m/Y");
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TSpkShipping'];
				$model->kode = $form_params['TSpkShipping']['kode'];
				$model->tgl_awal = $form_params['TSpkShipping']['tgl_awal'];
				$model->tgl_akhir = $form_params['TSpkShipping']['tgl_akhir'];
				$model->nama_tongkang = $form_params['TSpkShipping']['nama_tongkang'];
				$model->lokasi_muat = $form_params['TSpkShipping']['lokasi_muat'];
                $model->pic_shipping = $form_params['TSpkShipping']['pic_shipping'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('spmlog/index',['model'=>$model]);        
    }

    public function actionOpenKeputusanPembelianlog($id) {
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('spmlog/_info',['id'=>$id]);
		}
    }

    public function actionOpenDetailKeputusanPembelianlog($id) {
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('spmlog/_infoDetail',['id'=>$id]);
		}
    }
    
    public function actionOpenDetailTracking1($id) {
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('spmlog/_infoTracking',['id'=>$id]);
		}
    }

    public function actionSpmlogtracking() {
		$model = new \app\models\TSpkShippingTracking();
        $model->tgl_awal = date("01/m/Y");
        $model->tgl_akhir = date("d/m/Y");
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TSpkShippingTracking'];
				$model->kode = $form_params['TSpkShippingTracking']['kode'];
				$model->tgl_awal = $form_params['TSpkShippingTracking']['tgl_awal'];
				$model->tgl_akhir = $form_params['TSpkShippingTracking']['tgl_akhir'];
				//$model->nama_tongkang = $form_params['TSpkShippingTracking']['nama_tongkang'];
				$model->jenis = $form_params['TSpkShippingTracking']['jenis'];
                $model->lokasi = $form_params['TSpkShippingTracking']['lokasi'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('spmlogtracking/index',['model'=>$model]);        
    }

    public function actionOpenDetailTracking2($id) {
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('spmlogtracking/_infoTracking',['id'=>$id]);
		}
    }

    public function actionLoglist(){
		$model = new \app\models\TLoglist();
        $model->tgl_awal = date("01/m/Y");
        $model->tgl_akhir = date("d/m/Y");
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TLoglist'];
				$model->tgl_awal = $form_params['TLoglist']['tgl_awal'];
				$model->tgl_akhir = $form_params['TLoglist']['tgl_akhir'];
				$model->loglist_kode = $form_params['TLoglist']['loglist_kode'];
				$model->nomor = $form_params['TLoglist']['nomor'];
				$model->lokasi_muat = $form_params['TLoglist']['lokasi_muat'];
				//$model->model_ukuran_loglist = $form_params['TLoglist']['model_ukuran_loglist'];
                $model->suplier_id = $form_params['TLoglist']['suplier_id'];
                $model->area_pembelian = $form_params['TLoglist']['area_pembelian'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('loglist/index',['model'=>$model]);
	}

    public function actionLihatDetail(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $model = \app\models\TLoglist::findOne(['loglist_id'=>$loglist_id]);
            $ukuran = $model->model_ukuran_loglist;
                $ukuran == '2 Diameter' ? $colspan = 3 : $colspan = 5;
            $area_pembelian = $model->area_pembelian;
            $modDkg = \app\models\TDkg::find()->where(['loglist_id'=>$model->loglist_id])->all();
            $data = [];
            $data['html'] = '';
            if(!empty($loglist_id)){
                $data['html'] .= $this->renderPartial('loglist/_main',['modDkg'=>$modDkg, 'model'=>$model,'loglist_id'=>$loglist_id]);
                $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id, 'lampiran'=>1])->orderBy(['lampiran'=>SORT_ASC, 'loglist_detail_id'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    $i = 1;
                    $data['html'] .= '<br><br><br>
                                        <div class="col-md-3">
                                            <h4>Detail Terima Loglist</h4>
                                        </div>
                                        <div class="col-md-9 text-right">
                                        ';
                    
                    $sql_lampiran = "select distinct(lampiran) from t_loglist_detail where loglist_id = ".$loglist_id." ";
                    $query_lampiran = Yii::$app->db->createCommand($sql_lampiran)->queryAll();
                    foreach($query_lampiran as $lampiran) {
                        $data['html'] .= '<a class="btn btn-xs blue-hoki" id="btn-add-item" style="margin-left: 10px;" onclick="lihatLampiran('.$loglist_id.','.$lampiran['lampiran'].')"><i class="fa fa-paste"></i> '.$lampiran['lampiran'].'</a>';
                    }
                    $data['html'] .= '<a class="btn btn-xs blue-hoki" id="btn-add-item" style="margin-left: 10px;" onclick="lihatLampiran('.$loglist_id.',0)"><i class="fa fa-eye"> </i></a>';
                    $data['html'] .= '  </div>
                                        <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                        <thead>
                                            <tr>
                                                <th colspan="4">Nomor</th>
                                                <th rowspan="2" style="font-size: 1.1rem;">Kayu</th>
                                                <th rowspan="2" style="width: 50px;">Pjg<sup>m</sup></th>
                                                <th colspan="'.$colspan.'" id="diameter-th">Diameter</th>
                                                <th colspan="3">Unsur Cacat</th>
                                                <th colspan="2">Volume</th>
                                                <th rowspan="2" style="width: 30px; font-size: 0.9rem;">Fresh<br>Cut</th>
                                                <th rowspan="2" style="width: 60px; background-color: darkorange; color: #fff; font-size: 20px;"><div id="lampiran">L 1</div></th>
                                            </tr>
                                            <tr>
                                                <th style="width: 30px; font-size: 1.1rem;">No</th>
                                                <th style="width: 50px; font-size: 1.1rem;">Grade</th>
                                                <th style="width: 50px; font-size: 1.1rem;">Produksi</th>
                                                <th style="width: 50px; font-size: 1.1rem;">Pcs</th>';
                                                if ($ukuran == '2 Diameter') {
                                                    $data['html'] .= '<th class="diameter2" style="width: 50px; font-size: 1.1rem;">D1<sup>cm</sup></th>
                                                                        <th class="diameter2" style="width: 50px; font-size: 1.1rem;">D2<sup>cm</sup></th>';
                                                } else {
                                                    $data['html'] .= '<th class="diameter4" style="width: 50px; font-size: 1.1rem;">D1<sup>cm</sup></th>
                                                                        <th class="diameter4" style="width: 50px; font-size: 1.1rem;">D2<sup>cm</sup></th>
                                                                        <th class="diameter4" style="width: 50px; font-size: 1.1rem;">D3<sup>cm</sup></th>
                                                                        <th class="diameter4" style="width: 50px; font-size: 1.1rem;">D4<sup>cm</sup></th>';
                                                }
                                                $data['html'] .= '<th style="width: 53px; font-size: 1.1rem;">Rata2<sup>cm</sup></th>
                                                                    <th style="width: 50px; font-size: 1.1rem;">Panjang<sup>cm</sup></th>
                                                                    <th style="width: 50px; font-size: 1.1rem;">GB</th>
                                                                    <th style="width: 50px; font-size: 1.1rem;">GR</th>
                                                                    <th style="width: 70px; font-size: 1.1rem;">Range</th>
                                                                    <th style="width: 50px; font-size: 1.1rem;">m<sup>3</sup></th>
                                            </tr>
                                        </thead>                    
                                     ';
                    
                    foreach($modDetail as $x => $detail){
                        $lampiran = $detail->lampiran;
                        if($ukuran=="2 Diameter"){
                            $data['html'] .= $this->renderPartial('loglist/_item',['xxx'=>$ukuran,'i'=>$i,'area_pembelian'=>$area_pembelian,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>0]);
                        }else{
                            $data['html'] .= $this->renderPartial('loglist/_item4D',['xxx'=>$ukuran,'i'=>$i,'area_pembelian'=>$area_pembelian,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>0]);
                        }
                        $i++;
                    }
                    $data['html'] .= "</table>";
                    $data['html'] .= '
                                    <table class="table table-striped table-bordered table-advance table-hover" id="table-rekap">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="width: 30px;">No.</th>
                                                <th rowspan="2" class="text-center" style="width: 150px;">Jenis</th>
                                                <th colspan="2" class="text-center">25-29</th>
                                                <th colspan="2" class="text-center">30-39</th>
                                                <th colspan="2" class="text-center">40-49</th>
                                                <th colspan="2" class="text-center">50-59</th>
                                                <th colspan="2" class="text-center">60-69</th>
                                                <th colspan="2" class="text-center">70 up</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" style="width: 53px;">Pcs</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Pcs</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Pcs</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Pcs</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Pcs</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Pcs</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                    ';

                    $sql_jenis_kayu = "select distinct(a.kayu_id), b.group_kayu, b.kayu_nama ".
                                        "   from t_loglist_detail a ". 
                                        "   join m_kayu b on b.kayu_id = a.kayu_id ".
                                        "   where loglist_id = ".$loglist_id." ".
                                        "   and lampiran = 1 ".
                                        "   ";
                    $query_jenis_kayu = Yii::$app->db->createCommand($sql_jenis_kayu)->queryAll();
                    $i = 1;
                    $tot_batang_2529 = 0; $tot_volume_2529 = 0;
                    $tot_batang_3039 = 0; $tot_volume_3039 = 0;
                    $tot_batang_4049 = 0; $tot_volume_4049 = 0;
                    $tot_batang_5059 = 0; $tot_volume_5059 = 0;
                    $tot_batang_6069 = 0; $tot_volume_6069 = 0;
                    $tot_batang_70up = 0; $tot_volume_70up = 0;
                    foreach ($query_jenis_kayu as $kolom) {
                        $sql_batang_2529 = "select count(nomor_batang) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '25-29'  ".
                                            "   ";
                        $batang_2529 = Yii::$app->db->createCommand($sql_batang_2529)->queryScalar();
                        $batang_2529 == 0 ? $batang_2529 = '-' : $batang_2529 = $batang_2529;
                        $sql_volume_2529 = "select sum(volume_value) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '25-29' ".
                                            "   ";
                        $volume_2529 = Yii::$app->db->createCommand($sql_volume_2529)->queryScalar();
                        $volume_2529 == 0 ? $volume_2529 = '-' : $volume_2529 = $volume_2529;
                        //=============================================================================
                        $sql_batang_3039 = "select count(nomor_batang) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '30-39' ".
                                            "   ";
                        $batang_3039 = Yii::$app->db->createCommand($sql_batang_3039)->queryScalar();
                        $batang_3039 == 0 ? $batang_3039 = '-' : $batang_3039 = $batang_3039;
                        $sql_volume_3039 = "select sum(volume_value) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '30-39' ".
                                            "   ";
                        $volume_3039 = Yii::$app->db->createCommand($sql_volume_3039)->queryScalar();
                        $volume_3039 == 0 ? $volume_3039 = '-' : $volume_3039 = $volume_3039;
                        //=============================================================================
                        $sql_batang_4049 = "select count(nomor_batang) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '40-49' ".
                                            "   ";
                        $batang_4049 = Yii::$app->db->createCommand($sql_batang_4049)->queryScalar();
                        $batang_4049 == 0 ? $batang_4049 = '-' : $batang_4049 = $batang_4049;
                        $sql_volume_4049 = "select sum(volume_value) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '40-49' ".
                                            "   ";
                        $volume_4049 = Yii::$app->db->createCommand($sql_volume_4049)->queryScalar();
                        $volume_4049 == 0 ? $volume_4049 = '-' : $volume_4049 = $volume_4049;
                        //=============================================================================
                        $sql_batang_5059 = "select count(nomor_batang) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '50-59' ".
                                            "   ";
                        $batang_5059 = Yii::$app->db->createCommand($sql_batang_5059)->queryScalar();
                        $batang_5059 == 0 ? $batang_5059 = '-' : $batang_5059 = $batang_5059;
                        $sql_volume_5059 = "select sum(volume_value) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '50-59' ".
                                            "   ";
                        $volume_5059 = Yii::$app->db->createCommand($sql_volume_5059)->queryScalar();
                        $volume_5059 == 0 ? $volume_5059 = '-' : $volume_5059 = $volume_5059;
                        //=============================================================================
                        $sql_batang_6069 = "select count(nomor_batang) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '60-69' ".
                                            "   ";
                        $batang_6069 = Yii::$app->db->createCommand($sql_batang_6069)->queryScalar();
                        $batang_6069 == 0 ? $batang_6069 = '-' : $batang_6069 = $batang_6069;
                        $sql_volume_6069 = "select sum(volume_value) from t_loglist_detail ". 
                                            "   ";
                        $volume_6069 = Yii::$app->db->createCommand($sql_volume_6069)->queryScalar();
                        $volume_6069 == 0 ? $volume_6069 = '-' : $volume_6069 = $volume_6069;
                        //=============================================================================
                        $sql_batang_70up = "select count(nomor_batang) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '70-up' ".
                                            "   ";
                        $batang_70up = Yii::$app->db->createCommand($sql_batang_70up)->queryScalar();
                        $batang_70up == 0 ? $batang_70up = '-' : $batang_70up = $batang_70up;
                        $sql_volume_70up = "select sum(volume_value) from t_loglist_detail ". 
                                            "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '70-up' ".
                                            "   ";
                        $volume_70up = Yii::$app->db->createCommand($sql_volume_70up)->queryScalar();
                        $volume_70up == 0 ? $volume_70up = '-' : $volume_70up = $volume_70up;
                        //=============================================================================
                        
                        $data['html'] .= $this->renderPartial('loglist/_rekap',['sql_jenis_kayu' => $sql_jenis_kayu, 'i'=>$i,'model'=>$model,'kolom'=>$kolom,
                                                                    'batang_2529'=>$batang_2529,'volume_2529'=>$volume_2529,
                                                                    'batang_3039'=>$batang_3039,'volume_3039'=>$volume_3039,
                                                                    'batang_4049'=>$batang_4049,'volume_4049'=>$volume_4049,
                                                                    'batang_5059'=>$batang_5059,'volume_5059'=>$volume_5059,
                                                                    'batang_6069'=>$batang_6069,'volume_6069'=>$volume_6069,
                                                                    'batang_70up'=>$batang_70up,'volume_70up'=>$volume_70up
                                                                    ]);
                        $i++;
                        $tot_batang_2529 += $batang_2529; $tot_volume_2529 += $volume_2529;
                        $tot_batang_3039 += $batang_3039; $tot_volume_3039 += $volume_3039;
                        $tot_batang_4049 += $batang_4049; $tot_volume_4049 += $volume_4049;
                        $tot_batang_5059 += $batang_5059; $tot_volume_5059 += $volume_5059;
                        $tot_batang_6069 += $batang_6069; $tot_volume_6069 += $volume_6069;
                        $tot_batang_70up += $batang_70up; $tot_volume_70up += $volume_70up;
                    }
                    
                    $data['html'] .= "<tr>";
                    $data['html'] .= "<td colspan='2' class='td=leco; text-right'><b>TOTAL</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".$tot_batang_2529."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_2529,2)."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".$tot_batang_3039."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_3039,2)."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".$tot_batang_4049."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_4049,2)."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".$tot_batang_5059."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_5059,2)."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".$tot_batang_6069."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_6069,2)."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".$tot_batang_70up."</b></td>";
                    $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_70up,2)."</b></td>";
                    $data['html'] .= "</tr>";
                    $data['html'] .= '</tbody></table>';
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionLihatLampiran(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $lampiran = Yii::$app->request->post('lampiran');
            $edit = 0;
            $model = \app\models\TLoglist::findOne(['loglist_id'=>$loglist_id]);
            $ukuran = $model->model_ukuran_loglist;
            if ($ukuran == 0 || $ukuran == '2 Diameter') {
                $ukuran = '2 Diameter';
            } else if ($ukuran == 1 || $ukuran == '4 Diameter') {
                $ukuran = '4 Diameter';
            } else {
                $ukuran = 99;
            }
            $area_pembelian = $model->area_pembelian;
            $data = [];
            $data['html'] = '';
			$disabled = false;
            if(!empty($loglist_id)){
                if ($lampiran == 0 || $lampiran == '' || empty($lampiran)) {
                    $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id])->orderBy(['lampiran'=>SORT_ASC, 'loglist_detail_id'=>SORT_ASC])->all();
                } else {
                    $modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id,'lampiran'=>$lampiran])->orderBy(['lampiran'=>SORT_ASC, 'loglist_detail_id'=>SORT_ASC])->all();
                }    
                if(count($modDetail)>0){
                    $i = 1;
                    foreach($modDetail as $x => $detail){
                        $lampiran = $detail->lampiran;
                        if($ukuran=="2 Diameter"){
                            $data['html'] .= $this->renderPartial('loglist/_item',['xxx'=>$ukuran,'i'=>$i,'area_pembelian'=>$area_pembelian,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        }else{
                            $data['html'] .= $this->renderPartial('loglist/_item4D',['xxx'=>$ukuran,'i'=>$i,'area_pembelian'=>$area_pembelian,'lampiran'=>$lampiran,'modDetail'=>$detail,'edit'=>$edit]);
                        }
                        $i++;
                    }
                }
            }
            return $this->asJson($data);
        }
    }

    public function actionLihatRekap(){
		if(\Yii::$app->request->isAjax){
            $loglist_id = Yii::$app->request->post('loglist_id');
            $lampiran = Yii::$app->request->post('lampiran');
            $edit = 0;
            $model = \app\models\TLoglist::findOne(['loglist_id'=>$loglist_id]);
            $data = [];
            $data['html'] = '';
            if(!empty($loglist_id)){
                /*$modDetail = \app\models\TLoglistDetail::find()->where(['loglist_id'=>$loglist_id,'lampiran'=>$lampiran])->orderBy(['created_at'=>SORT_ASC])->all();
                if(count($modDetail)>0){
                    $i = 1;
                    foreach($modDetail as $x => $detail){
                        $lampiran = $detail->lampiran;
                        $data['html'] .= $this->renderPartial('_rekap',['i'=>$i,'model'=>$model,'modDetail'=>$modDetail]);
                        $i++;
                    }
                }*/

                if ($lampiran == 0 || $lampiran == "" || empty($lampiran)) {
                    $and_lampiran = " ";
                } else {
                    $and_lampiran = " and lampiran = ".$lampiran." ";
                }

                $sql_jenis_kayu = "select distinct(a.kayu_id), b.group_kayu, b.kayu_nama ".
                                    "   from t_loglist_detail a ". 
                                    "   join m_kayu b on b.kayu_id = a.kayu_id ".
                                    "   where loglist_id = ".$loglist_id." ".
                                    //"   and lampiran = ".$lampiran."".
                                    $and_lampiran.
                                    "   ";
                $query_jenis_kayu = Yii::$app->db->createCommand($sql_jenis_kayu)->queryAll();
                $i = 1;
                $tot_batang_2529 = 0; $tot_volume_2529 = 0;
                $tot_batang_3039 = 0; $tot_volume_3039 = 0;
                $tot_batang_4049 = 0; $tot_volume_4049 = 0;
                $tot_batang_5059 = 0; $tot_volume_5059 = 0;
                $tot_batang_6069 = 0; $tot_volume_6069 = 0;
                $tot_batang_70up = 0; $tot_volume_70up = 0;
                foreach ($query_jenis_kayu as $kolom) {
                    $sql_batang_2529 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '25-29' ".$and_lampiran." ".
                                        "   ";
                    $batang_2529 = Yii::$app->db->createCommand($sql_batang_2529)->queryScalar();
                    $batang_2529 == 0 ? $batang_2529 = '-' : $batang_2529 = $batang_2529;
                    $sql_volume_2529 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '25-29' ".$and_lampiran." ".
                                        "   ";
                    $volume_2529 = Yii::$app->db->createCommand($sql_volume_2529)->queryScalar();
                    $volume_2529 == 0 ? $volume_2529 = '-' : $volume_2529 = $volume_2529;
                    //=============================================================================
                    $sql_batang_3039 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '30-39' ".$and_lampiran." ".
                                        "   ";
                    $batang_3039 = Yii::$app->db->createCommand($sql_batang_3039)->queryScalar();
                    $batang_3039 == 0 ? $batang_3039 = '-' : $batang_3039 = $batang_3039;
                    $sql_volume_3039 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '30-39' ".$and_lampiran." ".
                                        "   ";
                    $volume_3039 = Yii::$app->db->createCommand($sql_volume_3039)->queryScalar();
                    $volume_3039 == 0 ? $volume_3039 = '-' : $volume_3039 = $volume_3039;
                    //=============================================================================
                    $sql_batang_4049 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '40-49' ".$and_lampiran." ".
                                        "   ";
                    $batang_4049 = Yii::$app->db->createCommand($sql_batang_4049)->queryScalar();
                    $batang_4049 == 0 ? $batang_4049 = '-' : $batang_4049 = $batang_4049;
                    $sql_volume_4049 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '40-49' ".$and_lampiran." ".
                                        "   ";
                    $volume_4049 = Yii::$app->db->createCommand($sql_volume_4049)->queryScalar();
                    $volume_4049 == 0 ? $volume_4049 = '-' : $volume_4049 = $volume_4049;
                    //=============================================================================
                    $sql_batang_5059 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '50-59' ".$and_lampiran." ".
                                        "   ";
                    $batang_5059 = Yii::$app->db->createCommand($sql_batang_5059)->queryScalar();
                    $batang_5059 == 0 ? $batang_5059 = '-' : $batang_5059 = $batang_5059;
                    $sql_volume_5059 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '50-59' ".$and_lampiran." ".
                                        "   ";
                    $volume_5059 = Yii::$app->db->createCommand($sql_volume_5059)->queryScalar();
                    $volume_5059 == 0 ? $volume_5059 = '-' : $volume_5059 = $volume_5059;
                    //=============================================================================
                    $sql_batang_6069 = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '60-69' ".$and_lampiran." ".
                                        "   ";
                    $batang_6069 = Yii::$app->db->createCommand($sql_batang_6069)->queryScalar();
                    $batang_6069 == 0 ? $batang_6069 = '-' : $batang_6069 = $batang_6069;
                    $sql_volume_6069 = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '60-69' ".$and_lampiran." ".
                                        "   ";
                    $volume_6069 = Yii::$app->db->createCommand($sql_volume_6069)->queryScalar();
                    $volume_6069 == 0 ? $volume_6069 = '-' : $volume_6069 = $volume_6069;
                    //=============================================================================
                    $sql_batang_70up = "select count(nomor_batang) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '70-up' ".$and_lampiran." ".
                                        "   ";
                    $batang_70up = Yii::$app->db->createCommand($sql_batang_70up)->queryScalar();
                    $batang_70up == 0 ? $batang_70up = '-' : $batang_70up = $batang_70up;
                    $sql_volume_70up = "select sum(volume_value) from t_loglist_detail ". 
                                        "   where loglist_id = ".$loglist_id." and kayu_id = ".$kolom['kayu_id']." and volume_range = '70-up' ".$and_lampiran." ".
                                        "   ";
                    $volume_70up = Yii::$app->db->createCommand($sql_volume_70up)->queryScalar();
                    $volume_70up == 0 ? $volume_70up = '-' : $volume_70up = $volume_70up;
                    //=============================================================================
                    
                    $data['html'] .= $this->renderPartial('loglist/_rekap',['i'=>$i,'model'=>$model,'kolom'=>$kolom,
                                                                'batang_2529'=>$batang_2529,'volume_2529'=>$volume_2529,
                                                                'batang_3039'=>$batang_3039,'volume_3039'=>$volume_3039,
                                                                'batang_4049'=>$batang_4049,'volume_4049'=>$volume_4049,
                                                                'batang_5059'=>$batang_5059,'volume_5059'=>$volume_5059,
                                                                'batang_6069'=>$batang_6069,'volume_6069'=>$volume_6069,
                                                                'batang_70up'=>$batang_70up,'volume_70up'=>$volume_70up
                                                                ]);
                    $i++;
                    $tot_batang_2529 += $batang_2529; $tot_volume_2529 += $volume_2529;
                    $tot_batang_3039 += $batang_3039; $tot_volume_3039 += $volume_3039;
                    $tot_batang_4049 += $batang_4049; $tot_volume_4049 += $volume_4049;
                    $tot_batang_5059 += $batang_5059; $tot_volume_5059 += $volume_5059;
                    $tot_batang_6069 += $batang_6069; $tot_volume_6069 += $volume_6069;
                    $tot_batang_70up += $batang_70up; $tot_volume_70up += $volume_70up;
                }
                $data['html'] .= "<tr>";
                $data['html'] .= "<td colspan='2' class='td=leco; text-right'><b>TOTAL</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_2529,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_2529,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_3039,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_3039,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_4049,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_4049,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_5059,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_5059,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_6069,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_6069,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_batang_70up,2)."</b></td>";
                $data['html'] .= "<td class='td-kecil text-right'><b>".\app\components\DeltaFormatter::formatNumberForUser($tot_volume_70up,2)."</b></td>";
                $data['html'] .= "</tr>";
            }
            return $this->asJson($data);
        }
    }  

	public function actionBiayaGrader(){
		$model = new \app\models\TBiayaGraderDetail();
        $model->tgl_awal = date("01/m/Y");
        $model->tgl_akhir = date("d/m/Y");
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TBiayaGraderDetail'];
				$model->tgl_awal = $form_params['TBiayaGraderDetail']['tgl_awal'];
				$model->tgl_akhir = $form_params['TBiayaGraderDetail']['tgl_akhir'];
				$model->biaya_grader_kode = $form_params['TBiayaGraderDetail']['biaya_grader_kode'];
				$model->status = $form_params['TBiayaGraderDetail']['status'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('biayaGrader/index',['model'=>$model]);
	}

	public function actionBiayaGraderPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TBiayaGraderDetail();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes = $_GET['TBiayaGraderDetail'];
		$model->tgl_awal = !empty($_GET['TBiayaGraderDetail']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TBiayaGraderDetail']['tgl_awal']):"";
		$model->tgl_akhir = !empty($_GET['TBiayaGraderDetail']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TBiayaGraderDetail']['tgl_akhir']):"";
		$model->biaya_grader_kode = $_GET['TBiayaGraderDetail']['biaya_grader_kode'];
		$model->status = $_GET['TBiayaGraderDetail']['status'];
		$paramprint['judul'] = Yii::t('app', 'Laporan Biaya Grader');
		if((!empty($model->tgl_awal)) && (!empty($model->tgl_akhir))){
			$paramprint['judul2'] = "Periode Tanggal ". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir);
		}
		if($caraprint == 'PRINT'){
			return $this->renderPartial('/laporan/biayaGrader/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->renderPartial('/laporan/biayaGrader/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->renderPartial('/laporan/biayaGrader/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionKontraklog(){
		$model = new \app\models\TLogKontrak();
        $model->tgl_awal = date("01/m/Y");
        $model->tgl_akhir = date("d/m/Y");
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TLogKontrak'];
				$model->tgl_awal = $form_params['TLogKontrak']['tgl_awal'];
				$model->tgl_akhir = $form_params['TLogKontrak']['tgl_akhir'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('kontraklog/index',['model'=>$model]);
	}

	public function actionKontraklogPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TLogKontrak();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes = $_GET['TLogKontrak'];
		$model->tgl_awal = !empty($_GET['TLogKontrak']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TLogKontrak']['tgl_awal']):"";
		$model->tgl_akhir = !empty($_GET['TLogKontrak']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TLogKontrak']['tgl_akhir']):"";
		$paramprint['judul'] = Yii::t('app', 'Laporan Kontrak Log');
		if((!empty($model->tgl_awal)) && (!empty($model->tgl_akhir))){
			$paramprint['judul2'] = "Periode Tanggal ". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir);
		}
		if($caraprint == 'PRINT'){
			return $this->renderPartial('/laporan/kontraklog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->renderPartial('/laporan/kontraklog/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->renderPartial('/laporan/kontraklog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionMonitoringPembelianLog(){
		$model = new \app\models\TPengajuanPembelianlog();
        $model->tgl_awal = date("01/m/Y");
        $model->tgl_akhir = date("d/m/Y");
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TPengajuanPembelianlog'];
				$model->tgl_awal = $form_params['TPengajuanPembelianlog']['tgl_awal'];
				$model->tgl_akhir = $form_params['TPengajuanPembelianlog']['tgl_akhir'];
				$model->suplier_id = $form_params['TPengajuanPembelianlog']['suplier_id'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('monitoringPembelianLog/index',['model'=>$model]);
	}

	public function actionMonitoringPembelianLogPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TPengajuanPembelianlog();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes = $_GET['TPengajuanPembelianlog'];
		$model->tgl_awal = !empty($_GET['TPengajuanPembelianlog']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TPengajuanPembelianlog']['tgl_awal']):"";
		$model->tgl_akhir = !empty($_GET['TPengajuanPembelianlog']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TPengajuanPembelianlog']['tgl_akhir']):"";
		$model->suplier_id = $_GET['TPengajuanPembelianlog']['suplier_id'];
		$paramprint['judul'] = Yii::t('app', 'Monitoring Pembelian Log');
		if((!empty($model->tgl_awal)) && (!empty($model->tgl_akhir))){
			$paramprint['judul2'] = "Periode Tanggal ". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir);
		}
		if($caraprint == 'PRINT'){
			return $this->renderPartial('/laporan/monitoringPembelianLog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->renderPartial('/laporan/monitoringPembelianLog/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->renderPartial('/laporan/monitoringPembelianLog/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
	public function actionMonitoringPembelianLogDetail($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPengajuanPembelianlog::findOne($id);
			$modMonitoring = \app\models\TMonitoringPembelianlog::find()->where("pengajuan_pembelianlog_id = ".$model->pengajuan_pembelianlog_id)->all();
			return $this->renderAjax('/laporan/monitoringPembelianLog/detailMonitoring',['model'=>$model,'modMonitoring'=>$modMonitoring]);
        }
    }

    public function actionLoglistDetail(){
		$model = new \app\models\TLoglistDetail();
        $model->tgl_awal = date("01/m/Y");
        $model->tgl_akhir = date("d/m/Y");
        if(\Yii::$app->request->get('dt')=='table-laporan'){
			if((\Yii::$app->request->get('laporan_params')) !== null){
				$form_params = []; parse_str(\Yii::$app->request->get('laporan_params'),$form_params); 
				$model->attributes = $form_params['TLoglistDetail'];
				$model->tgl_awal = $form_params['TLoglistDetail']['tgl_awal'];
				$model->tgl_akhir = $form_params['TLoglistDetail']['tgl_akhir'];
			}
			return \yii\helpers\Json::encode(\app\components\SSP::complex( $model->searchLaporanDt() ));
		}
		return $this->render('loglistDetail/index',['model'=>$model]);
	}

	public function actionLoglistPrint(){
		$this->layout = '@views/layouts/metronic/print';
		$model = new \app\models\TLoglistDetail();
		$caraprint = Yii::$app->request->get('caraprint');
		$model->attributes = $_GET['TLoglistDetail'];
		$model->tgl_awal = !empty($_GET['TLoglistDetail']['tgl_awal'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TLoglistDetail']['tgl_awal']):"";
		$model->tgl_akhir = !empty($_GET['TLoglistDetail']['tgl_akhir'])?\app\components\DeltaFormatter::formatDateTimeForDb($_GET['TLoglistDetail']['tgl_akhir']):"";
		$paramprint['judul'] = Yii::t('app', 'Laporan Loglist');
		if((!empty($model->tgl_awal)) && (!empty($model->tgl_akhir))){
			$paramprint['judul2'] = "Periode Tanggal ". \app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser($model->tgl_akhir);
		}
		if($caraprint == 'PRINT'){
			return $this->renderPartial('/laporan/loglistDetail/print',['model'=>$model,'paramprint'=>$paramprint]);
		}else if($caraprint == 'PDF'){
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->renderPartial('/laporan/loglistDetail/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->renderPartial('/laporan/loglistDetail/print',['model'=>$model,'paramprint'=>$paramprint]);
		}
	}
	
}
