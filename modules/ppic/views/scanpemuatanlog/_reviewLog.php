<?php foreach($modBrgLog as $i => $log){?>
<div class="modal fade zzz" id="modal-review" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left"><b>REVIEW SCAN PEMUATAN LOG</b></div>
            <div class="modal-body text-center">
                <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id' => 'form-review-log',
                    'fieldConfig' => [
                        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                        'labelOptions'=>['class'=>'col-md-4 control-label'],
                    ],
                ]); ?>
                <style>
                    td { text-align: left;}
                </style>
                
                <table style="margin-left: -5px;"  id="table-review-log">
                    <tbody>
                        <tr>
                            <td style="width: 140px;">QR Barcode</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><b><?php echo $no_barcode;?></b></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Jenis Kayu</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $log['log_kelompok'] ;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Nama Kayu</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $modKayu->kayu_nama;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Range Diameter</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $log['range_awal'];?>cm - <?php echo $log['range_akhir'] ?>cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">No Batang</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $modPersediaan->no_btg;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">No Lapangan</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $modPersediaan->no_lap;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Kode Potong</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo (empty($modPersediaan->pot))?"-":$modPersediaan->pot;?></td>
                            <!-- <td style="width: 140px;">Ukuran Realisasi :</td> -->
                        </tr>
                        <tr>
                            <td style="width: 140px;">Panjang</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->fisik_panjang;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "panjang",['class'=>'form-control float panjang','oninput' => 'hitungVolume()', 'style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Ujung 1</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->diameter_ujung1;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "diameter_ujung1",['class'=>'form-control float diameter_ujung1','oninput' => 'hitungRata();', 'style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Ujung 2</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->diameter_ujung2;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "diameter_ujung2",['class'=>'form-control float diameter_ujung2','oninput' => 'hitungRata();','style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Pangkal 1</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->diameter_pangkal1;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "diameter_pangkal1",['class'=>'form-control float diameter_pangkal1','oninput' => 'hitungRata();','style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Pangkal 2</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->diameter_pangkal2;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "diameter_pangkal2",['class'=>'form-control float diameter_pangkal2','oninput' => 'hitungRata();','style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Rata</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->fisik_diameter;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "diameter_rata",['class'=>'form-control float diameter_rata','style'=>'width:50px; font-size:1.2rem; padding:5px;','disabled'=>'disabled']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Cacat Panjang</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->cacat_panjang;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "cacat_panjang",['class'=>'form-control float cacat_panjang','oninput' => 'hitungVolume()','style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Cacat Gubal</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->cacat_gb;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "cacat_gb",['class'=>'form-control float cacat_gb','oninput' => 'hitungVolume()','style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Cacat Growong</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->cacat_gr;?>" disabled>
                                    </span>
                                    <span>
                                        <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "cacat_gr",['class'=>'form-control float cacat_gr','oninput' => 'hitungVolume()','style'=>'width:50px; font-size:1.2rem; padding:5px;']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Volume</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;">
                                <div style="display: flex; align-items: center; height: 100%;">
                                    <span style="margin-right: 10px;">
                                        <input class="form-control float" style="width:50px; font-size:1.2rem; padding:5px;" value="<?php echo $modPersediaan->fisik_volume;?>" disabled>
                                    </span>
                                    <span>
                                    <?php echo yii\helpers\Html::activeTextInput($modSpmLog, "volume",['class'=>'form-control float volume','style'=>'width:50px; font-size:1.2rem; padding:5px;', 'disabled'=>'disabled']); ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><hr></td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Simpan'),['id'=>'btn-save','class'=>'btn btn-primary btn-outline ciptana-spin-btn ladda-button', 'data' => ['toggle' => 'modal']]); ?>
                                <!-- <button type="button" id="btn-save-details" class="btn btn-primary btn-outline ciptana-spin-btn ladda-button" onclick="saveItem();" data-style="zoom-in" title="Simpan Detail Penerimaan"><span class="ladda-label">Simpan</span><span class="ladda-spinner"></span></button> -->
                            </td>
                            <td>&nbsp;</td>
                            <td class="text-center"><button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="cancelItem();" data-style="zoom-in" title="Batalkan Detail Penerimaan"><span class="ladda-label">Batal</span><span class="ladda-spinner"></span></button></td>
                        </tr>
                    </tbody>
                </table>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php } ?>

<?php $this->registerJs(" 
formconfig(); 
", yii\web\View::POS_READY); ?>
<?php $this->registerCssFile($this->theme->baseUrl."/pages/css/profile.min.css"); ?>

<script>
$(document).ready(function() {
    $('.panjang').val(<?php echo 0 //$modPersediaan->fisik_panjang ?>);
    $('.diameter_ujung1').val(<?php echo 0 //$modPersediaan->diameter_ujung1 ?>);
    $('.diameter_ujung2').val(<?php echo 0 //$modPersediaan->diameter_ujung2 ?>);
    $('.diameter_pangkal1').val(<?php echo 0 //$modPersediaan->diameter_pangkal1 ?>);
    $('.diameter_pangkal2').val(<?php echo 0 //$modPersediaan->diameter_pangkal2 ?>);
    $('.diameter_rata').val(<?php echo 0 //$modPersediaan->fisik_diameter ?>);
    $('.cacat_panjang').val(<?php echo 0 //$modPersediaan->cacat_panjang ?>);
    $('.cacat_gb').val(<?php echo 0 //$modPersediaan->cacat_gb ?>);
    $('.cacat_gr').val(<?php echo 0 //$modPersediaan->cacat_gr ?>);
    $('.volume').val(<?php echo 0 //$modPersediaan->fisik_volume ?>);
    hitungRata();
});

document.getElementById('btn-save').addEventListener('click', function(event) {
    event.preventDefault();
    if(validatingDetail()){
        saveItem();
    }
});

function saveItem() {
    var panjang         = $('.panjang').val();
    var diameter_ujung1 = $('.diameter_ujung1').val();
    var diameter_ujung2 = $('.diameter_ujung2').val();
    var diameter_pangkal1 = $('.diameter_pangkal1').val();
    var diameter_pangkal2 = $('.diameter_pangkal2').val();
    var diameter_rata   = $('.diameter_rata').val();
    var cacat_panjang   = ($('.cacat_panjang').val() !== '')?$('.cacat_panjang').val():0;
    var cacat_gb        = ($('.cacat_gb').val() !== '')?$('.cacat_gb').val():0;
    var cacat_gr        = ($('.cacat_gr').val() !== '')?$('.cacat_gr').val():0;
    var volume          = $('.volume').val();
    $('#modal-review').modal('toggle');
    var spm_ko_id = '<?php echo $spm_ko_id;?>';
    var no_barcode = '<?php echo $no_barcode;?>';

    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/SaveNoBarcode']); ?>',
        type   : 'POST',
        data   : {spm_ko_id:spm_ko_id, no_barcode:no_barcode, panjang: panjang, diameter_ujung1:diameter_ujung1,
                    diameter_ujung2:diameter_ujung2, diameter_pangkal1:diameter_pangkal1, diameter_pangkal2:diameter_pangkal2,
                    diameter_rata:diameter_rata, cacat_panjang:cacat_panjang, cacat_gb:cacat_gb, 
                    cacat_gr:cacat_gr, volume:volume
                },

        success: function (data) {
                if(data.status){
                     window.location.href = "/cis3/web/ppic/scanpemuatanlog/index?spm_ko_id="+spm_ko_id;
                }else {
                    cisAlert(data['msg']);
                    // console.log(data);
                }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function validatingDetail(){
	var has_error = 0;
	var panjang = unformatNumber( $('.panjang').val() );
	var diameter_ujung1 = unformatNumber( $('.diameter_ujung1').val() );
	var diameter_ujung2 = unformatNumber( $('.diameter_ujung2').val() );
	var diameter_pangkal1 = unformatNumber( $('.diameter_pangkal1').val() );
	var diameter_pangkal2 = unformatNumber( $('.diameter_pangkal2').val() );
    // console.log(diameter_ujung2);

    if(!panjang){
		$('.panjang').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		if(panjang <= 0){
			$('.panjang').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$('.panjang').removeClass('error-tb-detail');
		}
    }
    if(!diameter_ujung1){
		$('.diameter_ujung1').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		if(diameter_ujung1 <= 0){
			$('.diameter_ujung1').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$('.diameter_ujung1').removeClass('error-tb-detail');
		}
    }
    if(!diameter_ujung2){
		$('.diameter_ujung2').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		if(diameter_ujung2 <= 0){
			$('.diameter_ujung2').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$('.diameter_ujung2').removeClass('error-tb-detail');
		}
    }
    if(!diameter_pangkal1){
		$('.diameter_pangkal1').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		if(diameter_pangkal1 <= 0){
			$('.diameter_pangkal1').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$('.diameter_pangkal1').removeClass('error-tb-detail');
		}
    }
    if(!diameter_pangkal2){
		$('.diameter_pangkal2').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		if(diameter_pangkal2 <= 0){
			$('.diameter_pangkal2').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$('.diameter_pangkal2').removeClass('error-tb-detail');
		}
    }

    if(has_error === 0){
        return true;
    }
    return false;
}

function cancelItem() {
    $('#modal-review').modal('toggle');
}

function hitungRata(){
	var ujung1 = unformatNumber( $('.diameter_ujung1').val() );
	var ujung2 = unformatNumber( $('.diameter_ujung2').val() );
	var pangkal1 = unformatNumber( $('.diameter_pangkal1').val() );
	var pangkal2 = unformatNumber( $('.diameter_pangkal2').val() );

	var ratarata = Math.round((ujung1+ujung2+pangkal1+pangkal2)/4);
	$('.diameter_rata').val( ratarata );
	hitungVolume();
	// console.log(ujung1);
}

function hitungVolume(){
	var panjang = $('.panjang').val();
	var ratarata = $('.diameter_rata').val();
	var cacat_panjang = $('.cacat_panjang').val();
	var cacat_gb = $('.cacat_gb').val();
	var cacat_gr = $('.cacat_gr').val();

	panjang == '' ? panjang = 0 : panjang = parseFloat(panjang);
	ratarata == '' ? ratarata = 0 : ratarata = parseFloat(ratarata);
    cacat_panjang == '' ? cacat_panjang = 0 : cacat_panjang = parseFloat(cacat_panjang);
    cacat_gb == '' ? cacat_gb = 0 : cacat_gb = parseFloat(cacat_gb);
    cacat_gr == '' ? cacat_gr = 0 : cacat_gr = parseFloat(cacat_gr);
	
	var pGrowong = (0.7854 * cacat_gr * cacat_gr * (panjang - (cacat_panjang / 100)) / 10000).toFixed(2);
    pGrowong == '' ? pGrowong = 0 : pGrowong = pGrowong;
    var zzz = (0.7854 * (panjang - (cacat_panjang / 100)) * ((ratarata - cacat_gb) * (ratarata - (cacat_gb)) * 1) / 10000) - (pGrowong);
    // var Vol = ((zzz * 100) / 100).toFixed(2);
    var Vol = zzz.toFixed(2);
    $('.volume').val(Vol);
	// fillSpmLogRealisasi();
}
</script>