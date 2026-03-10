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