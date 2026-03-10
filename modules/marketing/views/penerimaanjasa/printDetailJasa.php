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
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].'-'.$model->kode.'-'.\app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal).'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<style>
table{
	font-size: 1.2rem;
}
table#table-judul tr td{
	vertical-align: top;
    padding: 3px;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
    padding: 3px;
}
</style>

<table id="table-judul" style="width: 20cm; margin: 10px;">
    <tr>
        <td style="width: 50%;">
            <table>
                <tr>
                    <td colspan="2">Kode</td>
                    <td>: <?php echo $model->kode;?></td>
                </tr>
                <tr>
                    <td colspan="2">Tanggal Op</td>
                    <td>: <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);?></td>
                </tr>
                <tr>
                    <td colspan="2">Jenis Produk</td>
                    <td>: <?php echo $model->jenis_produk;?></td>
                </tr>
                <tr>
                    <td colspan="2">No. Berkas PP</td>
                    <td>: <?php echo $model->pp_no;?></td>
                </tr>
                <?php /*<tr>
                    <td>Sales</td>
                    <td>: <?php echo Yii::$app->db->createCommand("select sales_nm from m_sales where sales_id = ".$model->sales_id."")->queryScalar();?></td>
                </tr>
                <tr>
                    <td>Disetujui</td>
                    <td>: <?php echo Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$model->disetujui."")->queryScalar();?></td>
                </tr>*/?>             
            </table>
        </td>
        <td style="width: 50%;">
            <?php /*<table>
                <tr>
                    <td>Customer</td>
                    <td>: <?php echo Yii::$app->db->createCommand("select cust_an_nama from m_customer where cust_id = ".$model->cust_id."")->queryScalar();?></td>
                </tr>
                <tr>
                    <td>Tanggal Kirim</td>
                    <td>: <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);?></td>
                </tr>
                <tr>
                    <td>Alamat Bongkar</td>
                    <td>: <?php echo $model->alamat_bongkar;?></td>
                </tr>
                <tr>
                    <td>Provinsi Bongkar</td>
                    <td>: <?php echo $model->provinsi_bongkar;?></td>
                </tr>
            </table>*/?>
        </td>
    </tr>
</table>

<table id="table-detail" style="width: 20cm; margin: 10px;" border="1">
    <tr>
        <th rowspan="2" class="text-center" style="width: 90px;">No</th>
        <th rowspan="2" class="text-center" style="width: 90px;">Tanggal</th>
        <th rowspan="2" class="text-center" style="width: 80px;">NoPol</th>
        <th rowspan="2" class="text-center" style="width: 190px;">Produk</th>
        <th rowspan="2" class="text-center" style="width: 60px;">No.<br>Palet</th>
        <th colspan="6" class="text-center">Dimensi</th>
        <th colspan="2" class="text-center">Dokumen</th>
        <th colspan="2" class="text-center">Aktual</th>
        <th rowspan="2" class="text-center" style="width: 70px;">Ket</th>
    <tr>
        <td colspan="2" style="width: 50px; text-align: center;">T</td>
        <td colspan="2" style="width: 50px; text-align: center;">L</td>
        <td colspan="2" style="width: 50px; text-align: center;">P</td>
        <td style="width: 50px; text-align: center;" >Qty</td>
        <td style="width: 50px; text-align: center;">Vol</td>
        <td style="width: 50px; text-align: center;" >Qty</td>
        <td style="width: 50px; text-align: center;">Vol</td>
    </tr>
    <?php
    $i = 0;
    foreach ($modDetail as $kolom) {
        $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser($kolom['tanggal']);
        $nopol = $kolom['nopol'];
        $produk_jasa_id = $kolom['produk_jasa_id'];
        $m_produk_jasa = \app\models\MProdukJasa::findOne(['produk_jasa_id'=>$produk_jasa_id]);
            $kode = $m_produk_jasa->kode;
            $nama = $m_produk_jasa->nama;
        $nomor_palet = $kolom['nomor_palet'];
        $t = $kolom['t'];
        $t_satuan = $kolom['t_satuan'];
        $l = $kolom['l'];
        $l_satuan = $kolom['l_satuan'];
        $p = $kolom['p'];
        $p_satuan = $kolom['p_satuan'];
        $qty_kecil = $kolom['qty_kecil'];
        $kubikasi = $kolom['kubikasi'];
        $qty_kecil_actual = $kolom['qty_kecil_actual'];
        $kubikasi_actual = $kolom['kubikasi_actual'];
        $keterangan = $kolom['keterangan'];
        $i++;
    ?>
    <tr>
        <td style="text-align: center;"><?php echo $i;?></td>
        <td style="text-align: center;"><?php echo $tanggal;?></td>
        <td style="text-align: center;"><?php echo $nopol;?></td>
        <td style="text-align: center;"><?php echo $kode." - ".$nama;?></td>
        <td style="text-align: center;"><?php echo $nomor_palet;?></td>
        <td style="text-align: right;"><?php echo $t;?></td>
        <td style="text-align: center;"><?php echo $t_satuan;?></td>
        <td style="text-align: right;"><?php echo $l;?></td>
        <td style="text-align: center;"><?php echo $l_satuan;?></td>
        <td style="text-align: right;"><?php echo $p;?></td>
        <td style="text-align: center;"><?php echo $p_satuan;?></td>
        <td style="text-align: right;"><?php echo $qty_kecil;?></td>
        <td style="text-align: right;"><?php echo $kubikasi;?></td>
        <td style="text-align: right;"><?php echo $qty_kecil_actual;?></td>
        <td style="text-align: right;"><?php echo $kubikasi_actual;?></td>
        <td style="text-align: center;"><?php echo $keterangan;?></td>
    </tr>
    <?php
    }
    ?>
</table>
