<div class="modal fade" id="modal-approval-spb" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'INFO SPB'); ?> <strong><?= $model->spb_kode; ?></strong></h4>
            </div>
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Kode SPB'); ?></strong></label>
                            <div class="col-md-7"><?= $model->spb_kode ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal SPB'); ?></strong></label>
							<div class="col-md-7"><?= (!empty($model->spb_tanggal)?\app\components\DeltaFormatter::formatDateTimeForUser2($model->spb_tanggal):" - "); ?></div>
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
                            <div class="col-md-7 spb_status">
                                <?php 
                                    if($model->spb_status == 'TERPENUHI'){
                                        echo '<span class="label label-sm label-success"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'BELUM DIPROSES'){
                                        echo '<span class="label label-sm label-info"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'SEDANG DIPROSES'){
                                        echo '<span class="label label-sm label-warning"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'DITOLAK'){
                                        echo '<span class="label label-sm label-danger"> '.$model->spb_status.' </span>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Diminta Oleh'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spb_diminta)?$model->spbDiminta->pegawai_nama:" - "); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Disetujui Oleh'); ?></strong></label>
                            <div class="col-md-7 spb_disetujui">
                                <?= (!empty($model->spb_disetujui)?$model->spbDisetujui->pegawai_nama:" - "); ?>
                                <?php 
                                if(!empty($model->reason_approval)){
                                    $modApproveReason = \yii\helpers\Json::decode($model->reason_approval);
                                    foreach($modApproveReason as $iap => $aprreas){
                                        if($aprreas['assigned_to'] == $model->spb_disetujui){
                                            if($aprreas['status'] === 'REJECTED'){
                                                $fontClass = "font-red-flamingo";
                                            }elseif($aprreas['status'] === 'APPROVED'){
                                                $fontClass = "font-green-seagreen";
                                            }else{
                                                $fontClass = "";
                                            }
                                            echo "<br><span class='$fontClass'>".$aprreas['status']." at: ".\app\components\DeltaFormatter::formatDateTimeForUser2($aprreas['tanggal_approve'])."</span>";
                                            echo '<span style="font-size: 1.1rem;">';
                                            echo "<br>&nbsp; <span class='$fontClass'>( ".$aprreas['reason']." )</span>";
                                            echo '</span>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Diketahui Oleh'); ?></strong></label>
                            <div class="col-md-7 spb_mengetahui">
                                <?= (!empty($model->spb_mengetahui)?$model->spbMengetahui->pegawai_nama:" - "); ?>
                                <?php 
                                if(!empty($model->reason_approval)){
                                    $modApproveReason = \yii\helpers\Json::decode($model->reason_approval);
                                    foreach($modApproveReason as $iap => $aprreas){
                                        if($aprreas['assigned_to'] == $model->spb_mengetahui){
                                            if($aprreas['status'] === 'REJECTED'){
                                                $fontClass = "font-red-flamingo";
                                            }elseif($aprreas['status'] === 'APPROVED'){
                                                $fontClass = "font-green-seagreen";
                                            }else{
                                                $fontClass = "";
                                            }
                                            echo "<br><span class='$fontClass'>".$aprreas['status']." at: ".\app\components\DeltaFormatter::formatDateTimeForUser2($aprreas['tanggal_approve'])."</span>";
                                            echo '<span style="font-size: 1.1rem;">';
                                            echo "<br>&nbsp; <span class='$fontClass'>( ".$aprreas['reason']." )</span>";
                                            echo '</span>';
                                        }
                                    }
                                }
                                ?>    
                            </div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status Approval'); ?></strong></label>
                            <div class="col-md-7 approve_status">
                                    <?php 
                                        if($model->approve_status == 'APPROVED'){
                                            echo '<span class="label label-sm label-success"> '.$model->approve_status.' </span>';
                                        }else if($model->approve_status == 'Not Confirmed'){
                                            echo '<span class="label label-sm label-info"> '.$model->approve_status.' </span>';
                                        }else if($model->approve_status == 'REJECTED'){
                                            echo '<span class="label label-sm label-danger"> '.$model->approve_status.' </span>';
                                        }
                                    ?>
                            </div>
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
									<th><?= Yii::t('app', 'Tanggal Dipakai'); ?></th>
									<th><?= Yii::t('app', 'Keterangan'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($modDetail as $i => $detail){ 
									// $detailspb = \app\models\TSpbDetail::getDetailItemSpb($model->spb_id, $detail->bhp_id);
								?>
								<tr>
									<td style="text-align: center;"><?php echo $i+1 ?></td>
									<td><?php echo $detail->bhp->bhp_nm; ?></td>
									<td style="text-align: center"><?php echo $detail->spbd_jml; ?></td>
									<td style="text-align: center"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail->spbd_tgl_dipakai); ?></td>
									<td style="vertical-align: middle; text-align: center;"><?= $detail->spbd_ket ?></td>
								</tr>
								<?php } ?>
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
    
", yii\web\View::POS_READY); ?>