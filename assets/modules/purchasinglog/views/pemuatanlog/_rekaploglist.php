<table style="width: 100%">
	<tbody>
		<tr>
			<td style="text-align: left; width: 25%;"><?= Yii::t('app', 'Kode'); ?></td>
			<td style="width: 5%;">:</td>
			<td style="font-weight: bold;"><?= $model->loglist_kode; ?></td>
		</tr>
		<tr>
			<td style="text-align: left; width: 25%;"><?= Yii::t('app', 'Tanggal Loglist'); ?></td>
			<td style="width: 5%;">:</td>
			<td style="font-weight: bold;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
		</tr>
		<tr>
			<td style="text-align: left; width: 25%;"><?= Yii::t('app', 'Lokasi Muat'); ?></td>
			<td style="width: 5%;">:</td>
			<td style="font-weight: bold;"><?= $model->lokasi_muat; ?></td>
		</tr>
		<tr>
			<td style="text-align: left; width: 25%;"><?= Yii::t('app', 'Jenis Kayu'); ?></td>
			<td style="width: 5%;">:</td>
			<td style="font-weight: bold;"><?= \app\models\TLoglistDetail::getKayuByLoglist($model->loglist_id); ?></td>
		</tr>
		<tr>
			<td style="text-align: left; width: 25%;"><?= Yii::t('app', 'Rekap Volume'); ?></td>
			<td style="width: 5%;">:</td>
			<td></td>
		</tr>
		<tr>
			<td style="width: 100%;" colspan="3"><?= \app\models\TLoglistDetail::getRekapPerRange($model->loglist_id); ?></td>
		</tr>
	</tbody>
</table>