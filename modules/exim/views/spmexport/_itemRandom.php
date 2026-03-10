<?php
$dotted = "";
if(($ii!=0)){
	if($modRandom[($ii-1)]['bundles_no']!=$random['bundles_no']){
		$dotted = "border-top: 2px dotted #777";
	}
}
$res = "Bundle No. ".$random['bundles_no']." ";
if(!empty($random['partition_kode'])){
	$res .= "Part. ".$random['partition_kode'];
}
$random->qty_besar = '-';
?>
<tr>
    <td style="vertical-align: middle; text-align: center; padding: 1px;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
	<td style="vertical-align: middle; padding: 1px; <?= $dotted ?>" id="item-detail" class="td-kecil item-random">
		<?php
		echo yii\bootstrap\Html::activeHiddenInput($random, "[".$i."]produk_id");
		echo "&nbsp; ".$res." : <span style='font-size:1.1rem;'>".$random['thick']." ".$random['thick_unit']." X "
					.$random['width']." ".$random['width_unit']." X ".$random['length']." ".$random['length_unit']."<span><br>";
		?>
    </td>
	<td style="vertical-align: middle; background-color: #FFF7DE; padding: 1px;" class="td-kecil text-align-center">
		-
    </td>
	<td style="vertical-align: middle; background-color: #FFF7DE; padding: 1px;" class="td-kecil text-align-right">
		<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($random['pcs'])." <i>(Pcs)</i>"; ?>
    </td>
	<td style="vertical-align: middle; background-color: #FFF7DE; padding: 1px;" class="td-kecil text-align-right">
		<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($random['volume']); ?>
    </td>
	<td class="text-align-right td-kecil" style="background-color: #EEF7D0; padding: 1px;">
		-
	</td>
	<td class="text-align-right td-kecil" style="background-color: #EEF7D0; padding: 1px;">
		<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($random['pcs'])." <i>(Pcs)</i>"; ?>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #EEF7D0; padding: 1px;">
		<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($random['volume']); ?>
	</td>
</tr>