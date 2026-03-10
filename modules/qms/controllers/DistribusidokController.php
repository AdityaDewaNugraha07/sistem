<?php

namespace app\modules\qms\controllers;

use Yii;
use app\controllers\DeltaBaseController;
use app\components\SSP;
use yii\helpers\Json;
use DateTime;

class DistribusidokController extends DeltaBaseController
{
    
	public $defaultAction = 'index';
    
	public function actionIndex(){
        $model = new \app\models\TDokumenDistribusi();
        $model->tanggal_dikirim = date('d/m/Y');

		if (isset($_GET['dokumen']) && isset($_GET['tgl_kirim'])) {
            $datetime = DateTime::createFromFormat('Y-m-d-H-i-s', $_GET['tgl_kirim']);
            $tanggal_dikirim = $datetime ? $datetime->format('Y-m-d H:i:s') : null;
            $tgl_kirim = $datetime ? $datetime->format('d/m/Y') : null;
            // print_r($tanggal_dikirim);exit;
            $modDistribusi = \app\models\TDokumenDistribusi::findAll(['dokumen_revisi_id'=>$_GET['dokumen'], 'tanggal_dikirim'=>$tanggal_dikirim]);
            if(count($modDistribusi) > 0){
                $model->dokumen_revisi_id = $_GET['dokumen'];
                $model->tanggal_dikirim = $tgl_kirim;
                $modRev = \app\models\TDokumenRevisi::findOne($_GET['dokumen']);
                $model->nama_dokumen = $modRev->nama_dokumen;
            } else {
                Yii::$app->session->setFlash('error', 'Data dokumen distribusi tidak ditemukan!!!');
            }
        }

		if (Yii::$app->request->post('TDokumenDistribusi')) {
			$transaction = \Yii::$app->db->beginTransaction();
			try {
                $success_1 = false; // t_dokumen_distribusi
                $model->load(\Yii::$app->request->post());
                
                $picList = isset($_POST['TDokumenDistribusi']['pic_iso_id']) ? $_POST['TDokumenDistribusi']['pic_iso_id'] : [];
                if (isset($_GET['edit'])){
                    $model = \app\models\TDokumenDistribusi::find()->where(['dokumen_revisi_id'=>$_GET['dokumen']])->all();
					if(count($model)>0){
						\app\models\TDokumenDistribusi::deleteAll(['dokumen_revisi_id'=>$_GET['dokumen']]);
					}
                }
                foreach ($picList as $i => $pic){
                    $model = new \app\models\TDokumenDistribusi();
                    $model->dokumen_revisi_id = $_POST['TDokumenDistribusi']['dokumen_revisi_id'];
                    $model->pic_iso_id = $pic;
                    // $model->tanggal_dikirim = date('Y-m-d H:i:s');
                    $tanggal = \app\components\DeltaFormatter::formatDateTimeForDb($_POST['TDokumenDistribusi']['tanggal_dikirim']);
                    $jam = date('H:i:s');
                    $model->tanggal_dikirim = $tanggal . ' ' . $jam;
                    $model->dikirim_oleh = Yii::$app->user->identity->pegawai->pegawai_id;
                    $model->status_penerimaan = false;
                    if($model->validate()){
                        if($model->save()){
                            $success_1 = true;
                        }
                    }
                }
                
                // print_r($tgl_kirim); exit;
                if ($success_1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data Berhasil disimpan'));
                    $tgl = $model->tanggal_dikirim;
                    $tgl_kirim = date('Y-m-d-H-i-s', strtotime($tgl));
                    return $this->redirect(['index','success'=>1,'dokumen'=>$_POST['TDokumenDistribusi']['dokumen_revisi_id'],'tgl_kirim'=>$tgl_kirim]);
                } else {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', !empty($errmsg)?$errmsg:Yii::t('app', \app\components\Params::DEFAULT_FAILED_TRANSACTION_MESSAGE));
                }
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $ex);
            }
		}

