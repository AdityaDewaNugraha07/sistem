<table style="width: 100%;" border="0">
	<tr style="">
		<td style="text-align: center; width: 20cm; vertical-align: middle;" colspan="2">
			<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul'] ?></u></span>
		</td>
	</tr>
	<tr>
		<td style="text-align: left; width: 14cm">
			<table style="width: 100%; font-size: 1.3rem" border="0">
				<tr>
					<td style="width: 2cm;">Supplier :</td>
					<td> &nbsp;  </td>
				</tr>
				<tr>
					<td colspan="2"><b>
						<?php
							if(!empty($model->spo_id)){
								echo $model->suplier->suplier_nm.",<br>".$model->suplier->suplier_almt;
							}else if(!empty($model->spl_id)){
								$mods = app\models\TTerimaBhpDetail::find()
										->select('suplier_id')
										->groupBy('suplier_id')
										->where(['terima_bhp_id'=>$model->terima_bhp_id])
										->all();
								if(count($mods)==1){
									echo $mods[0]->suplier->suplier_nm.",<br>".$mods[0]->suplier->suplier_almt;
								}else{
									echo "-";
								}
							}
						?>
					</b></td>
				</tr>
			</table>
		</td>
		<td style="text-align: right; width: 6cm">
			<table style="width: 100%; font-size: 1.3rem" border="0">
				<tr>
					<td style="width: 2.5cm;">No. :</td>
					<td><?= $model->terimabhp_kode; ?> </td>
				</tr>
				<tr >
					<td >Tanggal :</td>
					<td><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tglterima); ?> </td>
				</tr>
				<tr >
					<td >Kode PO/SPL :</td>
					<td> 
					<?php
					if(!empty($model->spo_id)){
						echo $model->spo->spo_kode;
					}else if(!empty($model->spl_id)){
						echo $model->spl->spl_kode;
					}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>