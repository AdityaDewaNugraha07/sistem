<tr style="background-color: #F1F4F7;">
	<th style="font-size: 1.2rem; width: 35px; position:absolute; left:0px; background-color: #F1F4F7;"><?= Yii::t('app', 'No.'); ?></th>
	<th style="font-size: 1.2rem; width: 300px; position:absolute; left:35px; background-color: #F1F4F7;"><?= Yii::t('app', 'Nama Produk') ?></th>
	<th style="font-size: 1.2rem; width: 50px; position:absolute; left:335px; line-height: 1; background-color: #F1F4F7;"><?= Yii::t('app', 'Lokasi<br>Gudang') ?></th>
	<th style="font-size: 1.2rem; width: 60px; position:absolute; left:385px; line-height: 1; background-color: #F1F4F7;"><?= Yii::t('app', 'Total<br>Palet') ?></th>
	<th style="font-size: 1.2rem; width: 85px; position:absolute; left:445px; line-height: 1; background-color: #F1F4F7;"><?= Yii::t('app', 'Total<br>Qty') ?></th>
	<th style="font-size: 1.2rem; width: 85px; position:absolute; left:530px; line-height: 1; background-color: #F1F4F7; border-right: 1px dotted #000"><?= Yii::t('app', 'Total<br>Kubikasi M<sup>3</sup>') ?></th>
	<?php if(isset($modHead)){ ?>
	<?php foreach($modHead as $i => $head){ ?>
	<th style="font-size: 1.1rem; width: 100px; border-right: solid 1px #999;" colspan="2">
		<?php
		$per = explode("-", $head['periode']);
		echo \app\components\DeltaFormatter::getMonthUser($per[1])." ";
		echo $per[0];
		?>
	</th>
	<?php } ?>
	<?php } ?>
</tr>
<tr>
	<th style="font-size: 1.2rem; width: 85px; position:absolute; left:530px; line-height: 1; border-right: 1px dotted #000"></th>
	<?php if(isset($modHead)){ ?>
	<?php foreach($modHead as $i => $head){ ?>
	<th style="font-size: 1rem; width: 50px; background-color: #F1F4F7; ">Palet</th>
	<th style="font-size: 1rem; width: 50px; background-color: #F1F4F7; border-right: solid 1px #999;">Pcs</th>
	<?php } ?>
	<?php } ?>
</tr>