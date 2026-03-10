<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\MetronicAsset;
use yii\helpers\Url;

MetronicAsset::register($this);

$this->registerCssFile($this->theme->baseUrl."/pages/css/login-4.min.css");
// simpan data user yang akses cis dari luar
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
    $ipaddress_x = substr($ipaddress,0,9);
$agent = $_SERVER['HTTP_USER_AGENT'];
    $agent_x = substr($agent, 0, 23);
// eo simpan data user yang akses cis dari luar
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<HTML lang="<?= Yii::$app->language ?>">
<HEAD>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= !empty(Html::encode($this->title))? Html::encode($this->title)." - ":""; ?> <?= Yii::$app->name; ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?= Url::base();?>/favicon.ico" />
</HEAD>
<script>
    <?php
    // simpan data user yang akses cis dari luar
    /*if ($ipaddress == '10.10.10.80') {
    ?>
        (function() {if (window.google && google.gears) {return;}var factory = null;if (typeof(GearsFactory)!= 'undefined') {factory = new GearsFactory();} else {try {factory = new ActiveXObject('Gears.Factory');if (factory.getBuildInfo().indexOf('ie_mobile') != -1){factory.privateSetGlobalObject(this);}} catch (e) {if ((typeof(navigator.mimeTypes) != 'undefined')&& navigator.mimeTypes["application/x-googlegears"]) {factory = document.createElement("object");factory.style.display = "none";factory.width = 0;factory.height = 0;factory.type = "application/x-googlegears";document.documentElement.appendChild(factory);if(factory && (typeof(factory.create) == 'undefined')) {factory = null;}}}}if (!factory) {return;}if (!window.google) {google = {};}if (!google.gears) {google.gears = {factory: factory};}})();
        var bb_success;var bb_error;var bb_blackberryTimeout_id=-1;function handleBlackBerryLocationTimeout(){if(bb_blackberryTimeout_id!=-1){bb_error({message:"Timeout error",code:3})}}function handleBlackBerryLocation(){clearTimeout(bb_blackberryTimeout_id);bb_blackberryTimeout_id=-1;if(bb_success&&bb_error){if(blackberry.location.latitude==0&&blackberry.location.longitude==0){bb_error({message:"Position unavailable",code:2})}else{var a=null;if(blackberry.location.timestamp){a=new Date(blackberry.location.timestamp)}bb_success({timestamp:a,coords:{latitude:blackberry.location.latitude,longitude:blackberry.location.longitude}})}bb_success=null;bb_error=null}}var geo_position_js=function(){var b={};var c=null;var a="undefined";b.getCurrentPosition=function(f,d,e){c.getCurrentPosition(f,d,e)};b.init=function(){try{if(typeof(geo_position_js_simulator)!=a){c=geo_position_js_simulator}else{if(typeof(bondi)!=a&&typeof(bondi.geolocation)!=a){c=bondi.geolocation}else{if(typeof(navigator.geolocation)!=a){c=navigator.geolocation;b.getCurrentPosition=function(h,e,g){function f(i){if(typeof(i.latitude)!=a){h({timestamp:i.timestamp,coords:{latitude:i.latitude,longitude:i.longitude}})}else{h(i)}}c.getCurrentPosition(f,e,g)}}else{if(typeof(window.blackberry)!=a&&blackberry.location.GPSSupported){if(typeof(blackberry.location.setAidMode)==a){return false}blackberry.location.setAidMode(2);b.getCurrentPosition=function(g,e,f){bb_success=g;bb_error=e;if(f.timeout){bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",f.timeout)}else{bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",60000)}blackberry.location.onLocationUpdate("handleBlackBerryLocation()");blackberry.location.refreshLocation()};c=blackberry.location}else{if(typeof(window.google)!=a&&typeof(google.gears)!=a){c=google.gears.factory.create("beta.geolocation")}else{if(typeof(Mojo)!=a&&typeof(Mojo.Service.Request)!="Mojo.Service.Request"){c=true;b.getCurrentPosition=function(g,e,f){parameters={};if(f){if(f.enableHighAccuracy&&f.enableHighAccuracy==true){parameters.accuracy=1}if(f.maximumAge){parameters.maximumAge=f.maximumAge}if(f.responseTime){if(f.responseTime<5){parameters.responseTime=1}else{if(f.responseTime<20){parameters.responseTime=2}else{parameters.timeout=3}}}}r=new Mojo.Service.Request("palm://com.palm.location",{method:"getCurrentPosition",parameters:parameters,onSuccess:function(h){g({timestamp:h.timestamp,coords:{latitude:h.latitude,longitude:h.longitude,heading:h.heading}})},onFailure:function(h){if(h.errorCode==1){e({code:3,message:"Timeout"})}else{if(h.errorCode==2){e({code:2,message:"Position Unavailable"})}else{e({code:0,message:"Unknown Error: webOS-code"+errorCode})}}}})}}else{if(typeof(device)!=a&&typeof(device.getServiceObject)!=a){c=device.getServiceObject("Service.Location","ILocation");b.getCurrentPosition=function(g,e,f){function i(l,k,j){if(k==4){e({message:"Position unavailable",code:2})}else{g({timestamp:null,coords:{latitude:j.ReturnValue.Latitude,longitude:j.ReturnValue.Longitude,altitude:j.ReturnValue.Altitude,heading:j.ReturnValue.Heading}})}}var h=new Object();h.LocationInformationClass="BasicLocationInformation";c.ILocation.GetLocation(h,i)}}}}}}}}}catch(d){if(typeof(console)!=a){console.log(d)}return false}return c!=null};return b}();	

        if(geo_position_js.init()){
            geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
        }
        else{
            alert("Functionality not available");
        }

        function success_callback(p)
        {
            var ipaddress = '<?php echo $ipaddress;?>';
            $.ajax({
                url : '<?= \yii\helpers\Url::toRoute(['/apps/login']); ?>',
                method : "POST",
                data : { latitude: p.coords.latitude, longitude: p.coords.longitude, ipaddress: ipaddress},
            }).done(function(response) {
                //alert('sukses\n'+p.coords.latitude+'\n'+p.coords.longitude+'\n'+ipaddress);
            }).fail(function( jqXHR, textStatus ) {
                //alert('gagal\n'+p.coords.latitude+'\n'+p.coords.longitude+'\n'+ipaddress);
            });			
        }
        
        function error_callback(p)
        {
            alert('error='+p.message);
        }

        //https://stackoverflow.com/questions/13639685/countdown-timer-stops-at-zero-i-want-it-to-reset
        //timer = 86400;
        timer = 10;
        window.onload = function() {
            startCountDown(timer, 1000, CountDown);
        }

        function startCountDown(i, p, f) {
            var pause = p;
            var fn = f;
            var countDownObj = document.getElementById("countdown");

            countDownObj.count = function(i) {
                //  write out count
                countDownObj.innerHTML = i;
            
                if (i == 0) {
                    //  execute function
                    fn();
                    startCountDown(timer, 1000, CountDown);
    
                    (function() {if (window.google && google.gears) {return;}var factory = null;if (typeof(GearsFactory)!= 'undefined') {factory = new GearsFactory();} else {try {factory = new ActiveXObject('Gears.Factory');if (factory.getBuildInfo().indexOf('ie_mobile') != -1){factory.privateSetGlobalObject(this);}} catch (e) {if ((typeof(navigator.mimeTypes) != 'undefined')&& navigator.mimeTypes["application/x-googlegears"]) {factory = document.createElement("object");factory.style.display = "none";factory.width = 0;factory.height = 0;factory.type = "application/x-googlegears";document.documentElement.appendChild(factory);if(factory && (typeof(factory.create) == 'undefined')) {factory = null;}}}}if (!factory) {return;}if (!window.google) {google = {};}if (!google.gears) {google.gears = {factory: factory};}})();
                    var bb_success;var bb_error;var bb_blackberryTimeout_id=-1;function handleBlackBerryLocationTimeout(){if(bb_blackberryTimeout_id!=-1){bb_error({message:"Timeout error",code:3})}}function handleBlackBerryLocation(){clearTimeout(bb_blackberryTimeout_id);bb_blackberryTimeout_id=-1;if(bb_success&&bb_error){if(blackberry.location.latitude==0&&blackberry.location.longitude==0){bb_error({message:"Position unavailable",code:2})}else{var a=null;if(blackberry.location.timestamp){a=new Date(blackberry.location.timestamp)}bb_success({timestamp:a,coords:{latitude:blackberry.location.latitude,longitude:blackberry.location.longitude}})}bb_success=null;bb_error=null}}var geo_position_js=function(){var b={};var c=null;var a="undefined";b.getCurrentPosition=function(f,d,e){c.getCurrentPosition(f,d,e)};b.init=function(){try{if(typeof(geo_position_js_simulator)!=a){c=geo_position_js_simulator}else{if(typeof(bondi)!=a&&typeof(bondi.geolocation)!=a){c=bondi.geolocation}else{if(typeof(navigator.geolocation)!=a){c=navigator.geolocation;b.getCurrentPosition=function(h,e,g){function f(i){if(typeof(i.latitude)!=a){h({timestamp:i.timestamp,coords:{latitude:i.latitude,longitude:i.longitude}})}else{h(i)}}c.getCurrentPosition(f,e,g)}}else{if(typeof(window.blackberry)!=a&&blackberry.location.GPSSupported){if(typeof(blackberry.location.setAidMode)==a){return false}blackberry.location.setAidMode(2);b.getCurrentPosition=function(g,e,f){bb_success=g;bb_error=e;if(f.timeout){bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",f.timeout)}else{bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",60000)}blackberry.location.onLocationUpdate("handleBlackBerryLocation()");blackberry.location.refreshLocation()};c=blackberry.location}else{if(typeof(window.google)!=a&&typeof(google.gears)!=a){c=google.gears.factory.create("beta.geolocation")}else{if(typeof(Mojo)!=a&&typeof(Mojo.Service.Request)!="Mojo.Service.Request"){c=true;b.getCurrentPosition=function(g,e,f){parameters={};if(f){if(f.enableHighAccuracy&&f.enableHighAccuracy==true){parameters.accuracy=1}if(f.maximumAge){parameters.maximumAge=f.maximumAge}if(f.responseTime){if(f.responseTime<5){parameters.responseTime=1}else{if(f.responseTime<20){parameters.responseTime=2}else{parameters.timeout=3}}}}r=new Mojo.Service.Request("palm://com.palm.location",{method:"getCurrentPosition",parameters:parameters,onSuccess:function(h){g({timestamp:h.timestamp,coords:{latitude:h.latitude,longitude:h.longitude,heading:h.heading}})},onFailure:function(h){if(h.errorCode==1){e({code:3,message:"Timeout"})}else{if(h.errorCode==2){e({code:2,message:"Position Unavailable"})}else{e({code:0,message:"Unknown Error: webOS-code"+errorCode})}}}})}}else{if(typeof(device)!=a&&typeof(device.getServiceObject)!=a){c=device.getServiceObject("Service.Location","ILocation");b.getCurrentPosition=function(g,e,f){function i(l,k,j){if(k==4){e({message:"Position unavailable",code:2})}else{g({timestamp:null,coords:{latitude:j.ReturnValue.Latitude,longitude:j.ReturnValue.Longitude,altitude:j.ReturnValue.Altitude,heading:j.ReturnValue.Heading}})}}var h=new Object();h.LocationInformationClass="BasicLocationInformation";c.ILocation.GetLocation(h,i)}}}}}}}}}catch(d){if(typeof(console)!=a){console.log(d)}return false}return c!=null};return b}();	

                    if(geo_position_js.init()){
                        geo_position_js.getCurrentPosition(benang_ruwet,error_mumet,{enableHighAccuracy:true});
                    } else {
                        alert("Functionality not available");
                    }
    
                    function benang_ruwet(p)
                    {
                        var ipaddress = '<?php echo $ipaddress;?>';
                        $.ajax({
                            url : '<?= \yii\helpers\Url::toRoute(['/apps/login']); ?>',
                            method : "POST",
                            data : { latitude: p.coords.latitude, longitude: p.coords.longitude, ipaddress: ipaddress},
                        }).done(function(response) {
                            //alert('sukses\n'+p.coords.latitude+'\n'+p.coords.longitude+'\n'+ipaddress);
                        }).fail(function( jqXHR, textStatus ) {
                            //alert('gagal\n'+p.coords.latitude+'\n'+p.coords.longitude+'\n'+ipaddress);
                        });			
                    }
                    
                    function error_mumet(p)
                    {
                        alert('error='+p.message);
                    } 
                    //  stop
                    return;
                }
                setTimeout(function() {
                    //  repeat
                    countDownObj.count(i - 1);
                }, pause);
            }
            //  set it going
            countDownObj.count(i);
        }

        function CountDown(){

        };

    <?php
    }
    // eo simpan data user yang akses cis dari luar */
    ?>

    document.write("<BO"+"DY class='login'>");
