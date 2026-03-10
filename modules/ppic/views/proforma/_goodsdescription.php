<table class="table table-bordered" style="width: 100%; margin-left: -15px;" id="table-goods_description">
	<thead>
		<tr>
			<th style="width: 40px; font-size: 1.2rem; text-align: center;">No.</th>
			<th style="font-size: 1.2rem; text-align: center;">Description</th>
			<th style="width: 100px; font-size: 1.2rem; text-align: center;">Size</th>
			<th style="width: 80px; font-size: 1.2rem; text-align: center;">M<sup>3</sup></th>
			<th style="width: 100px; font-size: 1.2rem; text-align: center;">Lot Code</th>
			<th style="width: 100px; font-size: 1.2rem; text-align: center; line-height:0.9;">Shipment<br>Time</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$total_m3 = 0;
		if(!empty($modEx->detail_order)){
			$details = yii\helpers\Json::decode($modEx->detail_order);
			if(count($details)){
				foreach($details as $i => $detail){
					echo "<tr>";
					echo	"<td style='padding:3px; font-size:1.1rem; text-align:center;'>".($i+1)."</td>";
					echo	"<td style='padding:3px; font-size:1.1rem; text-align:left;'>".$detail['detail_description']."</td>";
					echo	"<td style='padding:3px; font-size:1.1rem; text-align:left;'>".$detail['detail_size']."</td>";
					echo	"<td style='padding:3px; font-size:1.1rem; text-align:right;'>".$detail['detail_volume']."</td>";
					echo	"<td style='padding:3px; font-size:1.1rem; text-align:center;'>".(isset($detail['detail_lot_code'])?$detail['detail_lot_code']:"-")."</td>";
					echo	"<td style='padding:3px; font-size:1.1rem; text-align:center;'>".(isset($detail['shipment_time'])?$detail['shipment_time']:"-")."</td>";
					echo "</tr>";
					$total_m3 += $detail['detail_volume'];
				}
			}
		}else{
			echo "<tr><td colspan='6' class='text-align-center'><i>Not Found</i></td></tr>";
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" style="width: 40px; font-size: 1.2rem; text-align: right; padding: 3px;">
				<span class="pull-left"> &nbsp; Qty : 
					<?php
					if(!empty($modEx->detail_qty)){
						$detailQty = yii\helpers\Json::decode($modEx->detail_qty);
						if(count($detailQty)>0){
							foreach($detailQty as $ii => $qty){
								echo (isset($qty['detail_vehicle_qty'])?$qty['detail_vehicle_qty']." X ":"-")." ";
								echo (isset($qty['detail_vehicle_type'])? ucfirst($qty['detail_vehicle_type']):"-")." ";
								echo (isset($qty['detail_vehicle_size'])?"(".$qty['detail_vehicle_size']." feet)":"-")." ";
								echo "<br>";
							}
						}
					}
					?>
				</span>
				Total &nbsp; 
			</td>
			<td style="text-align: right; padding: 3px;"><?= $total_m3 ?></td>
		</tr>
	</tfoot>
</table>