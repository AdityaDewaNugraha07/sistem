<?php
$modSpb = \app\models\TSpb::findOne(['spb_id'=>$spb_id]);
$modDetail = \app\models\TSpbDetail::findAll(['spbd_id' =>$id]);

?>
<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Permintanan Barang (SPB)');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-6"><strong><?= $modSpb->spb_kode; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modSpb->spb_tanggal) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Dept. Pemesan'); ?></label>
                            <div class="col-md-6"><strong><?= $modSpb->departement->departement_nama; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Status'); ?></label>
                            <div class="col-md-6"><strong><?= $modSpb->spb_status; ?></strong></div>
                        </div>
						<div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Sifat Permintaan'); ?></label>
                            <div class="col-md-6"><strong><?= $modSpb->spb_tipe; ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">	
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Request By'); ?></label>
                            <div class="col-md-6"><strong><?= (!empty($modSpb->spb_diminta)?$modSpb->spbDiminta->pegawai_nama:""); ?></strong></div>
                        </div>
						<div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Menyetujui'); ?></label>
                            <div class="col-md-6"><strong><?= (!empty($modSpb->spb_disetujui)?$modSpb->spbDisetujui->pegawai_nama:""); ?></strong></div>
                        </div>
						<div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Mengetahui'); ?></label>
                            <div class="col-md-6"><strong><?= (!empty($modSpb->spb_mengetahui)?$modSpb->spbMengetahui->pegawai_nama:""); ?></strong></div>
                        </div>
						<div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Catatan Khusus'); ?></label>
                            <div class="col-md-6 font-red-soft"><strong><?= (!empty($modSpb->spb_keterangan)?$modSpb->spb_keterangan:""); ?></strong></div>
                        </div>
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-laporan">
								<thead>
									<tr style="background-color: #F1F4F7; ">
										<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Items'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty<br>Terpenuhi'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Satuan'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modDetail as $i => $detail){ 
										$mark = '';
										$role = false;
										if($id == $detail->spbd_id){
                                            $mark = 'background-color:  #fceeb1;';
                                            $role = FALSE;
                                        }
										?>
									<tr style="<?= $mark; ?>">
										<td style="text-align: center;"><?= $i+1; ?></td>
										<td><?= $detail->bhp->bhp_nm; ?></td>
										<td style="text-align: center;"><?= !empty($detail->spbd_jml)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->spbd_jml):"<center>-</center>"; ?></td>
										<td style="text-align: center;"><?= !empty($detail->spbd_jml_terpenuhi)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->spbd_jml_terpenuhi):"<center>-</center>"; ?></td>
										<td style="text-align: center;"><?= $detail->bhp->bhp_satuan ?></td>
										<td style="font-size: 1.1rem"><?= $detail->spbd_ket ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>