<?php app\assets\DatatableAsset::register($this); ?>
<style>
table{
	font-size: 1.4rem;
}
</style>
<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><center><?= Yii::t('app', 'Informasi Voucher Penerimaan'); ?></center></h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<?php
					$terima = $model->total_nominal;
					$terpakai = 0;
					if(count($modPiutang)>0){
						foreach($modPiutang as $i => $mod){
							$terpakai += $mod['bayar'];
						}
					}
					$sisa = $terima-$terpakai;
					?>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kode Voucher</label>
                            <div class="col-md-7"><strong>: <?= $model->kode." (".$model->kode_bbm.")" ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Jenis Penerimaan</label>
                            <div class="col-md-7"><strong>: <?= $model->tipe ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Sender</label>
                            <div class="col-md-7"><strong>: <?= $model->sender ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Deskripsi</label>
                            <div class="col-md-7"><strong>: <?= $model->deskripsi ?></strong></div>
						</div>
                    </div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Nominal Terima</label>
                            <div class="col-md-7"><strong>: <?= $model->mata_uang."&nbsp; ".app\components\DeltaFormatter::formatNumberForUserFloat($terima) ?></strong></div>
						</div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Pernah Terpakai</label>
                            <div class="col-md-7"><strong>: <?= $model->mata_uang."&nbsp; ".app\components\DeltaFormatter::formatNumberForUserFloat($terpakai) ?></strong></div>
						</div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Sisa</label>
                            <div class="col-md-7"><strong>: <?= $model->mata_uang."&nbsp; ".app\components\DeltaFormatter::formatNumberForUserFloat($sisa) ?></strong></div>
						</div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Terpakai Pada Bill</label>
                            <div class="col-md-7" style="font-size: 1.2rem;">: 
								<?php
								if(count($modPiutang)>0){
									foreach($modPiutang as $i => $mod){
										if($i>0){ echo "&nbsp; "; }
										echo "<a onclick='infoNota(\"".$mod['bill_reff']."\")'>".$mod['bill_reff']."</a> (".$model->mata_uang."&nbsp; ".app\components\DeltaFormatter::formatNumberForUserFloat($mod['bayar']).")<br>";
									}
								}
								?>
							</div>
						</div>
					</div>
                </div>
            </div>
             <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?php // echo yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>'printBbm("'.$model[0]->kode_bbm.'")']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>