<style>
#table-bayar thead tr th{
	font-size: 1.2rem !important;
}
#table-bayar tbody tr td{
	font-size: 1.2rem;
}
#table-bayar tfoot tr td{
	font-size: 1.2rem;
}
</style>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover" id="table-bayar">
		<thead>
			<tr><td colspan="6"><a class="btn btn-xs blue-steel btn-outline" onclick="newBayar(<?= $cust_id ?>)"><i class="fa fa-plus"></i> Pembayaran Baru</a></td></tr>
			<tr style="background-color: #F1F4F7; ">
				<th style="text-align: center; width: 20px;"><?= Yii::t('app', 'No.'); ?></th>
				<th style="text-align: center; "><?= Yii::t('app', 'Bill Reff'); ?></th>
				<th style="text-align: center; width: 80px; line-height: 0.8;"><?= Yii::t('app', 'Payment<br>Reff'); ?></th>
				<th style="text-align: center; width: 70px; line-height: 0.8;"><?= Yii::t('app', 'Payment<br>Date'); ?></th>
				<th style="text-align: center; width: 85px;"><?= Yii::t('app', 'Bayar'); ?></th>
				<th style="text-align: center; width: 30px;"><?= Yii::t('app', ''); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total = 0;
			if(count($modPiutangs)>0){
				foreach($modPiutangs as $i => $piutang){
					$label = $piutang['payment_reff'];
					if (strpos($piutang['payment_reff'], 'FVM') !== false) {
						$paramInfoBayar	= "infoVoucher('".$piutang['payment_reff']."')";
					}else if (strpos($piutang['payment_reff'], 'KB') !== false) {
                        $payment_reff = explode('/',$piutang['payment_reff']);
                        $payment_reff = $payment_reff[0];
						$paramInfoBayar	= "infoKasbesar('".app\models\TKasBesar::findOne(['kode'=>$payment_reff])->tanggal."')";
					}else if (strpos($piutang['payment_reff'], 'LPGC') !== false) {
						$bgCekParam = explode("-", $piutang['payment_reff']);
						$paramInfoBayar	= "";
						if(count($bgCekParam)>1){
							$paramInfoBayar	= "infoGirocek('". app\models\TKasBesarNontunai::findOne(['kode'=>$bgCekParam[0],'reff_number'=>$bgCekParam[1]])->tanggal."')";
						}
					}else if (strpos($piutang['payment_reff'], 'RPP') !== false) {
						$paramInfoBayar	= "infoReturPenjualan('{$piutang['payment_reff']}')";
					}else{
						if($piutang['payment_reff']=="CN"){
							$label = "Credit Note";
						}else if($piutang['payment_reff']=="Potongan"){
							$label = "Potongan";
						}
						$paramInfoBayar = "infoCatatan('".$piutang['piutang_penjualan_id']."')";
					}
                    if($is_export == 'true'){
                        $modInv = app\models\TInvoice::findOne(['nomor'=>$piutang['bill_reff']]);
                        $paramBillReff = "infoInvoice(".(!empty($modInv)?$modInv->invoice_id:"").")";
                    }else{
                        $paramBillReff = "infoNota('".$piutang['bill_reff']."')";
                    }
				?>
					<tr>
						<td style="text-align: center;">
							<?php echo $i+1; ?>
						</td>
						<td style="text-align: center; font-size: 1rem;"><a onclick="<?= $paramBillReff ?>"><?= $piutang['bill_reff'] ?></a></td>
						<td style="text-align: center; font-size: 1rem;"><a onclick="<?= $paramInfoBayar ?>"><?= $label ?></a></td>
						<td style="text-align: center; font-size: 1rem;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($piutang['tanggal_bayar']); ?></td>
						<td style="text-align: right; font-size: 1.1rem;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($piutang['bayar']) ?></td>
						<td style="text-align: center; font-size: 1rem;">
							<a class="btn btn-xs red-flamingo" onclick="openModal('<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/deletePiutang','id'=>$piutang['piutang_penjualan_id']]) ?>','modal-delete-record')"><i class="fa fa-trash-o"></i></a>
						</td>
					</tr>
			<?php
				$total += $piutang['bayar'];
				}
			}else{
				?>
					<tr><td colspan="5" style="text-align: center;"><i>Tidak ditemukan data pembayaran</i></td></tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr style="background-color: #F1F4F7">
				<td colspan="4" style="text-align: right; vertical-align: middle;"><b>Total Bayar &nbsp;</b></td>
				<td style="text-align: right; vertical-align: middle; font-weight: 600; font-size: 1.1rem;">
					<span class="pull-right" id="place-jumlahterbayar"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total) ?></span>
				</td>
			</tr>
			<!--<tr><td colspan="6"><a class="btn btn-xs blue-steel btn-outline" onclick="newBayar(<?= $cust_id ?>)"><i class="fa fa-plus"></i> Pembayaran Baru</a></td></tr>-->
		</tfoot>
	</table>
</div>