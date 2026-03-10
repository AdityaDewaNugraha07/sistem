<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1 ?></span>
    </td>
    <td style="vertical-align: middle;">
        <?= $detail->bhp_nama ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->qty_kebutuhan ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->qty_terpenuhi ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->sppd_qty ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $detail->current_stock ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <span class="satuan"><?= $detail->satuan; ?></span>
    </td>
    <td style="vertical-align: middle;">
        <?= !empty($detail->sppd_ket)?$detail->sppd_ket:'<center> - </center>' ?>
    </td>
</tr>