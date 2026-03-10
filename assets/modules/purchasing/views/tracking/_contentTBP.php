<div class="table-scrollable">
	<table class="table tracking table-striped table-bordered table-hover">
		<thead>
			<tr style="background-color: #F1F4F7; ">
				<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Kode'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'Supplier'); ?></th>
				<th style="text-align: center;"><?= Yii::t('app', 'No. Invoice'); ?></th>
				<th style="text-align: center;"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($models as $i => $model){ 
			$highlight = '';
			if( Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER ||
				Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADEP_FINNACC ||
				Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_STAFF_FINNACC ||
				Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADIV_FINNACC ){
				if(\app\models\MapTrackingpembelianChecklist::checkTrack($model->terimabhp_kode)){
					$highlight = 'background-color:  #F5FCC9;';
				}
			}		
			?>
			<tr style="<?= $highlight; ?>">
				<td style="text-align: center;"><?= $i+1; ?></td>
				<td><?= $model->terimabhp_kode; ?></td>
				<td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tglterima) ?></td>
				<td><?= !empty($model->suplier_id)?$model->suplier->suplier_nm:" - "; ?></td>
				<td style="text-align: center;"><?= !empty($model->nofaktur)?$model->nofaktur:"-"; ?></td>
				<td style="text-align: center;">
					<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoTBP(<?= $model->terima_bhp_id ?>)">
					<i class="fa fa-info-circle"></i>
					</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>