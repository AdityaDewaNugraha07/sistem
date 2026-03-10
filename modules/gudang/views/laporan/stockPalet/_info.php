<div class="modal fade" id="modal-madul" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover table-contrainer" style="width: 100%; border: 1px solid #A0A5A9;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Produk Nama</th>
                                <th>Produk Kode</th>
                                <th>Dimensi</th>
                                <th>T</th>
                                <th>L</th>
                                <th>P</th>
                                <th>Qty</th>
                                <th>Kubikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        
                        foreach ($t_terima_ko_kd as $kolom) {
                            $panjang = $kolom['p'];
                            $lebar = $kolom['l'];
                            $tinggi = $kolom['t'];
                            $qty = $kolom['qty'];
                            $kubikasi = ROUND($kolom['kapasitas_kubikasi'],4);
                            $i % 2 == 0 ? $bg = "#f5f5f5" : $bg = "#e3e3e3";
                        ?>
                            <tr>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $i;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $m_brg_produk->produk_nama;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $m_brg_produk->produk_kode;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $m_brg_produk->produk_dimensi;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $tinggi;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $lebar;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $panjang;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $qty;?></td>
                                <td style="background-color: <?php echo $bg;?>"><?php echo $kubikasi;?></td>
                            </tr>
                        <?php
                            $i++;
                        }
                        ?>
                        </tbody>
                        <tfoot style="background-color: #E3E7EA">
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>