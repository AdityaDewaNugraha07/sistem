<tr>
    <td style="vertical-align: top; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1 ?></span>
    </td>
    <td style="vertical-align: top;">
        <?= $detail->bhp->bhp_nm ?>
    </td>
    <td style="vertical-align: top;  text-align: center;">
        <?= $detail->spbd_jml ?>
    </td>
    <td style="vertical-align: top; text-align: center; font-size: 1.6rem; padding: 3px;">
        <span class="satuan"><?= !empty($detail->bhp->bhp_satuan)?$detail->bhp->bhp_satuan:''; ?></span>
    </td>
	<td style="vertical-align: top; text-align: center;">
        <?= (!empty($detail->spbd_tgl_dipakai)?\app\components\DeltaFormatter::formatDateTimeForUser2($detail->spbd_tgl_dipakai):" - ") ?>
    </td>
    <td style="vertical-align: middle; font-size: 1.1rem;">
        <?= (!empty($detail->spbd_ket)? nl2br($detail->spbd_ket) :" - ") ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        -
    </td>
</tr>