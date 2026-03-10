<?php
app\assets\MagnificPopupAsset::register($this);
if(!empty($model->monitoring_pembelianlog_id)){
	$show = "";
	$input = "none;";
	$img = "";
	$modAttch = Yii::$app->db->createCommand("SELECT * FROM t_attachment WHERE reff_no = '{$model->kode}'")->queryAll();
	if(count($modAttch)){
		foreach($modAttch as $i => $attch){
            $img .= "<div class='col-md-2'>
                        <a href=".yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$attch['file_name'].">";
			$img .=         "<img src='".yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$attch['file_name']."' style='width:100%;'><a onclick='hapusAttch(".$attch['attachment_id'].");' style='font-size:1rem;'>Hapus</a>";
			$img .=     "</a>
                    </div>";
		}
		if(count($modAttch) != ($i+1)){
			$img .= "<br>";
		}
	}
}else{
	$show = "none;";
	$input = "";
	$img = "";
}
?>
<tr class="tr-monitoring">
	<td class="td-kecil" style="vertical-align: top; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, 'monitoring_pembelianlog_id',[]); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, 'pengajuan_pembelianlog_id',[]); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: top; padding-top: 20px; ">
		<div class="input-group date date-picker">
			<?= \yii\bootstrap\Html::activeTextInput($model, 'tanggal',['class'=>'form-control','style'=>'width:100%; padding :1px; height:25px;','readonly'=>'readonly','placeholder'=>'Tanggal']); ?>
			<span class="input-group-btn">
				<button class="btn default" type="button" style="margin-left: -20px; width: 25px; height: 25px; padding: 1px;">
					<i class="fa fa-calendar"></i>
				</button>
			</span>
		</div>
		<?= \yii\bootstrap\Html::activeTextInput($model, 'lokasi_logpond',['class'=>'form-control','style'=>'width:100%; padding: 1px; font-size:1.2rem;  height:25px;','placeholder'=>'Lokasi Logpond']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center; vertical-align: top !important;">
		<table style="width:100%; border: #D6D6D9 solid 1px;" id="monitoring-detail">
			<thead>
				<tr style="background-color: #D8D8D9">
					<td style="width:150px; padding: 2px;">Kayu</td>
					<td style="width:90px; padding: 2px;">Kondisi</td>
					<td style="width:70px; padding: 2px;">Btg</td>
					<td style="width:70px; padding: 2px;">m<sup>3</sup></td>
					<td style="width:70px; padding: 2px;">GR<sup>%</sup></td>
					<td style="width:70px; padding: 2px;">Pecah<sup>%</sup></td>
					<td style="width:100px; padding: 2px;">Gubal<sup>%</sup></td>
					<td style="padding: 2px;"></td>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($model->monitoring_pembelianlog_id)){ ?>
					<?php $modDetail = \app\models\TMonitoringPembelianlogDetail::find()->where("monitoring_pembelianlog_id = ".$model->monitoring_pembelianlog_id)->all();
					if(count($modDetail)>0){
					$totalbtg = 0; $totalm3 = 0; $totalgr = 0; $totalpecah = 0; $totalcm = 0;
					foreach($modDetail as $i => $detail){ 
						$totalbtg += $detail->btg; $totalm3 += $detail->m3; $totalgr += $detail->gr; $totalpecah += $detail->pecah; $totalcm += $detail->cm;
					?>
						<tr>
							<td style="border: #D6D6D9 solid 1px;">
								<?= \yii\bootstrap\Html::activeHiddenInput($detail, '['.$i.']monitoring_pembelianlog_detail_id'); ?>
								<?php echo yii\helpers\Html::activeDropDownList($detail, '['.$i.']kayu_id',app\models\MKayu::getOptionList(),['class'=>'form-control','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;','disabled'=>true]); ?>
							</td>
							<td style="border: #D6D6D9 solid 1px;">
								<?php echo yii\helpers\Html::activeDropDownList($detail, '['.$i.']kondisi_global',['SEHAT'=>'SEHAT','RUSAK'=>'RUSAK'],['class'=>'form-control','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;','disabled'=>true]); ?>
							</td>
							<td style="border: #D6D6D9 solid 1px;">
								<?= \yii\bootstrap\Html::activeTextInput($detail, '['.$i.']btg',['class'=>'form-control float mondet-btg','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)','disabled'=>true]); ?>
							</td>
							<td style="border: #D6D6D9 solid 1px;">
								<?= \yii\bootstrap\Html::activeTextInput($detail, '['.$i.']m3',['class'=>'form-control float mondet-m3','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)','disabled'=>true]); ?>
							</td>
							<td style="border: #D6D6D9 solid 1px;">
								<?= \yii\bootstrap\Html::activeTextInput($detail, '['.$i.']gr',['class'=>'form-control float mondet-gr','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)','disabled'=>true]); ?>
							</td>
							<td style="border: #D6D6D9 solid 1px;">
								<?= \yii\bootstrap\Html::activeTextInput($detail, '['.$i.']pecah',['class'=>'form-control float mondet-pecah','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)','disabled'=>true]); ?>
							</td>
							<td style="border: #D6D6D9 solid 1px;">
								<?= \yii\bootstrap\Html::activeTextInput($detail, '['.$i.']cm',['class'=>'form-control float mondet-cm','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)','disabled'=>true]); ?>
							</td>
							<td style="border: #D6D6D9 solid 1px;" class="hidden">
								<a class="btn btn-xs blue-hoki btn-outline" onclick="addMonitoringDetail(this)" style="margin-right: -2px;"><i class="fa fa-plus"></i></a>
								<a class="btn btn-xs red-soft btn-outline" onclick="removeMonitoringDetail(this)"><i class="fa fa-minus"></i></a>
							</td>
						</tr>
					<?php } ?>
					<?php } ?>
				<?php }else{ ?>
					<?php
					$modDetail = new \app\models\TMonitoringPembelianlogDetail();
					$modDetail->btg = 0; $modDetail->m3 = 0; $modDetail->gr = 0; $modDetail->pecah = 0; $modDetail->cm = 0;
					?>
					<tr>
						<td style="border: #D6D6D9 solid 1px;">
							<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]monitoring_pembelianlog_detail_id'); ?>
							<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]kayu_id',app\models\MKayu::getOptionList(),['class'=>'form-control','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;']); ?>
						</td>
						<td style="border: #D6D6D9 solid 1px;">
							<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]kondisi_global',['SEHAT'=>'SEHAT','RUSAK'=>'RUSAK'],['class'=>'form-control','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;']); ?>
						</td>
						<td style="border: #D6D6D9 solid 1px;">
							<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]btg',['class'=>'form-control float mondet-btg','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)']); ?>
						</td>
						<td style="border: #D6D6D9 solid 1px;">
							<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]m3',['class'=>'form-control float mondet-m3','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)']); ?>
						</td>
						<td style="border: #D6D6D9 solid 1px;">
							<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]gr',['class'=>'form-control float mondet-gr','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)']); ?>
						</td>
						<td style="border: #D6D6D9 solid 1px;">
							<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]pecah',['class'=>'form-control float mondet-pecah','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)']); ?>
						</td>
						<td style="border: #D6D6D9 solid 1px;">
							<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]cm',['class'=>'form-control float mondet-cm','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px;','onblur'=>'totalMonitoringDetail(this)']); ?>
						</td>
						<td style="border: #D6D6D9 solid 1px;">
							<a class="btn btn-xs blue-hoki btn-outline" onclick="addMonitoringDetail(this)" style="margin-right: -2px;"><i class="fa fa-plus"></i></a>
							<a class="btn btn-xs red-soft btn-outline" onclick="removeMonitoringDetail(this)"><i class="fa fa-minus"></i></a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
                <tr>
					<td colspan="2"></td>
					<td><?= \yii\bootstrap\Html::textInput('totalbtg',(!empty($totalbtg)?$totalbtg:"0"),['class'=>'form-control float','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px; font-weight:600','disabled'=>true]); ?></td>
					<td><?= \yii\bootstrap\Html::textInput('totalm3',(!empty($totalm3)?$totalm3:"0"),['class'=>'form-control float','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px; font-weight:600','disabled'=>true]); ?></td>
					<td><?= \yii\bootstrap\Html::textInput('totalgr',(!empty($totalgr)?$totalgr:"0"),['class'=>'form-control float','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px; font-weight:600','disabled'=>true]); ?></td>
					<td><?= \yii\bootstrap\Html::textInput('totalpecah',(!empty($totalpecah)?$totalpecah:"0"),['class'=>'form-control float','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px; font-weight:600','disabled'=>true]); ?></td>
					<td><?= \yii\bootstrap\Html::textInput('totalcm',(!empty($totalcm)?$totalcm:"0"),['class'=>'form-control float','style'=>'width:100%; padding: 1px; font-size:1.2rem; height:25px; font-weight:600','disabled'=>true]); ?></td>
				</tr>
				<tr>
                    <td style="text-align: right; vertical-align: top;">Keterangan : </td>
                    <td colspan="6"><?= \yii\bootstrap\Html::activeTextarea($model, 'keterangan',['class'=>'form-control','style'=>'width:100%; padding: 1px; font-size:1.2rem;','rows'=>'1','placeholder'=>'Keterangan Monitoring']); ?></td>
				</tr>
				<tr>
                    <td colspan="8" class="td-kecil" style="vertical-align: middle; text-align: center; vertical-align: top !important;">
                        <div class="show-mode" style="display: <?= $show ?>; text-align: left;">
                            <div class="row">
                                <?= $img ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12"><a class="btn btn-xs blue btn-outline" onclick="addAttch(this);"><i class="fa fa-plus"></i> Add Pict</a></div>
                            </div>
                        </div>
                        <div class="show-mode" style="display: <?= $input ?>;">
                            <a class="btn btn-xs grey"><i class="fa fa-plus"></i> Add Pict</a>
                        </div>
                    </td>
				</tr>
			</tfoot>
		</table>
	</td>
	
	<td class="td-kecil" style="vertical-align: middle; text-align: center; vertical-align: top !important;">
		<div class="show-mode" style="display: <?= $show ?>;">
			<a class="btn btn-xs blue-steel" style="padding: 2px; margin-right: 0px;" onclick="editMonitoring(this);"><i class="fa fa-edit"></i></a> 
			<a class="btn btn-xs red" style="padding: 2px;" onclick="deleteMonitoring(<?= $model->monitoring_pembelianlog_id ?>);"><i class="fa fa-trash-o"></i></a>
		</div>
		<div class="input-mode" style="display: <?= $input ?>;">
            <a class="btn btn-xs hijau" style="padding: 2px; margin-right: 0px;" onclick="saveMonitoring(this,'<?= $model->pengajuan_pembelianlog_id ?>');"><i class="fa fa-check"></i></a> 
			<a class="btn btn-xs red" style="padding: 2px;" onclick="cancelItemThis(this,'monitoring');"><i class="fa fa-remove"></i></a>
		</div>
	</td>
</tr>
