<?php 
$modSppDetail = app\models\TSppDetail::findOne($detail['sppd_id']);
if(!empty($detail['bhp_id'])){
?>
<tr style="">
	<td class="td-kecil"  style="font-size:1.2rem; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<span class="no_urut"><?= $i+1; ?></span>
	</td>
	<td class="td-kecil"  style="font-size:1.2rem;">
		<b><?= $detail['spp_kode']; ?></b>
	</td>
	<td class="td-kecil"  style="font-size:1.2rem;">
		<?= !empty($detail['spp_tanggal'])?app\components\DeltaFormatter::formatDateTimeForUser2($detail['spp_tanggal']):""; ?>
	</td>
<!--	<td class="td-kecil" style="font-size:1.2rem;">
		<?php // echo !empty($detail['departement_nama'])?$detail['departement_nama']:""; ?>
	</td>-->
	<td class="td-kecil"  style="font-size:1.2rem;">
		<?= !empty($detail['bhp_kode'])?'<b>'.$detail['bhp_kode'].'</b><br>':""; ?>
		<?php
			$modBhp = app\models\MBrgBhp::findOne($detail['bhp_id']);
			echo $modBhp->Bhp_nm;
		?>
	</td>
	<td class="td-kecil" style="text-align: center; font-size: 1.1rem">
		<?= (!empty($detail['sppd_qty'])?$detail['sppd_qty']:"0")."<br>".(!empty($detail['bhp_satuan'])?$detail['bhp_satuan']:""); ?>
	</td>
	<td class="td-kecil" style="text-align: center; font-size: 1.1rem">
		<?php echo $modSppDetail->QtyTerbeli['qty']."<br>".(!empty($detail['bhp_satuan'])?$detail['bhp_satuan']:"") ?>
	</td> 
	<td class="td-kecil" style="padding:3px;"> 
		<?php
		$value_arr = [];
		if(empty($modSppDetail->spp->cancel_traksaksi_id)){
			if(!empty($detail['suplier_id'])){
				$modSupplier = app\models\MSuplier::findOne($detail['suplier_id']);
				$value_arr[$modSupplier->suplier_id] = $modSupplier->suplier_nm." ".$modSupplier->suplier_almt;
			}
			if(strpos($modSppDetail->StatusSppDetail, 'COMPLETE')){
				echo yii\bootstrap\Html::activeDropDownList($modSppDetail, 'suplier_id', $value_arr,['class'=>'form-control select2','style'=>'padding:3px; font-size:1.1rem;','prompt'=>'','disabled'=>TRUE]);
			}else{
				echo yii\bootstrap\Html::activeDropDownList($modSppDetail, 'suplier_id', $value_arr,['class'=>'form-control select2','style'=>'padding:3px; font-size:1.1rem;','prompt'=>'','onchange'=>'setSupplier(this,'.$detail['sppd_id'].')']);
			}
		}
		?>
	</td>
	<td class="td-kecil" style="text-align: center;">
		<?php echo $modSppDetail->StatusSppDetail ?>
	</td>
	<td class="td-kecil" style="font-size: 0.8rem;">
		<?= !empty($detail['sppd_ket'])?$detail['sppd_ket']:"<center>-</center>"; ?>
	</td>
	<td class="td-kecil" style="font-size: 0.9rem; text-align: center; vertical-align: middle;">
		<a href="javascript:void(0);" onclick="sppTerkait('<?= $detail['spp_id']; ?>')">Lihat</a>
	</td>
	<td class="td-kecil" style="font-size: 1rem !important; text-align: center; vertical-align: middle;">
		<?php
//		if(!empty($detail['reff_no'])){
//			$ex = substr($detail['reff_no'], 0,3);
//			if($ex == "SPO"){
//				$modSPO = \app\models\TSpo::findOne(['spo_kode'=>$detail['reff_no']]);
//				echo "<a onclick='infoSPO(".$modSPO->spo_id.",".$detail['bhp_id'].")'>".$detail['reff_no']."</a>";
//			}else if($ex == "SPL"){
//				$modSPL = \app\models\TSpl::findOne(['spl_kode'=>$detail['reff_no']]);
//				echo "<a onclick='infoSPL(".$modSPL->spl_id.",".$detail['bhp_id'].")'>".$detail['reff_no']."</a>";
//			}
//		}else{
//			"-";
//		}
		$sql = "SELECT * FROM map_spp_detail_reff WHERE sppd_id = ".$detail['sppd_id'];
		$mod = Yii::$app->db->createCommand($sql)->queryAll();
		if(count($mod)>0){
			foreach($mod as $res){
				$ex = substr($res['reff_no'], 0,3);
				if($ex == "SPO"){
					$modSPO = \app\models\TSpo::findOne(['spo_kode'=>$res['reff_no']]);
					echo "<a onclick='infoSPO(".$modSPO->spo_id.",".$detail['bhp_id'].")'>".$res['reff_no']."</a><br>";
				}else{
					$modSPL = \app\models\TSpl::findOne(['spl_kode'=>$res['reff_no']]);
					echo "<a onclick='infoSPL(".$modSPL->spl_id.",".$detail['bhp_id'].")'>".$res['reff_no']."</a><br>";
				}
			}
		}else{
			echo "-";
		}
		?>
	</td>
	<td class="td-kecil" style="font-size: 1rem !important; text-align: center; vertical-align: middle;">
		<?php echo $modSppDetail->QtyTerbeli['info_terima'] ?>
	</td>
</tr>
<?php } ?>