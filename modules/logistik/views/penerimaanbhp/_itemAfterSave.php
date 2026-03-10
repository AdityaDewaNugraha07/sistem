<?php $hide = ''; if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC){ $hide = 'none'; } ?>
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]subtotal'); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;">
		<?= $modelDetail->bhp->bhp_nm; ?>
    </td>
	<td style="vertical-align: middle; text-align: center;">
		<?= !empty($qty_po)?$qty_po:"-"; ?>
    </td>
    <td style="vertical-align: middle;">
		<center><?= $modelDetail->terimabhpd_qty; ?></center>
		<center><?= yii\helpers\Html::activeHiddenInput($modelDetail, '[ii]terimabhpd_qty',['class'=>'form-control money-format']); ?></center>
    </td>
    <td style="vertical-align: middle;">
		<span class="satuan">
			<?= !empty($modelDetail->bhp_id) ? $modelDetail->bhp->bhp_satuan : ""; ?>
		</span>
    </td>
    <td style="vertical-align: middle; text-align: right; padding: 3px;">
		<?= (empty($hide))?$modelDetail->terimabhpd_harga_display:''; ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]terimabhpd_harga',['disabled'=>'disabled']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]pph_peritem',['disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle; text-align: right;">
		<?= (empty($hide))?$modelDetail->subtotal:'' ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]subtotal',['disabled'=>'disabled']); ?>
    </td>
	<td style="vertical-align: middle; text-align: right;">
		<?= (empty($hide))?$modelDetail->pph_peritem:'' ?>
		<span style="font-size: 1.0rem; display: block;" id="npwpitemplace"><?= (!empty($modelDetail->npwp)?"NPWP : ".$modelDetail->npwp:"") ?></span>
    </td>
    <td style="vertical-align: middle; font-size: 1rem; padding: 3px; max-width: 200px; ">
        <?= (!empty($modelDetail->terimabhpd_keterangan)?$modelDetail->terimabhpd_keterangan:" - ") ?>
    </td>
	<td>
		<?php
		$sql = "SELECT * FROM t_retur_bhp WHERE terima_bhpd_id = ".$modelDetail->terima_bhpd_id;
		$mod = Yii::$app->db->createCommand($sql)->queryOne();
		
		if(!empty($mod)){ 
		?>
			<a onclick="infoReturBHP(<?= $mod['retur_bhp_id'] ?>);" class="blue-steel" style="font-size: 1rem"><?= $mod['kode']; ?></a>
		<?php
		} else {
			if ($model->cancel_transaksi_id != '') {

			} else {
				if ($model->status_approval == "APPROVED" || $model->status_approval == "ALLOWED") {
				?>
				<a href="javascript:void(0);" onclick="returBHP(<?= $modelDetail->terima_bhpd_id ?>);" class="btn blue-steel btn-xs btn-outline" style="font-size: 0.9rem"><i class="fa fa-share"></i> <?= Yii::t('app', 'Retur BHP'); ?></a><br>
				<?php
				}
				if (empty($model->voucher_pengeluaran_id) && Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER) { 
				?>
					<!--<a href="javascript:void(0);" onclick="abortItem(<?php // echo $modelDetail->terima_bhpd_id ?>);" class="btn red-flamingo btn-xs btn-outline" style="font-size: 0.9rem"><i class="fa fa-close"></i> <?= Yii::t('app', 'Abort'); ?></a>-->
				<?php
				}
			}
		}
		?>
	</td>
</tr>