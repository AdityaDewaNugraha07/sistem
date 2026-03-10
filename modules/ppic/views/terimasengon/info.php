<?php
if(substr($model->kode,0,3) == 'ATS'){
    $jnsLog = "Log Sengon";
}else if(substr($model->kode,0,3) == 'ATJ'){
    $jnsLog = "Log Jabon";    
}else{
    $jnsLog ="-";
}
?>
<div class="modal fade" id="modal-afkirsengon-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Afkir '); ?> <?= $jnsLog ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Kode Afkir'); ?></strong></label>
                            <div class="col-md-7"><?= $model->kode; ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Jenis Log'); ?></strong></label>
                            <div class="col-md-7"><?= $jnsLog ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal Terima'); ?></strong></label>
                            <div class="col-md-7"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>    
                </div><br>
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Diameter'); ?></th>
										<th><?= Yii::t('app', 'Panjang'); ?></th>
										<th><?= Yii::t('app', 'Qty'); ?></th>
										<th><?= Yii::t('app', 'M3'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modDetail as $i => $detail){ ?>
									<tr>
										<td><?php echo $i+1 ?></td>
										<td><?php echo $detail->diameter; ?></td>
										<td><?php echo $detail->panjang; ?></td>
										<td style="text-align: center"><?php echo $detail->qty_pcs; ?></td>
										<td style="text-align: center"><?php echo $detail->qty_m3; ?></td>
										
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
<?php $this->registerJs("
    $(\".tooltips\").tooltip({ delay: 50 });
", yii\web\View::POS_READY); ?>
<script>
function lihatSpb(spb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/terimasengon/index','afkir_sengon_id'=>'']); ?>'+afkir_sengon_id);
}

</script>