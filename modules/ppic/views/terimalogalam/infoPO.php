<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Purchase Order <b>' . $model->kode . '</b>'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
                            <table class="table table-striped table-bordered table-hover" id="table-info">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Produk</th>
                                        <th>Range Diameter</th>
                                        <th>FSC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($modDetail){
                                        foreach ($modDetail as $i => $detail){ 
                                        ?>
                                        <tr>
                                            <td class="text-align-center"><?= $i+1; ?></td>
                                            <td>
                                                <?php 
                                                    if(!$detail->produk_id){
                                                        $produk_ids = explode(',', $detail->produk_id_alias);
                                                        $log_namas = [];
                                                        foreach($produk_ids as $p => $log_id){
                                                            $modLog = app\models\MBrgLog::findOne($log_id);
                                                            $log_namas[] = $modLog->log_nama;
                                                        }
                                                        echo implode('<br>', $log_namas);
                                                    } else {
                                                        $modLog = app\models\MBrgLog::findOne($detail->produk_id);
                                                        echo $modLog->log_nama;
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-align-center"><?= $detail->diameter_alias; ?></td>
                                            <td class="text-align-center"><?= $detail->fsc=='true'?'FSC 100%':'Non FSC'; ?></td>
                                        </tr>
                                    <?php }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>