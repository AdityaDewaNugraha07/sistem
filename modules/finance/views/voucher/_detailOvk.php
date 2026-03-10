<?php
$supplier = ''; $alamat = ''; $bank = ''; $rekening = ''; $an_rek = '';

if($model->penerima_reff_table == "m_suplier"){
    $modSuplier = app\models\MSuplier::findOne($model->penerima_reff_id);
    if(!empty($modSuplier)){
        $supplier = $modSuplier->suplier_nm_company;
        $alamat = $modSuplier->suplier_almt;
        $bank = $modSuplier->suplier_bank;
        $rekening = $modSuplier->suplier_norekening;
        $an_rek = $modSuplier->suplier_an_rekening;
    }
} else if($model->penerima_reff_table == 'm_penerima_voucher'){
    $modPenerima = app\models\MPenerimaVoucher::findOne($model->penerima_voucher_id);
    if(!empty($modPenerima)){
        $supplier = $modPenerima->nama_penerima;
        $alamat = $modPenerima->penerima_alamat;
        $bank = $modPenerima->rekening_bank;
        $rekening = $modPenerima->rekening_no;
        $an_rek = $modPenerima->rekening_an;
    }
    if($voucher_pengeluaran_id) {
        $modVoc = app\models\TVoucherPengeluaran::findOne($voucher_pengeluaran_id);
        if($modVoc->penerima_pembayaran){
            $penerima_pembayaran = \yii\helpers\Json::decode($modVoc->penerima_pembayaran);
            $bank = $penerima_pembayaran[0]['nama_bank'];
            $rekening = $penerima_pembayaran[0]['rekening'];
            $an_rek = $penerima_pembayaran[0]['an_bank'];
        }
    }
    
} else if($model->penerima_reff_table == 'm_pegawai'){
    $modPenerima = app\models\MPegawai::findOne($model->pegawai_id);
    $modDept = app\models\MDepartement::findOne($modPenerima->departement_id);
    if(!empty($modPenerima)){
        $supplier = $modPenerima->pegawai_nama;
        $alamat = $modDept->departement_nama;
    }
} else if($model->penerima_reff_table == 't_asuransi'){
    $modAsuransi = app\models\TAsuransi::findOne(['kode'=>$model->reff_no]);
    if(!empty($modAsuransi)){
        $supplier = $modAsuransi->kepada;
    }
}

?>
<div class="col-md-12">
	<h4><?= Yii::t('app', 'Detail Open Voucher'); ?></h4>
