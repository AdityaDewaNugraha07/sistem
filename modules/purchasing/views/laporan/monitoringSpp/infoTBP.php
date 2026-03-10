<?php
$modTbp = \app\models\TTerimaBhp::findOne(['terima_bhp_id'=>$terima_bhp_id]);
$modDetail = \app\models\TTerimaBhpDetail::findAll(['terima_bhpd_id' =>$id]); //, 'bhp_id'=>$bhp_id
if(!empty($modTbp->spo_id)){
    $modelreff = app\models\TSpo::findOne(['spo_id'=>$modTbp->spo_id]);
    $reffPembelian = $modelreff->spo_kode;
}elseif(!empty($modTbp->spl_id)){
    $modelreff = app\models\TSpl::findOne(['spl_id'=>$modTbp->spl_id]);
    $reffPembelian = $modelreff->spl_kode;
}
?>
<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Penerimaan Barang');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-6"><strong><?= $modTbp->terimabhp_kode; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modTbp->tglterima) ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">	
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Reff Pembelian'); ?></label>
                            <div class="col-md-6">
                                <strong><?= (!empty($reffPembelian)? $reffPembelian:"-"); ?></strong>
                            </div>
                        </div>					
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Created By/Penerima'); ?></label>
                            <div class="col-md-6"><strong><?= (!empty($modTbp->created_by)? $modTbp->tbpCreatedBy->pegawai->pegawai_nama:""); ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Petugas Pengecekan'); ?></label>
                            <div class="col-md-6"><strong><?= (!empty($modTbp->pegawaipenerima)? $modTbp->pegawaichecker0->pegawai_nama:""); ?></strong></div>
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
										<th style="text-align: center;"><?= Yii::t('app', 'Harga'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Suplier'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modDetail as $i => $detail){ 
										$mark = '';
										$role = false;
										if($bhp_id == $detail->bhp_id){
                                            $mark = 'background-color:  #fceeb1;';
                                            $role = FALSE;
                                        }
										?>
									<tr style="<?= $mark; ?>">
										<td style="text-align: center;"><?= $i+1; ?></td>
										<td><?= $detail->bhp->bhp_nm; ?></td>
										<td style="text-align: center;"><?= !empty($detail->terimabhpd_qty)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_qty):"<center>-</center>"; ?> <?= $detail->bhp->bhp_satuan ?></td>
										<td style="text-align: center;"><?= !empty($detail->terimabhpd_harga)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga):"<center>-</center>"; ?></td>
										<td><?= !empty($detail->suplier->suplier_id)?$detail->suplier->suplier_nm:'<center>-</center>' ?></td>
										<td style="font-size: 1.1rem"><?= $detail->terimabhpd_keterangan ?></td>
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