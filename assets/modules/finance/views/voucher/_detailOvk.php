<div class="col-md-12">
	<h4><?= Yii::t('app', 'Detail Open Voucher'); ?></h4>
</div>
<div class="col-md-12">
    <div class="row">
		<div class="col-md-12">
			<table style="width: 100%; font-size: 1.1rem;">
				<tr>
					<td style="width: 120px">Kode Open Voucher</td>
                    <td style="width: 20px"><b>:</b></td>
					<td style=""><b><?= $model->kode ?></b></td>
					<td style="width: 120px">Tanggal</td>
                    <td style="width: 20px"><b>:</b></td>
					<td style=""><b><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></b></td>
				</tr>
				<tr>
                    <td>Penerima</td>
                    <td><b>:</b></td>
					<td><b>
                        <?php
                            if($model->penerima_reff_table == "m_suplier"){
                                $modSuplier = app\models\MSuplier::findOne($model->penerima_reff_id);
                                if(!empty($modSuplier)){
                                    echo "<b>".$modSuplier->suplier_nm."</b>".( !empty($modSuplier->suplier_almt)?"<br>".$modSuplier->suplier_almt:"" );
                                }
                            }
                        ?>
                    </b></td>
					<td>Keterangan</td>
                    <td><b>:</b></td>
					<td><b><?= !empty($model->keterangan)?$model->keterangan:"-" ?></b></td>
				</tr>
                <tr>
                    <td>Tipe</td>
                    <td><b>:</b></td>
					<td><b><?= $model->tipe ?></b></td>
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
                        $detail->nominal = number_format($detail->nominal);
                        $detail->ppn = number_format($detail->ppn);
                        $detail->pph = number_format($detail->pph);
                        $detail->subtotal = number_format($detail->subtotal);
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
                        <?= yii\bootstrap\Html::textInput('totaldppreff',number_format($model->total_dpp),['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
                    </td>
                    <td style="vertical-align: middle; text-align: right;">
                        <?= yii\bootstrap\Html::textInput('totalppnreff',number_format($model->total_ppn),['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
                    </td>
                    <td style="vertical-align: middle; text-align: right;">
                        <?= yii\bootstrap\Html::textInput('totalpphreff',number_format($model->total_pph),['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= \yii\bootstrap\Html::hiddenInput('totaldpreff',  number_format($model->total_dp), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('totalsisareff', number_format($model->total_sisa), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('totalpembayaranreff',  number_format($model->total_pembayaran), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('total_potongan',  number_format($model->total_potongan), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<?= \yii\bootstrap\Html::hiddenInput('biaya_tambahan',  number_format($model->biaya_tambahan), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
<div class="row">
    <div class="col-md-12" id="place-berkas-reff">
        <?= $this->render('@app/modules/finance/views/openvoucher/_reff_berkas', ['tipe'=>$model->tipe]) ?>
    </div>
</div>
<script>

</script>