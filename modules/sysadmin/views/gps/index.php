<html>
<head>
	<title>Javascript geo sample</title>
	<!-- <script src="geo-min.jsxxx" type="text/javascript" charset="utf-8"></script> -->
	<script>
	(function() {if (window.google && google.gears) {return;}var factory = null;if (typeof(GearsFactory)!= 'undefined') {factory = new GearsFactory();} else {try {factory = new ActiveXObject('Gears.Factory');if (factory.getBuildInfo().indexOf('ie_mobile') != -1){factory.privateSetGlobalObject(this);}} catch (e) {if ((typeof(navigator.mimeTypes) != 'undefined')&& navigator.mimeTypes["application/x-googlegears"]) {factory = document.createElement("object");factory.style.display = "none";factory.width = 0;factory.height = 0;factory.type = "application/x-googlegears";document.documentElement.appendChild(factory);if(factory && (typeof(factory.create) == 'undefined')) {factory = null;}}}}if (!factory) {return;}if (!window.google) {google = {};}if (!google.gears) {google.gears = {factory: factory};}})();
	var bb_success;var bb_error;var bb_blackberryTimeout_id=-1;function handleBlackBerryLocationTimeout(){if(bb_blackberryTimeout_id!=-1){bb_error({message:"Timeout error",code:3})}}function handleBlackBerryLocation(){clearTimeout(bb_blackberryTimeout_id);bb_blackberryTimeout_id=-1;if(bb_success&&bb_error){if(blackberry.location.latitude==0&&blackberry.location.longitude==0){bb_error({message:"Position unavailable",code:2})}else{var a=null;if(blackberry.location.timestamp){a=new Date(blackberry.location.timestamp)}bb_success({timestamp:a,coords:{latitude:blackberry.location.latitude,longitude:blackberry.location.longitude}})}bb_success=null;bb_error=null}}var geo_position_js=function(){var b={};var c=null;var a="undefined";b.getCurrentPosition=function(f,d,e){c.getCurrentPosition(f,d,e)};b.init=function(){try{if(typeof(geo_position_js_simulator)!=a){c=geo_position_js_simulator}else{if(typeof(bondi)!=a&&typeof(bondi.geolocation)!=a){c=bondi.geolocation}else{if(typeof(navigator.geolocation)!=a){c=navigator.geolocation;b.getCurrentPosition=function(h,e,g){function f(i){if(typeof(i.latitude)!=a){h({timestamp:i.timestamp,coords:{latitude:i.latitude,longitude:i.longitude}})}else{h(i)}}c.getCurrentPosition(f,e,g)}}else{if(typeof(window.blackberry)!=a&&blackberry.location.GPSSupported){if(typeof(blackberry.location.setAidMode)==a){return false}blackberry.location.setAidMode(2);b.getCurrentPosition=function(g,e,f){bb_success=g;bb_error=e;if(f.timeout){bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",f.timeout)}else{bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",60000)}blackberry.location.onLocationUpdate("handleBlackBerryLocation()");blackberry.location.refreshLocation()};c=blackberry.location}else{if(typeof(window.google)!=a&&typeof(google.gears)!=a){c=google.gears.factory.create("beta.geolocation")}else{if(typeof(Mojo)!=a&&typeof(Mojo.Service.Request)!="Mojo.Service.Request"){c=true;b.getCurrentPosition=function(g,e,f){parameters={};if(f){if(f.enableHighAccuracy&&f.enableHighAccuracy==true){parameters.accuracy=1}if(f.maximumAge){parameters.maximumAge=f.maximumAge}if(f.responseTime){if(f.responseTime<5){parameters.responseTime=1}else{if(f.responseTime<20){parameters.responseTime=2}else{parameters.timeout=3}}}}r=new Mojo.Service.Request("palm://com.palm.location",{method:"getCurrentPosition",parameters:parameters,onSuccess:function(h){g({timestamp:h.timestamp,coords:{latitude:h.latitude,longitude:h.longitude,heading:h.heading}})},onFailure:function(h){if(h.errorCode==1){e({code:3,message:"Timeout"})}else{if(h.errorCode==2){e({code:2,message:"Position Unavailable"})}else{e({code:0,message:"Unknown Error: webOS-code"+errorCode})}}}})}}else{if(typeof(device)!=a&&typeof(device.getServiceObject)!=a){c=device.getServiceObject("Service.Location","ILocation");b.getCurrentPosition=function(g,e,f){function i(l,k,j){if(k==4){e({message:"Position unavailable",code:2})}else{g({timestamp:null,coords:{latitude:j.ReturnValue.Latitude,longitude:j.ReturnValue.Longitude,altitude:j.ReturnValue.Altitude,heading:j.ReturnValue.Heading}})}}var h=new Object();h.LocationInformationClass="BasicLocationInformation";c.ILocation.GetLocation(h,i)}}}}}}}}}catch(d){if(typeof(console)!=a){console.log(d)}return false}return c!=null};return b}();	
	</script>
