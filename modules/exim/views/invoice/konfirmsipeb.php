<?php app\assets\RepeaterAsset::register($this); ?>
<style>
.note-editable p{
	margin: 0px;
}
</style>
<div class="modal fade" id="modal-konfirmsi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				
                <h4 class="modal-title"><?= Yii::t('app', 'Konfirmasi Cetak PEB (Pemberitahuan Ekspor Barang)'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-konfirmsi',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-10">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-2 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 200px; font-size: 1.2rem;"><b>Kode Bea Cukai</b></td>
                                <td style="width: 10px;">: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_kode_beacukai",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem;"><b>No. Pengajuan</b></td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_no_pengajuan",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>A. KANTOR PABEAN</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Kantor Pabean Pemuatan</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_kantorpabean_pemuatan",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Kantor Pabean Ekspor</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_kantorpabean_ekspor",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 200px; font-size: 1.2rem;"><b>B. JENIS EKSPOR</b></td>
                                <td style="width: 10px;">: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_jenis_ekspor",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem;"><b>C. KATEGORI EKSPOR</b></td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_kategori_ekspor",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem;"><b>D. CARA PERDAGANGAN</b></td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_cara_perdagangan",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; vertical-align: top;"><b>E. CARA PEMBAYARAN</b></td>
                                <td style="vertical-align: top;">: </td>
                                <td><?php
                                    if($model->payment_method == "LC"){ ?>
                                        <span class="input-group-btn" style="width: 20%">
                                            <?= yii\helpers\Html::activeTextInput($model, "peb_cara_pembayaran",['class'=>'form-control-kecil']); ?>
                                        </span>
                                        <span class="input-group-btn" style="width: 40%">
                                            <?= yii\helpers\Html::activeTextInput($model, "peb_carapembayaran_lcno",['class'=>'form-control-kecil','placeholder'=>'No. LC']); ?>
                                        </span>
                                        <span class="input-group-btn" style="width: 30%">
                                            <?= yii\helpers\Html::activeTextInput($model, "peb_carapembayaran_lctgl",['class'=>'form-control-kecil','placeholder'=>'Tgl LC']);; ?>
                                        </span>
                                        
                                <?php }else{
                                    echo yii\helpers\Html::activeTextInput($model, "peb_cara_pembayaran",['class'=>'form-control-kecil']);
                                } ?>
                                
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>EKSPORTIR</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Identitas</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_eksportir_identitas",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Nama</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_eksportir_nama",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Alamat</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_eksportir_alamat",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Niper</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_eksportir_niper",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Status</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_eksportir_status",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>PPJK</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">NPWP</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_ppjk_npwp",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Nama</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_ppjk_nama",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Alamat</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_ppjk_alamat",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>DATA PENGANGKUTAN</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Cara Pengangkutan</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pengangkutan_cara_pengangkutan",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Nama & Bendera<br>Sarana Pengangkut</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pengangkutan_nama_bendera",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">No. Pengangkut<br>(Voy/Flight/Nopol)</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pengangkutan_no",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Tgl Perkiraan Ekspor</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pengangkutan_tanggal_perkiraan",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>DOKUMEN PELENGKAP PABEAN</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">No./Tgl Invoice</td>
                                <td>: </td>
                                <td>
                                    <span class="input-group-btn" style="width: 60%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_no_inv",['class'=>'form-control-kecil']) ?>
                                    </span>
                                    <span class="input-group-btn" style="width: 30%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_tgl_inv",['class'=>'form-control-kecil']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">No./Tgl Packing</td>
                                <td>: </td>
                                <td>
                                    <span class="input-group-btn" style="width: 60%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_no_packing",['class'=>'form-control-kecil']) ?>
                                    </span>
                                    <span class="input-group-btn" style="width: 30%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_tgl_packing",['class'=>'form-control-kecil']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px;">Jenis/No./Tgl Dok</td>
                                <td>: </td>
                                <td>
                                    <span class="input-group-btn" style="width: 30%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_jenis_dok",['class'=>'form-control-kecil']) ?>
                                    </span>
                                    <span class="input-group-btn" style="width: 30%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_no",['class'=>'form-control-kecil']) ?>
                                    </span>
                                    <span class="input-group-btn" style="width: 30%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_tgl",['class'=>'form-control-kecil']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Kantor Bea Cukai<br>pendaftaran CK-5</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pelengkappabean_kantor_beacukai",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>DATA TRANSAKSI EKSPOR</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Bank Devisa<br>Hasil Ekspor</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_transaksiekspor_bank_devisa",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Jenis Valuta Asing</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_transaksiekspor_jenis_valuta",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">FOB</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_transaksiekspor_fob",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Freight</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_transaksiekspor_freight",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Asuransi (LN/DN)</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_transaksiekspor_asuransi",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Nilai Maklon</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_transaksiekspor_maklon",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>DATA PETI KEMAS</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Jumlah Peti Kemas</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_petikemas_jml",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">No./Ukuran Peti Kemas</td>
                                <td>: </td>
                                <td>
                                    <span class="input-group-btn" style="width: 30%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_petikemas_no",['class'=>'form-control-kecil']) ?>
                                    </span>
                                    <span class="input-group-btn" style="width: 30%">
                                        <?= yii\helpers\Html::activeTextInput($model, "peb_petikemas_ukuran",['class'=>'form-control-kecil']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Status Peti Kemas</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_petikemas_status",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table style="width: 100%;">
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>H. KOLOM BEA DAN CUKAI</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">No. Pendaftaran</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_beacukai_no_daftar",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Tanggal</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_beacukai_tgl_daftar",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">No. BC 1.1</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_beacukai_no_bc",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Tanggal</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_beacukai_tgl_bc",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Pos / Sub Pos</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_beacukai_pos",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>PENERIMA</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Nama</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_penerima_nama",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Alamat</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_penerima_alamat",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Negara</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_penerima_negara",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>PEMBELI</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Nama</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pembeli_nama",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Alamat</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pembeli_alamat",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Negara</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pembeli_negara",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>PELABUHAN TEMPAT MUAT EKSPOR</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Pel Muat Asal</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pelabuhanmuat_muat_asal",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Pel/Tempat Muat Ekspor</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pelabuhanmuat_muat_ekspor",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Pel Bongkar</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pelabuhanmuat_bongkar",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Pel Tujuan</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pelabuhanmuat_tujuan",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Negara Tujuan Ekspor</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_pelabuhanmuat_tujuan_ekspor",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>TEMPAT PEMERIKSAAN</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Lokasi Pemeriksaan</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_tempatperiksa_lokasi",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Kantor Pabean Pemeriksaan</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_tempatperiksa_kantor",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Gudang PLB</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_tempatperiksa_gudang",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>PENYERAHAN</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Cara Penyerahan</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_penyerahan_cara",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.3rem;" colspan="3"><b>KEMASAN</b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Jenis/Jml/Merk Kemasan</td>
                                <td>: </td>
                                <td><?= yii\helpers\Html::activeTextInput($model, "peb_kemasan_jenis_jml",['class'=>'form-control-kecil']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 50%;">
                                <tr>
                                    <td style="padding-left: 20px; font-size: 1.3rem;" colspan="3"><b>BARANG EKSPOR</b></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem; padding-left: 35px; line-height: 0.7; width: 215px;">Berat Kotor / Berat Bersih (kg)</td>
                                    <td style="width: 10px;">: </td>
                                    <td>
                                        <span class="input-group-btn" style="width: 30%">
                                            <?= yii\helpers\Html::activeTextInput($model, "peb_barangekspor_bruto",['class'=>'form-control-kecil']) ?>
                                        </span>
                                        <span class="input-group-btn" style="width: 30%">
                                            <?= yii\helpers\Html::activeTextInput($model, "peb_barangekspor_netto",['class'=>'form-control-kecil']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>  
                        </div>
                    </div>
                    <br>
                    <div class="row"  style="margin-left: 5px; margin-right: 5px;">
                        <div class="col-md-12">
                            <table style="width: 100%;" border='1' class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                    <tr>
                                        <th style="font-size: 1.2rem; line-height: 0.7; padding: 7px; width: 35px;">No.</th>
                                        <th style="font-size: 1.2rem; line-height: 0.7; padding: 7px; width: 30%;">Pos Tarif</th>
                                        <th style="font-size: 1.2rem; line-height: 0.7; padding: 7px; width: 16%;">HE Barang dan<br>Tarif BK pada<br>tanggal pendaftaran</th>
                                        <th style="font-size: 1.2rem; line-height: 0.7; padding: 7px; width: 20%;">Jumlah & jenis sat, Berat Bersih (kg), Volume (m3)</th>
                                        <th style="font-size: 1.2rem; line-height: 0.7; padding: 7px; width: 16%;">Negara Asal / Daerah Asal</th>
                                        <th style="font-size: 1.2rem; line-height: 0.7; padding: 7px;">Jumlah Nilai FOB</th>
                                        <th style="font-size: 1.2rem; line-height: 0.7; padding: 7px;">Jumlah Nilai Freight</th>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 1.1rem; line-height: 1; "></td>
                                        <td style="font-size: 1.1rem; line-height: 1; "><?= yii\helpers\Html::activeTextarea($model, "[0]detail_uraian",['class'=>'form-control-kecil','style'=>'height:50px;']) ?></td>
                                        <td style="font-size: 1.1rem; line-height: 1; "><?= yii\helpers\Html::activeTextarea($model, "[0]detail_he_barang",['class'=>'form-control-kecil','style'=>'height:50px;']) ?></td>
                                        <td style="font-size: 1.1rem; line-height: 1; "><?= yii\helpers\Html::activeTextarea($model, "[0]detail_qty",['class'=>'form-control-kecil','style'=>'height:50px;']) ?></td>
                                        <td style="font-size: 1.1rem; line-height: 1; "><?= yii\helpers\Html::activeTextarea($model, "[0]detail_asal",['class'=>'form-control-kecil','style'=>'height:50px;']) ?></td>
                                        <td style="font-size: 1.1rem; line-height: 1; "><?= yii\helpers\Html::activeTextarea($model, "[0]detail_fob",['class'=>'form-control-kecil','style'=>'height:50px;']) ?></td>
                                        <td style="font-size: 1.1rem; line-height: 1; "><?= yii\helpers\Html::activeTextarea($model, "[0]detail_freight",['class'=>'form-control-kecil','style'=>'height:50px;']) ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="margin-left: 5px; margin-right: 5px;">
                        <div class="col-md-6">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Nilai tukar mata uang</td>
                                    <td>: </td>
                                    <td><?= yii\helpers\Html::activeTextInput($model, "peb_barangekspor_nilai_tukar",['class'=>'form-control-kecil']) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="font-size: 1.3rem;" colspan="3"><b>PENERIMAAN NEGARA</b></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Nilai Bea Keluar</td>
                                    <td>: </td>
                                    <td><?= yii\helpers\Html::activeTextInput($model, "peb_penerimaannegara_bea_keluar",['class'=>'form-control-kecil']) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.2rem; padding-left: 20px; line-height: 0.7">Penerimaan Pajak Lainnya</td>
                                    <td>: </td>
                                    <td><?= yii\helpers\Html::activeTextInput($model, "peb_penerimaannegara_pajak",['class'=>'form-control-kecil']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Lanjutkan Print <i class="fa fa-arrow-right"></i>'),['class'=>'btn blue btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this);'
                    ]);
				?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
	$('.repeater').repeater({
        show: function () {
            $(this).slideDown();
            $('div[data-repeater-item][style=\"display: none;\"]').remove();
        },
        hide: function (e) {
            $(this).slideUp(e);
        },
    });
    
", yii\web\View::POS_READY); ?>
<script type="text/javascript">
</script>