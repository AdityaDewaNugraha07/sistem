<?php

namespace app\modules\finance\controllers;

use Yii;
use app\controllers\DeltaBaseController;

class PiutangpenjualanController extends DeltaBaseController
{
	public $defaultAction = 'index';
	public function actionIndex(){
        $model = new \app\models\TPiutangPenjualan();
		return $this->render('index',['model'=>$model]);
	}
	
	public function actionFindCustomerPiutang(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT m_customer.cust_id, m_customer.cust_an_nama FROM m_customer
					JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = m_customer.cust_id
					WHERE cust_an_nama ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					GROUP BY m_customer.cust_id, m_customer.cust_an_nama 
					ORDER BY m_customer.cust_an_nama";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['cust_id'], 'text'=>$val['cust_an_nama']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionSetDropdownCustPiutang(){
		if(\Yii::$app->request->isAjax){
			$dept_id = Yii::$app->request->post('dept_id');
            $data['html'] = [];
            if(!empty($dept_id)){
                $query = "
					SELECT m_customer.cust_id, m_customer.cust_an_nama FROM m_customer
					JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = m_customer.cust_id
					WHERE cust_an_nama ilike '%{$term}%' AND cancel_transaksi_id IS NULL
					GROUP BY m_customer.cust_id, m_customer.cust_an_nama 
					ORDER BY m_customer.cust_an_nama";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
                if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'cust_id', 'cust_an_nama');
					$html = \yii\bootstrap\Html::tag('option');
					foreach($arraymap as $i => $val){
						$html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i,]);
					}
                    $data['html'] = $html;
                }
            }
			return $this->asJson($data);
		}
	}
	
	public function actionGetAll(){
		if(\Yii::$app->request->isAjax){
			$cust_id = Yii::$app->request->post('cust_id');
			$is_export = Yii::$app->request->post('is_export');
			$data = [];
			$data['htmlnota'] = '';
			$data['htmlbayar'] = '';
			if($cust_id){
				if($is_export == 'true'){
                    if($this->updateTotalBayarAllByCust($cust_id)){
                        $models = \app\models\TInvoice::find()
                                ->where("cust_id = ".$cust_id." AND cancel_transaksi_id IS NULL AND piutang_active IS TRUE AND peb_tanggal IS NOT NULL")
                                ->orderBy("created_at DESC")->all();
                        $htmlnota = "_contentInvoice";
                        $bill_reff = \yii\helpers\ArrayHelper::getColumn($models, 'nomor');
                    }
				}else{
					$models = \app\models\TNotaPenjualan::find()
							->where("cust_id = ".$cust_id." AND cancel_transaksi_id IS NULL")
							->orderBy("created_at DESC")->all();
                    $htmlnota = "_contentNota";
                    $bill_reff = \yii\helpers\ArrayHelper::getColumn($models, 'kode');
				}
				if(count($models)>0){
					$data['htmlnota'] .= $this->renderPartial($htmlnota,['models'=>$models]);
					foreach($bill_reff as $i => $bill){
						$bill_reff[$i] = "'".$bill."'";
					}
					$bill_reff = implode(",", $bill_reff);
					$modPiutangs = \app\models\TPiutangPenjualan::find()->where("bill_reff IN(".$bill_reff.") AND cancel_transaksi_id IS NULL")->orderBy("created_at DESC")->all();
					$data['htmlbayar'] .= $this->renderPartial('_contentPembayaran',['modPiutangs'=>$modPiutangs,'cust_id'=>$cust_id,'is_export'=>$is_export]);
				}
			}
			return $this->asJson($data);
		}
	}
    
    public function UpdateTotalBayarAllByCust($cust_id){
		$done = true;
        $models = \app\models\TInvoice::find()->where("cust_id = ".$cust_id." AND status = 'UNPAID' AND cancel_transaksi_id IS NULL AND piutang_active IS TRUE AND peb_tanggal IS NOT NULL")->all();
        if(count($models)>0){
            foreach($models as $i => $mod){
                $totInduk = $mod->total_bayar;
                $modInvDetail = Yii::$app->db->createCommand("SELECT keterangan, harga_jual, harga_jual*SUM(ROUND(kubikasi_display::numeric,4)) AS subtotal
                                                            FROM t_invoice_detail where invoice_id = ".$mod->invoice_id."
                                                            GROUP BY 1,2 ")->queryAll();
                $totDetail = 0;
                if(count($modInvDetail)>0){
                    foreach($modInvDetail as $i => $det){
                        $xcvxcv = 0;
                        if(strlen(substr(strrchr($det['subtotal'], "."), 1)) > 2){
                            
                            // START KEBIJAKAN Perubahan Pembulatan dari Round Up ke Round Per tgl 14 Sept 2019
                            $tgl = date('Y-m-d', strtotime(\app\components\DeltaFormatter::formatDateTimeForDb($mod->tanggal)));
                            $tgl_kebijakan = date('Y-m-d', strtotime("2019-09-13"));
                            if( $tgl > $tgl_kebijakan ){
                                $xcvxcv = $det['subtotal'];
                            }else{
                                $xcvxcv = \app\components\DeltaFormatter::roundUp($det['subtotal'], 2);
                            }
                            // END KEBIJAKAN
                        }else{
                            $xcvxcv = $det['subtotal'];
                        }
                        $totDetail += $xcvxcv;
                    }
                }
                if($totInduk != $totDetail){
                    $mod->total_harga = $totDetail;
                    $mod->total_bayar = $totDetail;
                    if($mod->validate()){
                        $done &= $mod->save();
                    }
                }
            }
        }
        return $done;
	}
	
	public function actionOpenCustomerPiutang(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-customer'){
				$param['table'] = \app\models\MCustomer::tableName();
				$param['pk']= $param['table'].".".\app\models\MCustomer::primaryKey()[0];
				$param['column'] = [$param['table'].'.cust_id','cust_an_nama','cust_pr_nama','cust_an_alamat','cust_max_plafond',
                                                    'COALESCE(SUM(t_nota_penjualan.total_bayar),0) - COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0) AS piutang'];
				$param['join'] = ['JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = m_customer.cust_id'];
				$param['where'] = "cancel_transaksi_id IS NULL";
				$param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
				$param['order'] = "ORDER BY m_customer.cust_an_nama";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('customerPiutang',['actionname'=>'openCustomerPiutang']);
		}
	}
	
        public function actionOpenCustomerPiutang2(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-customer'){
				$param['table'] = \app\models\MCustomer::tableName();
				$param['pk']= $param['table'].".".\app\models\MCustomer::primaryKey()[0];
				$param['column'] = [$param['table'].'.cust_id','cust_an_nama','cust_pr_nama','cust_an_alamat','cust_max_plafond',
                                                        'COALESCE(SUM(t_nota_penjualan.total_bayar),0) - COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0) AS piutang'
                                                    ];
				$param['join'] = ['JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = m_customer.cust_id'];
				$param['where'] = "cancel_transaksi_id IS NULL";
				$param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
                                $param['having'] = "having ( COALESCE(SUM(t_nota_penjualan.total_bayar),0)- COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0))<>0";
				$param['order'] = "ORDER BY m_customer.cust_an_nama";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('customerPiutang',['actionname'=>'openCustomerPiutang']);
		}
	}
        
	public function actionOpenCustomerPiutangExport(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-customer'){
				$param['table'] = \app\models\MCustomer::tableName();
				$param['pk']= $param['table'].".".\app\models\MCustomer::primaryKey()[0];
				$param['column'] = [$param['table'].'.cust_id','cust_an_nama','cust_pr_nama','cust_an_alamat','cust_max_plafond',
									'COALESCE(SUM(t_invoice.total_bayar)-COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0),0) AS piutang'];
				$param['join'] = ['JOIN t_invoice ON t_invoice.cust_id = m_customer.cust_id'];
				$param['where'] = "cancel_transaksi_id IS NULL AND cust_tipe_penjualan = 'export'";
				$param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
				$param['order'] = "ORDER BY m_customer.cust_an_nama";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('customerPiutang',['actionname'=>'openCustomerPiutangExport']);
		}
	}
	
        public function actionOpenCustomerPiutangExport2(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-customer'){
				$param['table'] = \app\models\MCustomer::tableName();
				$param['pk']= $param['table'].".".\app\models\MCustomer::primaryKey()[0];
				$param['column'] = [$param['table'].'.cust_id','cust_an_nama','cust_pr_nama','cust_an_alamat','cust_max_plafond',
                                                        'COALESCE(SUM(t_invoice.total_bayar),0) - COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0) AS piutang'];
				$param['join'] = ['JOIN t_invoice ON t_invoice.cust_id = m_customer.cust_id'];
				$param['where'] = "cancel_transaksi_id IS NULL AND cust_tipe_penjualan = 'export'";
				$param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
                                $param['having'] = "having ( COALESCE(SUM(t_invoice.total_bayar),0) - COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0))<>0";
				
				$param['order'] = "ORDER BY m_customer.cust_an_nama";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('customerPiutang',['actionname'=>'openCustomerPiutangExport']);
		}
	}
        
	public function actionSetPembayaran(){
		if(\Yii::$app->request->isAjax){
			$bill_reff = Yii::$app->request->post('bill_reff');
			$data = [];
			$data['html'] = "";
			$data['model'] = [];
			if($bill_reff){
				$modNota = \app\models\TNotaPenjualan::findOne(['kode'=>$bill_reff]);
				$cekTerbayar = \app\models\TPiutangPenjualan::find()->where("bill_reff = '".$modNota->kode."' AND cancel_transaksi_id IS NULL")->all();
				$sisapiutang = $modNota->total_bayar;
				if(count($cekTerbayar)>0){
					$totalterbayar = 0;
					foreach($cekTerbayar as $i => $terbayar){
						$totalterbayar += $terbayar->total_bayar;
					}
					$sisapiutang = $modNota->total_bayar - $totalterbayar;
				}
				$data['html'] = $this->renderPartial('_contentPembayaran_old',['modNota'=>$modNota,'sisapiutang'=>$sisapiutang]);
				$data['modNota'] = $modNota->attributes;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionNewBayar(){
		if(\Yii::$app->request->isAjax){
			$cust_id = Yii::$app->request->get('cust_id');
			$modCust = \app\models\MCustomer::findOne($cust_id);
			$model = new \app\models\TPiutangPenjualan();
			$model->tipe = $modCust->cust_tipe_penjualan;
			$model->cust_id = $modCust->cust_id;
			$model->cust_an_nama = $modCust->cust_an_nama;
			$model->tanggal = date('Y-m-d');
			$model->nominal_bill = 0;
			$model->nominal_terbayar = 0;
			$model->tagihan = 0;
			$model->custtop_top = 0;
			$model->nominal_terima = 0;
			$model->bayar = 0;
			if( Yii::$app->request->post('TPiutangPenjualan')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false; // t_nota_penjualan
					$model = new \app\models\TPiutangPenjualan();
					$model->load(\Yii::$app->request->post());
					$model->kode = \app\components\DeltaGenerator::kodePPC();
					$model->status_bayar = "PAID";
					$model->sisa = $model->tagihan - $model->bayar;
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                            if($model->tipe == "lokal"){
                                $total_tagihan = \app\models\TNotaPenjualan::findOne(['kode'=>$model->bill_reff]);
                            }elseif($model->tipe == "export"){
                                $total_tagihan = \app\models\TInvoice::findOne(['nomor'=>$model->bill_reff]);
                            }
							$total_tagihan = $total_tagihan->total_bayar;
							$total_terbayar = \Yii::$app->db->createCommand("SELECT SUM(bayar) AS terbayar FROM t_piutang_penjualan WHERE bill_reff = '{$model->bill_reff}' AND cancel_transaksi_id IS NULL")->queryOne();
							$total_terbayar = (!empty($total_terbayar))? $total_terbayar['terbayar']:0;
							if($total_terbayar <= 0){
								$status = "UNPAID";
							}else if($total_terbayar < $total_tagihan){
								$status = "PARTIALLY";
							}else if($total_terbayar >= $total_tagihan){
								$status = "PAID";
							}
                            if($model->tipe == "lokal"){
                                $success_2 = \app\models\TNotaPenjualan::updateStatusPembayaran($model->bill_reff, $status);
                            }elseif($model->tipe == "export"){
                                $success_2 = \app\models\TInvoice::updateStatusPembayaran($model->bill_reff, $status);
                            }
                        }
                    }else{
                        $data['message_validate']=\yii\widgets\ActiveForm::validate($model); 
                    }
//					echo "<pre>";
//					print_r($success_1);
//					echo "<pre>";
//					print_r($success_2);
//					exit;
                    if ($success_1 && $success_2) {
                        $transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', \app\components\Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('newBayar',['model'=>$model,'modCust'=>$modCust]);
		}
	}
	
	public function actionSetNota(){
		if(\Yii::$app->request->isAjax){
			$bill_reff = Yii::$app->request->post('bill_reff');
			$data = [];
			if(!empty($bill_reff)){
				$query = "SELECT * FROM t_nota_penjualan
						JOIN t_op_ko ON t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
						WHERE t_nota_penjualan.kode = '{$bill_reff}'";
				$data['nota'] = Yii::$app->db->createCommand($query)->queryOne();
				if(!empty($data['nota'])){
					$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$data['nota']['op_ko_id']]);
					$data['nota']['nominal_bill'] = \app\components\DeltaFormatter::formatNumberForUserFloat($data['nota']['total_bayar']);
					$terbayar = Yii::$app->db->createCommand("SELECT SUM(bayar) AS bayar FROM t_piutang_penjualan WHERE bill_reff ='{$bill_reff}' AND cancel_transaksi_id IS NULL")->queryOne();
					$terbayar = (!empty($terbayar['bayar']))? $terbayar['bayar']:0;
					$data['nota']['pernah_terbayar'] = \app\components\DeltaFormatter::formatNumberForUserFloat($terbayar);
					$data['nota']['tagihan'] = \app\components\DeltaFormatter::formatNumberForUserFloat($data['nota']['total_bayar']-$terbayar);
					$data['nota']['custtop_top'] = !empty($modTempo->top_hari)?$modTempo->top_hari:0;
				}
			}
            return $this->asJson($data);
        }
	}
    
	public function actionSetInvoice(){
		if(\Yii::$app->request->isAjax){
			$bill_reff = Yii::$app->request->post('bill_reff');
			$data = [];
			if(!empty($bill_reff)){
				$query = "SELECT * FROM t_invoice WHERE t_invoice.nomor = '{$bill_reff}' AND piutang_active IS TRUE";
				$data['invoice'] = Yii::$app->db->createCommand($query)->queryOne();
				if(!empty($data['invoice'])){
					$data['invoice']['nominal_bill'] = \app\components\DeltaFormatter::formatNumberForUserFloat($data['invoice']['total_bayar']);
					$terbayar = Yii::$app->db->createCommand("SELECT SUM(bayar) AS bayar FROM t_piutang_penjualan WHERE bill_reff ='{$bill_reff}' AND cancel_transaksi_id IS NULL")->queryOne();
					$terbayar = (!empty($terbayar['bayar']))? $terbayar['bayar']:0;
					$data['invoice']['pernah_terbayar'] = \app\components\DeltaFormatter::formatNumberForUserFloat($terbayar);
					$data['invoice']['tagihan'] = \app\components\DeltaFormatter::formatNumberForUserFloat($data['invoice']['total_bayar']-$terbayar);
                    $data['invoice']['custtop_top'] = 0;
				}
			}
            return $this->asJson($data);
        }
	}
	
	public function actionFindVoucherPenerimaan(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$tipe = Yii::$app->request->get('tipe');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT t_voucher_penerimaan.kode FROM t_voucher_penerimaan
					WHERE t_voucher_penerimaan.kode ilike '%{$term}%' AND t_voucher_penerimaan.cancel_transaksi_id IS NULL 
						AND t_voucher_penerimaan.kode NOT IN ( SELECT payment_reff FROM t_piutang_penjualan WHERE cara_bayar = 'Transfer' )
                        AND mata_uang = '{$tipe}'
					GROUP BY t_voucher_penerimaan.kode";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['kode'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	public function actionFindKasBesar(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT h_saldo_kasbesar.kode FROM h_saldo_kasbesar
					WHERE h_saldo_kasbesar.kode ilike '%{$term}%' 
						AND h_saldo_kasbesar.kode NOT IN ( SELECT payment_reff FROM t_piutang_penjualan WHERE cara_bayar = 'Cash' )
					GROUP BY h_saldo_kasbesar.kode";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['kode'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	public function actionFindLPG(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT t_kas_besar_nontunai.kode FROM t_kas_besar_nontunai
					WHERE t_kas_besar_nontunai.kode ilike '%{$term}%' AND t_kas_besar_nontunai.cancel_transaksi_id IS NULL AND closing IS TRUE
						AND t_kas_besar_nontunai.kode NOT IN ( SELECT payment_reff FROM t_piutang_penjualan WHERE cara_bayar = 'BgCek' )
					GROUP BY t_kas_besar_nontunai.kode";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['kode'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}
	public function actionFindRetur(){
		if(\Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT t_retur_produk.kode FROM t_retur_produk
					WHERE t_retur_produk.kode ilike '%{$term}%' AND t_retur_produk.cancel_transaksi_id IS NULL
						AND t_retur_produk.kode NOT IN ( SELECT payment_reff FROM t_piutang_penjualan WHERE cara_bayar = 'Retur' )
					GROUP BY t_retur_produk.kode";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['kode'], 'text'=>$val['kode']];
					}
				}
			}
            return $this->asJson($data);
        }
	}

	public function actionOpenVoucher(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-info'){
				$param['table'] = \app\models\TVoucherPenerimaan::tableName();
				$param['pk']= $param['table'].".".\app\models\TVoucherPenerimaan::primaryKey()[0];
				$param['column'] = [$param['table'].'.voucher_penerimaan_id',
									$param['table'].'.tipe',
									$param['table'].'.kode',
									$param['table'].'.kode_bbm',
								   ['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],
									'm_acct_rekening.acct_nm',
									'sender',
									'deskripsi',
									$param['table'].'.mata_uang',
									$param['table'].'.total_nominal',
                                    "COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE payment_reff = t_voucher_penerimaan.kode),0) AS terpakai",
									"((t_voucher_penerimaan.total_nominal)-COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE payment_reff = t_voucher_penerimaan.kode),0)) AS sisa",
									];
				$param['join']= ['JOIN m_acct_rekening ON m_acct_rekening.acct_id = '.$param['table'].'.akun_kredit '];
//				$param['where'] = "cancel_transaksi_id IS NULL AND t_voucher_penerimaan.kode NOT IN ( SELECT payment_reff FROM t_piutang_penjualan WHERE cara_bayar = 'Transfer' )";
				$param['where'] = "cancel_transaksi_id IS NULL AND mata_uang = 'IDR'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('listVoucher',['action'=>'OpenVoucher']);
		}
	}
	public function actionOpenVoucherDollar(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-info'){
				$param['table'] = \app\models\TVoucherPenerimaan::tableName();
				$param['pk']= $param['table'].".".\app\models\TVoucherPenerimaan::primaryKey()[0];
				$param['column'] = [$param['table'].'.voucher_penerimaan_id',
									$param['table'].'.tipe',
									$param['table'].'.kode',
									$param['table'].'.kode_bbm',
								   ['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],
									'm_acct_rekening.acct_nm',
									'sender',
									'deskripsi',
									$param['table'].'.mata_uang',
									$param['table'].'.total_nominal',
									"(SELECT SUM(bayar) FROM t_piutang_penjualan WHERE payment_reff = t_voucher_penerimaan.kode) AS terpakai",
									"((t_voucher_penerimaan.total_nominal)-COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE payment_reff = t_voucher_penerimaan.kode),0)) AS sisa",
									];
				$param['join']= ['JOIN m_acct_rekening ON m_acct_rekening.acct_id = '.$param['table'].'.akun_kredit'];
				$param['where'] = "cancel_transaksi_id IS NULL AND mata_uang = 'USD'";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('listVoucher',['action'=>'OpenVoucherDollar']);
		}
	}
	public function actionOpenKasbesar(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-info'){
				$param['table'] = \app\models\HSaldoKasbesar::tableName();
				$param['pk']= $param['table'].".".\app\models\HSaldoKasbesar::primaryKey()[0];
				$param['column'] = [$param['table'].'.saldo_kasbesar_id',
									"CONCAT(h_saldo_kasbesar.kode,'/',EXTRACT(year FROM h_saldo_kasbesar.tanggal)) AS kode",
								   ['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],
									$param['table'].'.deskripsi',
									$param['table'].'.debit',
									];
//				$param['where'] = "h_saldo_kasbesar.kode NOT IN ( SELECT payment_reff FROM t_piutang_penjualan WHERE cara_bayar = 'Cash' )";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('listKasbesar');
		}
	}
	public function actionOpenBgcek(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-info'){
				$param['table'] = \app\models\TKasBesarNontunai::tableName();
				$param['pk']= $param['table'].".".\app\models\TKasBesarNontunai::primaryKey()[0];
				$param['column'] = [$param['table'].'.kas_besar_nontunai_id',
									$param['table'].'.kode',
								   ['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],
									$param['table'].'.nama_customer',
									$param['table'].'.cust_bank',
									$param['table'].'.cust_acct',
									$param['table'].'.reff_number',
									$param['table'].'.nominal',
									];
				$param['where'] = "t_kas_besar_nontunai.cancel_transaksi_id IS NULL AND t_kas_besar_nontunai.closing IS TRUE";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('listBgcek');
		}
	}
	public function actionOpenRetur(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='table-info'){
				$param['table'] = \app\models\TReturProduk::tableName();
				$param['pk']= $param['table'].".".\app\models\TReturProduk::primaryKey()[0];
				$param['column'] = [$param['table'].'.retur_produk_id',
									$param['table'].'.kode',
								   ['col_name'=>$param['table'].'.tanggal','formatter'=>'formatDateForUser2'],
									't_nota_penjualan.kode AS kode_nota',
									'm_customer.cust_an_nama',
									$param['table'].'.alasan_retur',
									'total_retur'
									];
				$param['join'] = "JOIN t_nota_penjualan ON t_nota_penjualan.nota_penjualan_id = t_retur_produk.nota_penjualan_id
								  JOIN m_customer ON t_retur_produk.cust_id = m_customer.cust_id";
				$param['where'] = "t_retur_produk.cancel_transaksi_id IS NULL";
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('listRetur');
		}
	}
	
	public function actionSetPaymentReff(){
		if(\Yii::$app->request->isAjax){
			$payment_reff = Yii::$app->request->post('payment_reff');
			$cara_bayar = Yii::$app->request->post('cara_bayar');
			$data = []; $data['nominal_terima'] = 0; $data['mata_uang'] = "IDR"; $data['tanggal_bayar'] = ""; $data['nominal_terpakai']=0;
			if($payment_reff){
				if($cara_bayar=="Transfer"){
					$mod = \app\models\TVoucherPenerimaan::findOne(['kode'=>$payment_reff]);
					$data['nominal_terima'] = $mod->total_nominal;
					$data['mata_uang'] = $mod->mata_uang;
					$data['tanggal_bayar'] = $mod->tanggal;
				}else if($cara_bayar=="Cash"){
                    $dat_kb = explode("/", $payment_reff);
					$mod = \app\models\HSaldoKasbesar::find()->where("kode = '{$dat_kb[0]}' AND EXTRACT(year FROM tanggal) = '{$dat_kb[1]}'")->one();
					$data['nominal_terima'] = $mod->debit;
					$data['tanggal_bayar'] = substr($mod->tanggal, 0,10);
				}else if($cara_bayar=="BgCek"){
					$bgCekParam = explode("-", $payment_reff);
					if(count($bgCekParam)>1){
						$mod = \app\models\TKasBesarNontunai::find()->where(['kode'=>$bgCekParam[0],'reff_number'=>$bgCekParam[1]])->andWhere("closing IS TRUE")->one();
						$data['nominal_terima'] = $mod->nominal;
						$data['tanggal_bayar'] = $mod->tanggal;
					}
				}else if($cara_bayar=="Retur"){
					$mod = \app\models\TReturProduk::findOne(['kode'=>$payment_reff]);
					$data['nominal_terima'] = $mod->total_retur;
					$data['tanggal_bayar'] = $mod->tanggal;
				}
				$terpakai = Yii::$app->db->createCommand("SELECT SUM(bayar) AS terpakai FROM t_piutang_penjualan WHERE payment_reff ='{$payment_reff}' AND cancel_transaksi_id IS NULL")->queryOne();
				$data['nominal_terpakai'] = (!empty($terpakai['terpakai']))? $terpakai['terpakai']:0;
			}
			return $this->asJson($data);
		}
	}
	
	public function actionDeletePiutang($id){
		if(\Yii::$app->request->isAjax){
			$model = \app\models\TPiutangPenjualan::findOne($id);
            if( Yii::$app->request->post('deleteRecord')){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $success_1 = false;
                    $success_2 = false; // t_nota_penjualan / t_invoice
                    if(strpos($model->bill_reff, "CWM") === FALSE) {
                        $modTagihan = \app\models\TNotaPenjualan::findOne(['kode'=>$model->bill_reff]);
                    }else{
                        $modTagihan = \app\models\TInvoice::findOne(['nomor'=>$model->bill_reff]);
                    }
					
					if($model->delete()){
						$success_1 = true;
						$total_tagihan = $modTagihan->total_bayar;
						$total_terbayar = \Yii::$app->db->createCommand("SELECT SUM(bayar) AS terbayar FROM t_piutang_penjualan WHERE bill_reff = '{$model->bill_reff}' AND cancel_transaksi_id IS NULL")->queryOne();
						$total_terbayar = (!empty($total_terbayar))? $total_terbayar['terbayar']:0;
						if($total_terbayar <= 0){
							$status = "UNPAID";
						}else if($total_terbayar < $total_tagihan){
							$status = "PARTIALLY";
						}else if($total_terbayar >= $total_tagihan){
							$status = "PAID";
						}
                        if(strpos($model->bill_reff, "CWM") === FALSE) {
                            $success_2 = \app\models\TNotaPenjualan::updateStatusPembayaran($model->bill_reff, $status);
                        }else{
                            $success_2 = \app\models\TInvoice::updateStatusPembayaran($model->bill_reff, $status);
                        }
					}else{
						$data['message'] = Yii::t('app', 'Data Gagal dihapus');
					}
					if ($success_1 && $success_2) {
						$transaction->commit();
						$data['status'] = true;
						$data['message'] = Yii::t('app', '<i class="icon-check"></i> Data Berhasil Dihapus');
						$data['callback'] = "getAll();";
					} else {
						$transaction->rollback();
						$data['status'] = false;
						(!isset($data['message']) ? $data['message'] = Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
						(isset($data['message_validate']) ? $data['message'] = null : '');
					}
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'actionname'=>'deletePiutang']);
		}
	}
	
	public function actionShowCatatan(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->get('piutang_penjualan_id');
			$model = \app\models\TPiutangPenjualan::findOne($id);
			return $this->renderAjax('showCatatan',['model'=>$model]);
		}
	}
	public function actionShowCatatan2(){
		if(\Yii::$app->request->isAjax){
			$id = Yii::$app->request->get('piutang_penjualan_id');
			$model = \app\models\TPiutangPenjualan::findOne($id);
			return $this->renderAjax('showCatatan2',['model'=>$model]);
		}
	}
	public function actionSetHighlightListVoucher(){
		if(\Yii::$app->request->isAjax){
            $tipe = Yii::$app->request->post('tipe');
            if($tipe == "OpenVoucherDollar"){
                $tipe = "USD";
            }else{
                $tipe = "IDR";
            }
			$modVoucher = \app\models\TVoucherPenerimaan::find()->where("cancel_transaksi_id IS NULL")->all();
			if(count($modVoucher)>0){
				$data = [];
				foreach($modVoucher as $i => $voucher){
					$sql = "SELECT * FROM t_piutang_penjualan
							JOIN t_voucher_penerimaan ON t_piutang_penjualan.payment_reff = t_voucher_penerimaan.kode
							WHERE t_voucher_penerimaan.voucher_penerimaan_id = ".$voucher->voucher_penerimaan_id." AND t_voucher_penerimaan.mata_uang = '{$tipe}'";
					$modPiutang = Yii::$app->db->createCommand($sql)->queryAll();
					$terpakai = 0;
					if(count($modPiutang)>0){
						foreach($modPiutang as $ii => $piutang){
							$terpakai += $piutang['bayar'];
						}
						if($terpakai > 0){
							$data[$voucher->voucher_penerimaan_id] = $voucher->total_nominal - $terpakai;
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	public function actionSetHighlightListKasbesar(){
		if(\Yii::$app->request->isAjax){
			$modKas = \app\models\HSaldoKasbesar::find()->all();
			if(count($modKas)>0){
				$data = [];
				foreach($modKas as $i => $kas){
                    $thn = date("Y", strtotime($kas['tanggal']));
					$sql = "SELECT * FROM t_piutang_penjualan
							JOIN h_saldo_kasbesar ON t_piutang_penjualan.payment_reff = h_saldo_kasbesar.kode AND TO_CHAR(t_piutang_penjualan.tanggal,'YYYY') = '".$thn."'
							WHERE h_saldo_kasbesar.saldo_kasbesar_id = ".$kas->saldo_kasbesar_id;
					$modPiutang = Yii::$app->db->createCommand($sql)->queryAll();
					$terpakai = 0;
					if(count($modPiutang)>0){
						foreach($modPiutang as $ii => $piutang){
							$terpakai += $piutang['bayar'];
						}
						if($terpakai > 0){
							$data[$kas->saldo_kasbesar_id] = $kas->debit - $terpakai;
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionSetHighlightListBegcek(){
		if(\Yii::$app->request->isAjax){
			$modKas = \app\models\TKasBesarNontunai::find()->all();
			if(count($modKas)>0){
				$data = [];
				foreach($modKas as $i => $kas){
					$sql = "SELECT * FROM t_piutang_penjualan
							JOIN t_kas_besar_nontunai ON substring(t_piutang_penjualan.payment_reff from 1 for 8) = t_kas_besar_nontunai.kode 
								AND substring(t_piutang_penjualan.payment_reff from 10 for 6) = t_kas_besar_nontunai.reff_number
							WHERE t_kas_besar_nontunai.kas_besar_nontunai_id = ".$kas->kas_besar_nontunai_id;
					$modPiutang = Yii::$app->db->createCommand($sql)->queryAll();
					$terpakai = 0;
					if(count($modPiutang)>0){
						foreach($modPiutang as $ii => $piutang){
							$terpakai += $piutang['bayar'];
						}
						if($terpakai > 0){
							$data[$kas->kas_besar_nontunai_id] = $kas->nominal - $terpakai;
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	public function actionSetHighlightListRetur(){
		if(\Yii::$app->request->isAjax){
			$modRetur = \app\models\TReturProduk::find()->where("cancel_transaksi_id IS NULL")->all();
			if(count($modRetur)>0){
				$data = [];
				foreach($modRetur as $i => $retur){
					$sql = "SELECT * FROM t_piutang_penjualan
							JOIN t_retur_produk ON t_piutang_penjualan.payment_reff = t_retur_produk.kode
							WHERE t_retur_produk.retur_produk_id = ".$retur->retur_produk_id;
					$modPiutang = Yii::$app->db->createCommand($sql)->queryAll();
					$terpakai = 0;
					if(count($modPiutang)>0){
						foreach($modPiutang as $ii => $piutang){
							$terpakai += $piutang['bayar'];
						}
						if($terpakai > 0){
							$data[$retur->retur_produk_id] = $retur->total_retur - $terpakai;
						}
					}
				}
			}
			return $this->asJson($data);
		}
	}
	
	public function actionSetCustomerPiutang(){
		if(\Yii::$app->request->isAjax){
			$isexport = Yii::$app->request->post('isexport');
			$data['html'] = "";
			$model = new \app\models\TPiutangPenjualan();
			if($isexport == 'true'){
				$data['placeholder'] = "Type Buyer Name";
				$data['html'] .= '<div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-5 control-label">'.Yii::t('app', 'Find Buyer').'</label>
									<div class="col-md-7">
										<span class="input-group-btn" style="width: 100%">
											'. \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', \app\models\TPiutangPenjualan::getOptionListCustPiutang(true),['class'=>'form-control select2','prompt'=>'','onchange'=>'getAll()','style'=>'width:100%;']).'
										</span>
										<span class="input-group-btn">
											<a class="btn btn-icon-only btn-default tooltips" onclick="openCustomerPiutang(\'export\');" data-original-title="List Buyer" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
										</span>
									</div>
								</div>';
			}else{
				$data['placeholder'] = "Ketik Nama Customer";
				$data['html'] .= '<div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-5 control-label">'.Yii::t('app', 'Cari Customer').'</label>
									<div class="col-md-7">
										<span class="input-group-btn" style="width: 100%">
											'. \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', \app\models\TPiutangPenjualan::getOptionListCustPiutang(false),['class'=>'form-control select2','prompt'=>'','onchange'=>'getAll()','style'=>'width:100%;']).'
										</span>
										<span class="input-group-btn">
											<a class="btn btn-icon-only btn-default tooltips" onclick="openCustomerPiutang(\'lokal\');" data-original-title="Daftar Customer Piutang" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
										</span>
									</div>
								</div>';
			}
			return $this->asJson($data);
		}
	}
}