</div>
<div class="col-md-12">
    <div class="row">
		<div class="col-md-12">
			<table style="width: 100%; font-size: 1.1rem;">
                <tr>
                    <td style="vertical-align: text-top;">Tipe</td>
                    <td style="vertical-align: text-top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: text-top;"><b><?= $model->tipe ?></b></td>
					<td style="width: 120px; text-align: right; vertical-align: text-top;">Tanggal</td>
                    <td style="width: 20px; vertical-align: text-top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: text-top;"><b><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></b></td>
                </tr>
                <tr>
					<td style="width: 120px; vertical-align: text-top;">Kode Open Voucher</td>
                    <td style="width: 20px; vertical-align: text-top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: text-top;"><b><?= $model->kode ?></b></td>
					<td style="text-align: right; vertical-align: text-top;">Keterangan</td>
                    <td style="vertical-align: text-top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: text-top;"><b><?= !empty($model->keterangan)?$model->keterangan:"-" ?></b></td>                    
				</tr>
				<tr>
                    <td style="vertical-align: text-top">Penerima</td>
                    <td style="vertical-align: text-top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: text-top"><b><?= $supplier.''. ( !empty($alamat)?"<br>".$alamat:"" ); ?></b></td>
                    <?php
                    if($model->penerima_voucher_qq != "" || $model->penerima_voucher_qq != NULL){
                    ?>
                    <td style="vertical-align: text-top; text-align: right;">Penerima QQ</td>
                    <td style="vertical-align: text-top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: text-top"><b>
                        <?php echo nl2br($model->penerima_voucher_qq); ?>
                    </b></td>
                    <?php
                    }
                    ?>
				</tr>
                <tr>
					<td style="width: 120px; vertical-align: text-top;">Rekening Bank</td>
                    <td style="width: 20px; vertical-align: text-top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: text-top;"><b><?= $bank." - ".$rekening."<br>a.n. ". $an_rek; ?></b></td>       
				</tr>
			</table>
		</div>
	</div>
	<div class="table-scrollable">
        <table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-ovk">
            <thead>
                <tr>
                    <th style="width: 30px; padding: 5px;">No.</th>
                    <th style="padding: 5px;">Deskripsi</th>
                    <th style="width: 100px; padding: 5px;">Nominal</th>
                    <th style="width: 100px; padding: 5px;">PPn</th>
                    <th style="width: 100px; padding: 5px;">Pph</th>
                    <th style="width: 120px; padding: 5px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(count($modOpenVoucherDetail)>0){
                    foreach($modOpenVoucherDetail as $i => $detail){
                        $detail->nominal = ($model->mata_uang == "IDR")?number_format($detail->nominal):app\components\DeltaFormatter::formatNumberForUserFloat($detail->nominal, 2);
                        $detail->ppn = ($model->mata_uang == "IDR")?number_format($detail->ppn):app\components\DeltaFormatter::formatNumberForUserFloat($detail->ppn, 2);
                        $detail->pph = ($model->mata_uang == "IDR")?number_format($detail->pph):app\components\DeltaFormatter::formatNumberForUserFloat($detail->pph, 2);
                        $detail->subtotal = ($model->mata_uang == "IDR")?number_format($detail->subtotal):app\components\DeltaFormatter::formatNumberForUserFloat($detail->subtotal, 2);
                        ?>
                        <tr>
                            <td style="vertical-align: middle; text-align: center;" class="td-kecil">
                                <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
                                <span class="no_urut"><?= $i+1 ?></span>
                            </td>
                            <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
                                <?= \yii\helpers\Html::activeTextarea($detail, '[ii]deskripsi', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','rows'=>'1','disabled'=>true]); ?>
                            </td>
                            <td style="vertical-align: middle;" class="td-kecil">
                                <?= \yii\helpers\Html::activeTextInput($detail, '[ii]nominal', ['class'=>'form-control float', 'onblur'=>'total()','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>true]); ?>
                            </td>
                            <td style="vertical-align: middle;" class="td-kecil">
                                <?= \yii\helpers\Html::activeTextInput($detail, '[ii]ppn', ['class'=>'form-control float', 'onblur'=>'total()','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>true]); ?>
                            </td>
                            <td style="vertical-align: middle;" class="td-kecil">
                                <?= \yii\helpers\Html::activeTextInput($detail, '[ii]pph', ['class'=>'form-control float', 'onblur'=>'total()','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>true]); ?>
                            </td>
                            <td style="vertical-align: middle;" class="td-kecil">
                                <?= \yii\helpers\Html::activeTextInput($detail, '[ii]subtotal', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','onblur'=>'total()','disabled'=>true]); ?>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="vertical-align: middle; text-align: right;"></td>
                    <td style="vertical-align: middle; text-align: right;">
                        <?= yii\bootstrap\Html::textInput('totaldppreff',($model->mata_uang == "IDR")?number_format($model->total_dpp):app\components\DeltaFormatter::formatNumberForUserFloat($model->total_dpp, 2),['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
                    </td>
                    <td style="vertical-align: middle; text-align: right;">
                        <?= yii\bootstrap\Html::textInput('totalppnreff',($model->mata_uang == "IDR")?number_format($model->total_ppn):app\components\DeltaFormatter::formatNumberForUserFloat($model->total_ppn, 2),['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
                    </td>
                    <td style="vertical-align: middle; text-align: right;">
                        <?= yii\bootstrap\Html::textInput('totalpphreff',($model->mata_uang == "IDR")?number_format($model->total_pph):app\components\DeltaFormatter::formatNumberForUserFloat($model->total_pph, 2),['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= \yii\bootstrap\Html::hiddenInput('totaldpreff',  ($model->mata_uang == "IDR")?number_format($model->total_dp):app\components\DeltaFormatter::formatNumberForUserFloat($model->total_dp, 2), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('totalsisareff', ($model->mata_uang == "IDR")?number_format($model->total_sisa):app\components\DeltaFormatter::formatNumberForUserFloat($model->total_sisa, 2), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('totalpembayaranreff',  ($model->mata_uang == "IDR")?number_format($model->total_pembayaran):app\components\DeltaFormatter::formatNumberForUserFloat($model->total_pembayaran, 2), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('total_potongan',  ($model->mata_uang == "IDR")?number_format($model->total_potongan):app\components\DeltaFormatter::formatNumberForUserFloat($model->total_potongan, 2), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('biaya_tambahan',  ($model->mata_uang == "IDR")?number_format($model->biaya_tambahan):app\components\DeltaFormatter::formatNumberForUserFloat($model->biaya_tambahan, 2), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<div class="row">
    <div class="col-md-12" id="place-berkas-reff">
        <?= $this->render('@app/modules/finance/views/openvoucher/_reff_berkas', ['tipe'=>$model->tipe]) ?>
    </div>
</div>
<script>

</script>