</head>	
<body>
	<?php
	isset($latitude) ? $latitude = $latitude : $latitude = "";
	isset($longitude) ? $longitude = $longitude : $longitude = "";
	// Function to get the client IP address
	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	$ipaddress = get_client_ip();

	/* @var $this yii\web\View */
	$this->title = '';
	app\assets\DatepickerAsset::register($this);
	app\assets\InputMaskAsset::register($this);
	?>

	<!-- BEGIN PAGE TITLE-->
	<h1 class="page-title"> <?php echo $this->title; ?></h1>
	<!-- END PAGE TITLE-->
	<!-- END PAGE HEADER-->
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet light bordered">
					<div class="row">
						<div class="col-md-12">
							<div class="portlet light bordered">
								<div class="portlet-body">	
									<div class="table-scrollable">
										<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 820px;">
											<thead style="background-color: #B2C4D3">
												<tr>
													<th style="text-align: center; width: 40px;"><?= Yii::t('app', 'No.'); ?></th>
													<th style="text-align: center; width: 120px; line-height: 1;"><?= Yii::t('app', 'Tanggal'); ?></th>
													<!-- <th style="text-align: center; width: 100px; line-height: 1;"><?= Yii::t('app', 'Latitude'); ?></th>
													<th style="text-align: center; width: 100px; line-height: 1;"><?= Yii::t('app', 'Longitude'); ?></th> -->
													<th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'Agent'); ?></th>
													<th style="text-align: center; width: 50px;"><?= Yii::t('app', 'View'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = 1;
												$sql_t_loc_all = "select * from t_loc order by id desc limit 10";
												$query_all_t_loc = Yii::$app->db->createCommand($sql_t_loc_all)->queryAll();

												foreach ($query_all_t_loc as $kolom) {
													$id = $kolom['id'];
													$datetime = $kolom['datetime'];
													$latitudes = $kolom['latitude'];
													$latitudes = $kolom['latitude'];
													$longitudes = $kolom['longitude'];
													$agents = $kolom['agent'];
												?>
												<tr>
													<td class="text-center"><?php echo $i;?></td>
													<td><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($datetime);?></td>
													<!-- <td><?php echo $latitudes;?></td>
													<td><?php echo $longitudes;?></td> -->
													<td><?php echo $agents;?></td>
													<td class="text-center" style="cursor: pointer;"><a onclick="showMap(<?php echo $latitudes.','.$longitudes;?>)"><i class="fa fa-eye"></i></a></td>
												</tr>
												<?php
													$i++;
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="aaa" style="display: none;">
				</div>
				<div class="row" id="xxx" style="display: none;">
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
		</div>

	<script>
		if(geo_position_js.init()){
			geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
		}
		else{
			alert("Functionality not available");
		}

		$.ajax({
			url : '<?= \yii\helpers\Url::toRoute(['/sysadmin/gps/showLoc']); ?>',
			method : "POST",
			data : {latitude:latitude,longitude:longitude},
		}).done(function(response) {
			$('#aaa').show();
			$('#xxx').hide();
			<?php
			echo 
			"$('#aaa').load('".\yii\helpers\Url::toRoute(['/sysadmin/gps/showLoc'])." div#bbb', {latitude:latitude, longitude:longitude}, function () {

			});";
			?>
		}).fail(function( jqXHR, textStatus ) {
			alert('gagal');
		});

		function showMap(latitude, longitude) {
			$.ajax({
				url : '<?= \yii\helpers\Url::toRoute(['/sysadmin/gps/showLoc']); ?>',
				method : "POST",
				data : {latitude:latitude,longitude:longitude},
			}).done(function(response) {
				$('#aaa').hide();
				$('#xxx').show();
				<?php
				echo 
				"$('#xxx').load('".\yii\helpers\Url::toRoute(['/sysadmin/gps/showLoc'])." div#yyy', {latitude:latitude, longitude:longitude}, function () {

				});";
				?>
			}).fail(function( jqXHR, textStatus ) {
				alert('gagal');
			});
		}
	</script>
	</body>
</html>