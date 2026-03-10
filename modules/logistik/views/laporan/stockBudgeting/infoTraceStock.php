<?php 
/*
pastikan div id modal adalah id yang dipanggil di index.php
*/
?>
<div class="modal fade" id="modal-tracestock" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <?php foreach ($view_stock as $stock) { ?>
                <h4 class="modal-title"><?= Yii::t('app', 'Trace Barang '); ?> <b><?php echo $stock['kode'];?></b></h4>
                <?php } ?>
            </div>
            <div class="modal-body">
                <div class="row">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <tr>
                            <th class='text-center'>Kode</th>
                            <th class='text-center'>Nama Item</th>
                            <th class='text-center'>Qty</th> 
                            <th class='text-center'>Target<br>Plan</th> 
                            <th class='text-center'>Target<br>Peruntukan</th>
                            <th class='text-center'>Dept.<br>Peruntukan</th> 
                            <th class='text-center'>Keterangan</th> 
                        </tr>
                        <?php // KONTEN MODAL ?>
                        <?php
                        foreach ($view_stock as $stock) {
                        ?>
                        <tr>
                            <td><center><?php echo $stock['kode'];?></center></td>
                            <td><?php echo $stock['bhp_nm'];?></td>
                            <td><center><?php echo $stock['jumlah'];?></center></td>
                            <td><center><?php echo $stock['target_plan'];?></center></td>
                            <td><center><?php echo $stock['target_peruntukan'];?></center></td>
                            <td><center>
                                <?php if($stock['dept_peruntukan'] == null){
                                    echo '-';
                                } else {
                                    echo $stock['dept_peruntukan'];
                                }?>
                            </center></td>
                            <td><?php echo $stock['keterangan'];?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <?php /* EO KONTEN MODAL */ ?>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
