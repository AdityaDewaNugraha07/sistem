<tr style="" class="item-saldo">
    <td class="td-kecil text-align-center"><?= $i+1; ?></td>
    <td class="td-kecil" style="text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser($model['created_at']); ?></td>
    <td class="td-kecil" style="font-weight: bold; text-align: center;"><?= $model['reff_no']; ?></td>
    <td class="td-kecil" style="font-size: 1.2rem;"><?= $model['deskripsi'] ?></td>
    <td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($model['nominal_in']); ?></td>
    <td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($model['nominal_out']); ?></td>
    <td class="td-kecil text-align-right">-</td>
</tr>