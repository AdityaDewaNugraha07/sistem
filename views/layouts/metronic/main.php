<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\MetronicAsset;
use yii\helpers\Url;

MetronicAsset::register($this);

$this->registerCssFile($this->theme->baseUrl . "/layouts/layout/css/layout.min.css", ['depends' => [yii\web\YiiAsset::className()]]);
$this->registerCssFile($this->theme->baseUrl . "/layouts/layout/css/themes/ciptana.min.css", ['depends' => [yii\web\YiiAsset::className()]]);

$user_id = $_SESSION["__id"];

// cek m_user_token cuy
if (isset($_SESSION['sess_username']) && isset($_SESSION['token'])) {
    $username = str_replace("'", "''", $_SESSION['sess_username']);
    $current_token = $_SESSION['token'];
    $sql = "select token from m_user_token where username = '" . $username . "' ";
    $db_token = Yii::$app->db->createCommand($sql)->queryScalar();
    if ($current_token != $db_token) {
?>
        <script>
            var current_token = '<?php echo $current_token; ?>';
            var db_token = '<?php echo $db_token; ?>';
            if (current_token != db_token) {
                alert('Anda telah login pada perangkat yang lain, halaman ini akan segera ditutup.');
                window.location.href = "/cis3/web/apps/logout";
            }
        </script>
<?php
        session_destroy();
    }
} else {
    session_destroy();
}
// eo cek m_user_token cuy
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<HTML lang="<?php echo Yii::$app->language ?>">

