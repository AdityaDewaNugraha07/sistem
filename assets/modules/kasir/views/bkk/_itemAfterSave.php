<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1 ?></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= $model['detail_deskripsi']; ?>
    </td>
    <td style="vertical-align: middle; text-align: right;" class="td-kecil">
        <?= app\components\DeltaFormatter::formatNumberForUserFloat($model['detail_nominal']); ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php
		$kasbonlabel = "";
		if(!empty($model['kas_bon_id'])){
			$modKasbon = app\models\TKasBon::findOne($model['kas_bon_id']);
			$kasbonlabel = "<a onclick='infoKasbon(".$modKasbon->kas_bon_id.")'>".$modKasbon->kode."</a>";
		}
		echo $kasbonlabel;
		?>
    </td>
	<td class="text-align-center">
		<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
	</td>
</tr>