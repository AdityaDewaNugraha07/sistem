<style>
.table-detail-hover > thead > tr > td, 
.table-detail-hover > thead > tr > th {
    padding: 2px;
}
.popover-content{
    background-color: rgba(57,60,61,0.25);
}
</style>
<div class="row">
    <div class="col-md-12">
        <table style="width: 100%; margin-bottom: 15px; margin-top: 15px;">
            <tr>
                <td style="width: 50%">
                    <table>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Kode SPB'); ?></strong></td>
                            <td>: <?= $model->spb_kode ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Dept. Pemesan'); ?></strong></td>
                            <td>: <?= $model->departement->departement_nama; ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                    <table>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Tanggal'); ?></strong></td>
                            <td>: <?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spb_tanggal); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Status'); ?></strong></td>
                            <td>: 
                                <?php 
                                    if($model->spb_status == 'BELUM DIPROSES'){
                                        echo '<span class="label label-sm label-info"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'SEDANG DIPROSES'){
                                        echo '<span class="label label-sm label-warning"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'TERPENUHI'){
                                        echo '<span class="label label-sm label-success"> '.$model->spb_status.' </span>';
                                    }else if($model->spb_status == 'DITOLAK'){
                                        echo '<span class="label label-sm label-danger"> '.$model->spb_status.' </span>';
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-advance table-detail-hover" id="table-detail" style="background-color: #fff">
            <thead>
                <tr>
                    <th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                    <th><?= Yii::t('app', 'Nama Barang'); ?></th>
                    <th><?= Yii::t('app', 'Jumlah Pesan'); ?></th>
                    <th><?= Yii::t('app', 'Jumlah Terpenuhi'); ?></th>
                    <th><?= Yii::t('app', 'Status'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($modDetail as $i => $detail){ ?>
                <tr>
                    <td><?php echo $i+1 ?></td>
                    <td><?php echo $detail->bhp->bhp_nm; ?></td>
                    <td style="text-align: center"><?php echo $detail->spbd_jml; ?></td>
                    <td style="text-align: center"><?php echo $detail->spbd_jml_terpenuhi; ?></td>
                    <td><?php echo (($detail->spbd_jml <= $detail->spbd_jml_terpenuhi)?'
                        <span class="label label-sm label-success"> Oke </span>
                        ':'
                        <span class="label label-sm label-danger"> Belum Terpenuhi </span>'
                        ) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
