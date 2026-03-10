<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$kode = $model->kode;
if($caraprint == "EXCEL"){
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
<table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px; border-bottom: solid 1px transparent;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
					</td>
					<td style="width: 3cm; height: 1cm; vertical-align: top; padding: 10px;">
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><h5>DETAIL ORDER</h5></td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
                        <th rowspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Produk'); ?></th>
                        <th colspan="3" style="line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty'); ?></th>
                    </tr>
                    <tr>
                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 50px"><?= Yii::t('app', 'Palet'); ?></th>
                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 130px"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 70px"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>

                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', '-'); ?></th>
                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'Satuan<br>Angkut'); ?></th>
                                        
                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', 'Batang'); ?></th>
                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', '-'); ?></th>
                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php //isi Detail Order ?>
                </tbody>
                <?php
                    $sql = "select m_produk_jasa.nama, m_produk_jasa.kode ".
                            "   , t_op_ko_detail.qty_besar, t_op_ko_detail.qty_kecil, t_op_ko_detail.kubikasi, t_op_ko_detail.harga_jual ".
                            "   from t_op_ko_detail ".
                            "   left join m_produk_jasa on m_produk_jasa.produk_jasa_id = t_op_ko_detail.produk_id ".
                            "   where op_ko_id = ".$op_ko_id." ".
                            " ";
                    $query = Yii::$app->db->createCommand($sql)->queryAll();
                    $total_harga = 0;
                    foreach ($query as $kolom) {
                ?>
                    <tr>
                        <td>1</td>
                        <td><?php echo $kolom['kode']." - ".$kolom['nama'];?></td>
                        <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kolom['qty_besar']);?></td>
                        <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kolom['qty_kecil']);?></td>
                        <td class="text-right"><?php echo $kolom['kubikasi'];?></td>
                    </tr>
                    <?php
                        $total_harga += $kolom['harga_jual'] * $kolom['kubikasi'];
                        }
                    ?>
                <tfoot>
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
		</td>
	</tr>
	<tr>
		<td><h5>RINCIAN</h5></td>
	</tr>
	<tr>
		<td>
			<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-terima-jasa">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">No.</th>
                        <th rowspan="2" style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Tanggal<br>Terima / Hasil'); ?></th>
                        <th rowspan="2" style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Nopol'); ?></th>
                        <th rowspan="2" style="width: 70px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'No. Palet'); ?></th>
                        <th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Produk'); ?></th>
                        <th colspan="3" style="width: 180px; line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Dimensi'); ?></th>
                        <th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Dokumen'); ?></th>
						<th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Aktual'); ?></th>
                        <th rowspan="2" style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Ket'); ?></th>
                        <th colspan="3" style="width: 180px; line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Diterima'); ?></th>
                   </tr>
                    <tr>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "T" ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "L" ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "P" ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Qty'); ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Vol'); ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Qty'); ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Vol'); ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "Kode" ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "Tanggal" ?></th>
                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "Vol" ?></th>
                    </tr>
                </thead>
                <tbody>
                	<?php 
                	$sql_y = "select t_terima_jasa.tanggal, t_terima_jasa.nopol, t_terima_jasa.nomor_palet ".
                                "   , t_terima_jasa.t, t_terima_jasa.l, t_terima_jasa.p".
                                "   , t_terima_jasa.t_satuan, t_terima_jasa.l_satuan, t_terima_jasa.p_satuan".
                                "   , t_terima_jasa.qty_kecil, t_terima_jasa.kubikasi, t_terima_jasa.keterangan   ".
                                "   , t_terima_jasa.qty_kecil_actual, t_terima_jasa.kubikasi_actual, m_produk_jasa.nama".
                                "   from t_terima_jasa INNER JOIN m_produk_jasa ON t_terima_jasa.produk_jasa_id = m_produk_jasa.produk_jasa_id". 
                                "   where t_terima_jasa.op_ko_id = ".$op_ko_id."". 
                                "   ";
                    $query_y = Yii::$app->db->createCommand($sql_y)->queryAll();
                    $i                      = 1;
                    $total_qty_kecil        = 0;
                    $total_kubikasi         = 0;
                    $total_qty_kecil_actual = 0;
                    $total_kubikasi_actual  = 0;
                    foreach ($query_y as $kolom_y) {
                        $query_spm = "  SELECT * FROM t_spm_ko_detail
                                        JOIN t_spm_ko on t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id
                                        WHERE op_ko_id = $op_ko_id AND status = 'REALISASI'
                                        AND '{$kolom_y['nomor_palet']}' = ANY(string_to_array(REPLACE(keterangan, '''', ''), ','))";
                        $mod_spm = Yii::$app->db->createCommand($query_spm)->queryOne();
                        $kode_spm = '-'; $tgl_spm = '-';
                        if($mod_spm){
                            $spm = \app\models\TSpmKo::findOne($mod_spm['spm_ko_id']);
                            $kode_spm = $spm->kode;
                            $tgl_spm = \app\components\DeltaFormatter::formatDateTimeForUser2($spm->tanggal);
                        }
                    ?>
                        <tr>
                            <td class='text-center'><?php echo $i;?></td>
                            <td class='text-center'><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($kolom_y['tanggal']);?></td>
                            <td class='text-center'><?php echo $kolom_y['nopol'];?></td>
                            <td class='text-center'><?php echo $kolom_y['nomor_palet'];?></td>
                            <td class='text-center'><?php echo $kolom_y['nama'];?></td>
                            <td class='text-right'><?php echo $kolom_y['t'].' '.$kolom_y['t_satuan'];?></td>
                            <td class='text-right'><?php echo $kolom_y['l'].' '.$kolom_y['l_satuan'];?></td>
                            <td class='text-right'><?php echo $kolom_y['p'].' '.$kolom_y['p_satuan'];?></td>
                            <td class='text-right'><?php echo $kolom_y['qty_kecil'];?></td>
                            <td class='text-right'><?php echo $kolom_y['kubikasi'];?></td>
                            <td class='text-right'><?php echo $kolom_y['qty_kecil_actual'];?></td>
                            <td class='text-right'><?php echo $kolom_y['kubikasi_actual'];?></td>
                            <td class='text-right'><?php echo $kolom_y['keterangan'];?></td>
                            <td class='text-center'><?= $kode_spm; ?></td>
                            <td class='text-center'><?= $tgl_spm; ?></td>
                            <td class='text-center'><?= $kode_spm == '-' ? '-' : $kolom_y['kubikasi']; ?></td>
                        </tr>
                    <?php
                        $i++;
                        $total_qty_kecil        += $kolom_y['qty_kecil'];
                        $total_kubikasi         += $kolom_y['kubikasi'];
                        $total_qty_kecil_actual += $kolom_y['qty_kecil_actual'];
                        $total_kubikasi_actual  += $kolom_y['kubikasi_actual'];
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7"></td>
                        <td style="vertical-align: middle; text-align: right;">
                            Total &nbsp; 
                    	</td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_qty_kecil) ?>
                        </td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubikasi) ?>
                        </td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_qty_kecil_actual) ?>
                        </td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubikasi_actual) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-12-0</span>
		</td>
	</tr>
</table>