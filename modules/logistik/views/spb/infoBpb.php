<div class="modal fade" id="modal-terima-bpb" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail BPB'); ?> <strong><?= $model->bpb_kode; ?></strong></h4>
            </div>
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Kode BPB'); ?></strong></label>
                            <div class="col-md-7"><?= $model->bpb_kode ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Nomor Berkas SPB'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spb_nomor)?$model->spb_nomor:" - ") ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Dept. Pemesan'); ?></strong></label>
                            <div class="col-md-7"><?= $model->departement->departement_nama; ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status'); ?></strong></label>
                            <div class="col-md-7 bpb_status">
                                <?php 
                                    if($model->bpb_status == 'BELUM DITERIMA'){
                                        echo '<span class="label label-sm label-info"> '.$model->bpb_status.' </span>';
                                    }else if($model->bpb_status == 'SUDAH DITERIMA'){
                                        echo '<span class="label label-sm label-success"> '.$model->bpb_status.' </span>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal Keluar'); ?></strong></label>
							<div class="col-md-7"><?= (!empty($model->bpb_tgl_keluar)?\app\components\DeltaFormatter::formatDateTimeForUser2($model->bpb_tgl_keluar):" - "); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Dikeluarkan Oleh'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->bpb_dikeluarkan)?$model->bpbDikeluarkan->pegawai_nama:" - "); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal Diterima'); ?></strong></label>
                            <div class="col-md-7 bpb_tgl_diterima"><?= $model->bpb_tgl_diterima; ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Diterima Oleh'); ?></strong></label>
                            <div class="col-md-7 bpb_diterima"><?= $model->bpb_diterima; ?></div>
                        </div>
						
                    </div>
                </div><br>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-bordered table-advance table-detail-hover" id="table-detail" style="background-color: #fff">
							<thead>
								<tr>
									<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
									<th><?= Yii::t('app', 'Nama Barang'); ?></th>
									<th><?= Yii::t('app', 'Jumlah Pesan'); ?></th>
									<th><?= Yii::t('app', 'Jumlah Terpenuhi'); ?></th>
									<th><?= Yii::t('app', 'Keterangan'); ?></th>
									<?php if(empty($modTerimaBhpSub)) {?>
									<th><?= Yii::t('app', 'Tidak Sesuai'); ?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($modDetail as $i => $detail){ 
									$detailspb = \app\models\TSpbDetail::getDetailItemSpb($model->spb_id, $detail->bhp_id);
								?>
								<tr>
									<td style="text-align: center;"><?php echo $i+1 ?></td>
									<td><?php echo $detail->bhp->bhp_nm; ?></td>
									<td style="text-align: center"><?php echo $detailspb->spbd_jml; ?></td>
									<td style="text-align: center"><?php echo $detail->bpbd_jml; ?></td>
									<td style="text-align: center"><?php echo $detail->bpbd_ket; ?></td>
									<?php if(empty($modTerimaBhpSub)) {?>
									<td style="vertical-align: middle; text-align: center;">
										<?php if(!empty($detail->cancel_transaksi_id)){ ?>
											<span class="label label-sm label-danger"><?= app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
										<?php }else{ ?>
											<?php if(Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER){ ?>
												<a class="btn btn-xs btn-outline red-flamingo" onclick="abortItem(<?= $detail->bpbd_id ?>,<?= $model->bpb_id ?>);" style="font-size: 1rem"><i class="fa fa-remove"></i> Abort</a>
											<?php } ?>
										<?php } ?>
									</td>
									<?php } ?>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php if (!empty($modTerimaBhpSub)){?>
								<h4><?= Yii::t('app', 'Rincian Set Plan '); ?> <strong><?= $model->bpb_kode; ?></strong></h4>
								<table class="table table-bordered table-advance table-rincian-hover" id="table-rincian" style="background-color: #fff">
									<thead>
										<tr>
											<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="text-align: center;"><?= Yii::t('app', 'Nama Barang'); ?></th>
											<th style="text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
											<th style="text-align: center;"><?= Yii::t('app', 'Target Plan'); ?></th>
											<th style="text-align: center;"><?= Yii::t('app', 'Peruntukan'); ?></th>
											<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
											<th style="text-align: center;"><?= Yii::t('app', 'Harga/Item'); ?></th>
											<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
											foreach($modTerimaBhpSub as $i => $terimabhp){ 
										?>
										<tr>
											<td style="vertical-align: middle; text-align: center;"><?php echo $i+1 ?></td>
											<td style="vertical-align: middle;" id="item-detail"><?= $terimabhp->bhp->bhp_nm; ?></td>
											<td style="vertical-align: middle; text-align: center;">
												<?= \app\components\DeltaFormatter::formatDateTimeForUser2($terimabhp->tanggal); ?>
											</td>
											<td style="vertical-align: middle; text-align: center;"><?= $terimabhp->target_plan; ?></td>
											<td style="vertical-align: middle; text-align: center;"><?= $terimabhp->target_peruntukan; ?></td>
											<td style="vertical-align: middle; width: 5%; text-align: center;"><?= $terimabhp->qty; ?></td>
											<td style="vertical-align: middle; width: 10%; text-align: center;"><?= $terimabhp->harga_peritem; ?></td>
											<td style="vertical-align: middle; width: 20%; ">
												<?php
												if ($terimabhp->keterangan == ''){
													echo '<center> - </center>';
												} else {
													echo $terimabhp->keterangan; 
												}
												?>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php } ?>
					</div>
				</div>
			</div>
            <div class="modal-footer">
				<?php if($model->bpb_status == "BELUM DITERIMA"){ ?>
					<?= yii\helpers\Html::button(Yii::t('app', '<i class="fa fa-download"></i> Terima BPB'),['class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>"terimaBarangModal(".$model->bpb_id.")"]); ?>
				<?php } ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    
", yii\web\View::POS_READY); ?>
<script>
	function terimaBarangModal(id) {
		var url = '<?= \yii\helpers\Url::toRoute(['/logistik/spb/terimaBarangModal', 'bpb_id' => '']) ?>' + id;
		var modal_id = 'modal-terima-bpb';
		$(".modals-place-2").load(url, function() {
			$("#modal-terima-bpb .modal-dialog").css('width','85%');
			$("#" + modal_id).modal('show');
			$("#" + modal_id).on('hidden.bs.modal', function() {
				dtAftersave();
			});
			spinbtn();
			draggableModal();
		});
		// return false;
	}

//function refreshInfoBpb(){
//	var bpb_id = <?php //echo $model->bpb_id ?>;
//	$.ajax({
//        url    : '<?php //echo \yii\helpers\Url::toRoute(['/logistik/spb/infoBpb']); ?>',
//        type   : 'GET',
//        data   : {bpb_id:bpb_id,refresh:true},
//        success: function (data) {
//            if(data){
//                if(data.bpb_status == "BELUM DITERIMA"){
//					$('.bpb_status').html('<span class="label label-sm label-info"> '+data.bpb_status+' </span>');
//				}else if(data.bpb_status == "SUDAH DITERIMA"){
//					$('.bpb_status').html('<span class="label label-sm label-success"> '+data.bpb_status+' </span>');
//				}
//				$('.bpb_tgl_diterima').html(data.bpb_tgl_diterima);
//				$('.bpb_diterima').html(data.bpb_diterima);
//				
//            }
//        },
//        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
//    });
//}

function abortItem(id,bpb_id){
	var url = '<?php echo \yii\helpers\Url::toRoute(['/logistik/bpb/abortItem','id'=>'']); ?>'+id+"&bpb_id="+bpb_id;
	$(".modals-place-confirm").load(url, function() {
		$("#modal-transaksi").modal('show');
		$("#modal-transaksi").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
} 

</script>