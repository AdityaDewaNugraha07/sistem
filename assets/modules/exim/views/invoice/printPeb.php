<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
//$kode = explode("-", $model->kode)[0];
$kode = $model->kode;
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
//	$satuanbesar = "CRATES";
//	$satuanbesar2 = "CRTE";
	$satuanbesar = "BUNDLES";
	$satuanbesar2 = "BNDL";
}else{
	$satuanbesar = "BUNDLES";
	$satuanbesar2 = "BNDL";
}
$peb = \yii\helpers\Json::decode($model->data_peb);
?>
<style>
table td{
	font-size: 0.9rem;
    padding: 0px;
    vertical-align: top; 
}
table.table-level2 tr td{
	font-size: 0.9rem;
    padding: 2px;
}
</style>
<?php $modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE); ?>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="2" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="width: 3cm; text-align: center; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="width: 8cm; text-align: left;  padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.3rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
						<span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table style="width: 100%;" border="0">
				<tr style="">
                    <td style="text-align: center; width: 2cm; border-right: solid 1px #000; padding:3px;"><b><?= $peb['peb_kode_beacukai'] ?></b></td>
					<td style="text-align: center; padding: 0px; padding-right: 2cm;">
						<span style="font-size: 1.1rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width: 50%; border-right: solid 1px transparent;">
            <table style="width: 100%; " class="table-level2">
				<tr style="">
					<td style="width: 3.8cm;">Nomor Pengajuan</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_no_pengajuan'] ?></td>
				</tr>
				<tr style="">
					<td style="font-weight: 600;">A. KANTOR PABEAN</td>
					<td style=""></td>
                    <td style=""></td>
				</tr>
				<tr style="">
					<td style="padding-left: 15px;">1. Kantor Pabean Pemuatan</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_kantorpabean_pemuatan'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 15px;">2. Kantor Pabean Ekspor</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_kantorpabean_ekspor'] ?></td>
				</tr>
				<tr style="">
					<td style="font-weight: 600;">B. JENIS EKSPOR</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_jenis_ekspor'] ?></td>
				</tr>
				<tr style="">
					<td style="font-weight: 600;">C. KATEGORI EKSPOR</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_kategori_ekspor'] ?></td>
				</tr>
				<tr style="">
					<td style="font-weight: 600;">D. CARA PERDAGANGAN</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_cara_perdagangan'] ?></td>
				</tr>
				<tr style="">
					<td style="font-weight: 600;">E. CARA PEMBAYARAN</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_cara_pembayaran'] ?><?= ($peb['peb_cara_pembayaran']=="LC")?" ( No. ".$peb['peb_carapembayaran_lcno'].", Tgl. ".$peb['peb_carapembayaran_lctgl'].")":"" ?></td>
				</tr>
			</table>
		</td>
        <td style="width: 50%; vertical-align: bottom;">
            <table style="width: 100%; border-left: solid 1px #000; border-top: solid 1px #000;" class="table-level2">
				<tr style="">
                    <td colspan="3" style="font-weight: 600;">H. KOLOM KHUSUS BEA DAN CUKAI</td>
				</tr>
				<tr style="">
					<td style="width: 3.8cm; padding-left: 5px;">1. Nomor Pendaftaran</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_beacukai_no_daftar'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 15px;">Tanggal</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_beacukai_tgl_daftar'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">2. Nomor BC 1.1</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_beacukai_no_bc'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 15px;">Tanggal</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_beacukai_tgl_bc'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 15px;">Pos / Sub Pos</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_beacukai_pos'] ?></td>
				</tr>
			</table>
        </td>
	</tr>
    <tr>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> EKSPORTIR </td>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> PENERIMA </td>
    </tr>
    <tr>
		<td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
				<tr style="">
					<td style="width: 3.8cm; padding-left: 5px;">1. Identitas</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_eksportir_identitas'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">2. Nama</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_eksportir_nama'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px; ">3. Alamat</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_eksportir_alamat'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">4. NIPER</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_eksportir_niper'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">5. Status</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_eksportir_status'] ?></td>
				</tr>
            </table>
        </td>
		<td style="width: 50%;"> 
            <table style="width: 100%; " class="table-level2">
				<tr style="">
					<td style="width: 3.8cm; padding-left: 5px;">9. Nama</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_penerima_nama'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">10. Alamat</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_penerima_alamat'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">11. Negara</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_penerima_negara'] ?></td>
				</tr>
            </table>
        </td>
    </tr>
    <tr>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> PPJK </td>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> PEMBELI </td>
    </tr>
    <tr>
		<td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
				<tr style="">
					<td style="width: 3.8cm; padding-left: 5px;">6. NPWP</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_ppjk_npwp'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">7. Nama</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_ppjk_nama'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px; ">8. Alamat</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_ppjk_alamat'] ?></td>
				</tr>
            </table>
        </td>
		<td style="width: 50%;">
            <table style="width: 100%; " class="table-level2">
				<tr style="">
					<td style="width: 3.8cm; padding-left: 5px;">12. Nama</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_pembeli_nama'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">13. Alamat</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pembeli_alamat'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">14. Negara</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pembeli_negara'] ?></td>
				</tr>
            </table>
        </td>
    </tr>
    <tr>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> DATA PENGANGKUTAN </td>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> DATA PELABUHAN / TEMPAT MUAT EKSPOR </td>
    </tr>
    <tr>
		<td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
				<tr style="">
					<td style="width: 3.8cm; padding-left: 5px;">15. Cara Pengangkutan</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_pengangkutan_cara_pengangkutan'] ?></td>
				</tr>
				<tr style="">
                    <td style="padding-left: 5px;">16. Nama & Bendara<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sarana Pengangkut</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pengangkutan_nama_bendera'] ?></td>
				</tr>
				<tr style="">
                    <td style="padding-left: 5px; ">17. No. Pengangkut <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Voy/Flight/Nopol)</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pengangkutan_no'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px; ">18. Tanggal Perkiraan Ekspor</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pengangkutan_tanggal_perkiraan'] ?></td>
				</tr>
            </table>
        </td>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
				<tr style="">
					<td style="width: 3.8cm; padding-left: 5px;">19. Pel. Muat Asal</td>
					<td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_pelabuhanmuat_muat_asal'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px;">20. Pel./Tempat Muat Ekspor</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pelabuhanmuat_muat_ekspor'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px; ">21. Pel. Bongkar</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pelabuhanmuat_bongkar'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px; ">22. Pel. Tujuan</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pelabuhanmuat_tujuan'] ?></td>
				</tr>
				<tr style="">
					<td style="padding-left: 5px; ">23. Negara Tujuan Ekspor</td>
					<td style="">:</td>
                    <td style=""><?= $peb['peb_pelabuhanmuat_tujuan_ekspor'] ?></td>
				</tr>
            </table>
        </td>
    </tr>
    <tr>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> DOKUMEN PELENGKAP PABEAN </td>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> DATA TEMPAT PEMERIKSAAN </td>
    </tr>
    <tr>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">24. Nomor & Tgl Invoice</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_pelengkappabean_no_inv'] ?> <span class="pull-right"><?= $peb['peb_pelengkappabean_tgl_inv'] ?></span></td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px;">25. Nomor & Tgl Packing</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_pelengkappabean_no_packing'] ?> <span class="pull-right"><?= $peb['peb_pelengkappabean_tgl_packing'] ?></span></td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px; ">26. Jenis, No. & Tgl<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dokumen Lainnya</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_pelengkappabean_jenis_dok'] ?> <?= $peb['peb_pelengkappabean_no'] ?> <span class="pull-right"><?= $peb['peb_pelengkappabean_tgl'] ?></span></td>
                </tr>
                <tr style="">
                    <td style="padding-left: 20px; ">Kantor Bea Cukai<br>Pendaftara CK-5</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_pelengkappabean_kantor_beacukai'] ?></td>
                </tr>
            </table>
        </td>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">27. Lokasi Pemeriksaan</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_tempatperiksa_lokasi'] ?> </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px;">28. Kantor Pabean Pemeriksaan</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_tempatperiksa_kantor'] ?> </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px; ">29. Gudang PLB</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_tempatperiksa_gudang'] ?> </td>
                </tr>
                <tr style="border-bottom: solid 1px #000; border-top: solid 1px #000;">
                    <td colspan="3" style="padding: 0px; padding-left: 12px; font-weight: 600;"> DATA PENYERAHAN </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px; ">30. Cara Penyerahan Barang</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_penyerahan_cara'] ?> </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="width: 100%; padding-left: 12px; font-weight: 600;"> DATA TRANSAKSI EKSPOR </td>
    </tr>
    <tr>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">31. Bank Devisa Hasil Ekspor</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_transaksiekspor_bank_devisa'] ?> </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px;">31. Jenis Valuta Asing</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_transaksiekspor_jenis_valuta'] ?> </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px; ">32. FOB</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_transaksiekspor_fob'] ?> </td>
                </tr>
            </table>
        </td>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">34. Freight</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style="" class="pull-right"><?= $peb['peb_transaksiekspor_freight'] ?> </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px;">35. Asuransi (LN/DN)</td>
                    <td style="">:</td>
                    <td style="" class="pull-right"><?= $peb['peb_transaksiekspor_asuransi'] ?> </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px; ">36. Nilai Maklon (Jika Ada)</td>
                    <td style="">:</td>
                    <td style="" class="pull-right"><?= $peb['peb_transaksiekspor_maklon'] ?> </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> DATA PETI KEMAS </td>
		<td style="width: 50%; padding-left: 12px; font-weight: 600;"> DATA KEMASAN </td>
    </tr>
    <tr>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">37. Jumlah Peti Kemas</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_petikemas_jml'] ?> </td>
                </tr>
                <tr style="">
                    <td style="padding-left: 5px;">38. Nomor, Ukuran dan</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_petikemas_no'] ?> <?= $peb['peb_petikemas_ukuran'] ?></td>
                </tr>
                <tr style="">
                    <td style="padding-left: 20px;">Status Peti Kemas</td>
                    <td style="">:</td>
                    <td style=""><?= $peb['peb_petikemas_status'] ?> </td>
                </tr>
            </table>
        </td>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">39. Jenis, Jumlah dan<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Merek Kemasan</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_kemasan_jenis_jml'] ?> </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="width: 100%; padding-left: 12px; font-weight: 600;"> DATA BARANG EKSPOR </td>
    </tr>
    <tr>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">40. Berat Kotor (kg)</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_barangekspor_bruto'] ?> </td>
                </tr>
            </table>
        </td>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">41. Berat Kotor (kg)</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style=""><?= $peb['peb_barangekspor_netto'] ?> </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 1.3cm; padding-left: 5px; border-right: solid 1px #000;">42. No.</td>
                    <td style="width: 5.7cm; padding-left: 5px; border-right: solid 1px #000;">
                        43. Pos Tarif/HS, uraian jumlah dan jenis<br> &nbsp; &nbsp; &nbsp;
                        barang cara lengkap, merk, tipe,<br> &nbsp; &nbsp; &nbsp;
                        ukuran, spesifikasi lain dan kode barang
                    </td>
                    <td style="padding-left: 5px;">
                        43. HE barang dan<br> &nbsp; &nbsp; &nbsp;
                        tarif BK pada<br> &nbsp; &nbsp; &nbsp;
                        tanggal pendaftaran
                    </td>
                </tr>
                <?php foreach($peb as $i => $detail){ ?>
                <?php if(is_array($detail)){ ?>
                <tr style="border-top: solid 1px #000;">
                    <td style="padding-left: 5px; border-right: solid 1px #000; text-align: center;"><?= ($i+1) ?></td>
                    <td style="border-right: solid 1px #000;">
                        <?= str_replace("\n", "<br>", $detail['detail_uraian']); ?>
                    </td>
                    <td style="padding-left: 5px;">
                        <?= str_replace("\n", "<br>", $detail['detail_he_barang']); ?>
                    </td>
                </tr>
                <?php } ?>
                <?php } ?>
            </table>
        </td>
        <td style="width: 50%;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 4cm; padding-left: 5px; border-right: solid 1px #000;">
                        45. Jumlah & Jenis sat,<br> &nbsp; &nbsp; &nbsp;
                        Berat Bersih (kg),<br> &nbsp; &nbsp; &nbsp;
                        Volume (m3)
                    </td>
                    <td style="width: 3.5cm; padding-left: 5px; border-right: solid 1px #000;">
                        46. - Negara Asal Barang<br>
                        47. - Daerah Asal Barang
                    </td>
                    <td style="padding-left: 5px;">45. Jumlah Nilai<br> &nbsp; &nbsp; &nbsp; FOB</td>
                </tr>
                <?php foreach($peb as $i => $detail){ ?>
                <?php if(is_array($detail)){ ?>
                <tr style="border-top: solid 1px #000;">
                    <td style="padding-left: 5px; border-right: solid 1px #000;">
                        <?= str_replace("\n", "<br>", $detail['detail_qty']); ?>
                    </td>
                    <td style="padding-left: 5px; border-right: solid 1px #000;">
                        <?= str_replace("\n", "<br>", $detail['detail_asal']); ?>
                    </td>
                    <td style="padding-left: 5px; text-align: right;">
                        <?= str_replace("\n", "<br>", $detail['detail_fob']); ?>
                    </td>
                </tr>
                <?php } ?>
                <?php } ?>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">49. Nilai tukar mata uang</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style="text-align: right;"><?= $peb['peb_barangekspor_nilai_tukar'] ?> </td>
                </tr>
            </table>
        </td>
        <td style="width: 50%; border-right: solid 1px #000;">
            <table style="width: 100%; " class="table-level2">
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">50. Nilai Bea Keluar</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style="text-align: right;"><?= $peb['peb_penerimaannegara_bea_keluar'] ?> </td>
                </tr>
                <tr style="">
                    <td style="width: 3.8cm; padding-left: 5px;">51. Penerimaan Pajak Lainnya</td>
                    <td style="width: 0.2cm;">:</td>
                    <td style="text-align: right;"><?= $peb['peb_penerimaannegara_pajak'] ?> </td>
                </tr>
            </table>
        </td>
    </tr>
</table>