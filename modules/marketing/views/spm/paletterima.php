<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<style>
.table-random, 
.table-random > tbody > tr > td, 
.table-random > tbody > tr > th, 
.table-random > tfoot > tr > td, 
.table-random > tfoot > tr > th, 
.table-random > thead > tr > td, 
.table-random > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}
</style>
<div class="modal fade" id="modal-palet-terima" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">
                    <?php
                    if($modOp->jenis_produk == "JasaKD" || $modOp->jenis_produk == "JasaMoulding"){
                        echo Yii::t('app', 'Penerimaan Palet ')." Customer : <b>".$modOp->cust->cust_an_nama."</b> - Kode OP : <b>".$modOp->kode."</b>"; 
                        $jdl_palet = "Palet Penerimaan";
                    }else if($modOp->jenis_produk == "JasaGesek"){
                        echo Yii::t('app', 'Data Palet Hasil Gesek ')." Customer : <b>".$modOp->cust->cust_an_nama."</b> - Kode OP : <b>".$modOp->kode."</b>"; 
                        $jdl_palet = "Palet Hasil Gesek";
                    }
                    ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4" style="<?= ($lihat=="1")?"display: none;":"" ?>">
                        <h5><?= $jdl_palet ?> </h5>
                        <table class="table table-striped table-bordered table-hover table-laporan table-random" id="table-palet">
                                                <thead>
                                                        <tr>
                                                                <th ><?= Yii::t('app', 'Nomor Palet') ?></th>
                                                                <th style="width: 100px;">Pilih</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <?php
                                $palet_sdh_spm = [];
                                $modSPM = app\models\TSpmKo::find()->where(['op_ko_id'=>$modOp->op_ko_id])->all();
                                if(count($modSPM)>0){
                                    foreach($modSPM as $ixc => $spm){
                                        $modDetailSpm = \app\models\TSpmKoDetail::find()->where("spm_ko_id = ".$spm->spm_ko_id)->all();
                                        foreach($modDetailSpm as $ii =>  $detspm){
                                            if(!empty($detspm['keterangan'])){
                                                $detspm_ket = explode(",", str_replace("'", "", $detspm['keterangan']) );
                                                if(count($detspm_ket)){
                                                    foreach($detspm_ket as $iii => $xcv){
                                                        $palet_sdh_spm[] = $xcv;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                        if(count($models)>0){
                                                foreach($models as $i => $model){ 
                                                        $checked = ""; $checkDisabled = false;
                                                        if (in_array($model['nomor_palet'], $nomor_palet_exist)) {
                                                                $checked = "checked=''";
                                                        }
                                                        if( in_array($model['nomor_palet'], $palet_sdh_spm) ) {
                                                            $checkDisabled = true;
                //                                            $checked = "checked=''";
                                                        }
                                                                                ?>
                                                        <tr>
                                                            <td style="text-align: center; vertical-align: middle;">
                                                                    <?= yii\bootstrap\Html::hiddenInput('nomor_palet',$model['nomor_palet']) ?>
                                                                    <?= $model['nomor_palet'] ?>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: middle;">
                                                                <?php 
                                                                $pilih_okok = '<input type="checkbox" '.$checked.' name="['.$i.']pilih" onclick="check(this,\''.$model['nomor_palet'].'\')">';

                                                                if(empty($spm_ko_id)){
                                                                    $modSpm = Yii::$app->db->createCommand("SELECT * FROM t_spm_ko JOIN t_spm_ko_detail ON t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id WHERE t_spm_ko.op_ko_id = ".$modOp->op_ko_id." AND t_spm_ko_detail.keterangan ILIKE '%".$model['nomor_palet']."%' ")->queryOne();
                                                                    if($checkDisabled){
                                                                        $pilih_okok = '<i style="font-size:1rem;">Sudah Dimuat</i><br><span style="font-size:1rem;" class="font-blue-steel">'.$modSpm['kode']."</span>";
                                                                    }
                                                                }

                                                                echo $pilih_okok;
                                                                ?>

                                                            </td>
                                                        </tr>
                                        <?php } ?>
                                        <?php } ?>
                                </tbody>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <h5>Palet Yang Dipilih untuk dimuat</h5>
                        <table class="table table-striped table-bordered table-hover table-laporan table-random" id="table-palet-isi">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nomor Palet</th>
									<th>Dimension (t x l x p)</th>
									<th style="width: 50px;">Qty (Pcs)</th>
									<th style="width: 80px;">M<sup>3</sup></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" style="text-align: right"><b>Total</b></td>
									<td style="text-align: right"><b><input type="text" disabled="disabled" name="tot_qty" style="width: 100px; font-weight: 600; text-align: center;"></b></td>
									<td style="text-align: right"><b><input type="text" disabled="disabled" name="tot_kubikasi" style="width: 100px; font-weight: 600; text-align: right;"></b></td>
								</tr>
							</tfoot>
						</table>
                    </div>
                </div>
            <div class="modal-footer">
                <?= yii\bootstrap\Html::hiddenInput('reff_ele',$tr_seq) ?>
                <?= yii\bootstrap\Html::hiddenInput('nomor_palet_all') ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" 
	setTimeout(function(){ 
        check();
	}, 300); ", yii\web\View::POS_READY); ?>
<script>
function check(){
    var op_ko_id = "<?= $modOp->op_ko_id ?>";
	var nopalet = "";
	var total_checked = 0;
	var tot_qty = 0;
	var tot_kubikasi = 0;
	$("#table-palet > tbody > tr").each(function(i){
		if($(this).find("input[name*='pilih']").is(":checked")){
			nopalet += "'"+$(this).find("input[name*='nomor_palet']").val()+"'";
			total_checked = total_checked+1;
			if($("#table-palet > tbody > tr").find("input[name*='pilih']:checked").length != (total_checked)){
				nopalet += ",";
			}
		}
	});
	$("input[name='nomor_palet_all']").val(nopalet);
	$('#table-palet-isi > tbody').html("<tr><td colspan='5' style='text-align:center'><i>Not Found</i></td></tr>");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/spm/getPaletisi']); ?>',
		type   : 'POST',
		data   : {op_ko_id:op_ko_id,nomor_palet:nopalet},
		success: function (data) {
			if(data.html){
				$('#table-palet-isi > tbody').html(data.html);
				$("input[name='tot_qty']").val(data.tot_qty);
				$("input[name='tot_kubikasi']").val(data.tot_kubikasi);
			}else{
				$("input[name='tot_qty']").val("0");
				$("input[name='tot_kubikasi']").val("0");
			}
            if(data.avaliable_palet){
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>