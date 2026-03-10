<div class="modal fade" id="modal-history" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'History Perubahan Log No Barcode') . '<b> ' . $no_barcode . '</b> / No Lap <b>' . $no_lap . '</b>'; ?></h4>
            </div>
			<div class="table-scrollable">
                <table class="table table-striped table-bordered table-advance table-hover" id="table-history">
                    <thead>    
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Jenis Kayu Asal</th>
                            <th>Jenis Kayu Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(count($model) > 0){
                                foreach($model as $m => $mod){ 
                                    $modKayuOld = \app\models\MKayu::findOne($mod['kayu_id_old']);
                                    $modKayuNew = \app\models\MKayu::findOne($mod['kayu_id_new']);
                                    ?>
                                    <tr>
                                        <td class="text-align-center"><?= $m+1; ?></td>
                                        <td class="text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser2($mod['tanggal']); ?></td>
                                        <td class="text-align-center"><?= $modKayuOld->kayu_nama; ?></td>
                                        <td class="text-align-center"><?= $modKayuNew->kayu_nama; ?></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="4" class="text-align-center">Belum ada history perubahan jenis kayu</td>
                                </tr>
                        <?php }
                        ?>
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>