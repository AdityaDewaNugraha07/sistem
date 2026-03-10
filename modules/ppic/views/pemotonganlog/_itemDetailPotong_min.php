<?php for ($i = 0; $i < $jml; $i++) { ?>
    <tr style="background: #e9ecef;" data-status="false">
        <td colspan="4" style="font-weight: bold; font-size: 14px; padding: 6px;">
            <span>Potongan ke-<?= $i + 1 ?> dari No. Lapangan <?= $no_lap; ?></span><br>
            <span style="font-size: 1.2rem; width: 50px;">(dalam satuan cm)</span>
        </td>
    </tr>
    <tr>
        <td style="font-size: 1.2rem; width: 50px; padding: 2px; vertical-align: middle;">Kode Potong</td>
        <td style="vertical-align: middle; padding: 2px;">
            <?php 
            echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']kode_pemotongan',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>($cut=='true'?false:true), 'oninput'=>"removeNonLetters(this)"]); ?>
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
				$modDetailPot->panjang_baru = $cut == 'true'?0:$panjang;		
				echo yii\helpers\Html::activeTextInput($modDetailPot, '[ii]['.($i).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','disabled'=>($cut=='true'?false:true),'onblur'=>'setVolBaru(this)']); 
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
<?php } ?>