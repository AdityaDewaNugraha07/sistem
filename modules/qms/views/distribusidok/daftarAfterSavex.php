<?php app\assets\DatatableAsset::register($this); ?>

<style>
.td-kecil4{
    line-height: 1 !important;
    padding: 3px !important;
    vertical-align: top !important;
    font-size:1.2rem !important;
}
</style>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Dokumen Revisi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Nama Dokumen'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Dikirim'); ?></th>
									<th><?= Yii::t('app', 'Dikirim Oleh'); ?></th>
                                    <th><?= Yii::t('app', 'Penerima Dokumen'); ?></th>
									<th style="width: 120px;"></th>
								</tr>
							</thead>
                            <tbody>
                                <?php 
                                if($data){
                                    foreach($data as $i => $d){ ?>
                                    <tr>
                                        <td class="text-align-center td-kecil4"><?= $i+1; ?></td>
                                        <td class="td-kecil4"><?= $d['nama_dokumen']; ?></td>
                                        <td class="text-align-center td-kecil4"><?= app\components\DeltaFormatter::formatDateTimeForUser2($d['tanggal_dikirim']); ?></td>
                                        <td class="text-align-center td-kecil4"><?= $d['dikirim_oleh']; ?></td>
                                        <td class="td-kecil4">
                                            <?php 
                                            $model = \app\models\TDokumenDistribusi::find()->where(['dokumen_revisi_id'=>$d['dokumen_revisi_id'], 'tanggal_dikirim'=>$d['tanggal_dikirim']])->all();
                                            foreach($model as $m => $mod){
                                                $pic = \app\models\MPicIso::findOne($mod['pic_iso_id']);
                                                $pegawai = \app\models\MPegawai::findOne($pic->pegawai_id);
                                                $departement = \app\models\MDepartement::findOne($pic->departement_id);
                                                $status = $mod['status_penerimaan']?"<span style='color:green;' class='td-kecil3'> Sudah Diterima</span>":"<span style='color:red;' class='td-kecil3'> Belum Diterima</span>";
                                                $no = $m+1;
                                                echo $no;
                                                echo '. ';
                                                echo $pegawai->pegawai_nama . ' - ' . $departement->departement_nama . $status;
                                                echo '<br>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-align-center td-kecil4">
                                            <?php 
                                            $model = \app\models\TDokumenDistribusi::find()->where(['dokumen_revisi_id'=>$d['dokumen_revisi_id'], 'tanggal_dikirim'=>$d['tanggal_dikirim']])->all();
                                            $allFalse = true;
                                            foreach($model as $m => $mod){
                                                if (!$mod->status_penerimaan) {
                                                    $allFalse = false;
                                                    break; // jika ada true, langsung hentikan
                                                }
                                            }
                                            if (!$allFalse) {
                                                echo '  <a class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Update" onclick="edit('.$d['dokumen_revisi_id'].', \''.$d['tanggal_dikirim'].'\')"><i class="fa fa-edit"></i></a>
									                    <a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('.$d['dokumen_revisi_id'].', \''.$d['tanggal_dikirim'].'\')"><i class="fa fa-eye"></i></a>';
                                            } else {
                                                echo '  <a class="btn btn-xs btn-outline grey tooltips"><i class="fa fa-edit"></i></a>
									                    <a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('.$d['dokumen_revisi_id'].', \''.$d['tanggal_dikirim'].'\')"><i class="fa fa-eye"></i></a>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php }
                                }
                                ?>
                            </tbody>
						</table>
						</div>
                    </div>
                </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
    
<?php $this->registerJs("
	formconfig();
    // $('#table-aftersave').dataTable();
    // dtTable();
", yii\web\View::POS_READY); ?>
<script>
function lihatDetail(id, tgl){
    var date = new Date(tgl);
	date = date.toString('yyyy-mm-dd-H-m-s');
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/index','dokumen'=>'']); ?>'+id+'&tgl_kirim='+date);
}
function edit(id, tgl){
    var date = new Date(tgl);
	date = date.toString('yyyy-mm-dd-H-m-s');
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/index','dokumen'=>'']); ?>'+id+'&tgl_kirim='+date+'&edit=1');
}
</script>