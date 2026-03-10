<?php

namespace app\controllers;

use app\models\MMtrgSetup;
use yii\web\Controller;

class MonitoringController extends Controller
{
    protected $repair_input     = 0;
    protected $repair_output    = 0;

    public function actionIndex()
    {
        $this->layout = '@views/monitoring/layout';
        return $this->render('index');
    }

    /**
     * @param $tanggal
     * @return string
     * @throws Exception
     */
    public function actionRotary($tanggal = null)
    {
        $this->layout   = '@views/monitoring/layout';
        $tanggal        = $tanggal === null ? MMtrgSetup::getActiveDate() : $tanggal;
        $data           = [];
        $hari_kerja     = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_ROTARY, $tanggal);
        $sengon         = $this->query(MMtrgSetup::KATEGORI_ROTARY, 'Sengon', $tanggal)->all();
        $jabon          = $this->query(MMtrgSetup::KATEGORI_ROTARY, 'Jabon', $tanggal)->all();

        if($sengon !== null) {
            $data = $this->_dataProcess($sengon, $tanggal);
        }

        if($jabon !== null) {
            $data = array_merge($data, $this->_dataProcess($jabon, $tanggal));
        }

        return $this->render('rotary', compact('data', 'hari_kerja'));
    }

    /**
     * @param $tanggal
     * @return string
     * @throws Exception
     */
    public function actionDrying($tanggal = null)
    {
        $this->layout   = '@views/monitoring/layout';
        $tanggal        = $tanggal === null ? MMtrgSetup::getActiveDate() : $tanggal;
        $hari_kerja     = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_DRYING, $tanggal);
        $data           = [];
        $sengon         = $this->query(MMtrgSetup::KATEGORI_DRYING, 'Sengon', $tanggal)->all();
        $jabon          = $this->query(MMtrgSetup::KATEGORI_DRYING, 'Jabon', $tanggal)->all();

        if($sengon !== null) {
            $data = $this->_dataProcess($sengon, $tanggal);
        }

        if($jabon !== null) {
            $data = array_merge($data, $this->_dataProcess($jabon, $tanggal));
        }

        return $this->render('drying', compact('data', 'hari_kerja'));
    }

    /**
     * @param $tanggal
     * @return string
     * @throws Exception
     */
    public function actionCb($tanggal = null)
    {
        $this->layout   = '@views/monitoring/layout';
        $tanggal        = $tanggal === null ? MMtrgSetup::getActiveDate() : $tanggal;
        $hari_kerja     = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_CORE_BUILDER, $tanggal);
        $data           = [];
        $sengon_manual  = $this->query(MMtrgSetup::KATEGORI_CORE_BUILDER, 'Sengon', $tanggal, ['like', 'grade', 'Manual'])->all();
        $sengon_ppc     = $this->query(MMtrgSetup::KATEGORI_CORE_BUILDER, 'Sengon', $tanggal,['like', 'grade', 'PPC'])->all();
        $jabon_manual   = $this->query(MMtrgSetup::KATEGORI_CORE_BUILDER, 'Jabon', $tanggal,['like', 'grade', 'Manual'])->all();
        $jabon_ppc      = $this->query(MMtrgSetup::KATEGORI_CORE_BUILDER, 'Jabon', $tanggal,['like', 'grade', 'PPC'])->all();

        if($sengon_manual !== null) {
            $data = $this->_dataProcess($sengon_manual);
        }

        if($sengon_ppc !== null) {
            $data = array_merge($data, $this->_dataProcess($sengon_ppc));
        }

        if($jabon_manual !== null) {
            $data = array_merge($data, $this->_dataProcess($jabon_manual));
        }

        if($jabon_ppc !== null) {
            $data = array_merge($data, $this->_dataProcess($jabon_ppc));
        }

        return $this->render('cb', compact('data', 'hari_kerja'));
    }

    /**
     * @param $tanggal
     * @return string
     * @throws Exception
     */
    public function actionPlytech($tanggal = null)
    {
        $this->layout   = '@views/monitoring/layout';
        $tanggal        = $tanggal === null ? MMtrgSetup::getActiveDate() : $tanggal;
        $hari_kerja     = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_PLYTECH, $tanggal);
        $data           = [];
        $sengon         = $this->query(MMtrgSetup::KATEGORI_PLYTECH, 'Sengon', $tanggal)->all();
        $jabon          = $this->query(MMtrgSetup::KATEGORI_PLYTECH, 'Jabon', $tanggal)->all();

        if(count($sengon) > 0) {
            $data = $this->_dataProcess($sengon, $tanggal);
            unset($data[count($data) -1]);
        }

        if(count($jabon) > 0) {
            $data = array_merge($data, $this->_dataProcess($jabon, $tanggal));
            unset($data[count($data) -1]);
        }

        return $this->render('plytech', compact('data', 'hari_kerja'));
    }

    /**
     * @param $tanggal
     * @return string
     * @throws Exception
     */
    public function actionRepair($tanggal = null)
    {
        $this->layout   = '@views/monitoring/layout';
        $tanggal        = $tanggal === null ? MMtrgSetup::getActiveDate() : $tanggal;
        $data           = [];
        $hari_kerja     = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_REPAIR, $tanggal);
        $io_sengon      = $this->query(MMtrgSetup::KATEGORI_REPAIR, 'Sengon', $tanggal, ['grade' => ['Input', 'Output']])->all();
        $sengon         = $this->query(MMtrgSetup::KATEGORI_REPAIR, 'Sengon', $tanggal, ['not', ['grade' => ['Input', 'Output']]])->all();
        $io_jabon       = $this->query(MMtrgSetup::KATEGORI_REPAIR, 'Jabon', $tanggal, ['grade' => ['Input', 'Output']])->all();
        $jabon          = $this->query(MMtrgSetup::KATEGORI_REPAIR, 'Jabon', $tanggal, ['not', ['grade' => ['Input', 'Output']]])->all();

        if(!empty($io_sengon)) {
            $data = $this->_dataProcess($io_sengon, $tanggal);
        }

        if(!empty($sengon)) {
            $data = array_merge($data, $this->_dataProcess($sengon, $tanggal));
        }

        if(!empty($io_jabon) || !empty($jabon)) {
            unset($data[count($data) -1]);
        }

        if(!empty($io_jabon)) {
            $data = array_merge($data, $this->_dataProcess($io_jabon, $tanggal));
        }

        if(!empty($jabon)) {
            $data = array_merge($data, $this->_dataProcess($jabon, $tanggal));
            unset($data[count($data) -1]);
        }

        return $this->render('repair', compact('data', 'hari_kerja'));
    }

    /**
     * @param $tanggal
     * @return string
     * @throws Exception
     */
    public function actionSetting($tanggal = null)
    {
        $this->layout   = '@views/monitoring/layout';
        $tanggal        = $tanggal === null ? MMtrgSetup::getActiveDate() : $tanggal;
        $data           = [];
        $hari_kerja     = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_SETTING, $tanggal);
        $sengon         = $this->query(MMtrgSetup::KATEGORI_SETTING, 'Sengon', $tanggal)->all();
        $jabon          = $this->query(MMtrgSetup::KATEGORI_SETTING, 'Jabon', $tanggal)->all();

        if($sengon !== null) {
            $data = $this->_dataProcess($sengon, $tanggal);
        }

        if($jabon !== null) {
            $data = array_merge($data, $this->_dataProcess($jabon, $tanggal));
        }

        return $this->render('setting', compact('data', 'hari_kerja'));
    }

    /**
     * @param $kategori_proses
     * @param $jenis_kayu
     * @param $tanggal
     * @param null $search
     * @return ActiveQuery
     */
    protected function query($kategori_proses, $jenis_kayu, $tanggal = null, $search = null)
    {
        $query  = MMtrgSetup::find();
        $params = [
            'kategori_proses' => $kategori_proses,
            'jenis_kayu' => $jenis_kayu
        ];

        if($tanggal !== null) {
            $params['tanggal'] = $tanggal;
        }

        $query = $query->where($params);

        if($search !== null) {
            $query = $query->andWhere($search);
        }


        $query->orderBy([
            'jenis_kayu' => SORT_DESC,
            'sequence' => SORT_ASC
        ]);

        return $query;
    }

    /**
     * @param array $data
     * @param null $tanggal
     * @return array
     * @throws Exception
     */
    protected function _dataProcess(array $data, $tanggal = null)
    {
        $result                 = [];
        $daily_input_actual     = 0;
        $monthly_input_actual   = 0;
        $curr_proses            = "";
        $next_proses            = "";
        foreach ($data as $key => $row) {
            if($key !== count($data) - 1) {
                $curr_proses = $next_proses;
                $next_proses = $data[$key + 1]->jenis_proses;
            }

            if ($key === 0) {
                $result[] = $this->_dataBuilder("section", $row);
            }

            $acc_plan       = MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->count('plan_harian');
            $plan_bulanan   = MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->sum('plan_harian');
            $jumlah_bulanan = MMtrgSetup::getSummary($row->grade, $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->sum('jumlah_aktual');

            $monthlies = [
                'plan_bulanan' => $plan_bulanan,
                'jumlah_bulanan' => $jumlah_bulanan,
                'monthly_achieve' => $plan_bulanan > 0 ? $jumlah_bulanan / $plan_bulanan * 100 : 0
            ];

            if($row->kategori_proses === MMtrgSetup::KATEGORI_ROTARY) {
                $hari_kerja = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_ROTARY, $tanggal);
                if($row->jenis_proses === MMtrgSetup::OUTPUT) {
                    $monthlies['plan_bulanan']      = $hari_kerja > 0 ? $plan_bulanan / $hari_kerja : 0;
                    $monthlies['jumlah_bulanan']    = $monthly_input_actual > 0 ? $jumlah_bulanan / $monthly_input_actual * 100 : 0;
                    $monthlies['monthly_achieve']   = $monthly_input_actual > 0 ? ($jumlah_bulanan / $monthly_input_actual * 100) / ($plan_bulanan / $hari_kerja) * 100 : 0;
                    $row->jumlah_aktual             = $daily_input_actual > 0 ? $row->jumlah_aktual / $daily_input_actual * 100 : 0;
                }else {
                    $monthlies['plan_bulanan']      = $plan_bulanan;
                    $monthlies['jumlah_bulanan']    = $jumlah_bulanan;
                    $monthlies['monthly_achieve']   = $plan_bulanan > 0 ? $jumlah_bulanan / $plan_bulanan * 100 : 0;

                    if($row->grade === 'Input') {
                        $monthly_input_actual   = $jumlah_bulanan;
                        $daily_input_actual     = $row->jumlah_aktual;
                    }

                    if($row->grade === 'Input / Jam') {
                        $plan_input = MMtrgSetup::getSummary('Input', $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->sum('plan_harian');
                        $plan_jam   = MMtrgSetup::getSummary('Jam Jalan', $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->sum('plan_harian');
                        $act_input  = MMtrgSetup::getSummary('Input', $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->sum('jumlah_aktual');
                        $act_jam    = MMtrgSetup::getSummary('Jam Jalan', $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->sum('jumlah_aktual');
                        $monthlies['plan_bulanan']      = $plan_jam > 0 ?$plan_input / $plan_jam : 0;
                        $monthlies['jumlah_bulanan']    = $act_input > 0 && $act_jam > 0 ? $act_input / $act_jam : 0;
                        $monthlies['monthly_achieve']   = $monthlies['plan_bulanan'] > 0 ? $monthlies['jumlah_bulanan'] / $monthlies['plan_bulanan'] * 100 : 0;
                    }
                }
            }else if ($row->kategori_proses === MMtrgSetup::KATEGORI_DRYING) {
                $monthly_total_volume = MMtrgSetup::getSummary('*', $row->jenis_proses, $row->jenis_kayu, $row->kategori_proses, $tanggal)->sum('jumlah_aktual');
                $daily_total_volume = MMtrgSetup::find()
                    ->where([
                        'tanggal' => $tanggal !== null ? $tanggal : MMtrgSetup::getActiveDate(),
                        'jenis_kayu' => $row->jenis_kayu,
                        'jenis_proses' => $row->jenis_proses,
                        'kategori_proses' => $row->kategori_proses
                    ])->sum('jumlah_aktual');

                $monthlies['plan_bulanan']      = $plan_bulanan ; // / $acc_plan;
                $monthlies['jumlah_bulanan']    = $monthly_total_volume > 0 ? $jumlah_bulanan / $monthly_total_volume * 100 : 0;
                $monthlies['monthly_achieve']   = $monthlies['jumlah_bulanan'] / $monthlies['plan_bulanan'] * 100;
                // $monthlies['monthly_achieve']   = $monthly_total_volume > 0 ? $jumlah_bulanan / $monthly_total_volume * 100 / $plan_bulanan * 100 : 0;
                $row->jumlah_aktual_m3          = $row->jumlah_aktual;
                $row->jumlah_bulanan_m3         = $jumlah_bulanan;
                $row->jumlah_aktual             = $daily_total_volume > 0 ? $row->jumlah_aktual / $daily_total_volume * 100 : 0;

            }else if($row->kategori_proses === MMtrgSetup::KATEGORI_CORE_BUILDER) {
                // $monthlies['plan_bulanan']  = $plan_bulanan;
                // if($row->jenis_proses === 'INPUT' && stripos($row->grade, 'input') !== false) {
                //     $daily_input_actual     = $row->jumlah_aktual;
                //     $monthly_input_actual   = $jumlah_bulanan;
                // }

                // if($monthly_input_actual > 0) {
                //     if($row->jenis_proses === 'INPUT') {
                //         $monthlies['jumlah_bulanan']    = $monthly_input_actual;
                //         $monthlies['monthly_achieve']   = $monthly_input_actual / $plan_bulanan * 100;
                //     }else {
                //         $monthlies['jumlah_bulanan']    = $jumlah_bulanan / $monthly_input_actual * 100;
                //         $monthlies['monthly_achieve']   = ($jumlah_bulanan / $monthly_input_actual * 100) / $plan_bulanan * 100;
                //     }
                // }

                // if($row->jenis_proses === 'OUTPUT' && $daily_input_actual > 0) {
                //     $row->jumlah_aktual = $row->jumlah_aktual / $daily_input_actual * 100;
                // }
            }else if($row->kategori_proses === MMtrgSetup::KATEGORI_REPAIR) {
                $hari_kerja = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_REPAIR, $tanggal);
                $monthlies['plan_bulanan']  = $plan_bulanan; // / $hari_kerja;            

            }else if($row->kategori_proses === MMtrgSetup::KATEGORI_SETTING) {
                $hari_kerja = MMtrgSetup::getHariKerja(MMtrgSetup::KATEGORI_SETTING, $tanggal);
                $monthlies['plan_bulanan']  = $plan_bulanan ; // / $hari_kerja;

                if($row->jenis_proses === 'INPUT' && stripos($row->grade, 'input') !== false) {
                    $daily_input_actual     = $row->jumlah_aktual;
                    $monthly_input_actual   = $jumlah_bulanan;
                }

                if($monthly_input_actual > 0) {
                    if($row->jenis_proses === 'INPUT') {
                        $monthlies['jumlah_bulanan']    = $monthly_input_actual;
                        $monthlies['monthly_achieve']   = $monthly_input_actual / $plan_bulanan * 100;
                    }else {
                        $monthlies['jumlah_bulanan']    = $jumlah_bulanan / $monthly_input_actual * 100;
                        $monthlies['monthly_achieve']   = ($jumlah_bulanan / $monthly_input_actual * 100) / $plan_bulanan * 100;
                    }
                }

                if($row->jenis_proses === 'OUTPUT' && $daily_input_actual > 0) {
                    // $row->jumlah_aktual = $row->jumlah_aktual / $daily_input_actual * 100;
                    $row->jumlah_aktual = $row->jumlah_aktual ;
                }
            }

            $result[] = $this->_dataBuilder('data', $row, $monthlies);
            if($key === count($data) - 1) {
                $result[] = $this->_dataBuilder("rendemen", $row, null, $result);
            }

            if(array_key_exists($key + 1, $data) && $curr_proses !== $next_proses) {
                if($row->kategori_proses === MMtrgSetup::KATEGORI_ROTARY) {
                    if($curr_proses !== '') {
                        $result[] = $this->_dataBuilder("section", $data[$key + 1]);
                    }
                }else if (!in_array($row->kategori_proses, [
                    MMtrgSetup::KATEGORI_DRYING,
                    MMtrgSetup::KATEGORI_PLYTECH
                ], true)) {
                    if($row->kategori_proses !== MMtrgSetup::KATEGORI_REPAIR) {
                        $result[] = $this->_dataBuilder("section", $data[$key + 1]);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param $type
     * @param $row
     * @param int $monthlies
     * @param array $data
     * @return array
     */
    protected function _dataBuilder($type, $row, $monthlies = 0, $data = [])
    {
        if($type === 'section') {
            return [
                'kategori_proses'   => $row->kategori_proses,
                'jenis_kayu'        => strtoupper($row->jenis_kayu),
                'jenis_proses'      => $row->jenis_proses,
                'grade'             => $row->jenis_proses,
                'plan_bulanan'      => "",
                'jumlah_bulanan'    => "",
                'monthly_achieve'   => "",
                'plan_harian'       => "",
                'jumlah_aktual'     => "",
                'daily_achieve'     => "",
                'satuan_harian'     => $row->satuan_harian
            ];
        }

        if($type === 'rendemen') {
            $rpbulanan = 0;
            $rjbulanan = 0;
            $rpharian  = 0;
            $rjharian  = 0;

            foreach ($data as $value) {
                if($row->kategori_proses === MMtrgSetup::KATEGORI_REPAIR) {
                    $row->satuan_harian = $row->satuan_harian ;
                    if($value['grade'] === 'Input') {
                        $rpbulanan = $value['plan_bulanan'];
                        $rjbulanan = $value['jumlah_bulanan'];
                        $rpharian  = $value['plan_harian'];
                        $rjharian  = $value['jumlah_aktual'];
                    }elseif($value['grade'] === 'Output') {
                        $rpbulanan = $rpbulanan > 0 ? $value['plan_bulanan'] / $rpbulanan * 100 : 0;
                        $rjbulanan = $rjbulanan > 0 ? $value['jumlah_bulanan'] / $rjbulanan * 100 : 0;
                        $rpharian  = $rpharian > 0 ? $value['plan_harian'] / $rpharian * 100 : 0;
                        $rjharian  = $rjharian > 0 ? $value['jumlah_aktual'] / $rjharian * 100 : 0;
                    }
                    elseif($value['grade'] !== 'Output' || $value['grade'] !== 'Input' ) {
                        $rpbulanan += $value['plan_bulanan'] > 0 ? $value['plan_bulanan'] : 0;
                        $rjbulanan += $value['jumlah_bulanan'] > 0 ? $value['jumlah_bulanan'] : 0;
                        $rpharian += $value['plan_harian'] > 0 ? $value['plan_harian'] : 0;
                        $rjharian += $value['jumlah_aktual'] > 0 ? $value['jumlah_aktual'] : 0;
                    }
                }else if($value['jenis_proses'] === 'OUTPUT' && $value['jenis_kayu'] === strtoupper($row->jenis_kayu)) {
                    if($row->kategori_proses === MMtrgSetup::KATEGORI_CORE_BUILDER && strpos($value['grade'], 'Sampah')) {
                        continue;
                    }
                    $rpbulanan += $value['plan_bulanan'];
                    $rjbulanan += $value['jumlah_bulanan'];
                    $rpharian  += $value['plan_harian'];
                    $rjharian  += $value['jumlah_aktual'];
                }
            }

            return [
                'kategori_proses'   => $row->kategori_proses,
                'jenis_kayu'        => strtoupper($row->jenis_kayu),
                'jenis_proses'      => $row->jenis_proses,
                'grade'             => 'RENDEMEN',
                'plan_bulanan'      => $rpbulanan,
                'jumlah_bulanan'    => $rjbulanan,
                'monthly_achieve'   => $rpbulanan > 0 ? $rjbulanan / $rpbulanan * 100 : 0,
                'plan_harian'       => $rpharian,
                'jumlah_aktual'     => $rjharian,
                'daily_achieve'     => $rpharian > 0 ? $rjharian / $rpharian * 100 : 0,
                'satuan_harian'     => $row->satuan_harian,
                'data' => $row
            ];
        }

        return [
            'kategori_proses'   => $row->kategori_proses,
            'jenis_kayu'        => strtoupper($row->jenis_kayu),
            'jenis_proses'      => $row->jenis_proses,
            'grade'             => $row->grade,
            'plan_bulanan'      => $monthlies['plan_bulanan'],
            'jumlah_bulanan'    => $monthlies['jumlah_bulanan'],
            'monthly_achieve'   => $monthlies['monthly_achieve'],
            'plan_harian'       => $row->plan_harian,
            'jumlah_aktual'     => $row->jumlah_aktual,
            'daily_achieve'     => $row->plan_harian > 0 ? $row->jumlah_aktual / $row->plan_harian * 100 : 0,
            'satuan_harian'     => $row->satuan_harian,
            'data' => $row
        ];
    }
}
