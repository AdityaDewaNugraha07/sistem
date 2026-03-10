<div class="modal fade" id="modal-penerimaanspb-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail SPB'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Kode SPB'); ?></strong></label>
                            <div class="col-md-7"><?= $model->spb_kode ?></div>
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
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal'); ?></strong></label>
                            <div class="col-md-7"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spb_tanggal); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status'); ?></strong></label>
                            <div class="col-md-7">
                                <?php 
                                    if($model->spb_status == 'BELUM DIPROSES'){
                                        echo '<span class="label label-sm label-info"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'SEDANG DIPROSES'){
                                        echo '<span class="label label-sm label-warning"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'TERPENUHI'){
                                        echo '<span class="label label-sm label-success"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'DITOLAK'){
                                        echo '<span class="label label-sm label-danger"> '.$model->spb_status.' </span>';
                                    }
                                ?>
                            </div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Prioritas'); ?></strong></label>
                            <div class="col-md-7"><?= $model->spb_tipe ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Catatan Khusus'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spb_keterangan)?$model->spb_keterangan:" - "); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Diminta Oleh'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spb_diminta)?$model->spbDiminta->pegawai_nama:' - ') ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Disetujui Oleh'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spb_disetujui)?$model->spbDisetujui->pegawai_nama:' - ') ?></div>
                        </div>
						<?php if(!empty($model->spb_mengetahui)){ ?>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Diketahui Oleh'); ?></strong></label>
							<div class="col-md-7"><?= (!empty($model->spb_mengetahui)?$model->spbMengetahui->pegawai_nama:' - ') ?></div>
						</div>
						<?php } ?>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status Approval'); ?></strong></label>
							<div class="col-md-7" style="margin-top:7px;">
								<?php
								if(count($model)>0){
									if($model->approve_status == \app\models\TApproval::STATUS_APPROVED){
										echo '<span class="label label-sm label-success"> '.$model->approve_status .' </span>';
									}else if($model->approve_status == \app\models\TApproval::STATUS_REJECTED){
										echo '<span class="label label-sm label-danger"> '.$model->approve_status  .' </span>';
									}else{
										echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
									}
								}else{
									echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
								}
								?>
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status Penerimaan'); ?></strong></label>
							<div class="col-md-7" style="margin-top:7px;">
								<?php 
								$modBpb = \app\models\TBpb::find()->where(['spb_id'=>$model->spb_id])->orderBy(['created_at'=>SORT_DESC])->all();
								if(count($modBpb)>0){
									foreach($modBpb as $i => $bpb){
										if($i!=0){ echo "<br>"; }
										if($bpb->bpb_status == "BELUM DITERIMA"){
											echo "<a style='font-size:0.8em;' class='font-red-intense' data-bpb='$bpb->bpb_id' onclick='infoBpb(".$bpb->bpb_id.")'>".$bpb->bpb_kode." - ".$bpb->bpb_status."</a>";
										}else if($bpb->bpb_status == "SUDAH DITERIMA"){
											echo "<a style='font-size:0.8em;' class='font-green-meadow' data-bpb='$bpb->bpb_id' onclick='infoBpb(".$bpb->bpb_id.")'>".$bpb->bpb_kode." - ".$bpb->bpb_status."</a>";
										}
									}
								}else{
									echo "<i style='font-size:0.8em;'>-- --</i>";
								}
								?>
							</div>
						</div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Nama Barang'); ?></th>
										<th><?= Yii::t('app', 'Jenis Barang'); ?></th>
										<th><?= Yii::t('app', 'Qty Pesan'); ?></th>
										<th><?= Yii::t('app', 'Qty Terpenuhi'); ?></th>
										<th><?= Yii::t('app', 'Qty Stok'); ?></th>
										<th><?= Yii::t('app', 'Satuan'); ?></th>
										<th><?= Yii::t('app', 'Tgl Dipakai'); ?></th>
										<th><?= Yii::t('app', 'Keterangan'); ?></th>
										<th><?= Yii::t('app', 'Status'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modDetail as $i => $detail){ ?>
									<tr>
										<td><?php echo $i+1 ?></td>
										<td><?php echo $detail->bhp->bhp_nm; ?></td>
										<td><?php echo $detail->bhp->bhp_group; ?></td>
										<td style="text-align: center"><?php echo $detail->spbd_jml; ?></td>
										<td style="text-align: center"><?php echo $detail->spbd_jml_terpenuhi; ?></td>
										<td style="text-align: center"><?= $detail->bhp->current_stock; ?></td>
										<td style="text-align: center"><?= $detail->bhp->bhp_satuan; ?></td>
										<td><?php echo (!empty($detail->spbd_tgl_dipakai)?\app\components\DeltaFormatter::formatDateTimeForUser2($detail->spbd_tgl_dipakai):'<center>-</center>'); ?></td>
										<td style="font-size: 1.1rem; padding: 5px;"><?= !empty($detail->spbd_ket)?$detail->spbd_ket:'<center>-</center>'; ?></td>
										<td><?php echo (($detail->spbd_jml <= $detail->spbd_jml_terpenuhi)?'
											<span class="label label-sm label-success"> Oke </span>
											':'
											<span class="label label-sm label-danger"> Belum Terpenuhi </span>'
											) ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				<?php if($model->mutation_req === TRUE){ ?>
					<b class="font-red-mint pull-left">SPB ini telah dilakukan permintaan mutasi</b><br>
					<?php
					$checkMutasi = app\models\TMutasiGudanglogistik::find()->where(['spb_id'=>$model->spb_id])->all();
					if(count($checkMutasi)>0){
						echo '<b class="pull-left"> Kode Mutasi : ';
						foreach($checkMutasi as $ii => $mutasi){
							echo "<a class='font-green-seagreen'>".$mutasi->kode."</a>";
							if(count($checkMutasi) != ($ii+1) ){ echo ", "; }
						}
						echo '</b>';
					}else{
						echo '<b class="pull-left"> Status Mutasi : <span class="font-blue-steel">Belum Dimutasikan</span></b>';
					}
					?>
				<?php } ?>
				<?php if($model->spb_diminta == \app\components\Params::DEFAULT_PEGAWAI_ID_MBAK_ONNY){
					echo '<span class="pull-left">'.yii\helpers\Html::a(Yii::t('app', '<i class="fa fa-print"></i>&nbsp; Print '),'javascript:void(0)',['class'=>'btn blue btn-outline','onclick'=>'printSpb('.$model->spb_id.')']).' &nbsp; </span>';
				} ?>
				<?php if(!empty($model->spb_keterangan)){
					echo "<span class='pull-left font-red-flamingo'><i><u>Catatan Khusus</u> : <b>".$model->spb_keterangan."</b></i></span>";
				} ?>
                <?php
				if($model->approve_status != app\models\TApproval::STATUS_REJECTED){
                    $jam_skrg = date("H:i:s");
                    if($jam_skrg < "15:30:00"){ //  maksimal SPP input jam 15.30 sore
                        echo yii\helpers\Html::a(Yii::t('app', '<i class="fa fa-external-link"></i>&nbsp; Proses ke SPP '),'javascript:void(0)',['class'=>'btn blue-hoki btn-outline','onclick'=>'lanjutSpp('.$model->departement_id.','.$model->spb_id.')']);
                    }else{
                        echo yii\helpers\Html::a(Yii::t('app', '<i class="fa fa-external-link"></i>&nbsp; Proses ke SPP '),'javascript:void(0)',['class'=>'btn grey btn-outline tooltips','data-original-title'=>'Waktu Proses SPP Maksimal 15:30 WIB']);
                    }
					echo yii\helpers\Html::a(Yii::t('app', '<i class="fa fa-external-link"></i>&nbsp; Proses ke BPB '),'javascript:void(0)',['class'=>'btn green-meadow btn-outline','onclick'=>'lanjutBpb('.$model->departement_id.','.$model->spb_id.')']);
				}
                if($model->spb_status == 'BELUM DIPROSES'){
					if($model->mutation_req !== TRUE){
						echo yii\helpers\Html::a(Yii::t('app', '<i class="fa fa-external-link"></i>&nbsp; Ajukan Mutasi '),'javascript:void(0)',['class'=>'btn blue-ebonyclay btn-outline','onclick'=>'permintaanMutasi('.$model->spb_id.')']);
					}
                    echo yii\helpers\Html::a(Yii::t('app', '<i class="fa fa-edit"></i>&nbsp; Edit SPB '),'javascript:void(0)',['class'=>'btn yellow btn-outline','onclick'=>'editSpb('.$model->spb_id.')']);
                    echo yii\helpers\Html::a(Yii::t('app', '<i class="fa fa-remove"></i>&nbsp; Tolak SPB '),'javascript:void(0)',['class'=>'btn red-intense btn-outline','onclick'=>'tolakSpb('.$model->spb_id.')']);
                }
                ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    $(\".tooltips\").tooltip({ delay: 50 });
", yii\web\View::POS_READY); ?>
<script>
function lihatSpb(spb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/spb/index','spb_id'=>'']); ?>'+spb_id);
}
function editSpb(spb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/spb/index','edit'=>true,'spb_id'=>'']); ?>'+spb_id);
}
function tolakSpb(spb_id){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanspb/tolakSpb']); ?>',
        type   : 'POST',
        data   : {spb_id:spb_id},
        success: function (data) {
            if(data){
                $("#modal-penerimaanspb-info").hide();
                clearmodal();
                resetForm(); 
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function lanjutBpb(dept_id,spb_id){
    window.open("<?= \yii\helpers\Url::toRoute('/logistik/bpb/index') ?>?loadjs[dept_id]="+dept_id+"&loadjs[spb_id]="+spb_id,"_blank");
}
function lanjutSpp(dept_id,spb_id){
    window.open("<?= \yii\helpers\Url::toRoute('/logistik/spp/index') ?>?loadjs[dept_id]="+dept_id+"&loadjs[spb_id]="+spb_id,"_blank");
}
function permintaanMutasi(spb_id){
    openModal('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanspb/permintaanMutasi','id'=>'']); ?>'+spb_id,'modal-global-confirm');
}

function printSpb(id){
	window.open("<?= yii\helpers\Url::toRoute('/logistik/spb/printout') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>