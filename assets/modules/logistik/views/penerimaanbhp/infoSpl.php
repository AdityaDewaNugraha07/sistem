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
                            <td><strong><?= Yii::t('app', 'Kode SPL'); ?></strong></td>
                            <td>: <?= $model->spl_kode ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Status'); ?></strong></td>
                            <td>: <?= $model->spl_status; ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                    <table>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Tanggal SPL'); ?></strong></td>
                            <td>: <?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spl_tanggal); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Disetujui Oleh'); ?></strong></td>
                            <td>: <?= $model->splDisetujui->pegawai_nama; ?>
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
                    <th><?= Yii::t('app', 'Nama Item'); ?></th>
                    <th><?= Yii::t('app', 'Qty'); ?></th>
                    <th><?= Yii::t('app', 'Harga Estimasi'); ?></th>
                    <th><?= Yii::t('app', 'Subtotal'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($modDetail as $i => $detail){ ?>
                <tr>
                    <td><?php echo $i+1 ?></td>
                    <td><?php echo $detail->bhp->bhp_nm; ?></td>
                    <td style="text-align: center"><?php echo \app\components\DeltaFormatter::formatNumberForUser($detail->spld_qty); ?></td>
                    <td style="text-align: right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($detail->spld_harga_estimasi); ?></td>
                    <td style="text-align: right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($detail->spld_qty * $detail->spld_harga_estimasi); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

