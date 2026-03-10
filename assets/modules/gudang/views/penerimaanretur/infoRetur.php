<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-8">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', "Terima Retur Penjualan"); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Retur'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= $model->kode ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Retur'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong>
								<?= $model->customer->cust_an_nama ?><br>
								<?= $model->customer->cust_an_alamat ?>
							</strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Alasan Retur'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= $model->alasan_retur ?></strong></div>
                        </div>
                    </div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Waktu Terima'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_terima) ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nopol Kendaraan'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= $model->kendaraan_nopol ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Supir'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= $model->kendaraan_supir ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nopol Kendaraan'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= !empty($model->petugas_penerima)?$model->petugasPenerima->pegawai_nama:"-" ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Diperiksa Security'); ?></label>
                            <div class="col-md-7" style="margin-top: 5px;"><strong><?= !empty($model->diperiksa_security)?$model->diperiksaSecurity->pegawai_nama:"-" ?></strong></div>
                        </div>
					</div>
                </div>
				<br>
				<div class="row">
					<div class="col-md-5" style="margin-left: 20px; margin-bottom: -15px;">
						<h4><?= Yii::t('app', 'Detail Produk Retur'); ?></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-11" style="margin-left: 20px;">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
										<th><?= Yii::t('app', 'Produk'); ?></th>
										<th><?= Yii::t('app', 'Pcs'); ?></th>
										<th><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$total_pcs = 0; $total_m3=0;
									if(!empty($modDetails)){
										foreach($modDetails as $i => $detail){
											echo "<tr>";
											echo	"<td style='text-align:center'>".($i+1)."</td>";
											echo	"<td style='text-align:left'>".$detail->produk->produk_nama."</td>";
											echo	"<td style='text-align:center'>".$detail->qty_kecil."</td>";
											echo	"<td style='text-align:center'>". number_format($detail->kubikasi,4)."</td>";
											echo "</tr>";
											$total_pcs += $detail->qty_kecil;
											$total_m3 += $detail->kubikasi;
										}
									}
									?>
									<tr>
										
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" class="text-align-right" style="font-size:1.4rem">Total &nbsp;</td>
										<td class="text-align-center" style="font-size:1.4rem"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_pcs) ?></td>
										<td class="text-align-center" style="font-size:1.4rem"><?= number_format($total_m3,4) ?></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Terima'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save(this,"$(\'#close-btn-modal\').removeAttr(\'disabled\'); $(\'#close-btn-modal\').trigger(\'click\'); $(\'#table-laporan\').dataTable().fnClearTable();")'
                    ]);
				?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
//$('.form_datetime').datetimepicker({
//	autoclose: !0,
//	isRTL: App.isRTL(),
//	format: 'dd/mm/yyyy - hh:ii',
//	fontAwesome: !0,
//	pickerPosition: App.isRTL() ? 'bottom-right' : 'bottom-left',
//	orientation: 'left',
//	clearBtn:true,
//	todayHighlight:true
//});
$('select[name*=\"[petugas_penerima]\"]').select2({
	allowClear: !0,
	placeholder: 'Ketik Petugas Penerima',
	width: '100%'
});
$('select[name*=\"[diperiksa_security]\"]').select2({
	allowClear: !0,
	placeholder: 'Ketik Nama Security',
	width: '100%'
});
", yii\web\View::POS_READY); ?>
<script>
function save(){
    var $form = $('#form-transaksi');
	$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_terima") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "petugas_penerima") ?>").parents(".form-group").removeClass("has-error");
	$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").removeClass("has-error");
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
		if(validatingDetail()){
            submitformajax($form);
        }
    }
    return false;
}

function validatingDetail($form){
	var has_error = 0;
	var waktu_terima = $("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_terima") ?>").val();
	var kendaraan_nopol = $("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").val();
	var kendaraan_supir = $("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").val();
	var petugas_penerima = $("#<?= \yii\bootstrap\Html::getInputId($model, "petugas_penerima") ?>").val();
	var diperiksa_security = $("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").val();
	if(!waktu_terima){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_terima") ?>").parents(".form-group").addClass("has-error");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "waktu_terima") ?>").parents(".form-group").removeClass("has-success");
		has_error = has_error + 1;
	}
	if(!kendaraan_nopol){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").parents(".form-group").addClass("has-error");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_nopol") ?>").parents(".form-group").removeClass("has-success");
		has_error = has_error + 1;
	}
	if(!kendaraan_supir){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").parents(".form-group").addClass("has-error");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "kendaraan_supir") ?>").parents(".form-group").removeClass("has-success");
		has_error = has_error + 1;
	}
	if(!petugas_penerima){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "petugas_penerima") ?>").parents(".form-group").addClass("has-error");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "petugas_penerima") ?>").parents(".form-group").removeClass("has-success");
		has_error = has_error + 1;
	}
	if(!diperiksa_security){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").addClass("has-error");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "diperiksa_security") ?>").parents(".form-group").removeClass("has-success");
		has_error = has_error + 1;
	}
	if(has_error === 0){
        return true;
    }
    return false;
}
</script>