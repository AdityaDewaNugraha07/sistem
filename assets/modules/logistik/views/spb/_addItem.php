<tr>
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail">
        <?php 
		if((isset($editable))&&(isset($detail))){
			if($editable == 'true'){
				echo yii\helpers\Html::activeHiddenInput($modDetail, '[ii]bhp_id');
				echo $detail->bhp->bhp_nm. '&nbsp; <span class="btn btn-xs btn-default" onclick="editSelectItem(this,'.$detail->bhp_id.')"><i class="fa fa-edit"></i></span>';
			}else{
				echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]bhp_id',[],['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:90%']);
			}
		}else{
			echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]bhp_id',[],['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:90%']);
		}
		?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]spbd_jml', ['class'=>'form-control float','style'=>'width:75px','placeholder'=>'Qty']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center; font-size: 1.6rem; padding: 3px;">
		<?php
		$disabled = true;
		if(!empty($editable)){
			$disabled = false;
		}
		?>
        <?php // echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]spbd_satuan', \app\models\TSpbDetail::getOptionListSatuan(),['class'=>'form-control','prompt'=>'','disabled'=>$disabled]); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]spbd_satuan'); ?>
		<span id="place-satuan"><?= $modDetail->spbd_satuan ?></span>
    </td>
    <td style="vertical-align: middle;">
        <div class="input-group date date-picker" data-date-start-date="+0d">
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]spbd_tgl_dipakai',['class'=>'form-control','style'=>'width:140px','readonly'=>'readonly','placeholder'=>'Pilih Tanggal']); ?>
            <?php // echo \yii\bootstrap\Html::textInput('spbd_tgl_dipakai','',['class'=>'form-control','style'=>'width:100%','readonly'=>'readonly','placeholder'=>'Pilih Tanggal']); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="margin-left: -40px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
	<td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextarea($modDetail, '[ii]spbd_ket', ['class'=>'form-control','style'=>'width:100%; height: 55px; font-size:1.1rem; padding:5px;','placeholder'=>'Keterangan']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>
<script>
function editSelectItem(ele,bhp_id){
	var tr = $(ele).parents('tr');
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/spb/editSelectItem']); ?>', 
		type   : 'POST', 
		data   : {bhp_id:bhp_id}, 
		success: function (data) { 
			if(data.dropdown){
				$(ele).closest('td').html(data.dropdown); 
				$(tr).find('select[name*="[bhp_id]"]').select2({
					allowClear: !0,
					placeholder: 'Ketik nama item',
					width: null,
					ajax: {
						url: '<?= \yii\helpers\Url::toRoute('/logistik/spb/findBhpActive') ?>',
						dataType: 'json',
						delay: 250,
						processResults: function (data) {
							return {
								results: data
							};
						},
						cache: true
					}
				});
				reordertable('#table-detail');
			}
		}, 
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); }, 
	});
}
</script>