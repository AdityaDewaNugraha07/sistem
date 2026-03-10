<?php
if(!empty($model->tagihan_sengon_id)){
	$disabled = true;
}else{
	$disabled = false;
}
?>
<tr>
    <td style="padding-top: 10px;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
        <!--<span class="no_urut"></span>-->
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]no_urut',['class'=>'form-control','style'=>'width:30px; ','disabled'=>'disabled']) ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]posengon_id') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]tagihan_sengon_id') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($model, '[ii]terima_sengon_detail_id') ?>
    </td>
	<td style="text-align: left;">
		<div class="input-group date date-picker">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]tanggal_datang',['class'=>'form-control','style'=>'width:100%','readonly'=>'readonly','disabled'=>$disabled]); ?>
            <span class="input-group-btn" style="display: <?= ($disabled==true)?"none":""; ?>;">
                <button class="btn default" type="button" style="margin-left: -10px; padding: 6px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
		<br>
		<span ><b>Disetujui : </b></span>
		<?= yii\bootstrap\Html::activeDropDownList($model, '[ii]disetujui', \app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'','disabled'=>$disabled]) ?>
	</td>
	<td style="text-align: left;">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]nopol', ['class'=>'form-control','disabled'=>$disabled]); ?>
		<br>
		<span ><b>NPWP : </b></span>
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]npwp',['class'=>'form-control','style'=>'width:100%','disabled'=>$disabled]); ?>
	</td>
	<td>
		<span class="cwm-total-diameter">
			<table style="width:100%" id="table-diameter">
				<?php
				if(!empty($diameter)){
					foreach($diameter as $i => $value){
						echo '<tr><td style="width:20%; text-align:center; line-height: 2;">'.$value['range'].'</td></tr>';
					}
					echo '<tr><td style="width:20%; font-size:1.4rem; text-align:right; line-height: 2;"><b><u>Total</u> &nbsp; </b></td></tr>';
				}
				?>
			</table>
		</span>
	</td>
	<td>
		<table style="width:100%" id="table-volume">
			<?php
			if(!empty($diameter)){
				$total = 0;
				foreach($diameter as $i => $value){
					$total += $value['m3'];
					echo '<tr><td style="width:20%; text-align:right; line-height: 2;">'.$value['m3'].' m<sup>3</sup></td></tr>';
				}
				echo '<tr><td style="width:20%; text-align:right; line-height: 2;"><b><u>'.$total.' m<sup>3</sup></u></b></td></tr>';
			}
			?>
		</table>
	</td>
	<td>
		<table style="width:100%" id="table-harga">
			<?php
			if(!empty($diameter)){
				$total = 0;
				foreach($diameter as $i => $value){
					$val = app\components\DeltaFormatter::formatNumberForUser( isset($model->spek[$value['range']]['harga'])?$model->spek[$value['range']]['harga']:0 );
					echo	'<tr><td style="width:20%; text-align:right; line-height: 2;">
								'.\yii\helpers\Html::activeTextInput($model, '[ii]['.$value['range'].']harga', ['class'=>'form-control money-format','style'=>'text-align : right; height:25px','onblur'=>'setNominal(this); setTotal();','value'=>$val,'disabled'=>$disabled]).
								  \yii\helpers\Html::activeHiddenInput($model, '[ii]['.$value['range'].']volume', ['class'=>'form-control float','style'=>'text-align : right; height:25px','value'=>$value['m3']]).
								  \yii\helpers\Html::activeHiddenInput($model, '[ii]['.$value['range'].']range', ['class'=>'form-control float','style'=>'text-align : right; height:25px','value'=>$value['range']]).'
							</td></tr>';
				}
			}
			?>
		</table>
	</td>
	<td>
		<table style="width:100%" id="table-subtotal_harga">
			<?php
			if(!empty($diameter)){
				$total = 0;
				foreach($diameter as $i => $value){
					$val = app\components\DeltaFormatter::formatNumberForUser( isset($model->spek[$value['range']]['subtotal_harga'])?$model->spek[$value['range']]['subtotal_harga']:0 );
					echo	'<tr><td style="width:20%; text-align:right; line-height: 2;">
								'.\yii\helpers\Html::activeTextInput($model, '[ii]['.$value['range'].']subtotal_harga', ['class'=>'form-control money-format','style'=>'text-align : right; height:25px','disabled'=>'disabled','value'=>$val]).'
							</td></tr>';
				}
				echo '<tr><td style="width:20%; font-size:1.4rem; text-align:right; line-height: 2;">'.\yii\helpers\Html::activeTextInput($model, '[ii]totalharga', ['class'=>'form-control money-format','style'=>'text-align : right; height:25px; font-weight: bold;','disabled'=>'disabled','value'=>$total]).'</td></tr>';
			}
			?>
		</table>
	</td>
	<td>
		<table style="width:100%" id="table-pph">
			<?php
			if(!empty($diameter)){
				$total = 0;
				foreach($diameter as $i => $value){
					$val = app\components\DeltaFormatter::formatNumberForUser( isset($model->spek[$value['range']]['pph'])?$model->spek[$value['range']]['pph']:0 );
					echo	'<tr><td style="width:20%; text-align:right; line-height: 2;">
								'.\yii\helpers\Html::activeTextInput($model, '[ii]['.$value['range'].']pph', ['class'=>'form-control money-format','style'=>'text-align : right; height:25px;','disabled'=>'disabled','value'=>$val]).'
							</td></tr>';
				}
				echo '<tr><td style="width:20%; font-size:1.4rem; text-align:right; line-height: 2;">'.\yii\helpers\Html::activeTextInput($model, '[ii]totalpph', ['class'=>'form-control money-format','style'=>'text-align : right; height:25px; font-weight: bold;','disabled'=>'disabled','value'=>$total]).'</td></tr>';
			}
			?>
		</table>
	</td>
	<td>
		<table style="width:100%" id="table-subtotal_bayar">
			<?php
			if(!empty($diameter)){
				$total = 0;
				foreach($diameter as $i => $value){
					$val = app\components\DeltaFormatter::formatNumberForUser( isset($model->spek[$value['range']]['subtotal_bayar'])?$model->spek[$value['range']]['subtotal_bayar']:0 );
					echo	'<tr><td style="width:20%; text-align:right; line-height: 2;">
								'.\yii\helpers\Html::activeTextInput($model, '[ii]['.$value['range'].']subtotal_bayar', ['class'=>'form-control money-format','style'=>'text-align : right; height:25px;','disabled'=>'disabled','value'=>$val]).'
							</td></tr>';
				}
				echo '<tr><td style="width:20%; font-size:1.4rem; text-align:right; line-height: 2;">'.\yii\helpers\Html::activeTextInput($model, '[ii]totalbayar', ['class'=>'form-control money-format','style'=>'text-align : right; height:25px; font-weight: bold;','disabled'=>'disabled','value'=>$total]).'</td></tr>';
			}
			?>
		</table>
	</td>
    <td style="padding-top: 10px;">
		<?php if($status == 'UNPAID'){ ?>
			<span class="label label-sm label-warning"><i><?= $status ?></i></span><br><br>
		<?php }else{ ?>
			<span class="label label-sm label-success"><i><?= $status ?></i></span><br><br>
		<?php } ?>
		<?php if(!empty($model->tagihan_sengon_id)){ ?>
			<a class="btn btn-xs red tooltips hapusitembutton" data-original-title="Delete" onclick="hapusItem(this);" style="margin-right: 0px;"><i class="fa fa-remove"></i></a>
		<?php }else{ ?>
			<a class="btn btn-xs blue tooltips resetdeletebutton" data-original-title="Clear" onclick="resetDelete(this);" style="margin-right: 0px;"><i class="fa fa-refresh"></i></a>
		<?php } ?>
    </td>
</tr>