<br><table style="width: 100%;" id="table-op_export">
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;"><?= "Contract No." ?></td>
		<td class="col-md-8" id="place-contract_no" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modOpEx, "nomor_kontrak", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 24px','disabled'=>"disabled"]) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;"><?= "Contract Date" ?></td>
		<td class="col-md-8" id="place-contract_date" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modOpEx, "tanggal", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 24px','disabled'=>"disabled"]) ?>
		</td>
	</tr>
	<tr style="line-height: 1;">
		<td class="col-md-4 control-label" style="padding: 2px;">Applicant</td>
		<td class="col-md-8" id="place-contract_date" style="padding: 2px 15px; font-weight:500;">
			<?= \yii\bootstrap\Html::activeDropDownList($modPackinglist, 'cust_id', \app\models\MCustomer::getOptionListExport(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setBuyer(this)','style'=>'width:100%;']); ?>
			<?= \yii\helpers\Html::activeHiddenInput($modPackinglist, "shipper", ['disabled'=>"disabled"]) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Applicant Address</td>
		<td class="col-md-8" id="place-applicant" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextarea($modPackinglist, "applicant_display", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 48px','disabled'=>'disabled']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Notify Party</td>
		<td class="col-md-8" id="place-contract_date" style="padding: 2px 15px;  font-weight:500;">
			<?= \yii\bootstrap\Html::activeDropDownList($modPackinglist, "notify_party", \app\models\MCustomer::getOptionListExport(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setBuyer(this)','style'=>'width:100%;']); ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Notify Address</td>
		<td class="col-md-8" id="place-notify_party" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextarea($modPackinglist, "notify_display", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 48px','disabled'=>'disabled']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Port of Loading</td>
		<td class="col-md-8" id="place-port_of_loading" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "port_of_loading", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 24px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Vessel</td>
		<td class="col-md-8" id="place-vessel" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "vessel", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 24px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Mother Vessel</td>
		<td class="col-md-8" id="place-applicant" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "mother_vessel", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 24px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">ETD</td>
		<td class="col-md-8" id="place-etd" style="padding: 2px 15px;">
			<div class="input-group input-medium date date-picker bs-datetime">
				<?= \yii\helpers\Html::activeTextInput($modPackinglist, "etd", ['class'=>'form-control input-medium date date-picker bs-datetime','style'=>'padding:2px; font-size:1.1rem; height: 24px']) ?>
				<span class="input-group-addon">
					<button class="btn btn-xs" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button>
				</span>
			</div>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Final Destination</td>
		<td class="col-md-8" id="place-final_destination" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "final_destination", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 24px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">ETA</td>
		<td class="col-md-8" id="place-eta" style="padding: 2px 15px;">
			<div class="input-group input-medium date date-picker bs-datetime">
				<?= \yii\helpers\Html::activeTextInput($modPackinglist, "eta", ['class'=>'form-control input-medium date date-picker bs-datetime','style'=>'padding:2px; font-size:1.1rem; height: 24px']) ?>
				<span class="input-group-addon">
					<button class="btn btn-xs" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button>
				</span>
			</div>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Statistic Product Code</td>
		<td class="col-md-8" id="place-static_product_code" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "static_product_code", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 25px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Goos Description</td>
		<td class="col-md-8" id="place-goods_description" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextarea($modPackinglist, "goods_description", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 48px']) ?>
		</td>
	</tr>
<!--	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Harvesting Area</td>
		<td class="col-md-8" id="place-harvesting_area" style="padding: 2px 15px;">
			<?php // echo \yii\helpers\Html::activeTextInput($modPackinglist, "harvesting_area", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 25px']) ?>
		</td>
	</tr>-->
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">HS Code</td>
		<td class="col-md-8" id="place-hs_code" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "hs_code", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 25px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Origin</td>
		<td class="col-md-8" id="place-origin" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "origin", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 25px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">SVLK No.</td>
		<td class="col-md-8" id="place-svlk_no" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "svlk_no", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 25px']) ?>
		</td>
	</tr>
	<tr style="line-height: 1">
		<td class="col-md-4 control-label" style="padding: 2px;">Vlegal No.</td>
		<td class="col-md-8" id="place-vlegal_no" style="padding: 2px 15px;">
			<?= \yii\helpers\Html::activeTextInput($modPackinglist, "vlegal_no", ['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; height: 25px']) ?>
		</td>
	</tr>
</table>