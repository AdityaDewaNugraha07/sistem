<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-closing',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-7">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<center><h4 class="modal-title"><?= Yii::t('app', $pesan); ?></h4></center>
            </div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kode Agenda</label>
                            <div class="col-md-7"><strong><?= $modAgenda->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Tanggal Agenda</label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modAgenda->tanggal) ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Penganggung Jawab</label>
                            <div class="col-md-7"><strong><?= app\models\MPegawai::findOne($modAgenda->penanggungjawab)->pegawai_nama; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Total Peserta</label>
                            <div class="col-md-7"><strong><?= count($modPeserta)." Orang"; ?></strong></div>
                        </div>
                    </div>
                </div>
                <br>
                <div id="hasil-confirm" style="height: 100px;"></div>
                <br>
                <div class="row">
                    <div class="col-md-6" style="margin-top: 25px;">
                        <div class="form-group col-md-6">
                            <div id="tstockopnamehasil-lanjut_adjustment">
                                <b>Lanjutkan Ke Proses Adjustment ? </b> &nbsp; &nbsp; &nbsp;
                                <label><input name="TStockopnameHasil[lanjut_adjustment]" value="1" type="radio" checked="" onchange="setApproval()"> Yes</label> &nbsp;&nbsp;
                                <label><input name="TStockopnameHasil[lanjut_adjustment]" value="0" type="radio" onchange="setApproval()"> No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-top: 25px;">
                        <?= $form->field($model, 'keterangan')->textarea()->label("Catatan"); ?>
                    </div>
                </div>
                <div class="row" id="place-approval" style="display: none;">
                    <div class="col-md-12" style="margin-top: 25px;">
                        <div class="form-group col-md-12 text-align-left" style="line-height: 1">
                            <span style="font-size: 1.3rem;">Adjustment akan dilakukan setelah Approval dari : </span>
                        </div>
                        <div class="form-group col-md-3 text-align-center" style="line-height: 1">
                            <?= yii\helpers\Html::activeHiddenInput($model, 'by_gmopr') ?>
                            <span style="font-size: 1.2rem"><b><u><?= $model->by_gmopr_display ?></u></b></span><br>
                            <span style="font-size: 1.1rem; margin-top: 5px;">GM Operational</span>
                        </div>
                        <div class="form-group col-md-3 text-align-center" style="line-height: 1">
                            <?= yii\helpers\Html::activeHiddenInput($model, 'by_dirut') ?>
                            <span style="font-size: 1.2rem"><b><u><?= $model->by_dirut_display ?></u></b></span><br>
                            <span style="font-size: 1.1rem; margin-top: 5px;">Direktur Utama</span>
                        </div>
                    </div>
                </div>
                <br><br>
			</div>
            <div class="modal-footer" style="text-align: center;">
				<?php 
					echo \yii\helpers\Html::button( Yii::t('app', 'Selesai!'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'yes()']);
				?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
formconfig();
setHasilConfirm();
setApproval();
", yii\web\View::POS_READY); 
?>
<script>
function setHasilConfirm(){
	var stockopname_agenda_id = "<?= $modAgenda->stockopname_agenda_id ?>";
    var jenis_produk = <?= json_encode($jenis_produk); ?>;
    $("#hasil-confirm").html("");
    $("#hasil-confirm").addClass("animation-loading");
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/setHasil']); ?>',
        type   : 'POST',
        data   : {stockopname_agenda_id:stockopname_agenda_id,jenis_produk:jenis_produk,confirm:true},
        success: function (data) {
			if(data.hasil){
                $("#hasil-confirm").html(data.hasil);
//                $("#table-detail > tbody > tr:last").find("td").each(function(){
//                    var isi = $(this).html();
//                    $(this).html( "<b>"+isi+"</b>" );
//                });
            }
            if(data.fnsy){
                
            }
            $("#hasil-confirm").removeClass("animation-loading");
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function setApproval(){
    if( $("input:radio[name*='[lanjut_adjustment]']:checked").val() == "1" ){
        $("#place-approval").slideDown();
    }else{
        $("#place-approval").slideUp();
    }
}
function yes(){
    var data = $("#form-closing").serialize();
    var jenis_produk = '<?= implode(",", $jenis_produk) ?>';
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/hasil']); ?>',
		type   : 'GET',
		data   : {id:<?= $modAgenda->stockopname_agenda_id ?>,confirm:true,jenis_produk:jenis_produk,data:data},
		success: function (data) {
			$('#modal-delete-record').modal('hide');
			if(data.status){
				if(data.message){
                    cisAlert(data.message);
				}
				<?php if(isset($tableid)){ ?> 
					$('#<?= $tableid ?>').dataTable().fnClearTable(); 
				<?php } ?>
				if(data.callback){
					eval(data.callback);
				}else{
					
				}
			}else{
				if(data.message){
                    if(data.message.errorInfo){
                        cisAlert(data.message.errorInfo[2]);
                    }else{
                        cisAlert(data.message);
                    }
				}
			}
			$('#modal-delete-record').find('.progress-success .bar').animate({'width':'0%'});
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		progress: function(e) {
			if(e.lengthComputable) {
				var pct = (e.loaded / e.total) * 100;
				$('#modal-delete-record').find('.progress-success .bar').animate({'width':pct.toPrecision(3)+'%'});
			}else{
				console.warn('Content Length not reported!');
			}
		}
	});
}
</script>