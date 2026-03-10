<?php 
	$modCompany = app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
?>
<table style="width: 100%" border="1">
	<tr>
		<td style="text-align: center">
			<h4 style="font-weight: 500;"><?= $modCompany->name ?></h4>
			<p>
				<?= $modCompany->alamat ?><br>
				<?= $modCompany->phone ?><br>
				<?= $modCompany->email ?>
			</p>
			<p>
			</p>
		</td>
	</tr>
	<tr>
		<td style="text-align: center">
			<h4 style="font-weight: 600;">Nama Laporan</h4>
			<p>Periode 21 Agustus 2017 sd 31 Sept 2017</p>
		</td>
	</tr>
</table>