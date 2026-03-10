<?php
$model = \app\models\TApproval::findOne($approval_id);
$nomor_produksi = str_replace("RPKO","",$model->reff_no);
$modKirimGudangDetail = \app\models\TKirimGudangDetail::findOne(['nomor_produksi'=>$nomor_produksi]);
$modBrgProduk = \app\models\MBrgProduk::findOne(['produk_id'=>$modKirimGudangDetail->produk_id]);
?>
<style>

td {
    font-size: 13px;
    line-height: 20px;
}
th {
    font-size: 13px;
    line-height: 20px;
}
#detail td, th {
    border: solid 1px #ccc;
    padding: 3px;
}
</style>

<div class="modal-body" >
    <div class="row">
        <table class="col-md-12 table">
            <tr>
                <td class="col-md-3 text-right" style="border-top: none;">Nomor Produksi</td>
                <td class="col-md-1 text-center" style="border-top: none;">:</td>
                <td class="col-md-8" style="border-top: none;"><?php echo $modKirimGudangDetail->nomor_produksi;?></td>
            </tr>
            <tr>
                <td class="col-md-3 text-right">Produk Nama</td>
                <td class="col-md-1 text-center">:</td>
                <td class="col-md-8"><?php echo $modBrgProduk->produk_nama;?></td>
            </tr>
            <tr>
                <td class="col-md-3 text-right">Produk Kode</td>
                <td class="col-md-1 text-center">:</td>
                <td class="col-md-8"><?php echo $modBrgProduk->produk_kode;?></td>
            </tr>
            <tr>
                <td class="col-md-3 text-right">Produk Dimensi</td>
                <td class="col-md-1 text-center">:</td>
                <td class="col-md-8"><?php echo $modBrgProduk->produk_dimensi;?></td>
            </tr>
            <?php
            $modRejectReason = \yii\helpers\Json::decode($modKirimGudangDetail->reject_reason);
            foreach($modRejectReason as $a => $b){
                $modPegawai = \app\models\MPegawai::findOne($b["by"]);
                ?>
                <tr>
                    <td class="col-md-3 text-right">Status</td>
                    <td class="col-md-1 text-center">:</td>
                    <td class="col-md-8" style="color: #f00; font-weight: bold;">REJECTED</td>
                </tr>
                <tr>
                    <td class="col-md-3 text-right">Rejected by</td>
                    <td class="col-md-1 text-center">:</td>
                    <td class="col-md-8"><?php echo $modPegawai->pegawai_nama;?></td>
                </tr>
                <tr>
                    <td class="col-md-3 text-right">Rejected at</td>
                    <td class="col-md-1 text-center">:</td>
                    <td class="col-md-8"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($b['at']);?></td>
                </tr>
                <tr>
                    <td class="col-md-3 text-right">Reason</td>
                    <td class="col-md-1 text-center">:</td>
                    <td class="col-md-8" style="font-weight: bold;"><?php echo $b['reason'];?></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <div class="row">
        <table class="col-md-12 table">
        <?php
        if (!empty($modKirimGudangDetail->approve_reason)) {
            $modApproveReason = \yii\helpers\Json::decode($modKirimGudangDetail->approve_reason);
            foreach($modApproveReason as $x => $y){
                $modPegawai = \app\models\MPegawai::findOne($y["by"]);
                ?>
                <tr>
                    <td class="col-md-12 text-center">
                        <br><span class="td-kecil label label-success" style="font-weight: bold;">APPROVED</span>
                        <br>by : <?php echo $modPegawai->pegawai_nama;?>
                        <br>at : <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($y['at']);?>
                        <br>reason : <?php echo $y['reason'];?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </table>
    </div>
</div>
<div class="modal-footer" style="text-align: center;">
	<?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
    <?php if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?php // <?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); */?>
    <?php } ?>
	<?php } ?>
</div>