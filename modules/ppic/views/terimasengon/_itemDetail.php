<tr>
    <td>
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
        <!--<span class="no_urut"></span>-->
		<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]no_urut',['class'=>'form-control','style'=>'width:30px; ','disabled'=>'disabled']) ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]terima_sengon_detail_id') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]total_pcs') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]total_m3') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]spek_cwm') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]spek_afkir') ?>
    </td>
	<td>
		<div class="input-group date date-picker">
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]tanggal_datang',['class'=>'form-control','style'=>'width:100%','readonly'=>'readonly']); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="margin-left: -10px; padding: 6px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]asal_kayu', ['class'=>'form-control']); ?>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]nopol', ['class'=>'form-control']); ?>
	</td>
	<td>
		<?php
		
		echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]suplier_pcs', ['class'=>'form-control money-format','style'=>'width:50px;','onblur'=>'setDetailValue()']); ?>
	</td>
	<td>
		<?php
		
		echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]suplier_m3', ['class'=>'form-control float','style'=>'width:50px;','onblur'=>'setDetailValue()']); ?>
	</td>
	<td style="text-align:left;">
		<span class="cwm-total-diameter">
		</span>
		Total Pcs : <br><span class="cwm-total-pcs" style="font-weight: bold;"></span><br>
		Total m<sup>3</sup> : <br><span class="cwm-total-m3" style="font-weight: bold;"></span><br>
	</td>
	<td style="text-align:left;">
		Total Pcs : <br><span class="afkir-total-pcs" style="font-weight: bold;"></span><br>
		Total m<sup>3</sup> : <br><span class="afkir-total-m3" style="font-weight: bold;"></span><br>
		Status Afkir : 
		<span class="status-afkir">
			<?php echo yii\bootstrap\Html::activeRadioList($modDetail, '[ii]status_afkir',['<b>Belum Dikirim</b>','<b>Sudah Dikirim</b>'],['encode'=>false,'separator'=>' &nbsp; ']) ?>
		</span>
	</td>
	<td style="text-align:left;">
		Total Pcs : <br><span class="total-pcs" style="font-weight: bold;"></span><br>
		Total m<sup>3</sup> : <br><span class="total-m3" style="font-weight: bold;"></span><br>
	</td>
	<td style="text-align:left;">
		Total Pcs : <br><span class="selisih-pcs" style="font-weight: bold;"></span><br>
		Total m<sup>3</sup> : <br><span class="selisih-m3" style="font-weight: bold;"></span><br>
	</td>
    <td style="padding-top: 10px;">
        <a class="btn btn-xs dark viewdetailbutton" onclick="detailPenerimaan(<?= $modDetail->terima_sengon_detail_id ?>);" style="margin-right: 0px; display: none;"><i class="fa fa-eye"></i></a>
        <a class="btn btn-xs blue importbutton" onclick="$('#tterimasengon-file').trigger( 'click' );" style="margin-right: 0px;"><i class="fa fa-download"></i></a>
		<?php
		$disabled = false;
		$onclick = 'hapusItem(this);';
		if(!empty($modDetail->terima_sengon_detail_id)){
			if($modDetail->checkTagihan($modDetail->terima_sengon_detail_id)==true){
				$disabled = true;
				$onclick = '';
			}
		}
		?>
		<?= yii\helpers\Html::a('<i class="fa fa-remove"></i>',null,['style'=>'margin-right: 0px;','class'=>'btn btn-xs red  hapusbutton','onclick'=>$onclick,'disabled'=>$disabled]) ?>
    </td>
</tr>