<table style="width: 100%; height: 1cm;" border="0">
	<tr style="">
		<td style="text-align: left; vertical-align: top; padding: 10px; width: 5.5cm; height: 1.5cm; border-bottom: 1px solid black; border-right: 1px solid black;">
			Dibayarkan Kepada :<br>
				<?php
//				$berkas_initial = substr($model->nomor_terkait,0,3);
//				switch ($berkas_initial){
//				case "SPO":
//					echo "<span style='font-size:1.3rem'><b>".$model->suplier->suplier_nm."</b></span>";
//					break;
//				}
				if(!empty($model->suplier_id)){
					echo "<span style='font-size:1.3rem'><b>".$model->suplier->suplier_nm."</b></span>";
				}else{
					if($model->tipe == "Top-up Kas Kecil" || $model->tipe == "Ganti Kas Kecil"){
						echo "<b>Kas Kecil CWM</b>";
					}else if($model->tipe == "Ganti Kas Besar"){
						echo "<b>Kas Besar CWM</b>";
					}else if($model->tipe == "Uang Dinas Grader"){
						$modAjuanDinas = \app\models\TAjuandinasGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
						echo $modAjuanDinas->graderlog->graderlog_nm;
					}else if($model->tipe == "Uang Makan Grader"){
						$modAjuanMakan = \app\models\TAjuanmakanGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
						echo $modAjuanMakan->graderlog->graderlog_nm;
					}
				}
				?>
			
		</td>
		<td style="text-align: center; vertical-align: top; padding: 10px;">
			<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul']; ?></u></span><br>
			<?php
			if(!empty($model->akun_debit)){
				echo "<span>".substr( \app\models\MAcctRekening::getByPk($model->akun_debit)->acct_nm, -3,3 )."</span>";
				if( substr($model->kode, 0,3) == "BBK" ){
					$kode = $model->kode;
				}else{
					$kode = \app\components\DeltaGenerator::kodeBuktiBankKeluar($model->akun_debit,$model->tanggal_bayar);
				}
			}
			?>
		</td>
		<td style="width: 5.5cm; height: 1.7cm; vertical-align: top; padding: 10px;">
			<table>
				<tr>
					<td style="width:1.7cm;">No.</td>
					<td>: &nbsp; <?= $kode; ?></td>
				</tr>
				<tr>
					<td style="width:1.7cm;">Tanggal</td>
					<!--<td>: &nbsp; <?php // echo \app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal_bayar); ?></td>-->
					<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal_bayar ); ?> </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>