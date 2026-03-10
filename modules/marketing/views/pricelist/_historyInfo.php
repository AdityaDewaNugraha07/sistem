<div class="modal fade" id="modal-history-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Price List'); ?> <b><?php echo $kode;?></b> - Tanggal Penetapan : <b><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($harga_tanggal_penetapan);?></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-scrollable">
                        <div class="col-md-12">
                            <div class="col-md-12 row text-right small" style="font-style: italic;">
                                * Klik tanda <i class='fa fa-arrow-circle-down' aria-hidden='true' style='color: #33cc33;'></i> atau
                                <i class='fa fa-arrow-circle-right' aria-hidden='true' style='color: #dedede;'></i> atau 
                                <i class='fa fa-arrow-circle-up' aria-hidden='true' style='color: #ff0000;'></i>
                                untuk melihat history lengkap perubahan harga
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="table-history-info">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><?= Yii::t('app', 'Produk Nama'); ?></th>
                                        <th><?= Yii::t('app', 'Produk Kode'); ?></th>
                                        <th><?= Yii::t('app', 'Produk Dimensi'); ?></th>
                                        <th><?= Yii::t('app', 'Harga Sebelumnya'); ?></th>
                                        <th><?= Yii::t('app', 'Harga End User'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($model as $kolom) {
                                    ?>
                                    <tr>
                                        <td class='text-center'><?php echo $i;?></td>
                                        <td><?php echo $kolom['produk_nama'];?></td>
                                        <td><?php echo $kolom['produk_kode'];?></td>
                                        <td><?php echo $kolom['produk_dimensi'];?></td>
                                        <?php
                                        $sql_harga_lama = "select a.harga_enduser ".
                                                        "	from m_harga_produk a ".
                                                        "	where a.produk_id = ".$kolom['produk_id']." ".
                                                        "	and a.status_approval = 'APPROVED' ".
                                                        "	and a.harga_tanggal_penetapan < '".$kolom['harga_tanggal_penetapan']."' ".
                                                        "	order by a.harga_id desc ".
                                                        "	limit 1 ".
                                                        "	";
                                        $harga_lama = Yii::$app->db->createCommand($sql_harga_lama)->queryScalar();
                                        $harga_lama > 0 || $harga_lama != NULL ? $harga_lama = $harga_lama : $harga_lama = 0;
                                        ?>
                                        <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($harga_lama);?></td>
                                        <td class="text-right">
                                            <?php
                                            if (($kolom['harga_enduser']) > $harga_lama) {
                                                $color = "#ff0000";
                                                $sign = "<a onclick='graf(".$kolom['produk_id'].")'><i class='fa fa-arrow-circle-up' aria-hidden='true' style='color: #ff0000;')'></i></a>";
                                            } else if (($kolom['harga_enduser']) < $harga_lama) {
                                                $color = "#33cc33";
                                                $sign = "<a onclick='graf(".$kolom['produk_id'].")'><i class='fa fa-arrow-circle-down' aria-hidden='true' style='color: #33cc33;'></i></a>";
                                            } else {
                                                $color = "#000";
                                                $sign = "<a onclick='graf(".$kolom['produk_id'].")'><i class='fa fa-arrow-circle-right' aria-hidden='true' style='color: #dedede;'></i></a>";
                                            }
                                            ?>
                                            <font style="color: <?php echo $color;?>"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kolom['harga_enduser'])." ".$sign;?></font>
                                        </td>
                                    </tr>
                                    <?php
                                        $i++;
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

<?php
if ($_SESSION['__id'] != 359) {
$this->registerJs("
    $('#table-history-info').bind('cut copy paste',function(e) {
        e.preventDefault();
    })
", yii\web\View::POS_READY);
} 
?>

<script>
    function graf(id,kode) {
        openModal('<?= \yii\helpers\Url::toRoute(['/marketing/pricelist/graf','id'=>'']) ?>'+id+'&kode='+kode,'modal-madul','85%');
    }
</script>