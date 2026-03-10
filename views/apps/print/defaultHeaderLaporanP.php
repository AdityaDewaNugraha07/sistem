<?php 
	$modCompany = app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
?>
<table style="width: 100%; margin-left: -10px;" border="0">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="border-bottom: 1px solid black;">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 2cm; height: 1cm; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3; width: 20cm;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?php echo (isset($paramprint['judul2'])?$paramprint['judul2']:"") ?>
					</td>                 
                </tr>
                <tr>
					<td colspan="2" style="text-align: right; vertical-align: bottom; font-size: 1rem;">
						<?php
						if(isset($_GET['caraprint'])){
							if($_GET['caraprint'] == 'PRINT'){
								echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
								echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
							}
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>