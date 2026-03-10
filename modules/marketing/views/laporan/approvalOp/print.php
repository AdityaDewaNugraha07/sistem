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
$max = 1;
$sql = $model->searchLaporan()->createCommand()->rawSql;
$modDetail = Yii::$app->db->createCommand($sql)->queryAll();
if(count($modDetail) > $max){
	$max = count($modDetail);
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
<table style="width: 20cm; margin: 10px; height: 10cm; border: solid 1px;">
	<tr>
		<td colspan="3" style="padding: 5px; border-bottom: solid 1px transparent;">
			<table style="width: 100%; " border="0">
				<tr style="">
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
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 1000px" id="table-detail">
				<tr style="border-bottom: solid 1px #000; border-top: solid 1px #000;">
                    <th style="text-align: left;  padding: 10px; border-right: solid 1px #000;">No</th>
					<th style="width: 50px;text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Kode') ?></th>
                    <th style="width: 100px;text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Jenis Produk') ?></th>
					<th style="width: 100px; text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Tanggal') ?></th>
					<th style="width: 100px; text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Nama Customer'); ?></th>
					<th style="width: 150px; text-align: center;  padding: 10px; border-right: solid 1px #000; line-height: 1;"><?= Yii::t('app', 'Alamat Bongkar'); ?></th>
					<th style="width: 100px; text-align: center;  padding: 10px; border-right: solid 1px #000; line-height: 1;"><?= Yii::t('app', 'Ketidaksesuaian'); ?></th>
                    <th style="width: 100px; text-align: center;  padding: 10px; border-right: solid 1px #000; line-height: 1;">Approval 1</th>
                    <th style="width: 100px; text-align: center;  padding: 10px; border-right: solid 1px #000; line-height: 1;">Approval 2</th>
                    <th style="width: 100px; text-align: center;  padding: 10px; border-right: solid 1px #000; line-height: 1;">Approval 3</th>
				</tr>
                                
				<?php for($i=0;$i<$max;$i++){?>
                <tr>
                    <td style="text-align: left; padding: 10px; border-right: solid 1px #000;"><?= $i+1; ?></td>
                    <td style="text-align: left; padding: 10px; border-right: solid 1px #000;"><?= $modDetail[$i]['kode']; ?></td>
                    <td style="text-align: left; padding: 10px; border-right: solid 1px #000;"><?= $modDetail[$i]['jenis_produk']; ?></td>
                    <td style="text-align: left; padding: 10px; border-right: solid 1px #000;"><?= \app\components\DeltaFormatter::formatDateTimeForUser($modDetail[$i]['tanggal']); ?></td>
                    <td style="text-align: left;padding: 10px; border-right: solid 1px #000;"><?= $modDetail[$i]['cust_an_nama']; ?></td>
                    <td style="text-align: left;padding: 10px; border-right: solid 1px #000;"><?= $modDetail[$i]['alamat_bongkar']; ?></td>
                    <td style="text-align: left;padding: 10px; border-right: solid 1px #000;"><?= $modDetail[$i]['status']; ?></td>
                    <td style="text-align: center;padding: 10px; border-right: solid 1px #000;">
                    <?php
                    if ($modDetail[$i]['status'] != "") {
                        $sql_approval = "select * from t_approval where reff_no = '".$modDetail[$i]['kode']."' and level = 1";
                        $approval = Yii::$app->db->createCommand($sql_approval)->queryOne();
                        $pegawai_id = $approval['assigned_to'];
                        $tanggal_approve = \app\components\DeltaFormatter::formatDateTimeForUser($approval['updated_at']);
                        $approval_status = $approval['status'];

                        if ($approval_status == "APPROVED") {
                            $btn = "btn-success";
                            $tanggal_approve = $tanggal_approve;
                        } else if ($approval_status == "REJECTED") {
                            $btn = "btn-danger";
                            $tanggal_approve = $tanggal_approve;
                        } else if ($approval_status == "Not Confirmed") {
                            $btn = "btn-default";
                            $tanggal_approve = '';
                        }

                        $sql_assigned_to = "select pegawai_nama from m_pegawai where pegawai_id = $pegawai_id ";
                        $pegawai_nama = Yii::$app->db->createCommand($sql_assigned_to)->queryScalar();

                        echo "<button class='btn ".$btn."'><font style='font-size: 10px; color: #000;'>M IWAN S</font></button>";
                        echo "<br><span style='font-size: 10px;'>".$tanggal_approve."</span>";
                    }
                    ?>
                    </td>
                    <td style="text-align: center;padding: 10px; border-right: solid 1px #000;">
                    <?php
                    if ($modDetail[$i]['status'] != "") {
                        $sql_approval = "select * from t_approval where reff_no = '".$modDetail[$i]['kode']."' and level = 2";
                        $approval = Yii::$app->db->createCommand($sql_approval)->queryOne();
                        $pegawai_id = $approval['assigned_to'];
                        $tanggal_approve = \app\components\DeltaFormatter::formatDateTimeForUser($approval['updated_at']);
                        $approval_status = $approval['status'];

                        if ($approval_status == "APPROVED") {
                            $btn = "btn-success";
                            $tanggal_approve = $tanggal_approve;
                        } else if ($approval_status == "REJECTED") {
                            $btn = "btn-danger";
                            $tanggal_approve = $tanggal_approve;
                        } else if ($approval_status == "Not Confirmed") {
                            $btn = "btn-default";
                            $tanggal_approve = '';
                        }

                        $sql_assigned_to = "select pegawai_nama from m_pegawai where pegawai_id = $pegawai_id ";
                        $pegawai_nama = Yii::$app->db->createCommand($sql_assigned_to)->queryScalar();

                        echo "<button class='btn ".$btn."'><font style='font-size: 10px; color: #000;'>HERYANTO S</font></button>";
                        echo "<br><span style='font-size: 10px;'>".$tanggal_approve."</span>";
                    }
                    ?>
                    </td>
                    <td style="text-align: center;padding: 10px; border-right: solid 1px #000;">
                    <?php
                    if ($modDetail[$i]['status'] != "") {
                        $sql_approval = "select * from t_approval where reff_no = '".$modDetail[$i]['kode']."' and level = 3";
                        $approval = Yii::$app->db->createCommand($sql_approval)->queryOne();
                        $pegawai_id = $approval['assigned_to'];
                        $tanggal_approve = \app\components\DeltaFormatter::formatDateTimeForUser($approval['updated_at']);
                        $approval_status = $approval['status'];

                        if ($approval_status == "APPROVED") {
                            $btn = "btn-success";
                            $tanggal_approve = $tanggal_approve;
                        } else if ($approval_status == "REJECTED") {
                            $btn = "btn-danger";
                            $tanggal_approve = $tanggal_approve;
                        } else if ($approval_status == "Not Confirmed") {
                            $btn = "btn-default";
                            $tanggal_approve = '';
                        }

                        $sql_assigned_to = "select pegawai_nama from m_pegawai where pegawai_id = $pegawai_id ";
                        $pegawai_nama = Yii::$app->db->createCommand($sql_assigned_to)->queryScalar();

                        echo "<button class='btn ".$btn."'><font style='font-size: 10px; color: #000;'>".$pegawai_nama."</font></button>";
                        echo "<br><span style='font-size: 10px;'>".$tanggal_approve."</span>";
                    }
                    ?>
                    </td>
                </tr>
				<?php }?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
        <!-- <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-12-0</span>-->
		</td>
	</tr>
	
</table>