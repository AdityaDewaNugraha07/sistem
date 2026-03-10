<?php

use app\models\HPersediaanLog;

if($modDetail->pemotongan_log_detail_id){
	$no_barcode_baru = ''; $diameter_ujung1_baru = ''; $diameter_ujung2_baru = ''; $diameter_pangkal1_baru = ''; $diameter_pangkal2_baru = ''; 
	$cacat_pjg_baru = ''; $cacat_gb_baru = ''; $cacat_gr_baru = ''; $panjang_baru = ''; $volume_baru = ''; 
	$reduksi_baru = ''; $alokasi = ''; $grading_rule = ''; 

	if(!empty($edit)){
		$disabled = false;
	} else {
		$disabled = true;
	}

	$modDetPot = \app\models\TPemotonganLogDetailPotong::find()->where(['pemotongan_log_detail_id'=>$modDetail->pemotongan_log_detail_id])->orderBy(['pemotongan_log_detail_potong_id'=>SORT_ASC])->all();
	foreach($modDetPot as $ii => $modDetailPot){
		$no_barcode_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']no_barcode_baru',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>true,'value'=>$modDetailPot['no_barcode_baru']]);
		$diameter_ujung1_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_ujung1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_ujung1_baru']]);
		$diameter_ujung2_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_ujung2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_ujung2_baru']]);
		$diameter_pangkal1_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_pangkal1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_pangkal1_baru']]);
		$diameter_pangkal2_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_pangkal2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_pangkal2_baru']]);
		$cacat_pjg_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_pjg_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_pjg_baru']]);
		$cacat_gb_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_gb_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_gb_baru']]);
		$cacat_gr_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_gr_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_gr_baru']]);
		$panjang_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['panjang_baru']]);
		$volume_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','disabled'=>true,'value'=>$modDetailPot['volume_baru']]);
		$reduksi_baru .= yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']reduksi_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','disabled'=>$disabled,'value'=>$modDetailPot['reduksi_baru']]);
		$alokasi .= \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']alokasi',['Sawmill'=>'Sawmill', 'Plymill'=>'Plymill', 'Afkir'=>'Afkir'],['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px;','onchange'=>'setGradingRule(this);', 'disabled'=>$disabled,'value'=>$modDetailPot['alokasi']]);
		if($modDetailPot['alokasi'] == 'Plymill'){
			$grading_rule .= \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>$disabled,'value'=>$modDetailPot['grading_rule']]);
		} else {
			$grading_rule .= \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>true,'value'=>$modDetailPot['grading_rule']]);
		}
		
	}
}
?>
<tr style="">
    <td style="text-align: center; padding: 2px; vertical-align: middle;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]pemotongan_log_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]pemotongan_log_detail_id") ?>
    </td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center>
		<div contenteditable="false" class="form-control input-lookalike" style="padding: 2px; text-align:center; font-size:13px; height:45px; color: #484c52; background-color: #eef1f5;">
			<?php
				if($modDetail->pemotongan_log_detail_id){
					$modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$modDetail->no_barcode, 'status'=>'IN']);
					$no_barcode_lap = $modDetail->no_barcode . "\n" . $modPersediaan->no_lap;
					echo nl2br(htmlspecialchars($no_barcode_lap));
				} else {
					echo nl2br(htmlspecialchars($modDetail->no_barcode_lap));
				}
			?>
		</div>
			<?= yii\helpers\Html::activeHiddenInput($modDetail, '[ii]no_barcode',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>true]); ?>
		</center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kayu_id',\app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]panjang',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]volume',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php 
		if($modDetail->pemotongan_log_detail_id && empty($edit)){
			$disabled = true;
		} else {
			$disabled = false;
		}
		$modDetail->reduksi = 0;
		?>
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]reduksi',['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px; text-align:center;', 'disabled'=>$disabled]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]jumlah_potong',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
			<?php if($modDetail->pemotongan_log_detail_id && empty($edit)){ ?>
				<a style="font-size: 1.3rem; cursor: not-allowed;" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
				<a style="font-size: 1.3rem; cursor: not-allowed;" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
			<?php }else{ ?>
				<a onclick="addPotongan(this);" style="font-size: 1.3rem;" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
				<a onclick="removePotongan(this);" style="font-size: 1.3rem;" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
			<?php } ?>
			
		</center>
	</td>

    <!-- DETAIL POTONG -->
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $no_barcode_baru;
			} else {
				for($i=0; $i<($modDetail->jumlah_potong); $i++){
					$modDetailPot->no_barcode_baru = $modDetail->no_barcode.".".app\components\DeltaFormatter::hurufPotong($i+1);
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']no_barcode_baru',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>true]);
				}
			}
		?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $panjang_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->panjang_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $diameter_ujung1_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->diameter_ujung1_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_ujung1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $diameter_ujung2_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->diameter_ujung2_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_ujung2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $diameter_pangkal1_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->diameter_pangkal1_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_pangkal1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $diameter_pangkal2_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->diameter_pangkal2_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_pangkal2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $cacat_pjg_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->cacat_pjg_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_pjg_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $cacat_gb_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->cacat_gb_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_gb_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $cacat_gr_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->cacat_gr_baru = 0;		
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_gr_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
				}
			}
		?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $reduksi_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->reduksi_baru = 0;
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']reduksi_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;']);
				}
			}
		?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
			if($modDetail->pemotongan_log_detail_id){
				echo $volume_baru;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					$modDetailPot->volume_baru = 0;
					echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;', 'disabled'=>'disabled']);
				}
			}
		?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php 
			if($modDetail->pemotongan_log_detail_id){
				echo $alokasi;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '[ii]['.($i).']alokasi',['Sawmill'=>'Sawmill', 'Plymill'=>'Plymill', 'Afkir'=>'Afkir'],['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px;','onchange'=>'setGradingRule(this);']); 
				}
			}
        ?>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php 
			if($modDetail->pemotongan_log_detail_id){
				echo $grading_rule;
			} else {
				for($i=0;$i<($modDetail->jumlah_potong);$i++){
					echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '[ii]['.($i).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control', 'prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>'disabled']); 
				}
			}
        ?>
	</td>
    <td style="vertical-align: middle; text-align: center;">
		<?php if($modDetail->pemotongan_log_detail_id && empty($edit)){ ?>
			<span id="place-deletebtn" >
				<a class="btn btn-xs grey" id="close-btn-this"><i class="fa fa-remove"></i></a>
			</span>
		<?php }else{ ?>
			<span id="place-cancelbtn" >
				<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
			</span>
		<?php } ?>
    </td>
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>
