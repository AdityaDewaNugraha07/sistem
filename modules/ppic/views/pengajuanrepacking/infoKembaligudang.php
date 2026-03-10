<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><center><?= Yii::t('app', 'Detail Pengiriman Palet Kembali Ke Gudang'); ?> <b>(<?= $model->hasil_dari ?>)</b></center></h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6"><b style="font-size: 1.6rem; font-weight: 300;">Data Permintaan</b></div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kode Permintaan</label>
                            <div class="col-md-7"><strong><?= $model->kode_permintaan ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Dibuat Oleh</label>
                            <div class="col-md-7"><strong><?= $model->dibuat_permintaan ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Keperluan Permintaan</label>
                            <div class="col-md-7"><strong><?= $model->keperluan_permintaan ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Keterangan Permintaan</label>
                            <div class="col-md-7"><strong><?= $model->keterangan_permintaan ?></strong></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6"><b style="font-size: 1.6rem; font-weight: 300;">Data Palet Lama</b></div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table id="table-detail-paletasal" class="table table-striped table-bordered table-advance table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="td-kecil" style="width: 60px;">No.</th>
                                        <th class="td-kecil" style="width: 160px;">Kode Permintaan<br>Tanggal</th>
                                        <th class="td-kecil" style="">Kode Barang Jadi / Produk</th>
                                        <th class="td-kecil" style="width: 60px;">Pcs</th>
                                        <th class="td-kecil" style="width: 80px;">M<sup>3</sup></th>
                                        <th class="td-kecil" style="width: 100px; line-height: 1">Tanggal<br>Mutasi Keluar</th>
                                        <th class="td-kecil" style="width: 100px; line-height: 1">Tanggal<br>Terima Mutasi</th>
                                        <th class="td-kecil"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $modMaps = \app\models\MapTerimamutasiHasilrepacking::find()->where("hasil_repacking_id = ".$model->hasil_repacking_id)->all();
                                    if(count($modMaps)>0){
                                        foreach($modMaps as $i => $map){
                                            $modPermintaan = \app\models\TPengajuanRepacking::findOne($map->pengajuan_repacking_id);
                                            if($modPermintaan->keperluan == 'Penanganan Barang Retur'){
                                                $modProduksi = app\models\TReturProdukDetail::findOne(['nomor_produksi'=>$map->nomor_produksi_lama]);
                                                $modTerima = $modProduksi;
                                                $qty_m3 = $modTerima->kubikasi;
                                            } else {
                                                $modProduksi = app\models\TProduksi::findOne(['nomor_produksi'=>$map->nomor_produksi_lama]);
                                                $modTerima = app\models\TTerimaKo::findOne(['nomor_produksi'=>$map->nomor_produksi_lama]);
                                                $qty_m3 = $modTerima->qty_m3;
                                            }
                                            $modMutasiKeluar = app\models\TMutasiKeluar::findOne(['nomor_produksi'=>$map->nomor_produksi_lama]);
                                            $modTerimaMutasi = app\models\TTerimaMutasi::findOne(['nomor_produksi'=>$map->nomor_produksi_lama]);
                                            echo "<tr>";
                                            echo "  <td class='td-kecil text-align-center'>".($i+1)."</td>";
                                            echo "  <td class='td-kecil text-align-left'><b>".$modPermintaan->kode."</b><br>".app\components\DeltaFormatter::formatDateTimeForUser2($modPermintaan->tanggal)."</td>";
                                            echo "  <td class='td-kecil text-align-left'><b>".$map->nomor_produksi_lama."</b><br>".$modProduksi->produk->produk_nama."</td>";
                                            echo "  <td class='td-kecil text-align-center'>".app\components\DeltaFormatter::formatNumberForUserFloat($modTerima->qty_kecil)."</td>";
                                            echo "  <td class='td-kecil text-align-right'>". number_format($qty_m3,4)."</td>";
                                            echo "  <td class='td-kecil text-align-center'>".app\components\DeltaFormatter::formatDateTimeForUser2($modMutasiKeluar->tanggal)."</td>";
                                            echo "  <td class='td-kecil text-align-center'>".app\components\DeltaFormatter::formatDateTimeForUser2($modTerimaMutasi->tanggal)."</td>";
                                            echo "</tr>";
                                        }
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6"><b style="font-size: 1.6rem; font-weight: 300;">Data Palet Baru</b></div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kode Produk</label>
                            <div class="col-md-7"><strong><?= $modProduk->produk_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Nama Produk</label>
                            <div class="col-md-7"><strong><?= $modProduk->produk_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Jenis Produk</label>
                            <div class="col-md-7"><strong><?= $modProduk->produk_group ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Jenis Dimensi</label>
                            <div class="col-md-7"><strong><?= $modProduk->produk_dimensi ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Satuan Besar</label>
                            <div class="col-md-7"><strong><?= $modProduk->produk_satuan_besar ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Qty Kirim</label>
                            <div class="col-md-7"><strong><?= $model->qty_kecil ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Satuan Kecil</label>
                            <div class="col-md-7"><strong><?= $modProduk->produk_satuan_kecil ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kubikasi</label>
                            <div class="col-md-7"><strong><?= number_format($model->qty_m3,4) ?></strong></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kode Pengiriman</label>
                            <div class="col-md-7"><strong><?= $model->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Tanggal Kirim</label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Jenis Palet</label>
                            <div class="col-md-7"><strong><?= $model->jenis_palet ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Tanggal Produksi</label>
                            <div class="col-md-7"><strong><?= $modPermintaan->keperluan == 'Penanganan Barang Retur'?\app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_produksi):\app\components\DeltaFormatter::formatDateTimeForUser2($modProduksi->tanggal_produksi) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">No. Urut Produksi</label>
                            <div class="col-md-7"><strong><?= $modPermintaan->keperluan == 'Penanganan Barang Retur'?'-':$modProduksi->nomor_urut_produksi ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kode Barang Jadi</label>
                            <div class="col-md-7"><strong><u><?= $model->nomor_produksi ?></u></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Keterangan</label>
                            <div class="col-md-7"><strong><?= $model->keterangan ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>