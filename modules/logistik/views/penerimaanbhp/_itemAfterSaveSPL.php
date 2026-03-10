<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;">
		<?= $detail->bhp->bhp_nm; ?>
    </td>
    <td style="vertical-align: middle;">
		<center><?= $detail->terimabhpd_qty; ?></center>
    </td>
    <td style="vertical-align: middle;">
		<span class="satuan">
			<?= !empty($detail->bhp_id) ? $detail->bhp->bhp_satuan : ""; ?>
		</span>
    </td>
    <td style="vertical-align: middle; text-align: right;">
		
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_estimasi) ?> &nbsp; 
    </td>
    <td style="vertical-align: middle; text-align: right;">
		<?= yii\bootstrap\Html::activeTextInput($detail, '[ii]terimabhpd_harga',['class'=>'form-control money-format','onblur'=>'setSubtotal(this)','disabled'=>'disabled','style'=>'text-align:right;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: right;">
		<?= \yii\helpers\Html::activeTextInput($detail, '[ii]subtotal_display', ['class'=>'form-control money-format','disabled'=>'disabled']); ?>
        <?= \yii\helpers\Html::activeHiddenInput($detail, '[ii]subtotal', ['disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;">
		<?= !empty($detail->terimabhpd_keterangan)?$detail->terimabhpd_keterangan:"<center> - </center>" ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?php
		$sql = "SELECT * FROM t_retur_bhp WHERE terima_bhpd_id = ".$modelDetail->terima_bhpd_id;
		$mod = Yii::$app->db->createCommand($sql)->queryOne();
		if(!empty($mod)){ ?>
			<a onclick="infoReturBHP(<?= $mod['retur_bhp_id'] ?>);" class="blue-steel" style="font-size: 1rem"><?= $mod['kode']; ?></a>
		<?php }else{ ?>
			<a href="javascript:void(0);" onclick="returBHP(<?= $modelDetail->terima_bhpd_id ?>);" class="btn blue-steel btn-xs btn-outline" style="font-size: 0.9rem"><i class="fa fa-share"></i> <?= Yii::t('app', 'Retur BHP'); ?></a>
		<?php } ?>
    </td>
</tr>