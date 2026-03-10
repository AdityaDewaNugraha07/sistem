<table style="width: 100%; height: 1.3cm;" border="0">
	<tr style="">
		<td style="text-align: center; width: 14cm; vertical-align: middle;" colspan="2">
			<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul'] ?></u></span>
		</td>
		<td style="text-align: right;">
			<table style="width: 100%; font-size: 1.3rem" border="0">
				<tr>
					<td style="width: 1.5cm;">No. </td>
					<td>: &nbsp; <?= $model->spl_kode; ?> </td>
				</tr>
				<tr >
					<td >Tanggal </td>
					<td>: &nbsp; <?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spl_tanggal); ?> </td>
				</tr>
			</table>
		</td>
	</tr>
</table>