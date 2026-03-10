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
                <div style="display: table; margin: auto;">
                    <div style="display: table-row;">
                        <div style="display: table-cell; padding: 2px 12px 2px 2px; vertical-align: middle;">
                            <a style="font-size: 2.2rem; cursor: not-allowed;" id="btn-min-cut" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
                        </div>
                        <div style="display: table-cell; padding: 2px; vertical-align: middle;">
                            <?= \yii\bootstrap\Html::activeCheckbox($modDetail, "[ii]potong",['class'=>'','label'=>'Cut', 'disabled'=>'']) ?>
                        </div>
                    </div>
                    <div>
                        <a style="font-size: 2.2rem; cursor: not-allowed;"  id="btn-add-cut" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
                    </div>
                </div>
			<?php }else{ ?>
                <div style="display: table; margin: auto;">
                    <div style="display: table-row;">
                        <div style="display: table-cell; padding: 2px 12px 2px 2px; vertical-align: middle;">
                            <a onclick="removePotongan(this);" style="font-size: 2.2rem;" id="btn-min-cut" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
                        </div>
                        <div style="display: table-cell; padding: 2px; vertical-align: middle;">
                            <?= \yii\bootstrap\Html::activeCheckbox($modDetail, "[ii]potong", ['class'=>'','label'=>'Cut', 'onchange'=>'setJmlPotong(this);']); ?>
                        </div>
                    </div>
                    <div>
                        <a onclick="addPotongan(this);" style="font-size: 2.2rem;" id="btn-add-cut" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
                    </div>
                </div>
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
	<td colspan="5">
		<table class="table table-bordered table-potong" id="table-potong">
            <?php 
                // TAMPILAN VIEW & EDIT
                if($modDetail->pemotongan_log_detail_id){ 
					// if(!empty($edit)){
					// 	$disabled = false;
					// } else {
					// 	$disabled = true;
					// }
					$modDetPot = \app\models\TPemotonganLogDetailPotong::find()->where(['pemotongan_log_detail_id'=>$modDetail->pemotongan_log_detail_id])->orderBy(['kode_pemotongan'=>SORT_ASC])->all();
					foreach($modDetPot as $ii => $modDetailPot){ 
                        if(!empty($edit)){
                            if($modDetailPot->status_penerimaan){
                                $disabled = true;
                                $dispotong = true;
                            } else {
                                $disabled = false;
                                if($modDetail->potong){
                                    $dispotong = false;
                                } else {
                                    $dispotong = true;
                                }
                            } 
                        } else {
                            $disabled = true;
                            $dispotong = true;
                        }?>
                        <tr style="background: #e9ecef;" data-status="<?= $modDetailPot->status_penerimaan ? 'true' : 'false' ?>">
                            <td colspan="4" style="font-weight: bold; font-size: 13px; padding: 6px;">
                                <span>Potongan <?= $modDetailPot['kode_pemotongan'] ?> dari No. Lapangan <?= $no_lap; ?></span><br>
                                <span style="font-size: 1.2rem; width: 50px;">(dalam satuan cm)</span>
                            </td>
                        </tr>
						<tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle; vertical-align: middle;">Kode Potong</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']kode_pemotongan',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>$dispotong,'value'=>$modDetailPot['kode_pemotongan'], 'oninput'=>"removeNonLetters(this)"]); ?></td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle; vertical-align: middle;">Cacat Panjang</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_pjg_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_pjg_baru']]); ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle; vertical-align: middle;">Panjang</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$dispotong,'value'=>$modDetailPot['panjang_baru'] * 100]); ?></td> <!-- convert panjang dari m ke cm, karna ambil database berupa m -->
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle; vertical-align: middle;">Gubal</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_gb_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_gb_baru']]); ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;U1</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_ujung1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_ujung1_baru']]); ?></td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Growong</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']cacat_gr_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['cacat_gr_baru']]); ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;U2</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_ujung2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_ujung2_baru']]); ?></td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Vol (m<sup>3</sup>)</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','disabled'=>true,'value'=>$modDetailPot['volume_baru']]); ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;P1</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_pangkal1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_pangkal1_baru']]); ?></td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Alokasi</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']alokasi',['Sawmill'=>'Sawmill', 'Plymill'=>'Plymill', 'Afkir'=>'Afkir', 'Gudang'=>'Gudang'],['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px;','onchange'=>'setGradingRule(this);', 'disabled'=>$disabled,'value'=>$modDetailPot['alokasi']]); ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;P2</td>
                            <td style="vertical-align: middle; padding: 2px;"><?php echo yii\helpers\Html::activeTextInput($modDetailPot, '['.($i).']['.($ii).']diameter_pangkal2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>$disabled,'value'=>$modDetailPot['diameter_pangkal2_baru']]); ?></td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;"><span id='label-grade-<?= $i?>-<?= $ii?>'><?= $modDetailPot['alokasi'] == 'Gudang'?'QRCode':'Grade'; ?></span></td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
                                if($modDetailPot['alokasi'] == 'Plymill'){
									echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>$disabled,'value'=>$modDetailPot['grading_rule']]);
								} else if($modDetailPot['alokasi'] == 'Sawmill') {
                                    echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Standard'=>'Standard', 'Tanduk'=>'Tanduk'],['class'=>'form-control', 'style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>$disabled,'value'=>$modDetailPot['grading_rule']]);
                                } else if($modDetailPot['alokasi'] == 'Gudang'){?>
                                    <a class="btn btn-xs default" id="print-qr-<?= $i; ?>-<?= $ii; ?>" onclick="window.open(
                                        '<?= yii\helpers\Url::toRoute('/ppic/pemotonganlog/print?id=' . $modDetailPot['pemotongan_log_detail_potong_id'] . '&no_barcode='.$modDetailPot['no_barcode_baru'].'&caraprint=PRINT') ?>', 
                                        'Print Barcode', 'width=1200', false, '_blank' )"><i class="fa fa-print"></i>
                                    </a>
                                    <?php echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px; display: none;', 'disabled'=>true,'value'=>$modDetailPot['grading_rule']]); ?>
                                <?php } else {
									echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '['.($i).']['.($ii).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>true,'value'=>$modDetailPot['grading_rule']]);
								} ?>
                            </td>
                        </tr>
					<?php }
                // TAMPILAN INDEX
				} else {
                    for ($i = 0; $i < $modDetail->jumlah_potong; $i++) { ?>
                        <tr style="background: #e9ecef;" data-status="false">
                            <td colspan="4" style="font-weight: bold; font-size: 14px; padding: 6px;">
                                <span>Potongan ke-<?= $i + 1 ?> dari No. Lapangan <?= $no_lap; ?></span><br>
                                <span style="font-size: 1.2rem; width: 50px;">(dalam satuan cm)</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Kode Potong</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']kode_pemotongan',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>false, 'oninput'=>"removeNonLetters(this)"]); ?>
                            </td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Cacat Panjang</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->cacat_pjg_baru = 0;
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_pjg_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle; width: 50px;">Panjang</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->panjang_baru = 0;		
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']); 
								?>
                            </td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Gubal</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->cacat_gb_baru = 0;
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_gb_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;U1</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->diameter_ujung1_baru = 0;
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_ujung1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
                            </td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Growong</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->cacat_gr_baru = 0;	
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']cacat_gr_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;U2</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->diameter_ujung2_baru = 0;		
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_ujung2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
                            </td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Vol (m<sup>3</sup>)</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->volume_baru = 0;
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;', 'disabled'=>'disabled']); 
								?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;P1</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->diameter_pangkal1_baru = 0;
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_pangkal1_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
                            </td style="vertical-align: middle; padding: 2px;">
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Alokasi</td>
                            <td  style="vertical-align: middle; padding: 2px;">
                                <?php echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '[ii]['.($i).']alokasi',['Sawmill'=>'Sawmill', 'Plymill'=>'Plymill', 'Afkir'=>'Afkir', 'Gudang'=>'Gudang'],['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px;','onchange'=>'setGradingRule(this);']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">&#8709;P2</td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
									$modDetailPot->diameter_pangkal2_baru = 0;	
									echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']diameter_pangkal2_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
								?>
                            </td>
                            <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;"><span id='label-grade-ii-<?= $i ?>'>Grade</span></td>
                            <td style="vertical-align: middle; padding: 2px;">
                                <?php 
                                echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '[ii]['.($i).']grading_rule',['Standard'=>'Standard', 'Tanduk'=>'Tanduk'],['class'=>'form-control', 'style'=>'padding: 2px; font-size:13px; height:25px;']);
                                // echo \yii\bootstrap\Html::activeDropDownList($modDetailPot, '[ii]['.($i).']grading_rule',['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3'],['class'=>'form-control', 'prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;', 'disabled'=>'disabled']); 
                                ?>
                            </td>
                        </tr>
                <?php }
                } ?>
        </table>
	</td>
</tr>
<?php $this->registerJs(" 
", yii\web\View::POS_READY); ?>
