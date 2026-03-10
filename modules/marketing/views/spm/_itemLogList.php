<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?php echo yii\bootstrap\Html::activeHiddenInput($model, "[ii]kayu_id", ['class'=>'kayu_id']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_id", ['class'=>'produk_id']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modPersediaan, "[ii]fisik_pcs", ['class'=>'fisik_pcs']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]no_barcode", ['class'=>'no_barcode']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]no_lap", ['class'=>'no_lap']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]no_grade", ['class'=>'no_grade']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]no_btg", ['class'=>'no_btg']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]no_produksi", ['class'=>'no_produksi']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]kayu_id", ['class'=>'kayu_id']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]kode_potong", ['class'=>'kode_potong']); ?>
		
		<span class="input-group-btn" style="width: 100%">
			<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]no_barcode',[],['class'=>'form-control select2','onchange'=>'setItemLogList(this)','prompt'=>'','style'=>'width:90%;']); ?>
		</span>
		<span class="input-group-btn" style="width: 20%">
			<a class="btn btn-icon-only btn-default tooltips" onclick="stockLogAvailable(this);" data-original-title="Stock Available" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
		</span>
    </td>
	<td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextInput($modSpmLog, "[ii]kayu_nama", ['class'=>'form-control kayu_nama','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
		<div id="kayu_nama_display" class="form-control td-kecil" style="background-color: #eef1f5; height: auto; display: none;"></div>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modPersediaan, "[ii]no_lap", ['class'=>'form-control no_lap','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modPersediaan, "[ii]no_grade", ['class'=>'form-control no_grade','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modPersediaan, "[ii]no_btg", ['class'=>'form-control float no_btg','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
    </td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]fisik_panjang",['class'=>'form-control float persediaan-panjang','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]diameter_ujung1",['class'=>'form-control float persediaan-diameter_ujung1','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]diameter_ujung2",['class'=>'form-control float persediaan-diameter_ujung2','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]diameter_pangkal1",['class'=>'form-control float persediaan-diameter_pangkal1','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]diameter_pangkal2",['class'=>'form-control float persediaan-diameter_pangkal2','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]cacat_panjang",['class'=>'form-control float persediaan-cacat_panjang','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]cacat_gb",['class'=>'form-control float persediaan-cacat_gb','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]cacat_gr",['class'=>'form-control float persediaan-cacat_gr','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #FFE495; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modPersediaan, "[ii]fisik_volume",['class'=>'form-control float persediaan-fisik_volume','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]panjang",['class'=>'form-control float panjang','onblur' => 'hitungVolume(this)', 'style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]diameter_ujung1",['class'=>'form-control float diameter_ujung1','onblur' => 'hitungRata(this);', 'style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]diameter_ujung2",['class'=>'form-control float diameter_ujung2','onblur' => 'hitungRata(this);','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]diameter_pangkal1",['class'=>'form-control float diameter_pangkal1','onblur' => 'hitungRata(this);','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]diameter_pangkal2",['class'=>'form-control float diameter_pangkal2','onblur' => 'hitungRata(this);','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]diameter_rata",['class'=>'form-control float diameter_rata','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]cacat_panjang",['class'=>'form-control float cacat_panjang','onblur' => 'hitungVolume(this)','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]cacat_gb",['class'=>'form-control float cacat_gb','onblur' => 'hitungVolume(this)','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]cacat_gr",['class'=>'form-control float cacat_gr','onblur' => 'hitungVolume(this)','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
	</td>
	<td style="background-color: #B6D25D; vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo yii\helpers\Html::activeTextInput($modSpmLog, "[ii]volume",['class'=>'form-control float volume','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'disabled'=>'disabled']); ?>
	</td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <a class="btn btn-xs red" onclick="cancelItemLogList(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>
<script>

</script>
