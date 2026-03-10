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
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
				<div class="row">
                    <div class="col-md-12">
						<?php echo $header; ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover table-detail-mepet" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'No.'); ?></th>
                                            <th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Barang Jadi') ?></th>
                                            <th><?= Yii::t('app', 'Produk') ?></th>
                                            <th style="line-height: 1"><?= Yii::t('app', 'Tanggal Keluar') ?></th>
                                            <th><?= Yii::t('app', 'Reff No.') ?></th>
                                            <th><?= Yii::t('app', 'Lok.<br>Gdg') ?></th>
                                            <th><?= Yii::t('app', 'Pcs') ?></th>
                                            <th><?= Yii::t('app', 'T') ?></th>
                                            <th><?= Yii::t('app', 'L') ?></th>
                                            <th><?= Yii::t('app', 'P') ?></th>
                                            <th><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                            <th><?= Yii::t('app', 'Keterangan') ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = $model->searchLaporanProdukKeluar()->createCommand()->rawSql;
                                    $contents = Yii::$app->db->createCommand($sql)->queryAll();
                                    if(count($contents)>0){ 
                                        foreach($contents as $i => $data){ ?>
                                            <?php
                                            if($data['produk_p']==0){
                                                $sqla = "select e.produk_nama, 
                                                            d.qty as qty_kecil, d.t, d.t_satuan, d.l, d.l_satuan, d.p, d.p_satuan, d.p, d.kapasitas_kubikasi as kubikasi, 
                                                            '' as cust_an_nama
                                                        from t_terima_ko c
                                                        join t_terima_ko_kd d on d.tbko_id = c.tbko_id
                                                        join m_brg_produk e on e.produk_id = c.produk_id                    
                                                        where c.nomor_produksi = '".$data['nomor_produksi']."'
                                                        group by e.produk_nama, 
                                                            d.qty, d.t, d.t_satuan, d.l, d.l_satuan, d.p, d.p_satuan, d.p, d.kapasitas_kubikasi, 
                                                            cust_an_nama";
                                                $modele = \Yii::$app->db->createCommand($sqla)->queryAll();
                                                if(count($modele)>0){ 
                                                    foreach($modele as $ii => $data_random){ ?>
                                        
                                        <tr>
                                            <td ><?= $i+1;  ?></td>
                                            <td ><?= $data['nomor_produksi']; ?></td>
                                            <td style="text-align: left;"><?= $data_random['produk_nama'] ?></td>
                                            <td ><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tgl_transaksi']) ?></td>
                                            <td ><?= $data['reff_no'] ?></td>
                                            <td ><?= $data['gudang_nm'] ?></td>                                            
                                            <td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data_random['qty_kecil']) ?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data_random['t'])." ".$data_random['t_satuan'];?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data_random['l'])." ".$data_random['l_satuan'];?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data_random['p'])." ".$data_random['p_satuan'];?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data_random['kubikasi'],4);//(strlen(substr(strrchr($data_random['kubikasi'], "."), 1)) > 4)? $data_random['kubikasi']*10000/10000: $data_random['kubikasi'];  ?></td>
                                            <td style="text-align: left;">
                                                    <?php
                                                    $ret = $data['keterangan'];
                                                    if($data['keterangan']=="PENJUALAN"){
                                                            $ret = $data['keterangan']." <b>".$data['penerima']."</b>";
                                                    }
                                                    echo $ret;
                                                    ?>
                                            </td>
                                        </tr>
                                        
                                                    <?php
                                                    }
                                                } 
                                            }else{
                                        
                                        ?>
                                        <tr>
                                            <td ><?= $i+1; ?></td>
                                            <td ><?= $data['nomor_produksi']; ?></td>
                                            <td style="text-align: left;"><?= $data['produk_nama'] ?></td>
                                            <td ><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tgl_transaksi']) ?></td>
                                            <td ><?= $data['reff_no'] ?></td>
                                            <td ><?= $data['gudang_nm'] ?></td>
                                            <td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['pcs']) ?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['produk_t'])." ".$data['produk_t_satuan'];?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['produk_l'])." ".$data['produk_l_satuan'];?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['produk_p'])." ".$data['produk_p_satuan'];?></td>
                                            <td style="text-align: right; width: 60px;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['m3'],4); //(strlen(substr(strrchr($data['m3'], "."), 1)) > 4)? app\components\DeltaFormatter::formatNumberForUser($data['m3'],4): app\components\DeltaFormatter::formatNumberForUser($data['m3'],4);  ?></td>
                                            <td style="text-align: left;">
                                                    <?php
                                                    $ret = $data['keterangan'];
                                                    if($data['keterangan']=="PENJUALAN"){
                                                            $ret = $data['keterangan']." <b>".$data['penerima']."</b>";
                                                    }
                                                    echo $ret;
                                                    ?>
                                            </td>
                                        </tr>
                                        <?php } 
                                        
                                        }
                                    }else{
                                            "<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>