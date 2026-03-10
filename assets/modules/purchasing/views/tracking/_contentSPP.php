<div class="table-scrollable">
	<table class="table tracking table-striped table-bordered table-hover">
		<thead>
			<tr style="background-color: #F1F4F7; ">
				<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Kode SPP'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Origin'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Status'); ?></th>
				<th style="text-align: center;"></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($models as $i => $model){ 
				$modSpp = app\models\TSpp::findOne($model['spp_id']);
				$highlight = '';
				if( Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER ||
					Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADEP_FINNACC ||
					Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_STAFF_FINNACC ||
					Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADIV_FINNACC ){
					if(\app\models\MapTrackingpembelianChecklist::checkTrack($modSpp->spp_kode)){
						$highlight = 'background-color:  #F5FCC9;';
					}
				}
			?>
			<tr style="<?= $highlight; ?>">
				<td style="text-align: center;"><?= $i+1; ?></td>
				<td><?= $modSpp->spp_kode; ?></td>
				<td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($modSpp->spp_tanggal) ?></td>
				<td style="text-align: center;"><?= !empty($modSpp->departement_id)?$modSpp->departement->departement_nama:" - "; ?></td>
				<td style="text-align: center;"><?= $modSpp->spp_status ?></td>
				<td style="text-align: center;">
					<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoSPP(<?= $modSpp->spp_id ?>)">
					<i class="fa fa-info-circle"></i>
					</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>