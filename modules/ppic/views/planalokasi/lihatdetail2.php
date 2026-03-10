
<div class="modal fade" id="modal-lihatdetail" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Stok Log Plan Alokasi <b>' . $jenis_alokasi .'</b> dengan jenis kayu <u>'.$kayu.'</u>'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-scrollable" style="padding-left: 10px; padding-right: 10px;">
                        <table id="table-detail" class="table table-striped table-bordered table-advance table-hover" style="width: 90%">
							<thead>
								<tr>
                                    <th rowspan="2">No.</th>
                                    <th colspan="4">Nomor</th>
                                    <th colspan="3">Ukuran</th>
                                    <th rowspan="2">Pot</th>
                                    <th colspan="4">⌀ (cm)</th>
                                    <th colspan="3">Cacat (cm)</th>
                                    <th rowspan="2">Status FSC</th>
                                </tr>
                                <tr>
                                    <th>Barcode</th>
                                    <th>Batang</th>
                                    <th>Lapangan</th>
                                    <th>Grade</th>
                                    <th>P<br>(m)</th>
                                    <th>⌀ Rata<br>(cm)</th>
                                    <th>V<br>(m<sup>3</sup>)</th>
                                    <th>Ujung 1</th>
                                    <th>Ujung 2</th>
                                    <th>Pangkal 1</th>
                                    <th>Pangkal 2</th>
                                    <th>P</th>
                                    <th>Gb</th>
                                    <th>Gr</th>
                                </tr>
							</thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                if(count($model) > 0){
                                    foreach($model as $i => $mod){
                                        $total += $mod['fisik_volume'];
                                        ?>
                                        <tr>
                                            <td class="text-align-center"><?= $i+1; ?></td>
                                            <td><?= $mod['no_barcode']; ?></td>
                                            <td class="text-align-center"><?= $mod['no_btg']; ?></td>
                                            <td class="text-align-center"><?= $mod['no_lap']; ?></td>
                                            <td class="text-align-center"><?= $mod['no_grade']; ?></td>
                                            <td class="text-align-right"><?= $mod['fisik_panjang']; ?></td>
                                            <td class="text-align-right"><?= $mod['fisik_diameter']; ?></td>
                                            <td class="text-align-right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($mod['fisik_volume'], 2); ?></td>
                                            <td class="text-align-center"><?= $mod['pot']?$mod['pot']:'-'; ?></td>
                                            <td class="text-align-right"><?= $mod['diameter_ujung1']; ?></td>
                                            <td class="text-align-right"><?= $mod['diameter_ujung2']; ?></td>
                                            <td class="text-align-right"><?= $mod['diameter_pangkal1']; ?></td>
                                            <td class="text-align-right"><?= $mod['diameter_pangkal2']; ?></td>
                                            <td class="text-align-right"><?= $mod['cacat_panjang']; ?></td>
                                            <td class="text-align-right"><?= $mod['cacat_gb']; ?></td>
                                            <td class="text-align-right"><?= $mod['cacat_gr']; ?></td>
                                            <td class="text-align-center"><?= $mod['fsc']=='true'?'FSC 100%':'Non FSC'; ?></td>
                                        </tr>
                                <?php }
                                } else {
                                    echo '<tr><td colspan="17" class="text-align-center">Data tidak ditemukan</td></tr>';
                                } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-align-right"><b>TOTAL</b></td>
                                    <td class="text-align-right"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total, 2); ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>