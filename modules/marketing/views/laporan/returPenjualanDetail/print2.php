<table>
	<thead>
		<tr>
			<th>No</th>
			<th>Kode Retur</th>
			<th style="line-height: 1;">Tanggal<br>Retur</th>
			<th>Sales</th>
			<th>Customer</th>
			<th>Alamat Customer</th>
			<th style="line-height: 1;">Jenis<br>Produk</th>
			<th>Produk</th>
			<th>Dimensi</th>
			<th>Pcs</th>
			<th>M<sup>3</sup></th>
			<th style="line-height: 1;">Harga<br>Jual</th>
			<th style="line-height: 1;">Harga<br>Retur</th>
			<th style="line-height: 1;">Sub<br>Total</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$sql 		= $model->searchLaporan()->createCommand()->rawSql;
		$results 	= Yii::$app->db->createCommand($sql)->queryAll();
		$no 		= 0;
		if (count($results) > 0) {
			foreach ($results as $data) { ?>
			<tr>
				<td><?= ++$no ?></td>
				<td><?= $data['kode'] ?></td>
				<td><?= $data['tanggal'] ?></td>
				<td><?= $data['sales_kode'] ?></td>
				<td><?= $data['cust_an_nama'] ?></td>
				<td><?= $data['cust_an_alamat'] ?></td>
				<td><?= $data['produk_group'] ?></td>
				<td><?= $data['produk_nama'] ?></td>
				<td><?= $data['produk_dimensi'] ?></td>
				<td><?= $data['qty_kecil'] ?></td>
				<td><?= $data['kubikasi'] ?></td>
				<td><?= $data['harga_jual'] ?></td>
				<td><?= $data['harga_retur'] ?></td>
				<td><?= in_array($data['produk_group'], ['Plywood', 'Lamineboard', 'Platform', 'Limbah'])
						? $data['qty_kecil'] * $data['harga_retur']
						: $data['kubikasi'] * $data['harga_retur'] ?>
				</td>
			</tr>
		<?php }
		} ?>
	</tbody>
</table>