<HEAD>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= !empty(Html::encode($this->title)) ? Html::encode($this->title) . " - " : ""; ?> <?= Yii::$app->name; ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?= Url::base(); ?>/favicon.ico" />
</HEAD>
<!-- BEGIN BODY -->
<?php
if (isset($_COOKIE['sidebar_closed'])) {
    $sidebar_closed = ($_COOKIE['sidebar_closed'] == '1') ? true : false;
} else {
    $sidebar_closed = false;
}
?>
<script>
    document.write("<BO" + "DY class='page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid <?= ($sidebar_closed) ? 'page-sidebar-closed' : '' ?>'>");

    // counter auto logout
    // var upgradeTime = 172800;        // 48 jam dalam detik 
    // var upgradeTime = 86400;         // 24 jam dalam detik 
    // var upgradeTime = 28800;         // 8 jam dalam detik 
    /*var upgradeTime = 3;              // 3 detik
    var seconds = upgradeTime;
    function timer() {
        var days        = Math.floor(seconds/24/60/60);
        var hoursLeft   = Math.floor((seconds) - (days*86400));
        var hours       = Math.floor(hoursLeft/3600);
        var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
        var minutes     = Math.floor(minutesLeft/60);
        var remainingSeconds = seconds % 60;

        function pad(n) {
            return (n < 10 ? "0" + n : n);
        }
        document.getElementById('countdown').innerHTML = pad(days) + ":" + pad(hours) + ":" + pad(minutes) + ":" + pad(remainingSeconds);
        if (seconds == 0) {
            clearInterval(countdownTimer);
            //document.getElementById('xxx').innerHTML = 'Done';
            return false;
        } else {
            seconds--;
        }
    }
    var countdownTimer = setInterval('timer()', 1000);
    */

    <?php
    // NOTIFIKASI APPROVAL UNTUK TOP MANAGEMENT
    $m_pegawai = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
    $pegawai_id = $m_pegawai->pegawai_id;
    $user_group_id = $m_pegawai->user_group_id;
    $approver = 0;
    $jumlah_t_approval = 0;

    // agreement
    $sql_agreement = "select count(assigned_to) as assigned_to from t_agreement where assigned_to = " . $pegawai_id . " and left(reff_no,3) <> 'MOP' ";
    $agreement = Yii::$app->db->createCommand($sql_agreement)->queryScalar();
    // approval
    $sql_approvers = "select count(assigned_to) as assigned_to from t_approval where assigned_to = " . $pegawai_id . "";
    $approverJml = Yii::$app->db->createCommand($sql_approvers)->queryScalar();

    $approver = $agreement + $approverJml;

    // agreemenet
    $t_agreement = \app\models\TAgreement::find()
        ->where(['assigned_to' => $pegawai_id])
        ->andWhere(['status' => 'Not Confirmed'])
        ->andWhere(['not like', 'reff_no', 'MOP'])
        // ->orderBy('tanggal_berkas DESC')
        ->all();
    $jumlah_t_agreement = count($t_agreement);
    // approval
    $t_approval = \app\models\TApproval::find()
        ->where(['assigned_to' => $pegawai_id])
        ->andWhere(['status' => 'Not Confirmed'])
        ->andWhere(['>=', 'tanggal_berkas', '2020-01-01'])
        // ->orderBy('tanggal_berkas DESC')
        ->all();
    $jumlah_t_approvalJml = count($t_approval);

    // kondisikan notif untuk masing-masing proses pada NCR 2024-10-22
    // controlNcr
    $sq_controlNcr = "select count(ncr_id) as jmlcontrol from t_ncr 
                    where status_control = 'f' and diketahui2 > 0 
                        and exists (select ncr_pic_control from m_ncr_pic_control where m_ncr_pic_control.ncr_pic_control = " . $pegawai_id . ")";
    $sql_controlNcr = Yii::$app->db->createCommand($sq_controlNcr)->queryScalar();

    // analisaNcr
    $sq_analisaNcr  = "select count(ncr_id) as jmlanalisa from t_ncr 
                    where status_control = 't' and status_analisa = 'f' and diketahui2 > 0
                            and exists (select ncr_pic_analisa from m_ncr_pic_analisa where m_ncr_pic_analisa.ncr_pic_analisa = " . $pegawai_id . ") ";
    $sql_analisaNcr = Yii::$app->db->createCommand($sq_analisaNcr)->queryScalar();

    // penangananNcr
    $sq_penangananNcr  = "select count(ncr_detail_id) as jmltindakan from view_ncr_detail 
                        where not exists (select * from t_ncr_perbaikan where t_ncr_perbaikan.ncr_detail_id=view_ncr_detail.ncr_detail_id) 
                            and status_approve = 1 and ncr_tindakan_pic = " . $pegawai_id . " ";
    $sql_penangananNcr = Yii::$app->db->createCommand($sq_penangananNcr)->queryScalar();

    // verifipenangananNcr
    $sq_verifipenangananNcr = "select count(status_verifikator) as jmlverifikasi
                            from view_ncr_perbaikan 
                            where ncr_verifikator_pic > 0 and status_verifikator = 'f' and ncr_verifikator_pic = " . $pegawai_id . " ";
    $sql_verifipenangananNcr = Yii::$app->db->createCommand($sq_verifipenangananNcr)->queryScalar();

    // statusncr_efektifitas
    $periodeEfektifitas = 1; // kebijakan sebelumnya 90 hari, per tanggal 12 maret 2024 1 hari atau 1 x 24 jam
    $sq_statusncr_efektifitas = "SELECT 
                                    COUNT(view_ncr.ncr_efektifitas) AS jmlefektifitas
                                FROM 
                                    view_ncr
                                WHERE 
                                    view_ncr.ncr_tgl >= '2024-03-14'
                                    AND view_ncr.ncr_status = 'f'
                                    AND view_ncr.ncr_efektifitas_tgl IS NULL
                                    AND view_ncr.diketahui2 IS NOT NULL
                                    AND view_ncr.ncr_status_tgl<=(current_date - INTERVAL '{$periodeEfektifitas} day')
                                    AND view_ncr.pic_approve_efektifitas ILIKE '%$pegawai_id%'
                                    AND NOT EXISTS (
                                        SELECT 1
                                        FROM t_ncr,json_array_elements(view_ncr.status_efektifitas_reason) AS reason
                                        WHERE reason->>'id' = '$pegawai_id' and t_ncr.ncr_id = view_ncr.ncr_id
                                    ) ";
    $sql_statusncr_efektifitas = Yii::$app->db->createCommand($sq_statusncr_efektifitas)->queryScalar();

    // kondisikan notif untuk masing-masing proses pada Open Tiket
    // penangananBap
    $sq_penangananBap = "select count(bap_tindakan_pic) from view_bap_detail 
                        where bap_tindakan_pic = " . $pegawai_id . "
                                and not exists (
                                        select * from t_bap_perbaikan where t_bap_perbaikan.bap_detail_id=view_bap_detail.bap_detail_id 
                                )";
    $sql_penangananBap = Yii::$app->db->createCommand($sq_penangananBap)->queryScalar();

    // kondisikan notif untuk masing-masing proses pada CCR
    // penangananccr
    $sq_penangananccr = "select count(ccr_tindakan_pic) as jumlah_ccr
                        from view_ccr_detail 
                        where ccr_tindakan_pic = " . $pegawai_id . "
                                and status_approve = 1
                                and not exists (
                                        select * from t_ccr_perbaikan where t_ccr_perbaikan.ccr_detail_id=view_ccr_detail.ccr_detail_id 
                                )";
    $sql_penangananccr = Yii::$app->db->createCommand($sq_penangananccr)->queryScalar();

    // grand total notifikasi
    $jumlah_t_approval = $jumlah_t_agreement + $jumlah_t_approvalJml + $sql_controlNcr + $sql_analisaNcr + $sql_penangananNcr + $sql_verifipenangananNcr + $sql_statusncr_efektifitas + $sql_penangananBap + $sql_penangananccr;

    // JIKA TOP MANAGEMENT
    if ($jumlah_t_approval > 0) {
    ?>
        //https://stackoverflow.com/questions/13639685/countdown-timer-stops-at-zero-i-want-it-to-reset
        timer = 60;
        window.onload = function() {
            startCountDown(timer, 1000, CountDown);

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            var user_id = '<?php echo $user_id; ?>';
            $.ajax({
                url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                type: 'POST',
                data: user_id,
                success: function(data) {
                    if (data) {
                        $(data).each(function() {
                            $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                user_id
                            }, function() {
                                var jumlah_t_approval = $('#ajax_counter_t_approval').text();
                                if (jumlah_t_approval > 0) {
                                    $("#counter").shake();
                                }
                            });
                        });
                    } else {

                    }
                },
                complete: function() {},
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
        }

        function startCountDown(i, p, f) {
            var pause = p;
            var fn = f;
            var countDownObj = document.getElementById("countdown");
            var jumlah_t_approval = $('#ajax_counter_t_approval').text();

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            countDownObj.count = function(i) {
                //  write out count
                countDownObj.innerHTML = i;
                if (i == 0) {
                    if (jumlah_t_approval > 0) {
                        $("#counter").shake();
                    }
                    //  execute function
                    fn();
                    startCountDown(timer, 1000, CountDown);
                    var user_id = '<?php echo $user_id; ?>';
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                        type: 'POST',
                        data: user_id,
                        success: function(data) {
                            if (data) {
                                $(data).each(function() {
                                    $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                        user_id
                                    }, function() {

                                    });
                                });
                            } else {

                            }
                        },
                        complete: function() {},
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
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

        function CountDown() {

        };
    <?php
        // jika bukan top management
    } else {
    ?>

        function startCountDown(i, p, f) {

        }

        function CountDown() {

        };
    <?php
    }
    // EO NOTIFIKASI APPROVAL UNTUK TOP MANAGEMENT

    // NOTIFIKASI CETAK SPM UNTUK MARKETING
    // denny 20, agus 21, lingga 93, lingga2 352, kampret 359
    // kadiv mkt 8, staff mkt 9, gm mkt 74, adm mkt 77
    $m_pegawai = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
    $pegawai_id = $m_pegawai->pegawai_id;
    $user_group_id = $m_pegawai->user_group_id;
    //if ($user_id == 20 || $user_id == 21 || $user_id == 93 || $user_id == 352 || $user_id == 359) {
    if ($user_group_id == 9 || $user_group_id == 77) { //$user_group_id == 8 || $user_group_id == 74 || 
    ?>
        //https://stackoverflow.com/questions/13639685/countdown-timer-stops-at-zero-i-want-it-to-reset
        timer = 60;
        window.onload = function() {
            startCountDown(timer, 1000, CountDown);

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            var user_id = '<?php echo $user_id; ?>';
            $.ajax({
                url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reloadSpm']); ?>',
                type: 'POST',
                data: user_id,
                success: function(data) {
                    if (data) {
                        $(data).each(function() {
                            $('#counterspm').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadSpmShow']); ?> div#ajax_counter_t_spm', {
                                user_id
                            }, function() {
                                var jumlah_t_spm = $('#ajax_counter_t_spm').text();
                                if (jumlah_t_spm > 0) {
                                    $("#counterspm").shake();
                                }
                            });
                        });
                    } else {

                    }
                },
                complete: function() {},
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });

            $.ajax({
                url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                type: 'POST',
                data: user_id,
                success: function(data) {
                    if (data) {
                        $(data).each(function() {
                            $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                user_id
                            }, function() {
                                var jumlah_t_approval = $('#ajax_counter_t_approval').text();
                                if (jumlah_t_approval > 0) {
                                    $("#counter").shake();
                                }
                            });
                        });
                    } else {

                    }
                },
                complete: function() {},
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
        }

        function startCountDown(i, p, f) {
            var pause = p;
            var fn = f;
            var countDownObj = document.getElementById("countdown");
            var jumlah_t_spm = $('#ajax_counter_t_spm').text();

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            countDownObj.count = function(i) {
                //  write out count
                countDownObj.innerHTML = i;
                if (i == 0) {
                    if (jumlah_t_spm > 0) {
                        $("#counter").shake();
                    }
                    //  execute function
                    fn();
                    startCountDown(timer, 1000, CountDown);
                    var user_id = '<?php echo $user_id; ?>';
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reloadSpm']); ?>',
                        type: 'POST',
                        data: user_id,
                        success: function(data) {
                            if (data) {
                                $(data).each(function() {
                                    $('#counterspm').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadSpmShow']); ?> div#ajax_counter_t_spm', {
                                        user_id
                                    }, function() {

                                    });
                                });
                            } else {

                            }
                        },
                        complete: function() {},
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                        type: 'POST',
                        data: user_id,
                        success: function(data) {
                            if (data) {
                                $(data).each(function() {
                                    $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                        user_id
                                    }, function() {

                                    });
                                });
                            } else {

                            }
                        },
                        complete: function() {},
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
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

        function CountDown() {

        };
    <?php
        // jika bukan admin sales / superuser
    } else {
    ?>

        function startCountDown(i, p, f) {

        }

        function CountDown() {

        };
    <?php
    }
    ?>
    // EO NOTIFIKASI CETAK SPM UNTUK MARKETING

    <?php
    // NOTIFIKASI NOTA PENJUALAN UNTUK TUK
    $m_pegawai = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
    $pegawai_id = $m_pegawai->pegawai_id;
    $user_group_id = $m_pegawai->user_group_id;
    // denny 20, agus 21, staff_tuk 27, kampret 359
    if ($user_group_id == 27) {
    ?>
        //https://stackoverflow.com/questions/13639685/countdown-timer-stops-at-zero-i-want-it-to-reset
        timer = 60;
        window.onload = function() {
            startCountDown(timer, 1000, CountDown);

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            var user_id = '<?php echo $user_id; ?>';
            $.ajax({
                url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reloadTuk']); ?>',
                type: 'POST',
                data: user_id,
                success: function(data) {
                    if (data) {
                        $(data).each(function() {
                            $('#counterspm').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadTukShow']); ?> div#ajax_counter_tuk', {
                                user_id
                            }, function() {
                                var jumlah_tuk = $('#ajax_counter_tuk').text();
                                if (jumlah_tuk > 0) {
                                    $("#counterspm").shake();
                                }
                            });
                        });
                    } else {

                    }
                },
                complete: function() {},
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
            $.ajax({
                url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                type: 'POST',
                data: user_id,
                success: function(data) {
                    if (data) {
                        $(data).each(function() {
                            $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                user_id
                            }, function() {
                                var jumlah_t_approval = $('#ajax_counter_t_approval').text();
                                if (jumlah_t_approval > 0) {
                                    $("#counter").shake();
                                }
                            });
                        });
                    } else {

                    }
                },
                complete: function() {},
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
        }

        function startCountDown(i, p, f) {
            var pause = p;
            var fn = f;
            var countDownObj = document.getElementById("countdown");
            var jumlah_tuk = $('#ajax_counter_tuk').text();

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            countDownObj.count = function(i) {
                //  write out count
                countDownObj.innerHTML = i;
                if (i == 0) {
                    if (jumlah_tuk > 0) {
                        $("#counter").shake();
                    }
                    //  execute function
                    fn();
                    startCountDown(timer, 1000, CountDown);
                    var user_id = '<?php echo $user_id; ?>';
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reloadTuk']); ?>',
                        type: 'POST',
                        data: user_id,
                        success: function(data) {
                            if (data) {
                                $(data).each(function() {
                                    $('#counterspm').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadTukShow']); ?> div#ajax_counter_tuk', {
                                        user_id
                                    }, function() {

                                    });
                                });
                            } else {

                            }
                        },
                        complete: function() {},
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                        type: 'POST',
                        data: user_id,
                        success: function(data) {
                            if (data) {
                                $(data).each(function() {
                                    $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                        user_id
                                    }, function() {

                                    });
                                });
                            } else {

                            }
                        },
                        complete: function() {},
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
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

        function CountDown() {

        };
    <?php
        // jika bukan tuk / superuser
    } else {
    ?>

        function startCountDown(i, p, f) {

        }

        function CountDown() {

        };
    <?php
    }
    // EO NOTIFIKASI NOTA PENJUALAN UNTUK TUK
    ?>


    <?php
    // NOTIFIKASI OP EXPORT - PACKINGLIST
    $m_pegawai = \app\models\MUser::find()->where(['user_id' => $user_id])->one();
    $pegawai_id = $m_pegawai->pegawai_id;
    //ppic , tina(98), ning (5)
    if ($user_group_id == 103  && $pegawai_id == 5 || $pegawai_id == 98) {
    ?>
        //https://stackoverflow.com/questions/13639685/countdown-timer-stops-at-zero-i-want-it-to-reset
        timer = 60;
        window.onload = function() {
            startCountDown(timer, 1000, CountDown);

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            var user_id = '<?php echo $user_id; ?>';
            $.ajax({
                url: '<?php echo \yii\helpers\Url::toRoute(['/apps/ReloadOpexportPackinglist']); ?>',
                type: 'POST',
                data: user_id,
                success: function(data) {
                    if (data) {
                        $(data).each(function() {
                            $('#counterspm').load('<?php echo \yii\helpers\Url::toRoute(['/apps/ReloadOpexportPackinglistShow']); ?> div#ajax_counter_opexport', {
                                user_id
                            }, function() {
                                var jumlah_tuk = $('#ajax_counter_opexport').text();
                                if (jumlah_tuk > 0) {
                                    $("#counterspm").shake();
                                }
                            });
                        });
                    } else {

                    }
                },
                complete: function() {},
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
            $.ajax({
                url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                type: 'POST',
                data: user_id,
                success: function(data) {
                    if (data) {
                        $(data).each(function() {
                            $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                user_id
                            }, function() {
                                var jumlah_t_approval = $('#ajax_counter_t_approval').text();
                                if (jumlah_t_approval > 0) {
                                    $("#counter").shake();
                                }
                            });
                        });
                    } else {

                    }
                },
                complete: function() {},
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
        }

        function startCountDown(i, p, f) {
            var pause = p;
            var fn = f;
            var countDownObj = document.getElementById("countdown");
            var jumlah_tuk = $('#ajax_counter_opexport').text();

            jQuery.fn.shake = function() {
                this.each(function(i) {
                    $(this).css({
                        "position": "absolute"
                    });
                    for (var x = 1; x <= 10; x++) {
                        $(this).animate({
                            left: 5
                        }, 10).animate({
                            right: 5
                        }, 10).animate({
                            left: 10
                        }, 10).animate({
                            right: 10
                        }, 10).animate({
                            left: 5
                        }, 11).animate({
                            right: 5
                        }, 15).animate({
                            left: 10
                        }, 15).animate({
                            right: 10
                        }, 15).animate({
                            left: 5
                        }, 15).animate({
                            right: 5
                        });
                    }
                });
                return this;
            }

            countDownObj.count = function(i) {
                //  write out count
                countDownObj.innerHTML = i;
                if (i == 0) {
                    if (jumlah_tuk > 0) {
                        $("#counter").shake();
                    }
                    //  execute function
                    fn();
                    startCountDown(timer, 1000, CountDown);
                    var user_id = '<?php echo $user_id; ?>';
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/apps/ReloadOpexportPackinglist']); ?>',
                        type: 'POST',
                        data: user_id,
                        success: function(data) {
                            if (data) {
                                $(data).each(function() {
                                    $('#counterspm').load('<?php echo \yii\helpers\Url::toRoute(['/apps/ReloadOpexportPackinglistShow']); ?> div#ajax_counter_opexport', {
                                        user_id
                                    }, function() {

                                    });
                                });
                            } else {

                            }
                        },
                        complete: function() {},
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/apps/reload']); ?>',
                        type: 'POST',
                        data: user_id,
                        success: function(data) {
                            if (data) {
                                $(data).each(function() {
                                    $('#counter').load('<?php echo \yii\helpers\Url::toRoute(['/apps/reloadShow']); ?> div#ajax_counter_t_approval', {
                                        user_id
                                    }, function() {

                                    });
                                });
                            } else {

                            }
                        },
                        complete: function() {},
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
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

        function CountDown() {

        };
    <?php
        // jika bukan ppic / superuser
    } else {
    ?>

        function startCountDown(i, p, f) {

        }

        function CountDown() {

        };
    <?php
    }
    // EO NOTIFIKASI OP EXPORT - PACKINGLIST
    ?>
</script>

<!--<BODY class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid <?php // echo ($sidebar_closed)?'page-sidebar-closed':'' 
                                                                                            ?>">-->
<?php $this->beginBody() ?>
<div class="page-wrapper">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?= Url::base(); ?>">
                    <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo.png" alt="" class="logo-default" />
                    <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-alt.png" alt="" class="logo-default2" />
                </a>
                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div>
            </div>
            <!-- END LOGO -->

            <!-- counter notif -->
            <div id="countdown" class="col-md-3 acountdown" style="color: #A6C054; position: absolute; left:40px; bottom: 0px; z-index: 10000;"></div>
            <?php
            /* LIMIT USER ACCESS BASED ON USER_ACCESS
                $real_url = Yii::$app->request->url;
                if ($real_url != '/cis/web/') {
                    $real_url_ = str_replace('/cis/web','',$real_url);

                    $urlX = explode("/", $real_url_);
                    $url1 = $urlX[1];
                    $url11 = $urlX[2];

                    $username = Yii::$app->user->identity->username;
                    $user_group_id = Yii::$app->user->identity->user_group_id;
                    $sql = "select m_menu.url from m_user_access ".
                                "   left join m_menu on m_menu.menu_id = m_user_access.menu_id ". 
                                "   where m_user_access.user_group_id = ".$user_group_id."". 
                                "   ";
                    $user_access = Yii::$app->db->createCommand($sql)->queryAll();
                    $statuses = array();
                    
                    foreach ($user_access as $key) {
                        $db_url = $key['url'];
                        $db_url = explode("/",$db_url);
                        $url2 = $db_url[1];
                        $url22 = $db_url[2];

                        (trim($url1) == trim($url2)) && (trim($url11) == trim($url22)) ? $xxx = 'sama' : $xxx = 'beda';
                        $statuses[] = $xxx;
                    }

                    $sama = array_search('sama', $statuses);
                    
                    if ($user_group_id != 1 && (!isset($sama) || $sama === false)) {
                    ?>
                        <script>
                            //alert('Anda telah mencoba masuk pada menu yang bukan merupakan hak Anda, halaman ini akan segera ditutup.');
                            //window.location.href = "/cis/web/apps/logout";
                            window.location.href = "/cis/web/";
                        </script>
                    <?php
                    } else {

                    }
                }*/
            // EO LIMIT USER ACCESS BASED ON USER_ACCESS
            ?>

            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                <span></span>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                    <?php if (\app\models\CSiteConfig::findOne(1)->notifikasi == TRUE) { ?>
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar" onclick="opennotif();">
                            <a href="javascript:;" class="dropdown-toggle">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default" id="place-notifnumber"></span>
                            </a>
                            <ul class="dropdown-menu"></ul>
                        </li>
                    <?php } ?>
                    <li class="dropdown dropdown-extended dropdown-notification" style="margin-top: 15px; margin-left: -140px;">
                        <div id="counterspm"></div>
                    </li>
                    <li class="dropdown dropdown-extended dropdown-notification" style="margin-top: 15px; margin-left: -60px;">
                        <div id="counter"></div>
                    </li>
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="img-circle" style="background-color: rgba(255,255,255,0.4); margin-right: 10px;" src="<?= \Yii::$app->view->theme->baseUrl; ?>/cis/img/user-profile-avatar/<?= \Yii::$app->user->identity->userProfile->avatar; ?>" />
                            <span class="username username-hide-on-mobile">
                                <?= Yii::$app->user->identity->userProfile->fullname; ?></b>
                            </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <?= yii\bootstrap\Html::a('<i class="icon-emoticon-smile"></i> ' . Yii::t('app', 'My Profile'), 'javascript:;', [
                                    'onclick' => "openModal('" . \yii\helpers\Url::toRoute('/sysadmin/user/accountInfo') . "','modal-account-info')",
                                    'data-target' => '#modal-account-info',
                                    'data-toggle' => 'modal'
                                ]) ?>
                            </li>
                            <li>
                                <?= yii\bootstrap\Html::a('<i class="icon-key"></i> ' . Yii::t('app', 'Change Password'), 'javascript:;', [
                                    'onclick' => "openModal('" . \yii\helpers\Url::toRoute('/sysadmin/user/changePassword') . "','modal-user-changepassword')",
                                    'data-target' => '#modal-user-changepassword',
                                    'data-toggle' => 'modal'
                                ]) ?>
                            </li>
                            <?php
                            $special_users = [
                                368 // yudi, staff IT
                            ];
                            if (
                                Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER
                                || in_array(Yii::$app->user->identity->user_id, $special_users)
                            ) {
                            ?>
                                <li>
                                    <?= yii\bootstrap\Html::a('<i class="fa fa-phone"></i> ' . Yii::t('app', 'Extension List'), 'javascript:;', [
                                        'onclick' => "openModal('" . \yii\helpers\Url::toRoute('/sysadmin/extensiontelepon/list') . "','modal-extension')",
                                        'data-target' => '#modal-extension',
                                        'data-toggle' => 'modal'
                                    ]) ?>
                                </li>
                            <?php } ?>

                            <?php
                            $user_id = $_SESSION['__id'];
                            $pegawai_id = Yii::$app->db->createCommand("select pegawai_id from m_user where user_id = " . $user_id . " ")->queryScalar();
                            $departement_id = Yii::$app->db->createCommand("select departement_id from m_pegawai where pegawai_id = " . $pegawai_id . " ")->queryScalar();
                            if ($departement_id == 112 || $departement_id == 116) {
                            ?>
                                <li class="dropdown-submenu" aria-haspopup="true">
                                    <?= yii\bootstrap\Html::a('<i class="icon-briefcase"></i> ' . Yii::t('app', 'System Utility'), 'javascript:;', [
                                        'onclick' => "return false;"
                                    ]) ?>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?= yii\bootstrap\Html::a(Yii::t('app', 'Data Correction'), \yii\helpers\Url::toRoute('/sysadmin/datacorrection/index')) ?>
                                        </li>
                                    </ul>
                                </li>
                            <?php
                            }
                            ?>

                            <li>
                                <?= yii\bootstrap\Html::a('<i class="icon-bar-chart"></i> ' . Yii::t('app', 'My Activity'), 'javascript:;', [
                                    'onclick' => "javascript.void(0)"
                                ]) ?>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <?= yii\bootstrap\Html::a('<i class="fa fa-sign-out"></i> ' . Yii::t('app', 'Log Out'), Url::toRoute('/apps/logout')) ?>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <ul class="page-sidebar-menu  page-header-fixed <?= ($sidebar_closed) ? 'page-sidebar-menu-closed' : '' ?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <li class="sidebar-toggler-wrapper hide">
                        <div class="sidebar-toggler">
                            <span></span>
                        </div>
                    </li>
                    <li class="sidebar-search-wrapper">
                    </li>

                    <li class="nav-item start <?= (Yii::$app->controller->id . '/' . Yii::$app->controller->action->id == 'apps/index') ? "active" : ""; ?>">
                        <a href="<?= Url::base(); ?>" class="nav-link nav-toggle" style="font-weight: 600 !important;">
                            <i class="icon-home" style="font-weight: 700 !important;"></i>
                            <span class="title">Home</span>
                        </a>
                    </li>
                    
                    <?php
                    if (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) {
                        $menugroups = app\models\MMenuGroup::find()->where(['active' => TRUE])->orderBy(['sequence' => SORT_ASC])->all();
                    } else {
                        $select = 'm_menu_group.menu_group_id, m_menu_group.name, m_menu_group.sequence, m_menu_group.icon';
                        $query = "
                                SELECT $select
                                FROM m_user_access
                                JOIN m_menu ON m_menu.menu_id=m_user_access.menu_id
                                JOIN m_menu_group ON m_menu_group.menu_group_id=m_menu.menu_group_id
                                WHERE m_user_access.user_group_id = " . Yii::$app->user->identity->user_group_id . " 
                                    AND m_menu_group.active = TRUE
                                GROUP BY $select
                                ORDER BY m_menu_group.sequence ASC
                            ";
                        $menugroups = Yii::$app->db->createCommand($query)->queryAll();
                    }
                    foreach ($menugroups as $i => $menugroup) {
                        if (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) {
                            $query = "
                                    SELECT m_module.module_id, m_module.name, m_module.icon 
                                    FROM m_menu
                                    JOIN m_module ON m_module.module_id = m_menu.module_id
                                    WHERE m_menu.menu_group_id = " . $menugroup['menu_group_id'] . " 
                                        AND m_module.active = TRUE 
                                    GROUP BY m_module.module_id, m_module.name, m_module.icon 
                                    ORDER BY m_module.sequence ASC
                                ";
                        } else {
                            $query = "
                                    SELECT m_module.module_id, m_module.name, m_module.icon 
                                    FROM m_user_access
                                    JOIN m_menu ON m_menu.menu_id=m_user_access.menu_id
                                    JOIN m_module ON m_module.module_id = m_menu.module_id
                                    WHERE m_menu.menu_group_id = " . $menugroup['menu_group_id'] . " 
                                        AND m_module.active = TRUE 
                                        AND m_user_access.user_group_id = " . Yii::$app->user->identity->user_group_id . "  
                                    GROUP BY m_module.module_id, m_module.name, m_module.icon 
                                    ORDER BY m_module.sequence ASC
                                ";
                        }
                        $modules = Yii::$app->db->createCommand($query)->queryAll();

                        if (count($modules) > 0) {
                            $query = "SELECT * FROM m_menu 
                                        JOIN m_menu_group ON m_menu_group.menu_group_id=m_menu.menu_group_id
                                        WHERE url ILIKE '%" . Yii::$app->controller->getRoute() . "%'";
                            $activegroupmenu = Yii::$app->db->createCommand($query)->queryOne();
                            $url = $activegroupmenu['url'];
                    ?>
                            <li class="nav-item start cat-m-<?= $menugroup['menu_group_id']; ?>">
                                <a href="javascript:;" class="nav-link nav-toggle" style="font-weight: 600 !important;">
                                    <i class="<?php echo $menugroup['icon']; ?>" style="font-weight: 600 !important;"></i>
                                    <span class="title"><?= $menugroup['name']; ?></span>
                                    <span class="arrow <?= ($activegroupmenu['menu_group_id'] == $menugroup['menu_group_id']) ? "open" : ""; ?>"></span>
                                </a>

                                <?php if ($menugroup['menu_group_id'] != \app\components\Params::MENU_GROUP_ID_KONFIGURASI) { ?>

                                    <?php /* MENU KEDUA */ ?>
                                    <?php if (count($modules) > 1) {  //Jika Module lebih dari satu 
                                    ?>
                                        <?php //echo "<span style='font-size: 10px;'>".$activegroupmenu['menu_group_id']." ".$menugroup['menu_group_id']."</span>";
                                        ?>
                                        <ul class="sub-menu" style="display:<?= ($activegroupmenu['menu_group_id'] == $menugroup['menu_group_id']) ? "block" : ""; ?>;">
                                            <?php foreach ($modules as $i => $module) { ?>
                                                <li class="nav-item mod-<?= $module['module_id']; ?>">
                                                    <a href="javascript:;" class="nav-link nav-toggle" style="font-weight: 500 !important;">
                                                        <i class="<?= $module['icon']; ?>" style="font-weight: 500 !important;"></i>
                                                        <span class="title"><?= $module['name']; ?></span>
                                                        <span class="arrow <?= ((strtolower(Yii::$app->controller->module->id) == str_replace("-", "", strtolower($module['name']))) && ($activegroupmenu['menu_group_id'] == $menugroup['menu_group_id'])) ? "open" : ""; ?>"></span>
                                                    </a>
                                                    <?php
                                                    $select = 'm_menu.menu_id,m_menu.name,m_menu.url';
                                                    if (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) {
                                                        $query = "
                                                            SELECT $select FROM m_menu
                                                            WHERE m_menu.menu_group_id = " . $menugroup['menu_group_id'] . "
                                                                AND m_menu.module_id = " . $module['module_id'] . "
                                                                AND m_menu.active = TRUE
                                                            GROUP BY $select
                                                            ORDER BY m_menu.sequence ASC
                                                        ";
                                                    } else {
                                                        $query = "
                                                            SELECT $select FROM m_user_access
                                                            JOIN m_menu ON m_menu.menu_id=m_user_access.menu_id
                                                            WHERE m_menu.menu_group_id = " . $menugroup['menu_group_id'] . "
                                                                AND m_menu.module_id = " . $module['module_id'] . "
                                                                AND m_menu.active = TRUE
                                                                AND m_user_access.user_group_id = " . Yii::$app->user->identity->user_group_id . " 
                                                            GROUP BY $select
                                                            ORDER BY m_menu.sequence ASC
                                                        ";
                                                    }
                                                    $menus = Yii::$app->db->createCommand($query)->queryAll();
                                                    ?>

                                                    <?php /* MENU KETIGA */ ?>
                                                    <?php //echo "<span style='font-size: 10px;'>".strtolower(Yii::$app->controller->module->id)." - ".str_replace("-","",strtolower($module['name']))." ".$activegroupmenu['menu_group_id']." ".$menugroup['menu_group_id']."</span>";
                                                    ?>
                                                    <?php
                                                    // 
                                                    $module_name = strtolower($module['name']);
                                                    $module_name = str_replace("-", "", $module_name);
                                                    $module_name = str_replace(" ", "", $module_name);
                                                    //echo "<span style='font-size: 10px;'>".strtolower(Yii::$app->controller->module->id)." - ".$module_name."</span>";
                                                    if ((strtolower(Yii::$app->controller->module->id) == $module_name) && ($activegroupmenu['menu_group_id'] == $menugroup['menu_group_id'])) {
                                                        $goblock = "block";
                                                    } else {
                                                        $goblock = "";
                                                    }
                                                    //echo $block;
                                                    ?>

                                                    <?php /*<ul class="sub-menu" style="display: <?php echo $goblock;?>;">
                                                    <?php foreach($menus as $i => $menu){ ?>
                                                        <?php
                                                        if ($menu['url'] != "/marketing/pricelist/create") {
                                                        ?>
                                                        <?php $sel = strpos($menu['url'], '/'.Yii::$app->controller->id.'/'.Yii::$app->controller->action->id); ?>
                                                        <li class="nav-item <?php echo ($sel == true)?'active ':''; echo "menu-".$menu['menu_id']; ?>">
                                                            <a href="<?= Url::toRoute($menu['url']); ?>" class="nav-link " id="<?= $menu['url'] ?>" style="font-weight: 300 !important; font-size: 0.9em;">
                                                                &nbsp;- &nbsp; <span class="title"><?= $menu['name']; ?></span>
                                                            </a>
                                                        </li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    </ul>*/ ?>
                                                    <ul class="sub-menu" style="display: <?php echo $goblock; ?>;">
                                                        <?php foreach ($menus as $i => $menu) { ?>
                                                            <?php
                                                            if ($menu['url'] != "/marketing/pricelist/create") {
                                                                $jerami = explode("/", $menu['url']);
                                                                $jarum = Yii::$app->controller->action->id;
                                                                /* 
                                                            if ($jerami[1] == "tuk") {
                                                                $jerami[3] == $jarum ? $status = "active" : $status = "";
                                                            } else {
                                                                $sel = strpos($menu['url'], '/'.Yii::$app->controller->id.'/'.Yii::$app->controller->action->id);
                                                                $sel == true ? $status = 'active' : $status = '';
                                                            }*/
                                                                $sel = strpos($menu['url'], '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id);
                                                                $sel == true ? $status = 'active' : $status = '';
                                                            ?>
                                                                <li class="nav-item <?php echo $status; ?>">
                                                                    <a href="<?= Url::toRoute($menu['url']); ?>" class="nav-link " id="<?= $menu['url'] ?>" style="font-weight: 300 !important; font-size: 0.9em;">
                                                                        &nbsp;- &nbsp; <span class="title"><?= $menu['name']; ?></span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } else { ?>
                                        <?php $module = $modules[0]; ?>
                                        <?php
                                        if (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) {
                                            $query = "
                                                    SELECT m_menu.menu_id,m_menu.name,m_menu.url FROM m_menu
                                                    WHERE m_menu.menu_group_id = " . $menugroup['menu_group_id'] . "
                                                        AND m_menu.module_id = " . $module['module_id'] . "
                                                        AND m_menu.active = TRUE
                                                    GROUP BY m_menu.menu_id,m_menu.name,m_menu.url
                                                    ORDER BY m_menu.sequence ASC
                                                ";
                                        } else {
                                            $query = "
                                                    SELECT m_menu.menu_id,m_menu.name,m_menu.url FROM m_user_access
                                                    JOIN m_menu ON m_menu.menu_id=m_user_access.menu_id
                                                    WHERE m_menu.menu_group_id = " . $menugroup['menu_group_id'] . "
                                                        AND m_menu.module_id = " . $module['module_id'] . "
                                                        AND m_menu.active = TRUE
                                                        AND m_user_access.user_group_id = " . Yii::$app->user->identity->user_group_id . " 
                                                    GROUP BY m_menu.menu_id,m_menu.name,m_menu.url
                                                    ORDER BY m_menu.sequence ASC
                                                ";
                                        }
                                        $menus = Yii::$app->db->createCommand($query)->queryAll();
                                        ?>
                                        <ul class="sub-menu" style="display:<?= ($activegroupmenu['menu_group_id'] == $menugroup['menu_group_id']) ? "block" : ""; ?>;">
                                            <?php foreach ($menus as $i => $menu) { ?>
                                                <?php $sel = strpos($menu['url'], '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id); ?>
                                                <li class="nav-item <?php echo ($sel == true) ? 'active ' : '';
                                                                    echo "menu-" . $menu['menu_id']; ?>">
                                                    <a href="<?= Url::toRoute($menu['url']); ?>" class="nav-link " id="<?= $menu['url'] ?>" style="font-weight: 300 !important; font-size: 0.9em;">
                                                        &nbsp;-&nbsp; <span class="title"><?= $menu['name']; ?></span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php
                                    if (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) {
                                        $query = "
                                                SELECT m_menu.menu_id,m_menu.name,m_menu.url FROM m_menu
                                                WHERE m_menu.menu_group_id = " . \app\components\Params::MENU_GROUP_ID_KONFIGURASI . "
                                                    AND m_menu.module_id = " . \app\components\Params::MODULE_ID_ADMINISTRATOR . "
                                                    AND m_menu.active = TRUE
                                                GROUP BY m_menu.menu_id,m_menu.name,m_menu.url
                                                ORDER BY m_menu.sequence ASC
                                            ";
                                    } else {
                                        $query = "
                                                SELECT m_menu.menu_id,m_menu.name,m_menu.url FROM m_user_access
                                                JOIN m_menu ON m_menu.menu_id=m_user_access.menu_id
                                                WHERE m_menu.menu_group_id = " . $menugroup['menu_group_id'] . "
                                                    AND m_menu.module_id = " . $module['module_id'] . "
                                                    AND m_menu.active = TRUE
                                                    AND m_user_access.user_group_id = " . Yii::$app->user->identity->user_group_id . " 
                                                GROUP BY m_menu.menu_id,m_menu.name,m_menu.url
                                                ORDER BY m_menu.sequence ASC
                                            ";
                                    }
                                    $menus = Yii::$app->db->createCommand($query)->queryAll();
                                    ?>
                                    <ul class="sub-menu" style="display:<?= ($activegroupmenu['menu_group_id'] == $menugroup['menu_group_id']) ? "block" : ""; ?>;">
                                        <?php foreach ($menus as $i => $menu) { ?>
                                            <?php $sel = strpos($menu['url'], '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id); ?>
                                            <li class="nav-item <?php echo ($sel == true) ? 'active ' : '';
                                                                echo "menu-" . $menu['menu_id']; ?>">
                                                <a href="<?= Url::toRoute($menu['url']); ?>" class="nav-link " id="<?= $menu['url'] ?>" style="font-weight: 300 !important; font-size: 0.9em;">
                                                    &nbsp;-&nbsp; <span class="title"><?= $menu['name']; ?></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php
                    // JOBDESC DUL                    
                    $m_user = \app\models\MUser::findOne(['user_id' => $_SESSION['__id']]);
                    $pegawai_id = $m_user->pegawai_id;
                    $t_jobdesc = \app\models\TJobdesc::findOne(['pegawai_id' => $pegawai_id]);
                    if (!empty($t_jobdesc->jobdesc_id) && $t_jobdesc->jobdesc_id != '') {
                    ?>
                        <li class="nav-item start <?= (Yii::$app->controller->id . '/' . Yii::$app->controller->action->id == 'apps/index') ? "active" : ""; ?>">
                            <a onclick="infoJobDescSu(<?php echo $t_jobdesc->jobdesc_id; ?>)" class="nav-link nav-toggle" style="font-weight: 600 !important;">
                                <i class="fa fa-hand-o-right" style="font-weight: 700 !important;"></i>
                                <span class="title">Jobdesc</span>
                            </a>
                        </li>
                        <script>
                            // fungsi tombol infoJobdesc
                            function infoJobDescSu(id) {
                                openModal('<?= \yii\helpers\Url::toRoute(['/hrd/jobdesc/info', 'id' => '']) ?>' + id, 'modal-jobdesc-info', '95%');
                            }
                        </script>
                    <?php
                    }
                    // EO JOBDESC DUL
                    ?>

                </ul>
                <!-- END SIDEBAR MENU -->
                <!-- END SIDEBAR MENU -->
            </div>
            <!-- END SIDEBAR -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <?= $content ?>
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner">
            <div class="copyright" style="font-size: 1rem;"> <?= " &copy; 2018 - " . date("Y") . " Hak Cipta <b>IT Dept - PT. Cipta Wijaya Mandiri</b>" ?></div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <div class="modals-place-3-min"></div>
    <div class="modals-place-2-min"></div>
    <div class="modals-place"></div>
    <div class="modals-place-2"></div>
    <div class="modals-place-3"></div>
    <div class="modals-place-confirm"></div>
    <!-- END FOOTER -->
</div>
<script>
    function opennotif() {
        openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/notifikasi/show') ?>', 'modal-notif');
    }

    function opennotifTApproval() {
        openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/notifikasi/showTApproval') ?>', 'modal-notif', '60%');
    }

    function opennotifOpexportProforma(id) {
        openModal('<?= \yii\helpers\Url::toRoute('/sysadmin/notifikasi/showOpexportProforma') ?>?id=' + id, 'modal-notif', '85%');
    }
</script>
<?php $this->registerJsFile($this->theme->baseUrl . "/layouts/layout/scripts/layout.min.js", ['depends' => [yii\web\YiiAsset::className()]]) ?>

<?php $this->endBody() ?>
<script type="text/javascript">
    document.write("</BO" + "DY>" + "</HT" + "ML>");
</script>
<!--</BODY>
</HTML>-->
<?php $this->endPage() ?>