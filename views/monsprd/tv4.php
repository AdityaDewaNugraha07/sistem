<!DOCTYPE html>
<html>
<head>
    <title>TV4</title>
    <link rel="shortcut icon" href="<?= yii\helpers\Url::base();?>/favicon.ico" />
</head>
<body style="margin:0">
    <iframe id="sheet" src="<?= $sheetUrl; ?>" style="width:100vw;height:100vh;border:5;"></iframe>
    
    <script>
        // reload setiap 2 menit
        var loadtime = 2 * 60000;
        setInterval(function(){
            var iframe = document.getElementById('sheet');
            iframe.src = iframe.src.split('?')[0] + '?t=' + new Date().getTime();
        }, loadtime);
    </script>
</body>
</html>