		return $this->render('index', ['model'=>$model]);
	}

    public function actionSetPic(){
        $id = Yii::$app->request->post('id');
        $dokumen_revisi_id = Yii::$app->request->post('dokumen_revisi_id');
        $data['html'] = '';

        $model = \app\models\TDokumenRevisi::findOne($dokumen_revisi_id);
        $modDok = \app\models\MDokumen::findOne($model->dokumen_id);
        $modPic = \app\models\MPicIso::find()->all();

        $checked = [];
        if ($id) {
            $existingDistribusi = \app\models\TDokumenDistribusi::findAll(['dokumen_revisi_id' => $id]);
            foreach ($existingDistribusi as $item) {
                $checked[] = $item->pic_iso_id;
            }
        }

        if (count($modPic) > 0) {
            foreach ($modPic as $i => $mod) {
                $pegawai = \app\models\MPegawai::findOne($mod['pegawai_id']);
                $departement = \app\models\MDepartement::findOne($mod['departement_id']);
                $label = $pegawai->pegawai_nama . ' - ' . $departement->departement_nama;
                $isChecked = in_array($mod->pic_iso_id, $checked) ? 'checked' : '';

                $data['html'] .= '  <div>
                                        <input type="checkbox" style="margin-right: 5px;" name="TDokumenDistribusi[pic_iso_id]['.$i.']" value="' . $mod->pic_iso_id . '" '.$isChecked.'>
                                        <label>'.$label.'</label> 
                                    </div> ';
            }
        }
        return $this->asJson($data);
    }

    // MANUAL T_T
    public function actionDaftarAfterSave()
    {
        if (Yii::$app->request->isAjax) {
            if(\Yii::$app->request->get('dt')=='modal-aftersave'){
                $request = Yii::$app->request;

                // parameter datatables
                $draw = intval($request->get('draw', 1));
                $start = intval($request->get('start', 0));
                $length = intval($request->get('length', 50));
                $search = $request->get('search')['value'] ?$request->get('search')['value']: '';
                $order = $request->get('order', []);

                // kolom ordering
                $columns = [
                    0 => 't_dokumen_revisi.dokumen_revisi_id',
                    1 => 'nomor_dokumen',
                    2 => 't_dokumen_revisi.nama_dokumen',
                    3 => 'tanggal_dikirim',
                    4 => 'b.pegawai_nama'
                ];

                // query ordering
                $orderBy = 'tanggal_dikirim DESC';
                if (!empty($order)) {
                    $colIndex = intval($order[0]['column']);
                    $dir = strtoupper($order[0]['dir']) === 'ASC' ? 'ASC' : 'DESC';
                    if (isset($columns[$colIndex])) {
                        $orderBy = "{$columns[$colIndex]} $dir";
                    }
                }

                // query search
                $whereSearch = '';
                $params = [];

                if (!empty($search)) {
                    $whereSearch = "AND (
                        nomor_dokumen ILIKE '%" . $search . "%' OR
                        t_dokumen_revisi.nama_dokumen ILIKE '%" . $search . "%' OR
                        b.pegawai_nama ILIKE '%" . $search . "%'
                    )";
                }

                // total data tanpa filter
                $countSql = "
                    SELECT COUNT(DISTINCT ROW(t_dokumen_revisi.dokumen_revisi_id, tanggal_dikirim, b.pegawai_nama))
                    FROM t_dokumen_distribusi
                    JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                    JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                    JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh
                ";
                $recordsTotal = Yii::$app->db->createCommand($countSql)->queryScalar();

                // total data setelah filter
                $countFilteredSql = "
                    SELECT COUNT(*) FROM (
                        SELECT 1
                        FROM t_dokumen_distribusi
                        JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                        JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                        JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh
                        JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id
                        WHERE 1=1 $whereSearch
                        GROUP BY t_dokumen_revisi.dokumen_revisi_id, tanggal_dikirim, b.pegawai_nama
                    ) AS filtered_data
                ";
                $recordsFiltered = Yii::$app->db->createCommand($countFilteredSql, $params)->queryScalar();

                // query utama
                $dataSql = "
                    SELECT 
                        t_dokumen_revisi.dokumen_revisi_id,
                        nomor_dokumen,
                        revisi_ke,
                        t_dokumen_revisi.nama_dokumen,
                        tanggal_dikirim,
                        b.pegawai_nama as dikirim_oleh
                    FROM t_dokumen_distribusi
                    JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                    JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id
                    JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                    JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh
                    WHERE 1=1 $whereSearch
                    GROUP BY t_dokumen_revisi.dokumen_revisi_id, tanggal_dikirim, b.pegawai_nama,nomor_dokumen
                    ORDER BY $orderBy
                    LIMIT $length OFFSET $start
                ";
                $model = Yii::$app->db->createCommand($dataSql, $params)->queryAll();

                $response = [
                    "draw" => $draw,
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => [],
                ];

                // cari semua penerima masing2 dokumen
                foreach ($model as $d) {
                    $models = \app\models\TDokumenDistribusi::find()
                        ->where([
                            'dokumen_revisi_id' => $d['dokumen_revisi_id'],
                            'tanggal_dikirim' => $d['tanggal_dikirim']
                        ])->all();

                    $penerimaText = '';
                    $allFalse = true;
                    foreach ($models as $idx => $mod) {
                        $pic = \app\models\MPicIso::findOne($mod->pic_iso_id);
                        $pegawai = \app\models\MPegawai::findOne($pic->pegawai_id);
                        $departement = \app\models\MDepartement::findOne($pic->departement_id);

                        $status = $mod->status_penerimaan
                            ? "<span style='color:green;' class='td-kecil3'> Sudah Diterima</span>"
                            : "<span style='color:red;' class='td-kecil3'> Belum Diterima</span>";

                        $penerimaText .= ($idx + 1) . '. ' . $pegawai->pegawai_nama . ' - ' . $departement->departement_nama . ' ' . $status . '<br>';
                        if (!$mod->status_penerimaan) {
                            $allFalse = false;
                        }
                    }
                    $aksi = !$allFalse
                        ? ' <a class="btn btn-xs btn-outline blue-hoki" onclick="edit(' . $d['dokumen_revisi_id'] . ', \'' . $d['tanggal_dikirim'] . '\')"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-xs btn-outline dark" onclick="lihatDetail(' . $d['dokumen_revisi_id'] . ', \'' . $d['tanggal_dikirim'] . '\')"><i class="fa fa-eye"></i></a>'
                        : ' <a class="btn btn-xs btn-outline grey"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-xs btn-outline dark" onclick="lihatDetail(' . $d['dokumen_revisi_id'] . ', \'' . $d['tanggal_dikirim'] . '\')"><i class="fa fa-eye"></i></a>';

                    $response['data'][] = [
                        $d['dokumen_revisi_id'],
                        $d['nomor_dokumen'],
                        $d['revisi_ke'],
                        $d['nama_dokumen'],
                        $d['tanggal_dikirim'],
                        $d['dikirim_oleh'],
                        $penerimaText,
                        $aksi,
                    ];
                }
                return \yii\helpers\Json::encode($response);
            }
            return $this->renderAjax('daftarAfterSave');
        }
    }

    /*public function actionDaftarAfterSave(){
		if(\Yii::$app->request->isAjax){
			if(\Yii::$app->request->get('dt')=='modal-aftersave'){
				$param['table']= \app\models\TDokumenDistribusi::tableName();
				$param['pk']= $param['table'].".". \app\models\TDokumenDistribusi::primaryKey()[0];
				$param['column'] = [$param['table'].'.dokumen_distribusi_id',
									't_dokumen_revisi.nama_dokumen', 
                                    'a.pegawai_nama as pic_iso', 
                                    'tanggal_dikirim', 
                                    'b.pegawai_nama as dikirim_oleh',
                                    'status_penerimaan'
									];
				$param['join']= ['  JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                                    JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                                    JOIN m_pegawai a ON a.pegawai_id = m_pic_iso.pegawai_id
                                    JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh'];
				return \yii\helpers\Json::encode(\app\components\SSP::complex( $param ));
			}
			return $this->renderAjax('daftarAfterSave');
        }
    }
    public function actionDaftarAfterSave()
    {
        if (Yii::$app->request->isAjax) {
            if(\Yii::$app->request->get('dt')=='modal-aftersave'){
                $sql = "
                    SELECT 
                        t_dokumen_revisi.dokumen_revisi_id,
                        t_dokumen_revisi.nama_dokumen,
                        tanggal_dikirim,
                        b.pegawai_nama as dikirim_oleh
                    FROM t_dokumen_distribusi
                    JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                    JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                    JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh
                    GROUP BY t_dokumen_revisi.dokumen_revisi_id, tanggal_dikirim, b.pegawai_nama
                ";
                $model = Yii::$app->db->createCommand($sql)->queryAll();

                // $data = [];
                $response = [
                    "draw" => intval(Yii::$app->request->get('draw')),
                    "recordsTotal" => count($model), 
                    "recordsFiltered" => count($model),
                    "data" => [],
                ];
                foreach ($model as $d) {
                    $models = \app\models\TDokumenDistribusi::find()
                        ->where([
                            'dokumen_revisi_id' => $d['dokumen_revisi_id'], 
                            'tanggal_dikirim' => $d['tanggal_dikirim']
                        ])->all();

                    $penerimaText = '';
                    $allFalse = true;
                    foreach ($models as $idx => $mod) {
                        $pic = \app\models\MPicIso::findOne($mod->pic_iso_id);
                        $pegawai = \app\models\MPegawai::findOne($pic->pegawai_id);
                        $departement = \app\models\MDepartement::findOne($pic->departement_id);

                        $status = $mod->status_penerimaan
                            ? "<span style='color:green;' class='td-kecil3'>Sudah Diterima</span>"
                            : "<span style='color:red;' class='td-kecil3'>Belum Diterima</span>";

                        $penerimaText .= ($idx + 1) . '. ' . $pegawai->pegawai_nama . ' - ' . $departement->departement_nama . ' ' . $status . '<br>';
                        if (!$mod->status_penerimaan) {
                            $allFalse = false;
                        }
                    }

                    if (!$allFalse) {
                        $aksi = '
                            <a class="btn btn-xs btn-outline blue-hoki" onclick="edit(' . $d['dokumen_revisi_id'] . ', \'' . $d['tanggal_dikirim'] . '\')"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-xs btn-outline dark" onclick="lihatDetail(' . $d['dokumen_revisi_id'] . ', \'' . $d['tanggal_dikirim'] . '\')"><i class="fa fa-eye"></i></a>
                        ';
                    } else {
                        $aksi = '
                            <a class="btn btn-xs btn-outline grey"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-xs btn-outline dark" onclick="lihatDetail(' . $d['dokumen_revisi_id'] . ', \'' . $d['tanggal_dikirim'] . '\')"><i class="fa fa-eye"></i></a>
                        ';
                    }
                    // $data[] = [
                    //     'dokumen_revisi_id' => $d['dokumen_revisi_id'],
                    //     'nama_dokumen' => $d['nama_dokumen'],
                    //     'tanggal_dikirim' => $d['tanggal_dikirim'],
                    //     'dikirim_oleh' => $d['dikirim_oleh'],
                    //     'penerima' => $penerimaText,
                    //     'aksi' => $aksi,
                    // ];
                    $response['data'][] = [
                        $d['dokumen_revisi_id'],
                        $d['nama_dokumen'],
                        $d['tanggal_dikirim'],
                        $d['dikirim_oleh'],
                        $penerimaText,
                        $aksi,
                    ];
                }
                
                // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                // return ['data' => $data];
                return \yii\helpers\Json::encode($response);
            }
            return $this->renderAjax('daftarAfterSave');
        }
    }**/

    public function actionPick(){
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->get('dt')=='table-dokumen'){
                $param['table'] = \app\models\TDokumenRevisi::tableName();
                $param['pk']= $param['table'].".". \app\models\TDokumenRevisi::primaryKey()[0];
                $param['column'] = [$param['table'].'.dokumen_revisi_id','nomor_dokumen',$param['table'].'.nama_dokumen','revisi_ke','jenis_dokumen','kategori_dokumen'];
                $param['join'] = ["JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id"];
                $param['where'] = "dokumen_revisi_id NOT IN (SELECT dokumen_revisi_id FROM t_dokumen_distribusi)";
                return Json::encode(SSP::complex( $param ));
            }
            return $this->renderAjax('pick');
        }
    }

    public function actionSetNama(){
        $dokumen_revisi_id = Yii::$app->request->post('dokumen_revisi_id');
        $modRev = \app\models\TDokumenRevisi::findOne($dokumen_revisi_id);
        $data['nama'] = $modRev->nama_dokumen;
        return $this->asJson($data);
    }
}