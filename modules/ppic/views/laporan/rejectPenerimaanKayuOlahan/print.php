<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>

<style>
table{
	font-size: 1.1rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>

<table style="width: 20cm; margin: 10px;">
	<tr>
		<td colspan="3" style="padding: 5px; border-bottom: solid 1px transparent;">
			<table style="width: 100%;">
				<tr>
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= $paramprint['judul2'] ?>
					</td>
					<td style="width: 3cm; height: 1cm; vertical-align: top; padding: 10px;">
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table style="width: 20cm; margin: 0.5cm;">
    <tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%;" id="table-detail" class="table">
                <thead>
                    <tr>
                        <th style="line-height: 1;" class="text-center"><?= Yii::t('app', 'No. Reff') ?></th>
                        <th style="line-height: 1;" class="text-center"><?= Yii::t('app', 'Tanggal<br>Berkas') ?></th>
                        <th style="line-height: 1;" class="text-center"><?= Yii::t('app', 'Tanggal<br>Approve') ?></th>
                        <th style="line-height: 1;" class="text-center"><?= Yii::t('app', 'Produk Nama/<br>Produk Kode') ?></th>
                        <th style="line-height: 1;" class="text-center"><?= Yii::t('app', 'Status Kirim Gudang') ?></th>
                        <th style="line-height: 1;" class="text-center"><?= Yii::t('app', 'Status Approval') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dehek = Yii::$app->db->createCommand($model)->queryAll();
                    foreach ($dehek as $u) {
                    ?>
                    <tr>
                        <td class="td-kecil" style='font-size: 1rem;'><?php echo str_replace("RPKO","",$u['reff_no']);?></td>
                        <td class="td-kecil" style='font-size: 1rem;'><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($u['tanggal_berkas']);?></td>
                        <td class="td-kecil" style='font-size: 1rem;'><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($u['tanggal_approve']);?></td>
                        <td class="td-kecil" style='font-size: 1rem;'><?php echo $u['produk_nama']."<br>".$u['produk_kode'];?></td>
                        <td class="td-kecil text-center">
                            <?php
                            if(!empty($u['reject_reason'])){
                                $r = \yii\helpers\Json::decode($u['reject_reason']);
                                foreach($r as $a => $imu){
                                    $pelaku = \app\models\MPegawai::findOne(['pegawai_id'=>$imu['by']]);
                                    echo "<span style='font-size: 1rem;'>REJECTED</span>";
                                    echo "<br><span style='font-size: 1rem;'>by : ".$pelaku->pegawai_nama."</span>";
                                    echo "<br><span style='font-size: 1rem;'>at : ".\app\components\DeltaFormatter::formatDateTimeForUser2($imu['at'])."</span>";
                                    echo "<br><span style='font-size: 1rem;'>reason : ".$imu['reason']."</span>";
                                }
                            }
                            ?>
                        </td>
                        <td class="td-kecil text-center">
                            <?php
                            if(!empty($u['approve_reason'])){
                                $r = \yii\helpers\Json::decode($u['approve_reason']);
                                foreach($r as $a => $imu){
                                    $pelaku = \app\models\MPegawai::findOne(['pegawai_id'=>$imu['by']]);
                                    echo "<span style='font-size: 1rem;'>APPROVED</span>";
                                    echo "<br><span style='font-size: 1rem;'>by : ".$pelaku->pegawai_nama."</span>";
                                    echo "<br><span style='font-size: 1rem;'>at : ".\app\components\DeltaFormatter::formatDateTimeForUser2($imu['at'])."</span>";
                                    echo "<br><span style='font-size: 1rem;'>reason : ".$imu['reason']."</span>";
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                <tbody>
                <tfooter>
                <tfooter>
            </table>
        </td>
    </tr>
</table>

