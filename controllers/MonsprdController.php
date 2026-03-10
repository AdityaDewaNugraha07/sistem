<?php

namespace app\controllers;

use yii\web\Controller;

class MonsprdController extends Controller
{
    public function actionTv1() {
        $this->layout = false;
        // $sheetUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vT0dYZrbfWV34KZJwyUhdv5daCpZZIbYS6NWiH5D9roob0CP6JFnokYnkcQhb-Naahv_vk96pFZN2Uy/pubhtml";
        $sheetUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vSzePio2IfZRs8J1hTK-fALur0IuDB_2Zxsmb7Jn5x753BfrfIU0Xeb6u1IqKqnhg/pubhtml";
        return $this->render('tv1', ['sheetUrl' => $sheetUrl]);
    }

    public function actionTv2() {
        $this->layout = false;
        $sheetUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vTwbc8dpXrsAEorvwrRY3cPXgc6-bj1HL4T3fbMFD-b6-BXEm8KxVZu1_9sVyoj7w/pubhtml";
        return $this->render('tv2', ['sheetUrl' => $sheetUrl]);
    }

    public function actionTv3() {
        $this->layout = false;
        $sheetUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vSQrTFscCLiYC3QzK3dQsp8yE5Wkb54yssOJd3pZymVCQAVl3BYem_jhWGmQaoVHg/pubhtml";
        return $this->render('tv3', ['sheetUrl' => $sheetUrl]);
    }

    public function actionTv4() {
        $this->layout = false;
        $sheetUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vThzU4sxFGpvHK6PyNalMT6L4yYcMKd071pPW2cn6AmqN3998gqo_EGqIU9sFaCOeZ4T51ZdRyElntu/pubhtml";
        return $this->render('tv4', ['sheetUrl' => $sheetUrl]);
    }

}
?>