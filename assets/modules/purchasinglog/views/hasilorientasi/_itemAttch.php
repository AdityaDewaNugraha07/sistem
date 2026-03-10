<div class="col-md-2">
	<div class="fileinput fileinput-new" data-provides="fileinput">
		<div class="fileinput-new thumbnail" style="width: 150px; height: 115px;">
			<img src="<?= Yii::$app->view->theme->baseUrl ?>/cis/img/no-image.png" alt="" /> </div>
		<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
		<div>
			<span class="btn blue-hoki btn-outline btn-file btn-xs">
				<span class="fileinput-new"> Select image </span>
				<span class="fileinput-exists"> Change </span>
				<?= \yii\bootstrap\Html::activeFileInput($model, '[ii]file') ?>
			</span> 
			<a href="javascript:;" class="btn red fileinput-exists btn-xs" data-dismiss="fileinput"> Remove </a>
		</div>
	</div>
</div>