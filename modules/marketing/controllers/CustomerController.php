<?php

namespace app\modules\marketing\controllers;

use app\components\DeltaFormatter;
use app\components\DeltaGenerator;
use app\components\Params;
use app\components\SSP;
use app\models\HCustData;
use app\models\HCustomer;
use app\models\HCustTop;
use app\models\MCustomer;
use app\models\MCustTop;
use app\models\TApproval;
use Yii;
use app\controllers\DeltaBaseController;
use yii\base\Exception;
use yii\helpers\Json;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class CustomerController extends DeltaBaseController
{
	
	public $defaultAction = 'index';

    /**
     * @version 2022-02-17
     * @note cuma dirapikan
     * @return string
     */
	public function actionIndex()
    {
        if(Yii::$app->request->get('dt')=='table-customer'){
			$param['table']     = MCustomer::tableName();
			$param['pk']        = MCustomer::primaryKey()[0];
			$param['column']    = [ 'cust_id', 'cust_kode', 'cust_an_nama', 'cust_pr_nama', 'cust_an_alamat', $param['table'].'.status_approval', $param['table'].'.active', $param['table'].'.cust_max_plafond'];
			$param['where']     = "cust_tipe_penjualan = 'lokal'";
			return Json::encode(SSP::complex( $param ));
		}
		return $this->render('index');
	}

    /**
     * @version 2022-02-17
     * @note penyederhanaan logic khususnya pada if else untuk keterbacaan kode
     * @return string|void|Response
     * @throws Exception
     * @throws \yii\db\Exception
     */
	public function actionCreate()
    {
		if(Yii::$app->request->isAjax){
			$model                      = new MCustomer();
            $modCustTop                 = new MCustTop();
			$model->active              = true;
			$model->cust_tipe_penjualan = Params::DEFAULT_DESTINASI_PENJUALAN;
			$model->cust_tanggal_join   = date('d/m/Y');
			$model->cust_an_tgllahir    = date('d/m/Y');
			$model->cust_is_pkp         = 0;
            /**
             * approval 2 :
             * dirut (heryanto suwardi 22)
             * kadiv marketing (iwan sulistyo 19)
             */
            $model->by_kadiv            = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
            $model->by_dirut            = Params::DEFAULT_PEGAWAI_ID_ASENG;
            $model->status_approval     = 'Not Confirmed';
            $model->cust_max_plafond    = max($model->cust_max_plafond, 0);
            
            $model->kode_customer = trim(DeltaGenerator::kodeCustomer());
			if( Yii::$app->request->post('MCustomer')){
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_mcustomer          = false;
                    $success_mcusttop           = false;
//                    $success_hcusttop           = false;
                    $success_validate_ktp_npwp  = false;
                    $success_validate_photo     = false;
                    $success_upload_ktp         = false;
                    $success_upload_npwp        = false;
                    $success_upload_photo       = false;
                    $success_hcustdata          = false;

                    $model = new MCustomer();
                    $model->load(Yii::$app->request->post());
                    $model->cust_tanggal_join = (!empty($model->cust_tanggal_join) ? DeltaFormatter::formatDateTimeForDb($model->cust_tanggal_join) : '');
                    $model->file1               = UploadedFile::getInstance($model, 'cust_file_ktp');
                    $model->file2               = UploadedFile::getInstance($model, 'cust_file_npwp');
                    $model->file3               = UploadedFile::getInstance($model, 'cust_file_photo');
                    $model->by_kadiv            = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $model->by_dirut            = Params::DEFAULT_PEGAWAI_ID_ASENG;
                    $model->kode_customer       = trim(DeltaGenerator::kodeCustomer());
                    $model->status_approval     = 'Not Confirmed';

                    $str                = Yii::$app->getSecurity()->generateRandomString(4);
                    $time               = date('Ymd_His');
                    $dir_path           = Yii::$app->basePath.'/web/uploads/mkt/customer';

                    if(!is_dir($dir_path)){
                        mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                        mkdir($dir_path);
                    }

                    if (empty($model->file1) && empty($model->file2)) {
                        $data['message']    = Yii::t('app', 'Upload KTP DAN ATAU NPWP');
                    } else {
                        $success_validate_ktp_npwp  = true;
                        if(!empty($model->file1)) $model->cust_file_ktp     = "$time-ktp-$str.{$model->file1->extension}";
                        if(!empty($model->file2)) $model->cust_file_npwp    = "$time-npwp-$str.{$model->file2->extension}";
                    }

                    if (empty($model->file3)) {
                        $data['message']    = Yii::t('app', 'Upload FOTO');
                    } else {
                        $success_validate_photo     = true;
                        $model->cust_file_photo     = "$time-photo-$str.{$model->file3->extension}";
                    }

                    if($model->validate() && $model->save()){
                        if(!empty($model->file1)) $success_upload_ktp   = $model->file1->saveAs("$dir_path/$model->cust_file_ktp");
                        if(!empty($model->file2)) $success_upload_npwp  = $model->file2->saveAs("$dir_path/$model->cust_file_npwp");
                        if(!empty($model->file3)) $success_upload_photo = $model->file3->saveAs("$dir_path/$model->cust_file_photo");

                        $success_mcustomer  = true;
                        if(isset($_POST['MCustTop'])) foreach($_POST['MCustTop'] as $custtop){
                            $modCustTop             = new MCustTop();
                            $modCustTop->attributes = $custtop;
                            $modCustTop->cust_id    = $model->cust_id;
                            $modCustTop->active     = true;
                            if($modCustTop->validate() && $modCustTop->save()){
                                $success_mcusttop   = true;
                            }else{
                                $data['message']    = Yii::t('app', 'Data TOP Gagal di simpan');
                            }
                        }

                        // simpan di h_cust_data
                        $model_hcustdata                = new HCustData();
                        $model_hcustdata->attributes    = $model->attributes;
                        $model_hcustdata->cust_id       = $model->getPrimaryKey();
                        $success_hcustdata              = $model_hcustdata->save();
                    }else{
                        $validate   = ActiveForm::validate($model);

                        if(!isset($data['message'])) {
                            if(isset($validate['mcustomer-file1'])) {
                                $data['message'] = '<strong>Gagal Upload KTP: </strong></br>'. $validate['mcustomer-file1'][0];
                                goto end;
                            }
                            if(isset($validate['mcustomer-file2'])) {
                                $data['message'] = '<strong>Gagal Upload NPWP: </strong></br>'. $validate['mcustomer-file2'][0];
                                goto end;
                            }
                            if(isset($validate['mcustomer-file3'])) {
                                $data['message'] = '<strong>Gagal Upload FOTO: </strong></br>'. $validate['mcustomer-file3'][0];
                                goto end;
                            }

                        }
                        end:
                    }

                    // simpan di h_customer
//                    $model_hcustomer                        = new HCustomer();
//                    $model_hcustomer->cust_id               = $model->cust_id;
//                    $model_hcustomer->kode_customer         = $model->kode_customer;
//                    $model_hcustomer->cust_max_plafond_lama = $_POST['MCustomer']['cust_max_plafond_lama'];
//                    $model_hcustomer->cust_max_plafond      = $model->cust_max_plafond;
//                    $model_hcustomer->by_kadiv              = $model->by_kadiv;
//                    $model_hcustomer->by_dirut              = $model->by_dirut;
//                    $model_hcustomer->status_approval       = 'Not Confirmed';
//                    $model_hcustomer->created_at            = $model->created_at;
//                    $model_hcustomer->created_by            = $model->created_by;
//                    $model_hcustomer->cust_alamat           = empty($model->cust_pr_alamat) ? $model->cust_an_alamat : $model->cust_pr_alamat;
//                    $success_hcustomer                      = $model_hcustomer->save();

                    // simpan di h_cust_top
//                    foreach($_POST['MCustTop'] as $custtop){
//                        $hCustTop                   = new HCustTop();
//                        $hCustTop->attributes       = $custtop;
//                        $hCustTop->cust_id          = $model->cust_id;
//                        $hCustTop->kode_customer    = $model->kode_customer;
//
//                        if($hCustTop->validate() && $hCustTop->save()){
//                            $success_hcusttop   = true;
//                        }else{
//                            $data['message']    = Yii::t('app', 'Data HTOP Gagal di update');
//                        }
//                    }

                    // simpan di t_approval
                    //$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>trim($model->kode_customer)])->all();

                    // skrip dibawah sudah tidak digunakan karena user tidak bisa mengedit setelah mengajukan approval
                    /*if (count($modelApproval) > 0) { // edit mode
                        if(\app\models\TApproval::deleteAll(['reff_no'=>trim($model->kode_customer)])){
                            $success_3 = $this->saveApproval($model);
                        }
                        $success_3 = true;
                    // insert data ke tabel t_approval
                    } else { // insert mode
                        $success_3 = $this->saveApproval($model);
                    }*/
                    //

                    if (
                        $success_mcustomer                              &&
                        $success_mcusttop                               &&
//                        $success_hcusttop                               &&
//                        $success_hcustomer                              &&
                        $success_hcustdata                              &&
                        $success_validate_ktp_npwp                      &&
                        ($success_upload_ktp || $success_upload_npwp)   &&
                        $success_validate_photo                         &&
                        $success_upload_photo
                    ) {
                        $success_approval   = $this->saveApproval($model);
                        if ($success_approval) {
                            $transaction->commit();
                            $data['status']     = true;
                            $data['message']    = Yii::t('app', Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE);
                        } else {
                            $transaction->rollback();
                            $data['status']     = false;
                            $data['message']    = !isset($data['message']) ?  Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : $data['message'];
                        }

                     } else {
                        $transaction->rollback();
                        if(is_file("$dir_path/$model->cust_file_ktp"))      unlink("$dir_path/$model->cust_file_ktp");
                        if(is_file("$dir_path/$model->cust_file_npwp"))   unlink("$dir_path/$model->cust_file_npwp");
                        if(is_file("$dir_path/$model->cust_file_photo"))  unlink("$dir_path/$model->cust_file_photo");
                        $data['status']     = false;
                        $data['message']    = !isset($data['message']) ?  Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : $data['message'];
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['status']     = false;
                    $data['message']    = $ex;
                }
                return $this->asJson($data);
			}
			return $this->renderAjax('create',[ 'model' => $model, 'modCustTop' => $modCustTop ]);
		}
	}

    /**
     * @version 2022-02-17
     * @note cuma dirapikan
     * @param $id
     * @return string|void
     * @throws \yii\db\Exception
     */

    public function actionInfo($id)
    {
		if(Yii::$app->request->isAjax){
            $model                          = MCustomer::findOne($id);
            $cust_id                        = $model->cust_id;
            $sql_h_customer                 = "select cust_max_plafond from h_customer where cust_id = ".$cust_id." order by hcust_id desc ";
            $h_customer_cust_max_plafond    = Yii::$app->db->createCommand($sql_h_customer)->queryScalar();
            $h_customer_cust_max_plafond    = isset($h_customer_cust_max_plafond) ? $h_customer_cust_max_plafond : 0;
			return $this->renderAjax('info', ['model' => $model, 'h_customer_cust_max_plafond' => $h_customer_cust_max_plafond]);
		}
	}

    /**
     * @param $id
     * @param $tipe
     * @return string|void
     */

    public function actionImage($id, $tipe){
        if(Yii::$app->request->isAjax){
            $model = MCustomer::findOne($id);
            return $this->renderAjax('image',['model'=>$model, 'tipe'=>$tipe]);
        }
    }

    /**
     * @param $id
     * @return string|void|Response
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    
    public function actionEdit($id)
    {
        if(Yii::$app->request->isAjax){
            $model                      = MCustomer::findOne($id);
            $modCustTop                 = new MCustTop();
            $modCustTops                = MCustTop::find()->where(['active'=>TRUE,'cust_id'=>$model->cust_id])->all();
            $model->cust_is_pkp         = ($model->cust_is_pkp)?1:0;
            $model->cust_tanggal_join   = (!empty($model->cust_tanggal_join) ? DeltaFormatter::formatDateTimeForUser2($model->cust_tanggal_join) : '');
            $model->cust_max_plafond    = (!empty($model->cust_max_plafond) ? DeltaFormatter::formatNumberForUser($model->cust_max_plafond) : 0);

            if( Yii::$app->request->post('MCustomer')){
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success_update_mcustomer   = false;
                    $success_save_hcusttop      = false;
                    $success_save_approval      = false;
                    $success_hcustdata          = false;
                    $success_save_mcustomer     = false;
                    $success_save_hcustomer     = false;

                    $cust_file_ktp_old      = $model->cust_file_ktp;
                    $cust_file_npwp_old     = $model->cust_file_npwp;
                    $cust_file_photo_old    = $model->cust_file_photo;

                    $model->load(Yii::$app->request->post());
                    $model->cust_tanggal_join   = (!empty($model->cust_tanggal_join) ? DeltaFormatter::formatDateTimeForDb($model->cust_tanggal_join) : '');
                    $model->file1               = UploadedFile::getInstance($model, 'cust_file_ktp');
                    $model->file2               = UploadedFile::getInstance($model, 'cust_file_npwp');
                    $model->file3               = UploadedFile::getInstance($model, 'cust_file_photo');
                    $model->cust_file_ktp       = !empty($model->cust_file_ktp)?$model->cust_file_ktp:$cust_file_ktp_old;
                    $model->cust_file_npwp      = !empty($model->cust_file_npwp)?$model->cust_file_npwp:$cust_file_npwp_old;
                    $model->cust_file_photo     = !empty($model->cust_file_photo)?$model->cust_file_photo:$cust_file_photo_old;
                    $model->by_kadiv            = Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO;
                    $model->by_dirut            = Params::DEFAULT_PEGAWAI_ID_ASENG;

                    // m_customer.kode_customer / t_approval.reff_no
                    // plafon
                    $cust_max_plafond_lama  = $_POST['MCustomer']['cust_max_plafond_lama'];

                    // m_cust_top
                    if(isset($_POST['MCustTop'])){
                        $a = $_POST['MCustTopLama'];
                        $b = $_POST['MCustTop'];
                        $status_MCustTop = $a == $b ?  "sama" : "beda";
                    } else {
                        $status_MCustTop = "beda";
                    }

//                    $model->cust_max_plafond = $cust_max_plafond_lama != $cust_max_plafond ? $_POST['MCustomer']['cust_max_plafond'] : $model->cust_max_plafond;
                    // jika m_customer.cust_max_plafond atau m_cust_top tidak sama, maka insert ke h_customer dan h_cust_top
                    // Jika cust_max_plafond_lama tidak sama dengan cust_max_plafond yang baru maka minta approval cuy
                    if (($cust_max_plafond_lama != $model->cust_max_plafond) && $status_MCustTop == "sama"
                        || ($cust_max_plafond_lama == $model->cust_max_plafond) && $status_MCustTop == "beda"
                        ||  ($cust_max_plafond_lama != $model->cust_max_plafond) && $status_MCustTop == "beda" ) {

                        $model->kode_customer   = trim(DeltaGenerator::kodeCustomer());
                        $model->update();
                        $sql_update = "update m_customer ".
                                        "   set status_approval = 'Not Confirmed' ".
                                        "   , by_kadiv = $model->by_kadiv ".
                                        "   , by_dirut = $model->by_dirut ".
                                        "   , kode_customer = '$model->kode_customer' ".
                                        "   , updated_at = '$model->updated_at' ".
                                        "   , updated_by = $model->updated_by ".
                                        "   , cust_max_plafond = $cust_max_plafond_lama ".
                                        "   where cust_id = $model->cust_id ".
                                        "   ";
                        if(Yii::$app->db->createCommand($sql_update)->execute()){
                            $success_update_mcustomer = true;
                        }

                        // jika plafond berubah simpan di h_customer
                        if($cust_max_plafond_lama !== $model->cust_max_plafond) {
                            $model_hcustomer                        = new HCustomer();
                            $model_hcustomer->cust_id               = $id;
                            $model_hcustomer->kode_customer         = $model->kode_customer;
                            $model_hcustomer->cust_max_plafond_lama = $cust_max_plafond_lama;
                            $model_hcustomer->cust_max_plafond      = $model->cust_max_plafond;
                            $model_hcustomer->by_kadiv              = $model->by_kadiv;
                            $model_hcustomer->by_dirut              = $model->by_dirut;
                            $model_hcustomer->status_approval       = 'Not Confirmed';
                            $model_hcustomer->created_at            = $model->created_at;
                            $model_hcustomer->created_by            = $model->created_by;
                            $model_hcustomer->cust_alamat           = empty($model->cust_pr_alamat) ? $model->cust_an_alamat : $model->cust_pr_alamat;
                            $success_save_hcustomer                 = $model_hcustomer->save();
                        }

                        // jika top berubah simpan di
                        if (isset($_POST['MCustTop']) && $status_MCustTop == 'beda') foreach($_POST['MCustTop'] as $custtop){
                            $hCustTop                   = new HCustTop();
                            $hCustTop->attributes       = $custtop;
                            $hCustTop->cust_id          = $model->cust_id;
                            $hCustTop->kode_customer    = $model->kode_customer;

                            if($hCustTop->validate() && $hCustTop->save()){
                                $success_save_hcusttop  = true;
                            }else{
                                $data['message']        = Yii::t('app', 'Data HTOP Gagal di update');
                            }
                        }

                        if ($success_update_mcustomer && ($success_save_hcustomer || $success_save_hcusttop)) {
                            $success_save_approval      = $this->saveApproval($model);
                        }

                    }
                    // jika tidak ada perubahan cust_max_plafond atau m_cust_top, simpan yang lain selain m_customer.cust_max_plafond dan m_cust_top
                    else {
                        // jika validasi m_customer sukses maka simpan
                        if ($model->validate()) {
                            $model->status_approval     = 'APPROVED';
//                            $model->by_kadiv            = NULL;
//                            $model->by_dirut            = NULL;
//                            $model->approve_reason      = NULL;
//                            $model->reject_reason       = NULL;
//                            $model->kode_customer       = NULL;
                            $model->cust_max_plafond    = $cust_max_plafond_lama;
                            if ($model->save()) {
                                $data['message']        = "Data berhasil disimpan, tidak ada perubahan plafon dan term of payment";
                                $success_save_mcustomer = true;
                            } else {
                                $data['message'] = "Data tanpa perubahan gagal disimpan";
                            }
                        }
                        // jika validasi m_customer gagal tampilkan pesan
                        else {
                            $data['message'] = "Validasi m_customer gagal";
                        }
                    }

                    if ($success_save_approval || ($success_save_mcustomer || $success_update_mcustomer)) {
                        $str                = Yii::$app->getSecurity()->generateRandomString(4);
                        $time               = date('Ymd_His');
                        $dir_path           = Yii::$app->basePath.'/web/uploads/mkt/customer';

                        if(!is_dir($dir_path)){
                            mkdir(Yii::$app->basePath.'/web/uploads/mkt');
                            mkdir($dir_path);
                        }

                        if(!empty($model->file1)) $model->cust_file_ktp     = "$time-ktp-$str.{$model->file1->extension}";
                        if(!empty($model->file2)) $model->cust_file_npwp    = "$time-npwp-$str.{$model->file2->extension}";
                        if(!empty($model->file3)) $model->cust_file_photo   = "$time-photo-$str.{$model->file3->extension}";

                        // simpan di h_cust_data
                        $model_hcustdata                = new HCustData();
                        $model_hcustdata->attributes    = $model->attributes;
                        $model_hcustdata->cust_id       = $model->getPrimaryKey();
                        $success_hcustdata              = $model_hcustdata->save();

                        if($model->update() !== false && $success_hcustdata){
                            if(!empty($model->file1)) $model->file1->saveAs("$dir_path/$model->cust_file_ktp");
                            if(!empty($model->file2)) $model->file2->saveAs("$dir_path/$model->cust_file_npwp");
                            if(!empty($model->file3)) $model->file3->saveAs("$dir_path/$model->cust_file_photo");

                            $transaction->commit();
                            $data['status'] = true;
                            $data['message'] = empty($data['message']) ? Yii::t('app', 'Data Customer Berhasil Diupdate') : $data['message'];
                        } else {
                            $transaction->rollback();
                            if(is_file("$dir_path/$model->cust_file_ktp"))    unlink("$dir_path/$model->cust_file_ktp");
                            if(is_file("$dir_path/$model->cust_file_npwp"))   unlink("$dir_path/$model->cust_file_npwp");
                            if(is_file("$dir_path/$model->cust_file_photo"))  unlink("$dir_path/$model->cust_file_photo");
                            $data['status'] = false;
                            $data['message'] = "Data gagal update";
                        }
                    } else {
                        $transaction->rollback();
                        $data['status'] = false;
                        $data['message'] = "Data gagal update save = " . $success_save_mcustomer;
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message'] = $ex->getMessage();
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('edit', compact('model', 'modCustTop', 'modCustTops'));
        }
    }

    /**
     * @version 2022-02-19
     * @note cuma dirapikan
     * @param $model
     * @return bool
     */
    public function saveApproval($model){
        $success                        = true;

        $modelApproval                  = new TApproval();
        $modelApproval->assigned_to     = $model->by_kadiv;
        $modelApproval->reff_no         = trim($model->kode_customer);
        $modelApproval->tanggal_berkas  = date("Y-m-d");
        $modelApproval->level           = 1;
        $modelApproval->status          = TApproval::STATUS_NOT_CONFIRMATED;
        $success                        &= $modelApproval->createApproval();
        
        $modelApproval = new TApproval();
        $modelApproval->assigned_to     = $model->by_dirut;
        $modelApproval->reff_no         = trim($model->kode_customer);
        $modelApproval->tanggal_berkas  = date("Y-m-d");
        $modelApproval->level           = 2;
        $modelApproval->status          = TApproval::STATUS_NOT_CONFIRMATED;
        $success                        &= $modelApproval->createApproval();
        
        return $success;
    }

    /**
     * @param $id
     * @return string|void|Response
     * @throws \yii\db\Exception
     * @throws \Exception
     * @version 2022-02-19
     * @note menghilangkan proses hapus gambar karena digunakan di tabel h_cust-data
     * @note TIDAK DIPAKAI
     */
    public function actionDelete($id)
    {
        if(Yii::$app->request->isAjax){
            $tableid                = Yii::$app->request->get('tableid');
            $model                  = MCustomer::findOne($id);
            $modCustTop             = MCustTop::find()->where(['cust_id'=>$model->cust_id])->all();
            $modHcustTop            = HCustTop::findAll(['cust_id' => $model->cust_id]);
//            $cust_file_ktp_old      = $model->cust_file_ktp;
//            $cust_file_npwp_old     = $model->cust_file_npwp;
//            $cust_file_photo_old    = $model->cust_file_photo;

            if( Yii::$app->request->post('deleteRecord')){
                $transaction                    = Yii::$app->db->beginTransaction();
                try {
                    $success_delete_mcustomer   = false;
                    $success_delete_mcusttop    = false;
                    $success_delete_hcusttop    = false;
                    if(count($modCustTop)>0 && count($modHcustTop)) {
                        $total_delete_mcusttop  = MCustTop::deleteAll(['cust_id' => $model->cust_id]);
                        $total_delete_hcusttop  = HCustTop::deleteAll(['cust_id' => $model->cust_id]);
                        $success_delete_mcusttop= $total_delete_mcusttop > 0;
                        $success_delete_hcusttop= $total_delete_hcusttop > 0;
                    }
                    if($model->delete() && $success_delete_mcusttop && $success_delete_hcusttop){
//                        karena gambar di peruke di tabel h_cust_data maka untuk proses hapus di hilangkan
//                        if($cust_file_ktp_old != null){
//                            if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_ktp_old)) {
//                                unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_ktp_old);
//                            }
//                        }
//                        if($cust_file_npwp_old != null){
//                            if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_npwp_old)) {
//                                unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_npwp_old);
//                            }
//                        }
//                        if($cust_file_photo_old != null){
//                            if (file_exists(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_photo_old)) {
//                                unlink(Yii::$app->basePath.'/web/uploads/mkt/customer/'.$cust_file_photo_old);
//                            }
//                        }
                        $success_delete_mcustomer   = true;
                    }else{
                        $data['message']            = Yii::t('app', 'Data Customer Gagal dihapus');
                    }
                    if ($success_delete_mcustomer) {
                        $transaction->commit();
                        $data['status']             = true;
                        $data['message']            = Yii::t('app', '<i class="icon-check"></i> Data Customer Berhasil Dihapus');
                    } else {
                        $transaction->rollback();
                        $data['status']             = false;
                        (!isset($data['message']) ? $data['message'] = Yii::t('app', Params::DEFAULT_FAILED_TRANSACTION_MESSAGE) : '');
                        (isset($data['message_validate']) ? $data['message'] = null : '');
                    }
                } catch (\yii\db\Exception $ex) {
                    $transaction->rollback();
                    $data['message']    = $ex->getMessage();
                }
                return $this->asJson($data);
            }
            return $this->renderAjax('@views/apps/partial/_deleteRecordConfirm',['id'=>$id,'tableid'=>$tableid]);
        }
    }
    
    public function actionSetDropdownJenisProdukTOP()
    {
        if(Yii::$app->request->isAjax){
			$list_jenis = Yii::$app->request->post('list_jenis');
            $html = '';
            $data['habis'] = false;
            if(!empty($list_jenis)){
                $params = "";
                foreach($list_jenis as $i => $val){
                    $params .= "'".$val."'";
                    if(count($list_jenis)>$i+1){
                        $params .= ",";
                    }
                }
                $mod = [];
                $mod = \app\models\MDefaultValue::find()->where(['active'=>true,'type'=>'jenis-produk'])->andWhere('value NOT IN ('.$params.')')->all();
                $arraymap = \yii\helpers\ArrayHelper::map($mod, 'value', 'name');
                foreach($arraymap as $i => $val){
                    $html .= \yii\bootstrap\Html::tag('option',$val,['value'=>$i]);
                }
            }
			$data['html']= $html;
			return $this->asJson($data);
		}
    }
	
	public function actionMasterOnModal(){
		if(Yii::$app->request->isAjax){
			if(Yii::$app->request->get('dt')=='table-customer'){
				$param['table'] = MCustomer::tableName();
				$param['pk']= $param['table'].".". MCustomer::primaryKey()[0];
				$param['column'] = [$param['table'].'.cust_id','cust_kode','cust_an_nama','cust_pr_nama','cust_an_alamat','cust_max_plafond',
                                                    'COALESCE(SUM(t_nota_penjualan.total_bayar)-COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0),0) AS piutang'];
				$param['join'] = ['LEFT JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = '.$param['table'].'.cust_id'];
				$param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
				$param['where'] = "active IS TRUE and m_customer.status_approval='APPROVED' AND m_customer.cust_tipe_penjualan = 'lokal'";
				return Json::encode(SSP::complex( $param ));
			}
			return $this->renderAjax('masterOnTable');
		}
	}

    public function actionPick(){
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->get('dt')=='table-customer'){
                $param['table'] = MCustomer::tableName();
                $param['pk']= $param['table'].".". MCustomer::primaryKey()[0];
                $param['column'] = [$param['table'].'.cust_id','cust_kode','cust_an_nama','cust_pr_nama','cust_an_alamat','cust_max_plafond',
                                    'COALESCE(SUM(t_nota_penjualan.total_bayar)-COALESCE((SELECT SUM(bayar) FROM t_piutang_penjualan WHERE t_piutang_penjualan.cust_id = m_customer.cust_id),0),0) AS piutang'];
                $param['join'] = ['LEFT JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = '.$param['table'].'.cust_id'];
                $param['group'] = "GROUP BY ".$param['table'].".cust_id, cust_kode, cust_an_nama, cust_pr_nama, cust_max_plafond";
                $param['where'] = "active IS TRUE";
                return Json::encode(SSP::complex( $param ));
            }
            return $this->renderAjax('pick');
        }
    }

	public function actionFindCustomer(){
		if(Yii::$app->request->isAjax){
			$term = Yii::$app->request->get('term');
			$data = [];
			$active = "";
			if(!empty($term)){
				$query = "
					SELECT * FROM m_customer 
					WHERE cust_an_nama ilike '%{$term}%' AND active IS TRUE
					ORDER BY cust_an_nama";
				$mod = Yii::$app->db->createCommand($query)->queryAll();
				$ret = [];
				if(count($mod)>0){
					$arraymap = \yii\helpers\ArrayHelper::map($mod, 'cust_id', 'cust_an_nama');
					foreach($mod as $i => $val){
						$data[] = ['id'=>$val['cust_id'], 'text'=>$val['cust_an_nama']." ".(!empty($val['cust_pr_nama'])?"- ".$val['cust_pr_nama']:"")];
					}
				}
			}
            return $this->asJson($data);
        }
	}

	public function actionCustomerPrint()
    {
        $this->layout = '@views/layouts/metronic/print';

        $search = Yii::$app->request->get('search');
        if ($search != "") {
            $andWhere = " and (cust_kode ilike '%".$search."%' or cust_kode ilike '%".$search."%' or cust_an_nama ilike '%".$search."%' or cust_pr_nama ilike '%".$search."%' or cust_pr_nama ilike '%".$search."%' or cust_an_alamat ilike '%".$search."%' )";
        } else {
            $andWhere = '';
        }
        
        $sql = "select * from m_customer where 1=1 ".$andWhere." ";
        $model = Yii::$app->db->createCommand($sql)->queryAll();
        $caraprint = Yii::$app->request->get('caraprint');
        $paramprint['judul'] = "Laporan Customer";
		if ($caraprint == 'PRINT') {
			return $this->render('/customer/print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
		} else if($caraprint == 'PDF') {
			$pdf = Yii::$app->pdf;
			$pdf->options = ['title' => $paramprint['judul']];
			$pdf->filename = $paramprint['judul'].'.pdf';
			$pdf->methods['SetHeader'] = ['Generated By: '.Yii::$app->user->getIdentity()->userProfile->fullname.'||Generated At: ' . date('d/m/Y H:i:s')];
			$pdf->content = $this->render('/customer/print',['model'=>$model,'paramprint'=>$paramprint]);
			return $pdf->render();
		}else if($caraprint == 'EXCEL'){
			return $this->render('/customer/print',['model'=>$model,'paramprint'=>$paramprint, 'search'=>$search]);
		}
	}

    /**@version 2022-02-19
     * @note fungsi untuk mengupdate status active pada tabel m_customer menjadi false
     * @param $id
     * @return string|Response
     */
    public function actionInactivated($id)
    {
        $tableid = Yii::$app->request->get('tableid');
        if(Yii::$app->request->post('updaterecord')) {
            $customer                   = MCustomer::findOne($id);
            $customer->active           = false;
            $customer->cust_max_plafond = (string) $customer->cust_max_plafond;
            if($customer->save()) {
                $data['status']         = true;
                $data['message']        = Params::DEFAULT_SUCCESS_TRANSACTION_MESSAGE;
                $data['callback']       = "
                    $('.modals-place').children('.modal').hide(); 
                    $('.modals-place').children('.modal').remove(); 
                    $('.modal-backdrop').remove();
                ";
            }else {
                $data['status']         = false;
                $data['message']        = Params::DEFAULT_FAILED_TRANSACTION_MESSAGE;
            }

            return $this->asJson($data);
        }
        return $this->renderAjax('@views/apps/partial/_globalConfirm',[
            'id' => $id,
            'tableid' => $tableid,
            'actionname' => 'inactivated',
            'pesan' => 'Apakah anda yakin ingin menonaktifkan customer ini?'
        ]);
    }

}
