<?php
use app\models\HPersediaanLog;
use app\models\TLogKeluar;

?>
<tr class="row-detail">
    <td style="text-align: center; padding: 2px; vertical-align: middle;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]pemotongan_log_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]pemotongan_log_detail_id") ?>
    </td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
        <?php 
            if($modDetail->pemotongan_log_detail_id){
				$modLog = TLogKeluar::findOne(['no_barcode'=>$modDetail->no_barcode]);
				$modPersediaan = HPersediaanLog::findOne(['no_barcode'=>$modDetail->no_barcode, 'reff_no'=>$modLog->reff_no]);
                $no_lap = $modPersediaan->no_lap;
			}
        ?>
		<center>
			<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_barcode',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>true]); ?>
			<input type="text" class="form-control" name="no_lap" id="no_lap" style="padding: 2px; text-align:center; font-size:13px; height:25px;" value="<?= $no_lap; ?>" disabled>
            <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kayu_id',\app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px; text-align:center;','disabled'=>true]); ?>
		</center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php 
		if($modDetail->pemotongan_log_detail_id){
			$modDetail->panjang = $modDetail->panjang * 100; // convert panjang dari m dijadikan cm
		}
		?>
		<center>
			<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]panjang',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
			<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
			<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]volume',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
		</center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]jumlah_potong',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
			<?php if($modDetail->pemotongan_log_detail_id && empty($edit)){ ?>
				<a style="font-size: 1.5rem; cursor: not-allowed;" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
				<a style="font-size: 1.5rem; cursor: not-allowed;" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
			<?php }else{ ?>
				<a onclick="addPotongan(this);" style="font-size: 1.5rem;" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
				<a onclick="removePotongan(this);" style="font-size: 1.5rem;" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
			<?php } ?>
		</center>
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
<!-- DETAIL POTONG -->
<tr class="row-detail-potong">
	<!-- <td></td> -->
	<td colspan="5">
		<table class="table table-bordered table-potong" id="table-potong">
            <thead>
                <tr>
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', 'Kode<br>Potong'); ?></th>
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', 'Panjang <sup>cm</sup>'); ?></th>
					<!-- <th colspan="2" style="font-size: 1.1rem; line-height: 1; text-align: center;"><?= Yii::t('app', '&#8709; <sup>cm</sup>'); ?></th> -->
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', '&#8709;U1<br>&#8709;U2'); ?></th>
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', '&#8709;P1<br>&#8709;P2'); ?></th>
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', 'Cacat <sup>cm</sup><br>P<br>Gb<br>Gr'); ?></th>
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', 'Volume <sup>m3</sup>'); ?></th>
					<th style="width: 80px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', 'Alokasi<br>Grade'); ?></th>
                </tr>
				<!-- <tr>
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', '&#8709; U1<br>&#8709; U2'); ?></th>
					<th style="width: 60px; font-size: 1.1rem; line-height: 1; text-align: center; vertical-align: middle;"><?= Yii::t('app', '&#8709; P1<br>&#8709; P2'); ?></th>
				</tr> -->
            </thead>
            <tbody>
				<?php 
				if($modDetail->pemotongan_log_detail_id){ 
					if(!empty($edit)){
						$disabled = false;
					} else {
						$disabled = true;
					}
					$modDetPot = \app\models\TPemotonganLogDetailPotong::find()->where(['pemotongan_log_detail_id'=>$modDetail->pemotongan_log_detail_id])->orderBy(['kode_pemotongan'=>SORT_ASC])->all();
					foreach($modDetPot as $ii => $modDetailPot){ ?>
						<tr>
							<td><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']kode_pemotongan',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$disabled,'value'=>$modDetailPot['kode_pemotongan']]); ?></td>
							<td><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['panjang_baru'] * 100]); ?></td> <!-- convert panjang dari m ke cm, karna ambil database berupa m -->
							<td>
								<?php 
								echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_ujung1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_ujung1_baru']]);
								echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_ujung2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_ujung2_baru']]);
								?>
							</td>
							<td>
								<?php 
								echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_pangkal1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_pangkal1_baru']]);
								echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_pangkal2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_pangkal2_baru']]);
								?>
							</td>
							<td>
								<?php 
								echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_pjg_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_pjg_baru']]);
								echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_gb_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_gb_baru']]);
								echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_gr_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_gr_baru']]);
								?>
							</td>
							<td><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','disabled'=>true,'value'=>$modDetailPot['volume_baru']]); ?></td>
							<td>
								<?php 
								echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']alokasi',['Sawmill'=>'Sawmill', 'Plymill'=>'Plymill', 'Afkir'=>'Afkir'],['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px;','onchange'=>'setGradingRule(this);', 'disabled'=>$disabled,'value'=>$modDetailPot['alokasi']]);
								if($modDetailPot['alokasi'] == 'Plymill'){
									echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>$disabled,'value'=>$modDetailPot['grading_rule']]);
								} else {
									echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>true,'value'=>$modDetailPot['grading_rule']]);
								}
								?>
							</td>
						</tr>
					<?php }
				} else {
					for ($i = 0; $i < $modDetail->jumlah_potong; $i++) { ?>
						<tr>
							<td>
								<?php echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']kode_pemotongan',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>false]); ?>
							</td>
							<td>
								<?php 
									$modDetailPot->panjang_baru = 0;		
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']); 
								?>
							</td>
							<td>
								<?php 
									$modDetailPot->diameter_ujung1_baru = 0;
									$modDetailPot->diameter_ujung2_baru = 0;		
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_ujung1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_ujung2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
							</td>
							<td>
								<?php 
									$modDetailPot->diameter_pangkal1_baru = 0;
									$modDetailPot->diameter_pangkal2_baru = 0;	
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_pangkal1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_pangkal2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
							</td>
							<td>
								<?php 
									$modDetailPot->cacat_pjg_baru = 0;	
									$modDetailPot->cacat_gb_baru = 0;
									$modDetailPot->cacat_gr_baru = 0;	
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_pjg_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_gb_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_gr_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
							</td>
							<td>
								<?php 
									$modDetailPot->volume_baru = 0;
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;', 'disabled'=>'disabled']); 
								?>
							</td>
							<td>
								<?php 
									echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '[ii]['.($i).']alokasi',['Sawmill'=>'Sawmill', 'Plymill'=>'Plymill', 'Afkir'=>'Afkir'],['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px;','onchange'=>'setGradingRule(this);']); 
									echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '[ii]['.($i).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control', 'prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>'disabled']);
								?>
							</td>
						</tr>
					<?php } 
				}?>
            </tbody>
        </table>
	</td>
	<!-- <td></td> -->
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>
