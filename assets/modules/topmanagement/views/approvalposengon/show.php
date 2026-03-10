<style>
.form-group {
    margin-bottom: 0 !important;
}
table.table-striped thead tr th{
	padding : 3px !important;
}
.table-striped, 
.table-striped > tbody > tr > td, 
.table-striped > tbody > tr > th, 
.table-striped > tfoot > tr > td, 
.table-striped > tfoot > tr > th, 
.table-striped > thead > tr > td, 
.table-striped > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Rencana'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kode ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Rencana'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal) ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Kebutuhan'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_awal)." - ".\app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_akhir) ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Pengiriman'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser($modReff->tanggal_pengiriman_awal)." - ".\app\components\DeltaFormatter::formatDateTimeForUser($modReff->tanggal_pengiriman_akhir) ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'PO Dibuat Oleh'); ?></label>
                <div class="col-md-7"><strong><?= $modReff->Pembuat ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['assigned_to'] ?></label>
				<div class="col-md-7"><strong><?= $model->assignedTo->pegawai_nama; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['approved_by'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->approved_by)?$model->approvedBy->pegawai_nama:"-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['tanggal_approve'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->tanggal_approve)?app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve):"-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['level'] ?></label>
				<div class="col-md-7"><strong><?= $model->level; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['status'] ?></label>
				<div class="col-md-7"><strong>
					<?php
					if($model->status == \app\models\TApproval::STATUS_APPROVED){
						echo '<span class="label label-success">'.$model->status.'</span>';
					}else if($model->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						echo '<span class="label label-default">'.$model->status.'</span>';
					}else if($model->status == \app\models\TApproval::STATUS_REJECTED){
						echo '<span class="label label-danger">'.$model->status.'</span>';
					}
					?>
				</strong></div>
			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-hoki bordered">
				<div class="portlet-title">
					<div class="tools" style="float: left;">
						<a href="javascript:;" class="collapse" data-original-title="" title=""> </a> &nbsp; 
					</div>
					<div class="caption"> <?= Yii::t('app', 'Show Detail'); ?> </div>
				</div>
				<div class="portlet-body" style="background-color: #d9e2f0" >
					<div class="row"  style="margin-top: -15px;">
                        <div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-industri">
									<thead>
                                        <?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang') ?>
                                        <tr>
                                            <th style="width: 30px;" rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                            <th style="" rowspan="2"><?= Yii::t('app', 'Suplier'); ?></th>
                                            <th colspan="<?= count($ukuranganrange)+1 ?>">Qty M<sup>3</sup> By Panjang Log</th>
                                            <th rowspan="2" colspan="<?= count($ukuranganrange)+1 ?>">Harga By Diameter</th>
                                        </tr>
                                        <tr>
                                            <?php foreach($ukuranganrange as $i => $range){ ?>
                                            <th style="width: 110px;"><?= $range ?> cm</th>
                                            <?php } ?>
                                            <th style="width: 120px;">Subtotal M<sup>3</sup></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$total_m3 = 0;
                                        foreach($ukuranganrange as $i => $range){
											$total_ver_m3[$range] = 0;
										}
                                        $modDetail = \app\models\TPosengon::find()->where(['posengon_rencana_id'=>$modReff->posengon_rencana_id])->orderBy("posengon_id ASC")->all();
                                        if(count($modDetail)>0){
                                            foreach($modDetail as $i => $detail){
                                                echo "<tr>";
                                                echo	"<td class='text-align-center'>".($i+1)."</td>";
                                                echo	"<td style=''><b>".$detail->suplier->suplier_nm."</b><br> ".$detail->suplier->suplier_almt."</td>";
                                                    $subtotal_m3 = 0;
                                                    foreach($ukuranganrange as $i => $range){
                                                        $sql = "SELECT kuota FROM t_posengon WHERE posengon_id = ".$detail->posengon_id;
                                                        $modQty = Yii::$app->db->createCommand($sql)->queryOne();
                                                        $kuotaArr = \yii\helpers\Json::decode($modQty['kuota']);
                                                        $kuota = (isset($kuotaArr[$range])?$kuotaArr[$range]:0);
                                                        echo "<td class='text-align-right' style='vertical-align:middle'>". number_format( $kuota )." M<sup>3</sup></td>";
                                                        $subtotal_m3 += $kuota;
                                                        $total_ver_m3[$range] += $kuota;
                                                    }
                                                    $total_m3 += $subtotal_m3;
                                                echo    "<td class='text-align-right' style='vertical-align:middle'>". number_format($subtotal_m3)." M<sup>3</sup></td>";
                                                
                                                
                                                $diameterharga = \yii\helpers\Json::decode($detail->diameter_harga); $maxrow = 0;
                                                foreach($diameterharga as $i => $asdasd){
                                                    if( count($asdasd) > $maxrow){
                                                        $maxrow = count($asdasd);
                                                    }
                                                }
                                                ?>
                                                
                                                <td colspan="<?= (count($diameterharga)*2) ?>">
                                                    <table style="width: 100%">
                                                        <thead>
                                                            <tr style="border-bottom: solid 1px #000;">
                                                                <?php foreach($diameterharga as $i => $diaharga){ ?>
                                                                    <th colspan="2" style="padding: 3px; font-size: 1rem; <?= ( count($diameterharga)!=($i+1)?"border-right: solid 1px #000;":"" ) ?>" class="text-align-center">
                                                                        <?= "Panjang ".$diaharga[0]['panjang']."cm (". ucfirst(str_replace("_", " ", $diaharga[0]['wilayah'])).")" ?>
                                                                    </th>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr style="border-bottom: solid 1px #000;">
                                                                <?php foreach($diameterharga as $i => $diaharga){ ?>
                                                                    <th style="padding: 1px; font-size: 1rem; width: <?= (100/Count($diameterharga))/2 ?>%; border-right: solid 1px #000;" class="text-align-center">D (cm)</th>
                                                                    <th style="padding: 1px; font-size: 1rem; width: <?= (100/Count($diameterharga))/2 ?>%; <?= ( count($diameterharga)!=($i+1)?"border-right: solid 1px #000;":"" ) ?>" class="text-align-center">Harga</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php for($jj=0; $jj<$maxrow; $jj++){ ?>
                                                            <tr style="border-bottom: solid 1px #000;">
                                                                <?php foreach($diameterharga as $i => $diaharga){ ?>
                                                                    <td style="padding: 1px; font-size: 1rem; border-right: solid 1px #000;" class="text-align-center">
                                                                        <?php echo isset($diaharga[($jj)])? $diaharga[($jj)]['diameter_awal']."-".$diaharga[($jj)]['diameter_akhir']:""; ?>
                                                                    </td>
                                                                    <td style="padding: 1px; font-size: 1rem; <?= ( count($diameterharga)!=($i+1)?"border-right: solid 1px #000;":"" ) ?>" class="text-align-right">
                                                                        <?php echo isset($diaharga[($jj)])? number_format($diaharga[($jj)]['harga']):""; ?>
                                                                    &nbsp; </td>
                                                                <?php } ?>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                
                                            <?php echo "</tr>";
                                            }
                                        }else{
                                            echo "<tr><td colspan='5'><i><center>Data not found</center></i></td></tr>";
                                        }
										?>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="text-align: right;">&nbsp; TOTAL (M<sup>3</sup>)</td>
                                            <?php 
                                            foreach($ukuranganrange as $i => $range ){
                                                echo "<td class='text-align-right'>".number_format($total_ver_m3[$range])." M<sup>3</sup></td>";
                                            }
                                            echo "<td class='text-align-right'>".number_format($total_m3)." M<sup>3</sup></td>";
                                            ?>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: right;">&nbsp; TOTAL (%)</td>
                                            <?php 
                                            foreach($ukuranganrange as $i => $range ){
                                                echo "<td class='text-align-right'>".(($total_m3!=0)? number_format( ($total_ver_m3[$range]/$total_m3)*100 ):0)." %</td>";
                                            }
                                            echo "<td class='text-align-right'>100 %</td>";
                                            ?>
                                        </tr>
                                    </tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer" style="text-align: center;">
	<?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) && (count($modDetail)>0) ){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
	<?php } ?>
</div>
<script>

</script>