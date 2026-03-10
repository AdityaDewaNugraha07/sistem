<table style="width: 100%;" border="0">
	<tr style="">
		<td style="text-align: center; width: 14cm; vertical-align: middle;" colspan="2">
			<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul'] ?></u></span>
		</td>
	</tr>
	<tr>
		<td style="text-align: left; width: 15cm">
			<table style="width: 100%; font-size: 1.3rem" border="0">
				<tr>
					<td style="width: 3cm;">Nama Bagian </td>
					<td>: &nbsp; <?= $model->departement->departement_nama; ?> </td>
				</tr>
				<tr >
					<td colspan="2"><i>Mohon dibelikan barang sebagai berikut :</i></td>
				</tr>
			</table>
		</td>
		<td style="text-align: right;">
			<table style="width: 100%; font-size: 1.3rem" border="0">
				<tr>
					<td style="width: 1.5cm;">No. </td>
					<td>: &nbsp; <?= $model->spp_kode; ?> </td>
				</tr>
				<tr >
					<td >Tanggal </td>
					<td>: &nbsp; <?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal); ?> </td>
				</tr>
			</table>
		</td>
	</tr>
</table>