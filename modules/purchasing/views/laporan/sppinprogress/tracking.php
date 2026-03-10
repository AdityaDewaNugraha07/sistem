<?php
/**
 * @var array $model
 * @var $bhp MBrgBhp
 */

use app\components\DeltaFormatter;
use app\models\MBrgBhp;

?>
<div class="modal fade" id="modal-tracking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tracking SPP') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4 style="text-decoration: underline; margin-top: -10px; text-align: center; font-weight: bold; margin-bottom: 10px"><?= $bhp->bhp_nm ?></h4>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan">
                            <thead>
                            <tr>
                                <th colspan="3"><?= Yii::t('app', 'Pembelian')?></th>
                                <th colspan="3"><?= Yii::t('app', 'Penerimaan')?></th>
                            </tr>
                            <tr style="background-color: #ececec">
                                <td><?= Yii::t('app', 'Tanggal') ?></td>
                                <td><?= Yii::t('app', 'Nomor Referensi') ?></td>
                                <td><?= Yii::t('app', 'Qty') ?></td>
                                <td><?= Yii::t('app', 'Tanggal') ?></td>
                                <td><?= Yii::t('app', 'Nomor Penerimaan') ?></td>
                                <td><?= Yii::t('app', 'Qty') ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $reff_qty = 0;
                                $trm_qty = 0;
                                if(count($model) > 0): ?>
                                    <?php foreach ($model as $row) : ?>
                                        <tr>
                                            <td class="text-center"><?= DeltaFormatter::formatDateTimeForUser2($row['reff_tanggal'])?></td>
                                            <td class="text-center"><?= $row['reff_kode']?></td>
                                            <td class="text-right"><?= $row['reff_qty']?></td>
                                            <td class="text-center"><?= DeltaFormatter::formatDateTimeForUser2($row['tglterima'])?></td>
                                            <td class="text-center"><?= $row['terimabhp_kode']?></td>
                                            <td class="text-right"><?= $row['terimabhpd_qty']?></td>
                                        </tr>
                                    <?php
                                        $reff_qty += $row['reff_qty'];
                                        $trm_qty += $row['terimabhpd_qty'];
                                        endforeach ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6" style="font-style: italic; text-align: center">Tidak ada data</td>
                                </tr>
                                <?php endif ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total</th>
                                    <th><?= $reff_qty ?></th>
                                    <th colspan="2" class="text-right">Total</th>
                                    <th><?= $trm_qty ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>