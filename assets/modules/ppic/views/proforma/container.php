<div class="row">
	<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-advance table-hover table-contrainer" style="width: 100%; border: 1px solid #A0A5A9;">
				<thead>
					<?php 
					$column = "";
					$colspan_header = 8;
					$colspan_footer = 3;
					if($jenis_produk == "Plywood" || $jenis_produk == "Lamineboard" || $jenis_produk == "Platform"){
						$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA">Wood<br>Type</th>'
								. '<th rowspan="2" style="width: 120px; background-color: #E3E7EA">Glue</th>';
						$colspan_header = 10;
						$colspan_footer = 5;
					}else if($jenis_produk == "Sawntimber"){
						$column = '<th rowspan="2" style="width: 150px; background-color: #E3E7EA">Condition</th>';
						$colspan_header = 9;
						$colspan_footer = 4;
					}else if($jenis_produk == "Moulding"){
						$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA">Wood<br>Type</th>'
								. '<th rowspan="2" style="width: 175px; background-color: #E3E7EA">Profil</th>';
						$colspan_header = 10;
						$colspan_footer = 5;
					}?>
					
					<tr style="background-color: #E3E7EA">
						<td colspan="<?= $colspan_header ?>" style="vertical-align: middle;">
							<b>Container Seq. : <?= yii\bootstrap\Html::textInput("container_no",(!empty($container_no)?$container_no:""),['class'=>'form-control','disabled'=>'disabled','style'=>'width:40px; text-align:center; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container No. : <?= yii\bootstrap\Html::textInput("container_kode",(!empty($container_kode)?$container_kode:""),['class'=>'form-control','onblur'=>'setContainerDetails();','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Seal No : <?= yii\bootstrap\Html::textInput("seal_no",(!empty($seal_no)?$seal_no:""),['class'=>'form-control','onblur'=>'setContainerDetails();','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container Size : <?= yii\bootstrap\Html::textInput("container_size",(!empty($container_size)?$container_size:""),['class'=>'form-control float','onblur'=>'setContainerDetails();','style'=>'width:30px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6','placeholder'=>"ex. 1 X 20'"]) ?> Feet</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Lot Code : <?= yii\bootstrap\Html::textInput("lot_code",(!empty($lot_code)?$lot_code:""),['class'=>'form-control','onblur'=>'setContainerDetails();','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<span class="pull-right">
								<a class="btn btn-icon-only blue-steel tooltips btn-outline" onclick="hapuscontainer(this)" data-original-title="Hapus Container ini" style="width: 24px; height: 24px; padding-top: 1px; padding-bottom: 1px;"><i class="fa fa-trash-o"></i></a>
							</span>
						</td>
					</tr>
					<tr>
						<th rowspan="2" style="width: 50px; background-color: #E3E7EA;" class="kolom-bundle">Bundle<br>No.</th>
						<th rowspan="2" style="width: 100px; background-color: #E3E7EA" class="kolom-grade">Grade</th>
						<?= $column ?>
						<th rowspan="2" style="width: 120px; background-color: #E3E7EA" class="kolom-thick">Thick</th>
						<th rowspan="2" style="width: 120px; background-color: #E3E7EA" class="kolom-width">Width</th>
						<th rowspan="2" style="width: 120px; background-color: #E3E7EA" class="kolom-length">Length</th>
						<th colspan="2" style="background-color: #E3E7EA">Qty</th>
						<th rowspan="2" style="background-color: #E3E7EA" class="kolom-action"></th>
					</tr>
					<tr>
						<th style="width: 80px; background-color: #E3E7EA" class="kolom-pcs">Pcs</th>
						<th style="width: 80px; background-color: #E3E7EA" class="kolom-volume">M<sup>3</sup></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($modPackinglist)){
						$sql = "SELECT * FROM t_packinglist_container WHERE packinglist_id=".$modPackinglist->packinglist_id." AND container_no=".$container_no;
						$mod = Yii::$app->db->createCommand($sql)->queryAll(); 
						$table_id = "";
						$modContainer = app\models\TPackinglistContainer::find()->where("packinglist_id=".$modPackinglist->packinglist_id." AND container_no=".$container_no)->orderBy("packinglist_container_id ASC")->all();
						if(count($modContainer)>0){
							foreach($modContainer as $i => $container){
								$container->volume_display = number_format($container->volume,4);
								echo $this->render('bundle',['model'=>$container,'table_id'=>$table_id,'jenis_produk'=>$jenis_produk]);
							}
						}
						?>
					<?php }else{ ?>
						<tr id="place-emptytr" class="uncount-tr"><td colspan="<?= $colspan_header ?>" style="text-align: center; font-size: 1.2rem;">Data Tidak Ditemukan</td></tr>
					<?php } ?>
				</tbody>
				<tfoot style="background-color: #E3E7EA">
					<tr>
						<td style="vertical-align: middle; padding: 2px;"><a class="btn btn-xs btn-default" onclick="addBundle(this)"><i class="fa fa-plus"> Add Bundle</i></a></td>
						<td colspan="<?= $colspan_footer ?>" style="text-align: right; vertical-align: middle; font-size: 1.2rem;" > 
							Gross Weight (kg) : <?= yii\bootstrap\Html::textInput("gross_weight",(!empty($gross_weight)?$gross_weight:0),['class'=>'form-control float','onblur'=>'total(); setContainerDetails();','style'=>'width:80px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> &nbsp; &nbsp; &nbsp;
							Nett Weight (kg) : <?= yii\bootstrap\Html::textInput("nett_weight",(!empty($nett_weight)?$nett_weight:0),['class'=>'form-control float','onblur'=>'total(); setContainerDetails();','style'=>'width:80px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?>
						</td>
						
						<td style="text-align: right; vertical-align: middle;">Total &nbsp; </td>
						<td style="text-align: right; padding: 2px; vertical-align: middle;"> <?= yii\bootstrap\Html::textInput("tot_pcs",0,['class'=>'form-control float','disabled'=>'disabled','style'=>'text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> </td>
						<td style="text-align: right; padding: 2px; vertical-align: middle;"> <?= yii\bootstrap\Html::textInput("tot_vol",0,['class'=>'form-control float','disabled'=>'disabled','style'=>'text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> </td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div><br>
	</div>
</div>