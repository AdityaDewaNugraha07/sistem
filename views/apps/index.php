<?php
/* @var $this yii\web\View */

use app\models\MPengumuman;
use app\models\TVideoTraining;
use app\models\TVideoTrainingPeserta;

$this->title = 'Home';
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Hi, Good People. <small style="color: #0c2d0e; font-weight: 500;">Welcome to Ciptana Integrated Systems</small></h1>
<!-- END PAGE TITLE-->
<!-- BEGIN PAGE BAR -->
<!-- END PAGE BAR -->
<!-- END PAGE HEADER-->
<!--<div class="note note-info">
    <p> Anda login sebagai <b><?php // echo Yii::$app->user->identity->userGroup->name 
								?></b></p>
</div>-->
<!--<iframe style="width: 95%; height: 400px" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vRCE8Akh0t3mgcsQm6WzGDCBc_dcwtUM7lFcN1_NOy5zxronOxk94vc6pKorDMELRtCuy_tgOuI8yu3/pubhtml?gid=1873011153&amp;single=true&amp;widget=true&amp;headers=false"></iframe>-->
<div class="row">
	<div class="col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<?php
				$no_training = true;
				//                echo Yii::$app->user->identity->pegawai->pegawai_id;
				//                $modVideoTraining = TVideoTraining::find()
				//                    ->innerJoin('t_video_training_peserta', 't_video_training.video_training_id=t_video_training_peserta.video_training_id')
				//                    ->where(['<=', 'tgl_awal', date('Y-m-d')])
				//                    ->andWhere(['>=', 'tgl_akhir', date('Y-m-d')])
				//                    ->andWhere(['t_video_training_peserta.pegawai_id' => Yii::$app->user->identity->pegawai->pegawai_id])
				//                    ->one();
				$modVideoTraining = TVideoTraining::find()->with([
					'peserta' => function ($query) {
						$query->andWhere(['pegawai_id' => Yii::$app->user->identity->pegawai->pegawai_id]);
					}
				])
					->where(['<=', 'tgl_awal', date('Y-m-d')])
					->andWhere(['>=', 'tgl_akhir', date('Y-m-d')])
					->all();
				//                echo "<pre>";
				//                print_r($modVideoTraining);
				if (!empty($modVideoTraining)) {
					foreach ($modVideoTraining as $video) {
						if ($video->relatedRecords['peserta']) {
							$modVideoTraining = $video;
							$no_training = false;
						}
					}

					if ($no_training === false) {
						echo $this->render('@app/modules/hrd/views/videotraining/homepage', compact('modVideoTraining'));
					}
				} ?>
				<?php
				if ($no_training) {
					$modPengumuman = MPengumuman::find()->where("active = TRUE")->orderBy(['seq' => SORT_ASC])->all();
					if (count($modPengumuman) > 0) {
						foreach ($modPengumuman as $i => $pengumuman) { ?>
							<div class="note note-<?= $pengumuman->tipe ?>">
								<h4 class="block" style="font-weight: 500;">
									<span class="<?= ($pengumuman->judul_pulsate == TRUE) ? 'judul-pengumuman' : '' ?>"><?= $pengumuman->judul ?><span>
								</h4>
								<p style="text-align: justify"> <?= $pengumuman->deskripsi; ?></p>
							</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject bold">
						<h4>Shortcuts</h4>
					</span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload" data-original-title="" title=""> </a>
					<a href="javascript:;" class="fullscreen" data-original-title="" title=""> </a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="tiles">
					<div class="tile image double" onclick="gotoopenticket()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/open-tickets.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Open Tickets</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotoexportalert()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/export-alert.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Alert System</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotodocalert()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/doc-alert.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Documents Alert</h5>
							</div>
						</div>
					</div>
					<!--					<div class="tile image double" onclick="gotopkwt()" style="width: 140px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php // echo \Yii::$app->view->theme->baseUrl; 
										?>/pages/media/gallery/pkwt.jpg" alt="" style="opacity: 0.5"> </div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;"> <h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">PKWT</h5> </div>
						</div>
					</div>
					<div class="tile image double" onclick="gotogajiborong()" style="width: 140px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php // echo \Yii::$app->view->theme->baseUrl; 
										?>/pages/media/gallery/gaji-borong.jpg" alt="" style="opacity: 0.5"> </div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;"> <h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Karyawan Borong</h5> </div>
						</div>
					</div>-->
					<div class="tile image double" onclick="gotoreportbhp()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/reportbhp.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Report</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotokpi()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/kpi.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">KPI</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotoasset()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/asset.jpg" alt="" style="opacity: 0.2">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Fixed Assets</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotodinasluar()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/perjalanan-dinas.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Dinas Luar</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotoncr()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/ncr.png" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">NCR</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotoccr()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/ccr.png" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">CCR</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotonotulen()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/notulen.png" alt="" style="opacity: 0.2">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Notulen</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotoproject()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/projectmanagement.jpg" alt="" style="opacity: 0.3">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Project Management</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotomop()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/mop.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Monitoring Hasil Produksi</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotosatpam()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/pos_satpam.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">S A T P A M</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotojamuan()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/jamuan.jpg" alt="" style="opacity: 0.5">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Jamuan Makan</h5>
							</div>
						</div>
					</div>
					<div class="tile image double" onclick="gotocistoolkit()" style="width: 130px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/toolkit.jpg" alt="" style="opacity: 0.3">
						</div>
						<div class="tile-object text-align-left">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;">
								<h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">CIS Toolkit</h5>
							</div>
						</div>
					</div>
					<?php
					/*$user_id = $_SESSION["__id"];
					$sql_nama_jabatan = "select name from view_user_opt where user_id = '$user_id' ";
					$nama_jabatan = Yii::$app->db->createCommand($sql_nama_jabatan)->queryScalar();
					if ($nama_jabatan == "Super User" || $nama_jabatan == "Kadiv Marketing" || $nama_jabatan == "Kadep Marketing") {
					?>					
					<div class="tile image double" onclick="goToFindDevice()" style="width: 140px !important;  height: 80px !important;">
						<div class="tile-body">
							<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/pages/media/gallery/find_device.png" alt="" style="opacity: 0.5"> </div>
						<div class="tile-object text-align-center">
							<div class="name font-red-thunderbird" style="margin-bottom: 0px;"> <h5 style="font-weight: 600; color: #000; text-shadow: 4px 3px 2px rgb(100, 100, 100)">Find Device</h5> </div>
						</div>
					</div>
					<?php
                    }*/
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->registerJsFile($this->theme->baseUrl . "/global/plugins/jquery.pulsate.min.js", ['depends' => [yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
	$('.judul-pengumuman').each(function(){
		$(this).pulsate({ color: '#bf1c56' });
	});
	if($('.note').find('img')){
		$('.note').find('img').each(function(){
			$(this).attr('style','max-width: 100%; height: auto; width: auto;');
		})
	}
", yii\web\View::POS_READY); ?>

<script>
	function gotoopenticket() {
		window.open('/openticket/', '_blank');
	}

	function gotoexportalert() {
		window.open('/export-alert/', '_blank');
	}

	function gotodocalert() {
		window.open('/alert/', '_blank');
	}

	function gotopkwt() {
		window.open('/pkwt/', '_blank');
	}

	function gotogajiborong() {
		window.open('/gaji-borong/', '_blank');
	}

	function gotoreportbhp() {
		window.open('/report/', '_blank');
	}

	function gotokpi() {
		window.open('/kpi/', '_blank');
	}

	function gotoasset() {
		window.open('/asset/', '_blank');
	}

	function gotodinasluar() {
		window.open('/dinasluar/', '_blank');
	}

	function gotoncr() {
		window.open('/ncr/', '_blank');
	}

	function gotoccr() {
		window.open('/ccr/', '_blank');
	}

	function gotonotulen() {
		window.open('/notulen/', '_blank');
	}

	function gotoproject() {
		window.open('/project/', '_blank');
	}

	function gotomop() {
		window.open('/mop/', '_blank');
	}

	function gotosatpam() {
		window.open('/satpam/', '_blank');
	}

	function gotojamuan() {
		window.open('/jamuan/', '_blank');
	}

	function goToFindDevice() {
		window.open('/find-device/', '_blank');
	}

	function gotocistoolkit() {
		window.open('http://tm.ciptana.co.id:296/toolkit/', '__blank');
	}
</script>