<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$kode = $model->kode;
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<style>
table{
	font-size: 1.2rem;
}
table#table-detail{
	font-size: 1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
                        Keperluan <u><?= $model->keperluan ?></u>
					</td>
					<td style="width: 5cm; height: 1cm; vertical-align: top; padding: 10px;">
						<table>
							<tr>
								<td style="width:1.5cm;"><b>Kode</b></td>
								<td>: &nbsp; <?= $kode; ?></td>
							</tr>
							<tr>
								<td><b>Tanggal</b></td>
								<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal ); ?> </td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<tr style="border-bottom: 1px solid black; background-color: #F1F4F7">
                    <th class="td-kecil2" style="width: 30px; text-align: center; border-right: 1px solid black;"><?= Yii::t('app', 'No.'); ?></th>
                    <th class="td-kecil2" style="width: 65px; text-align: center; border-right: 1px solid black; line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
                    <th class="td-kecil2" style="text-align: center; border-right: 1px solid black; padding: 20px;"><?= Yii::t('app', 'KBJ / Nama Produk') ?></th>
                    <th class="td-kecil2" style="width: 150px; text-align: center; border-right: 1px solid black; "><?= Yii::t('app', 'Dimensi') ?></th>
                    <th class="td-kecil2" style="width: 60px; text-align: center; border-right: 1px solid black; "><?= Yii::t('app', 'Qty Pcs') ?></th>
                    <th class="td-kecil2" style="width: 60px; text-align: center; border-right: 1px solid black; "><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                    <th class="td-kecil2" style="width: 80px; text-align: center; border-right: 1px solid transparent; "><?= Yii::t('app', 'Ket') ?></th>
                    <th></th>
                </tr>
				<?php
				$total_palet = count($modDetail);
				$total_pcs = 0;
				$total_m3 = 0;
				$row = 0;
					foreach($modDetail as $i => $detail){
						$total_pcs += $detail->qty_kecil;
						$total_m3 += $detail->qty_m3; ?>
                        <?php $row = $row+1; ?>
                        <tr style="border-bottom: 1px solid black">
                            <td class="td-kecil2" style="border-right: 1px solid black; text-align: center;"><?= $i+1; ?></td>
                            <td class="td-kecil2" style="border-right: 1px solid black; text-align: center;"><?= $detail->produk->produk_group; ?></td>
                            <td class="td-kecil2" style="border-right: 1px solid black; text-align: left; line-height: 1">
                                <b><?= $detail->nomor_produksi; ?></b><br>
                                <?= $detail->produk->produk_nama ?>
                            </td>
                            <td class="td-kecil2" style="border-right: 1px solid black;"><?= $detail->produk->produk_dimensi; ?></td>
                            <td class="td-kecil2" style="border-right: 1px solid black; text-align: center;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil); ?></td>
                            <td class="td-kecil2" style="border-right: 1px solid black; text-align: right;"><?= $detail->qty_m3; ?> </td>
                            <td class="td-kecil2" style="border-right: 1px solid transparent;"><?= $detail->keterangan; ?></td>
                        </tr>
				<?php } ?>
				<?php
				$max = 32;
                $blankspace = $max - $row;
                if($blankspace > 0){
                    for($ii=0;$ii < $blankspace;$ii++){
				?>
                        <tr>
                            <td class="td-kecil2" style="border-right: 1px solid black; border-left: solid 1px transparent;"> &nbsp;</td>
                            <td class="td-kecil2" style="border-right: 1px solid black;">&nbsp;<br>&nbsp;</td>
                            <td class="td-kecil2" style="border-right: 1px solid black;">&nbsp;</td>
                            <td class="td-kecil2" style="border-right: 1px solid black;">&nbsp;</td>
                            <td class="td-kecil2" style="border-right: 1px solid black;">&nbsp;</td>
                            <td class="td-kecil2" style="border-right: 1px solid black;">&nbsp;</td>
                            <td class="td-kecil2" style="padding: 2px 4px;">&nbsp;</td>
                        </tr>
				<?php
                    } 
                }
                ?>
				<tr style="border-top: solid 1px #000; background-color: #F1F4F7;" >
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>TOTAL</b>  &nbsp; </td>
					<td class="text-align-left" style="padding: 5px; border-right: solid 1px #000;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_palet) ?> Palet</b></td>
					<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_pcs) ?> </b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_m3) ?></b></td>
					<td style="padding: 2px 4px;">&nbsp;</td>
				</tr>
                <tr>
                    <td colspan="7" style="border-top: 1px solid black">
                        <table border="0" style="width:100%;">
                            <tr>
                                <td style="vertical-align: middle; font-size: 1.1rem;  text-align: left; padding: 3px; border-right: 1px solid #000;">
                                    Note :
                                </td>
                                 <td style="vertical-align: middle; font-size: 1.1rem;  text-align: center; width: 4cm;  border-right: 1px solid #000; border-bottom: 1px solid #000;">
                                    Disetujui Oleh
                                </td>
                                <td style="vertical-align: middle; font-size: 1.1rem;  text-align: center; width: 4cm; border-bottom: 1px solid #000;">
                                    Disiapkan Oleh
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 1.5cm; padding: 5px;  border-right: 1px solid #000;" rowspan="2"><?= $model->keterangan ?></td>
                                <td style="padding: 2px; border-right: 1px solid #000; text-align: center; line-height: 1; vertical-align: middle">
                                    <?php
                                    $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->approved_by]);
                                    if($modApproval->status == app\models\TApproval::STATUS_APPROVED){
                                        echo '<span class="font-green-jungle"><b style="font-size:1.4rem;">'.$modApproval->status.'</b>';
                                        echo '<br><span style="font-size:0.9rem;">at '.app\components\DeltaFormatter::formatDateTimeForUser2($modApproval->tanggal_approve).'</span></span>';
                                    }else if($modApproval->status == app\models\TApproval::STATUS_REJECTED){
                                        echo '<span class="font-red-flamingo"><b style="font-size:1.4rem;">'.$modApproval->status.'</b>';
                                        echo '<br><span style="font-size:0.9rem;">at '.app\components\DeltaFormatter::formatDateTimeForUser2($modApproval->tanggal_approve).'</span></span>';
                                    }else{
                                        echo '<b style="font-size:1.4rem;">('.$modApproval->status.')</b>';
                                    }
                                    ?>
                                </td>
                                <td style="padding: 2px; text-align: center; line-height: 1; vertical-align: middle">
                                    <?php
                                    $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->prepared_by]);
                                    if($modApproval->status == app\models\TApproval::STATUS_APPROVED){
                                        echo '<span class="font-green-jungle"><b style="font-size:1.4rem;">'.$modApproval->status.'</b>';
                                        echo '<br><span style="font-size:0.9rem;">at '.app\components\DeltaFormatter::formatDateTimeForUser2($modApproval->tanggal_approve).'</span></span>';
                                    }else if($modApproval->status == app\models\TApproval::STATUS_REJECTED){
                                        echo '<span class="font-red-flamingo"><b style="font-size:1.4rem;">'.$modApproval->status.'</b>';
                                        echo '<br><span style="font-size:0.9rem;">at '.app\components\DeltaFormatter::formatDateTimeForUser2($modApproval->tanggal_approve).'</span></span>';
                                    }else{
                                        echo '<b style="font-size:1.4rem;">('.$modApproval->status.')</b>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: bottom; line-height: 1;  text-align: center; width: 175px; border-right: 1px solid #000;">
                                    <?php
                                    echo "<span style='font-size:0.9rem'><b><u> ". $model->approvedBy->pegawai_nama." </u></b></span><br>";
                                    echo "<span style='font-size:0.8rem'>Kadiv Operasional </span>";
                                    ?>
                                </td>
                                <td style="vertical-align: bottom; line-height: 1;  text-align: center; width: 175px;">
                                    <?php
                                    echo "<span style='font-size:0.9rem'><b><u> ". $model->preparedBy->pegawai_nama." </u></b></span><br>";
                                    echo "<span style='font-size:0.8rem'>Kadept PPIC </span>";
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-PPC-29</span>
		</td>
	</tr>
</table>