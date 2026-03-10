<?php
/* @var $this yii\web\View */

use app\components\Params;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Penerimaan Dokumen';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan Dokumen'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.table-advance tr td:first-child {
    border-left-width: 1px !important;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" style="margin-left: 5px;" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Dokumen Yang Telah Diterima'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-list"></i>
                                    <span class="caption-subject bold"><?= Yii::t('app', 'List Dokumen Yang Belum Diterima'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
										<thead>
											<tr>
												<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
												<th style="text-align: center;"><?= Yii::t('app', 'Nama Dokumen'); ?></th>
                                                <th style="text-align: center;"><?= Yii::t('app', 'Revisi Ke'); ?></th>
                                                <th style="text-align: center;"><?= Yii::t('app', 'Nomor Dokumen'); ?></th>
                                                <th style="text-align: center;"><?= Yii::t('app', 'Jenis Dokumen'); ?></th>
                                                <th style="text-align: center;"><?= Yii::t('app', 'Tanggal Dikirim'); ?></th>
                                                <th style="text-align: center;"><?= Yii::t('app', 'Dikirim Oleh'); ?></th>
                                                <th style="text-align: center;"><?= Yii::t('app', 'Penerima Dokumen'); ?></th>
                                                <th style="text-align: center;"><?= Yii::t('app', 'Terima'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php 
                                            $pegawai_id = Yii::$app->user->identity->pegawai->pegawai_id;
                                            $query = "  SELECT dokumen_distribusi_id, t_dokumen_revisi.nama_dokumen, t_dokumen_revisi.revisi_ke, m_dokumen.nomor_dokumen, m_dokumen.jenis_dokumen,
                                                        tanggal_dikirim, b.pegawai_nama as dikirim_oleh, a.pegawai_nama as pic_iso
                                                        FROM t_dokumen_distribusi
                                                        JOIN t_dokumen_revisi ON t_dokumen_revisi.dokumen_revisi_id = t_dokumen_distribusi.dokumen_revisi_id
                                                        JOIN m_dokumen ON m_dokumen.dokumen_id = t_dokumen_revisi.dokumen_id
                                                        JOIN m_pic_iso ON m_pic_iso.pic_iso_id = t_dokumen_distribusi.pic_iso_id
                                                        JOIN m_pegawai a ON a.pegawai_id = m_pic_iso.pegawai_id
                                                        JOIN m_pegawai b ON b.pegawai_id = t_dokumen_distribusi.dikirim_oleh
                                                        WHERE status_penerimaan IS NOT TRUE AND m_pic_iso.pegawai_id = $pegawai_id ";
                                            $model = Yii::$app->db->createCommand($query)->queryAll();
                                            if(count($model) > 0){
                                                foreach($model as $i => $data){ ?>
                                                    <tr>
                                                        <td class="text-align-center td-kecil"><?= $i+1; ?></td>
                                                        <td class="td-kecil"><?= $data['nama_dokumen']; ?></td>
                                                        <td class="text-align-center td-kecil"><?= $data['revisi_ke']; ?></td>
                                                        <td class="text-align-center td-kecil"><?= $data['nomor_dokumen']; ?></td>
                                                        <td class="text-align-center td-kecil"><?= $data['jenis_dokumen']; ?></td>
                                                        <td class="text-align-center td-kecil"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_dikirim']); ?></td>
                                                        <td class="text-align-center td-kecil"><?= $data['dikirim_oleh']; ?></td>
                                                        <td class="text-align-center td-kecil"><?= $data['pic_iso']; ?></td>
                                                        <td class="text-align-center td-kecil"><a href="javascript:void(0);" onclick="terimaDokumen(<?= $data['dokumen_distribusi_id']; ?>);" class="label label-info" style="font-size: 1.0rem;"><i class="fa fa-plus"></i> TERIMA</a></td>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr>
                                                    <td colspan="9" class="text-align-center">Data tidak ditemukan</td>
                                                </tr>
                                            <?php }
                                            ?>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <!-- <div class="col-md-12 right">
                                <?php //echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php //echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php 
$page = '';
if(isset($_GET['dokumen_revisi_id'])){
    $page = 'aftersave('.$_GET['dokumen_revisi_id'].');';
}
?>
<?php $this->registerJs(" 
	formconfig();
    $page
    $('select[name*=\"[dokumen_revisi_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Masukkan Nama Dokumen',
	});
    $('select[name*=\"[pic_iso_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Masukkan Nama Pegawai',
	});
", yii\web\View::POS_READY); ?>
<script>
function terimaDokumen(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/qms/penerimaandok/terimaDokumen','id'=>'']) ?>'+id;
	var modal_id = 'modal-penerimaan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) { });
		$("#"+modal_id+" .modal-dialog").css('width',"40%");
	});
	return false;
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/qms/penerimaandok/daftarAfterSave']) ?>','modal-aftersave','90%');
}
</script>