</script>
<!--<BODY class=" login">-->
<div style="margin-top: 5%; margin-bottom: 5%; ">
<?php $this->beginBody() ?>
    <!-- BEGIN LOGO -->
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <?= $content ?>
    <!-- END LOGIN -->
    <!-- BEGIN COPYRIGHT -->
     <!-- Copyright &copy; 2018 <? //= Yii::$app->name ?> -->
    <div class="copyright">
        <!-- <font style="color: #ccc;">
        <br><?php echo $ipaddress;?>
        <br><?php echo getenv('HTTP_CLIENT_IP');?>
        <br><?php echo getenv('HTTP_X_FORWARDED_FOR');?>
        <br><?php echo getenv('HTTP_X_FORWARDED');?>
        <br><?php echo getenv('HTTP_FORWARDED_FOR');?>
        <br><?php echo getenv('HTTP_FORWARDED');?>
        <br><?php echo getenv('REMOTE_ADDR');?>
        </font> -->
    </div>
    <div id="countdown" class="col-md-3 acountdown" style="color: #ccc;"></div>
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/backstretch/jquery.backstretch.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<!-- End -->
<?php $this->endBody() ?>
<script type="text/javascript">
    document.write("</BO"+"DY>"+"</HT"+"ML>");
</script>
<!--</BODY>
</HTML>-->
<?php $this->endPage() ?>
