<?php
if(!isset($sizelabel)){
	$sizelabel = "3";
}
if(!isset($sizeelement)){
	$sizeelement = "9";
}
?>
<div class="form-group">
	<label class="col-md-<?= $sizelabel ?> control-label"><?= $label ?></label>
	<div class="col-md-<?= $sizeelement ?>">
		<span class="input-group-btn" style="width: 30%">
			<?= $form->field($model, 'jenis_periode')->dropDownList( ["YTD"=>"YTD","MTD"=>"MTD","DTD"=>"DTD"],['style'=>'width:100%',"value"=>"DTD"]); ?>
		</span>
        <span class="input-group-addon textarea-addon" style="width: 5%; background-color: #fff; border: 0;"> &nbsp; </span>
		<span class="input-group-btn" style="width: 30%">
			<?= $form->field($model, 'tgl_awal',[
						'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
									 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
									 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
		</span>
		<span class="input-group-addon textarea-addon" style="width: 5%; background-color: #fff; border: 0;"> sd </span>
		<span class="input-group-btn" style="width: 30%">
			<?= $form->field($model, 'tgl_akhir',[
						'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
									 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
									 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
		</span>
	<span class="help-block"></span>
	</div>
</div>
