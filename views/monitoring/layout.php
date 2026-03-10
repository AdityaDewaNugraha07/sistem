<?php
/* @var $content string */

use yii\bootstrap\Html;
use yii\helpers\Url;

$uid = str_replace(" ", "-", strtolower($this->title));

$this->beginPage() ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="<?= Url::base() ?>/favicon.ico" />
    <title><?= Html::encode($this->title) ?></title>
    <style>
        body {
            background-color: var(--<?= $uid ?>-background);
            color: var(--<?= $uid ?>-foreground);
            font-size: var(--<?= $uid ?>-font-size);
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            /*font-weight: bold;*/
        }

        .warning {
            animation: blinker 3s linear infinite;
            font-weight: bold;
            color: #ff5400 !important;
        }

        @keyframes blinker {

            0%,
            79% {
                color: var(--<?= $uid ?>-foreground);
                /*text-shadow: 0 1px 0 orangered;*/
            }

            80%,
            100% {
                color: var(--<?= $uid ?>-background);
                /*text-shadow: 0 1px 0 yellow;*/
            }
        }

        .notice {
            color: #fff;
            background-color: #ff0000;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            border-radius: 4px;
            animation: blink 1s linear infinite;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }


        .achieved {
            color: var(--<?= $uid ?>-achieved);
            font-weight: bold;
            /*text-shadow: yellow;*/
        }

        .font-setting-wrap {
            background: rgb(0, 0, 204);
            background: linear-gradient(220deg, var(--<?= $uid ?>-background) 0%, var(--<?= $uid ?>-foreground) 50%, var(--<?= $uid ?>-achieved) 100%);
            box-shadow: 5px 5px 5px black;
            border-radius: 5px;
            color: var(--<?= $uid ?>-background);
            padding: 15px 30px 15px 15px;
            font-size: 14px;
            position: fixed;
            bottom: -160px;
            left: -360px;
            transition: .3s;
        }

        .font-setting-wrap input {
            opacity: .7;
            cursor: pointer;
        }

        .font-setting-wrap.active {
            bottom: 10px;
            left: 10px;
        }

        .font-setting-wrap #font-setting {
            background: linear-gradient(to right, var(--<?= $uid ?>-background) 0%, var(--<?= $uid ?>-background) 29%, var(--<?= $uid ?>-foreground) 29%, var(--<?= $uid ?>-foreground) 100%);
            border: solid 1px var(--<?= $uid ?>-background);
            border-radius: 8px;
            height: 7px;
            width: 300px;
            outline: none;
            transition: background 450ms ease-in;
            -webkit-appearance: none;
            margin-top: 10px;
        }

        .wrapper-icon {
            width: 30px;
            height: 30px;
            background: var(--<?= $uid ?>-foreground);
            position: absolute;
            top: -60px;
            right: -60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .3s;
            z-index: 10;
            opacity: 1;
        }

        .wrapper-icon.active {
            right: -10px;
            top: -10px;
        }

        .wrapper-icon svg {
            display: none;
            fill: var(--<?= $uid ?>-background);
        }

        .wrapper-icon svg.active {
            display: block;
        }

        .color-picker {
            border: none;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .btn-reset {
            padding: 3px 6px;
            margin-top: 10px;
            color: var(--<?= $uid ?>-foreground);
            background: var(--<?= $uid ?>-background);
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-reset:hover {
            opacity: .7;
            transition: .3s;
        }

        header {
            display: flex;
            justify-content: space-between;
            margin-top: -35px;
            margin-bottom: -20px;
        }
    </style>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <div class="font-setting-wrap">
        <label for="font-setting">Font Size: <span class="value"></span></label><br>
        <input type="range" min="8" max="64" id="font-setting"><br>
        <label for="<?= $uid ?>-foreground">Foreground: </label>
        <input type="color" id="<?= $uid ?>-foreground" class="color-picker" value="#FFFF00" onchange="settingColor(this)">
        <span></span><br>
        <label for="<?= $uid ?>-background">Background: </label>
        <input type="color" id="<?= $uid ?>-background" class="color-picker" value="#00005d" onchange="settingColor(this)">
        <span></span><br>
        <label for="<?= $uid ?>-achieved">Achieved: </label>
        <input type="color" id="<?= $uid ?>-achieved" class="color-picker" value="#AFFF00" onchange="settingColor(this)">
        <span></span><br>
        <button onclick="resetSetting()" class="btn-reset">Reset Default</button>
        <div class="wrapper-icon">
            <svg class="gear active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M24 13.616v-3.232c-1.651-.587-2.694-.752-3.219-2.019v-.001c-.527-1.271.1-2.134.847-3.707l-2.285-2.285c-1.561.742-2.433 1.375-3.707.847h-.001c-1.269-.526-1.435-1.576-2.019-3.219h-3.232c-.582 1.635-.749 2.692-2.019 3.219h-.001c-1.271.528-2.132-.098-3.707-.847l-2.285 2.285c.745 1.568 1.375 2.434.847 3.707-.527 1.271-1.584 1.438-3.219 2.02v3.232c1.632.58 2.692.749 3.219 2.019.53 1.282-.114 2.166-.847 3.707l2.285 2.286c1.562-.743 2.434-1.375 3.707-.847h.001c1.27.526 1.436 1.579 2.019 3.219h3.232c.582-1.636.75-2.69 2.027-3.222h.001c1.262-.524 2.12.101 3.698.851l2.285-2.286c-.744-1.563-1.375-2.433-.848-3.706.527-1.271 1.588-1.44 3.221-2.021zm-12 2.384c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4z" />
            </svg>
            <svg class="times" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z" fill-rule="nonzero" />
            </svg>
        </div>
    </div>
    <?php $this->endBody() ?>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        renderWatch();
        settingFontSize();
        fullScreen();
        mergeRow();
        reload();
        showSetting();
        initSetting();
    });

    // function renderWatch() {
    //     const dateEl = document.querySelector('.datetime');
    //     dateEl.innerHTML = getDateTime();
    //     setInterval(() => {
    //         dateEl.innerHTML = getDateTime();
    //     }, 1000);
    // }

    // function getDateTime() {
    //     const date = new Date();
    //     const datetime = {}

    //     datetime.year = date.getFullYear();
    //     datetime.month = date.toLocaleDateString("id-ID", {
    //         month: 'long'
    //     });
    //     datetime.day = date.getDate();
    //     datetime.hours = date.getHours();
    //     datetime.minutes = date.getMinutes();
    //     datetime.second = date.getSeconds();

    //     for (const key in datetime) {
    //         if (datetime[key] < 10) {
    //             datetime[key] = '0' + datetime[key];
    //         }
    //     }
    //     return `<span style="font-size: 80%">${datetime.day} ${datetime.month} ${datetime.year}</span><br>${datetime.hours}:${datetime.minutes}:${datetime.second} WIB`;
    // }

    function renderWatch() {
        const serverTimeStr = <?= json_encode(date('Y-m-d H:i:s')) ?>;
        const serverTime = new Date(serverTimeStr);
        const dateEl = document.querySelector('.datetime');

        // Update waktu setiap detik
        setInterval(() => {
            serverTime.setSeconds(serverTime.getSeconds() + 1);
            dateEl.innerHTML = getDateTime(serverTime);
        }, 1000);
    }

    function getDateTime(dateTimeStr) {
        const date = new Date(dateTimeStr);
        const datetime = {};

        datetime.year = date.getFullYear();
        datetime.month = date.toLocaleDateString("id-ID", {
            month: 'long'
        });
        datetime.day = date.getDate();
        datetime.hours = date.getHours();
        datetime.minutes = date.getMinutes();
        datetime.second = date.getSeconds();

        for (const key in datetime) {
            if (datetime[key] < 10) {
                datetime[key] = '0' + datetime[key];
            }
        }
        return `<span style="font-size: 80%">${datetime.day} ${datetime.month} ${datetime.year}</span><br>${datetime.hours}:${datetime.minutes}:${datetime.second} WIB`;
    }

    function fullScreen() {
        document.addEventListener('dblclick', () => {
            if (document.fullscreenElement) {
                document.exitFullscreen().then().catch(err => {
                    alert(err.message);
                });
            } else {
                document.documentElement.requestFullscreen().then().catch(err => {
                    alert(err.message);
                });
            }
        });
    }

    function mergeRow() {
        const table = document.querySelector('table');

        let headerCell = null;

        for (let row of table.rows) {
            const firstCell = row.cells[0];

            if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
                headerCell = firstCell;
            } else {
                headerCell.rowSpan++;
                firstCell.remove();
            }
        }
    }

    function reload() {
        setInterval(() => {
            document.location.reload();
        }, 60 * 1000);
    }

    function showSetting() {
        const wrapperIcon = document.querySelector('.wrapper-icon');
        const settingWrap = document.querySelector('.font-setting-wrap');
        const gear = document.querySelector('.gear');
        const times = document.querySelector('.times');

        wrapperIcon.addEventListener('click', () => {
            if (settingWrap.classList.contains('active') && wrapperIcon.classList.contains('active')) {
                settingWrap.classList.remove('active');
                wrapperIcon.classList.remove('active');
                gear.classList.add('active');
                times.classList.remove('active');
            } else {
                settingWrap.classList.add('active');
                wrapperIcon.classList.add('active');
                gear.classList.remove('active');
                times.classList.add('active');
            }
        });
    }

    function settingFontSize() {
        const lvalue = document.querySelector('.value');
        const input = document.querySelector('#font-setting');
        const root = document.documentElement;

        const size = localStorage.getItem('<?= $uid ?>-font');
        if (size) {
            root.style.setProperty('--<?= $uid ?>-font-size', size + 'px');
            lvalue.innerHTML = size + 'px';
            input.style.background = barStyle(size, input);
            input.value = size;
        } else {
            const defaultVal = getComputedStyle(root).getPropertyValue('--<?= $uid ?>-font-size');
            const parseDefaultVal = parseInt(defaultVal.replace('px', ''));
            root.style.setProperty('--<?= $uid ?>-font-size', defaultVal);
            lvalue.innerHTML = defaultVal;
            input.style.background = barStyle(parseDefaultVal, input);
            input.value = parseDefaultVal;
        }

        input.addEventListener('input', (e) => {
            lvalue.innerHTML = e.target.value + 'px';
            root.style.setProperty('--<?= $uid ?>-font-size', e.target.value + 'px');
            input.style.background = barStyle(e.target.value, e.target);
        });

        function barStyle(size, input) {
            localStorage.setItem('<?= $uid ?>-font', size);
            const currentValue = ((size - input.min) / (input.max - input.min) * 100).toFixed(0);
            return `linear-gradient(to right, var(--<?= $uid ?>-background) 0%, var(--<?= $uid ?>-background) ${currentValue}%, var(--<?= $uid ?>-foreground) ${currentValue}%, var(--<?= $uid ?>-foreground) 100%)`;
        }
    }

    function settingColor(element) {
        const root = document.documentElement;
        element.nextElementSibling.innerHTML = element.value.toUpperCase();
        localStorage.setItem(element.id, element.value);
        root.style.setProperty(`--${element.id}`, element.value);
    }

    function initSetting() {
        const inputs = document.querySelectorAll('input[type=color]');
        const root = document.documentElement;
        inputs.forEach(e => {
            const value = localStorage.getItem(e.id);
            if (value) {
                e.value = value;
                e.nextElementSibling.innerHTML = value;
                root.style.setProperty(`--${e.id}`, value);
            }
        });
    }

    function resetSetting() {
        localStorage.clear();
        document.location.reload();
    }
</script>

</html>
<?php $this->endPage() ?>