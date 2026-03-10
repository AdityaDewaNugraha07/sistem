<?php 
namespace app\components;
use Yii;
use yii\db\Exception;
use app\models\TTerimaLogalamDetail;

/**
* Class untuk custom format tertentu
* @author Arie Satriananta <ariesatriananta@yahoo.com>
*/
class DeltaGenerator 
{
	
	public static function ciptanaDefaultCodeFormat($table,$codecolumn,$prefix,$date=null)
    {
		$default = "001";
		if(!empty($date)){
			$tgl = date('dmy', strtotime( DeltaFormatter::formatDateTimeForDb($date)));
		}else{
			$tgl = date('dmy');
		}
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
    }
	public static function ciptanaDefaultCodeFormatMaster($table,$codecolumn,$prefix,$date=null)
    {
		$default = "01";
		if(!empty($date)){
			$tgl = date('dmy', strtotime( DeltaFormatter::formatDateTimeForDb($date)));
		}else{
			$tgl = date('dmy');
		}
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,9,2)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
    }
	
	public static function getUmur($tglLahir)
    {
        $tglLahir = DeltaFormatter::formatDateTimeForDb($tglLahir);
        $dob=$tglLahir; $today=date("Y-m-d");
        list($y,$m,$d)=explode('-',$dob);
        list($ty,$tm,$td)=explode('-',$today);
        if($td-$d<0){
            $day=($td+30)-$d;
            $tm--;
        }
        else{
            $day=$td-$d;
        }
        if($tm-$m<0){
            $month=($tm+12)-$m;
            $ty--;
        }
        else{
            $month=$tm-$m;
        }
        $year=$ty-$y;

//        $umur = str_pad($year, 2, '0', STR_PAD_LEFT).' Thn '. str_pad($month, 2, '0', STR_PAD_LEFT) .' Bln '. str_pad($day, 2, '0', STR_PAD_LEFT).' Hr';
        $umur = str_pad($year, 2, '0', STR_PAD_LEFT);
        
        return $umur;
    }
    
    public static function kodeSpb()
    {
        return self::ciptanaDefaultCodeFormat('t_spb', 'spb_kode', 'SPB');
    }
    
    public static function kodeBpb()
    {
		return self::ciptanaDefaultCodeFormat('t_bpb', 'bpb_kode', 'BPB');
    }
	
    public static function kodeSpp()
    {
		return self::ciptanaDefaultCodeFormat('t_spp', 'spp_kode', 'SPP');
    }
	
    public static function kodeSpo()
    {
		return self::ciptanaDefaultCodeFormat('t_spo', 'spo_kode', 'SPO');
    }
	
    public static function kodeSpl()
    {
		return self::ciptanaDefaultCodeFormat('t_spl', 'spl_kode', 'SPL');
    }
	
	public static function kodeTerimaBhp()
    {
		return self::ciptanaDefaultCodeFormat('t_terima_bhp', 'terimabhp_kode', 'TBP');
    }
	
	public static function kodePengajuanBiayaGreder()
    {
        return self::ciptanaDefaultCodeFormat('t_biaya_grader', 'biaya_grader_kode', 'PBG'); // Pengajuan Biaya Grader
    }
	
	public static function kodeLoglistJoinGrade()
    {
        return self::ciptanaDefaultCodeFormat('t_loglist', 'loglist_kode', 'LGL'); // Loglist
    }
	
	public static function kodePengajuanDpLog()
    {
        return self::ciptanaDefaultCodeFormat('t_log_bayar_dp', 'kode', 'PDL'); // Pengajuan Dp Log
    }
	
	public static function kodePemuatanLog()
    {
        return self::ciptanaDefaultCodeFormat('t_log_bayar_dp', 'kode', 'MLG'); // Muat Log
    }
	
	public static function kodeTerimaSengon()
    {
		return self::ciptanaDefaultCodeFormat('t_terima_sengon', 'kode', 'TSG'); // Terima Sengon
	}
        
        public static function kodeTerimaJabon()
    {
		return self::ciptanaDefaultCodeFormat('t_terima_sengon', 'kode', 'TJB'); // Terima Jabon
	}
    
	public static function kodeAfkirSengon()
    {
		return self::ciptanaDefaultCodeFormat('t_afkir_sengon', 'kode', 'ATS'); // Afkir Terima Sengon
	}
        
        public static function kodeAfkirJabon()
    {
		return self::ciptanaDefaultCodeFormat('t_afkir_sengon', 'kode', 'ATJ'); // Afkir Terima Jabon
	}
    
	public static function kodeMutasiSengon()
    {
		return self::ciptanaDefaultCodeFormat('t_mutasi_sengon', 'kode', 'MLS'); // Mutasi Log Sengon
	}
        
        public static function kodeMutasiJabon()
    {
		return self::ciptanaDefaultCodeFormat('t_mutasi_sengon', 'kode', 'MLJ'); // Mutasi Log Jabon
	}
	
	public static function kodeTagihanSengon()
    {
		return self::ciptanaDefaultCodeFormat('t_tagihan_sengon', 'kode', 'TFS'); // Tagihan Finance Sengon
	}
        
	public static function kodeTagihanJabon()
    {
		return self::ciptanaDefaultCodeFormat('t_tagihan_sengon', 'kode', 'TFJ'); // Tagihan Finance Jabon
	}
	
	public static function kodeJurnalAcct()
    {
		return self::ciptanaDefaultCodeFormat('t_acct_jurnal', 'kode', 'FJA'); // Jurnal Akuntansi
	}
	
	public static function kodeVoucherPengeluaran()
    {
		return self::ciptanaDefaultCodeFormat('t_voucher_pengeluaran', 'kode', 'FVK'); // Pengajuan Voucher
	}
    
    public static function kodeOpenVoucher()
    {
		return self::ciptanaDefaultCodeFormat('t_open_voucher', 'kode', 'OVK'); // Open Voucher Keluar
	}
	
	public static function kodeBuktiBankKeluar($acctbank_id,$tgl_bayar)
    {
		$modAcctRek = \app\models\MAcctRekening::findOne($acctbank_id);
		$rule1 = "BBK".substr($modAcctRek->acct_nm, -3,3);
		$rule2 = "K"; // Uang Keluar
		$rule3 = date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl_bayar)));
		$rule = $rule1.$rule2.$rule3;
        return $rule;
	}
	
	public static function kodeMutasiGudangLogistik()
    {
		return self::ciptanaDefaultCodeFormat('t_mutasi_gudanglogistik', 'kode', 'MGL'); // Mutasi Barang kayu dari gudang ke logistik
	}
	
	public static function kodeDpBhp()
    {
		return self::ciptanaDefaultCodeFormat('t_dp_bhp', 'kode', 'DPB');
    }
	
	public static function kodePengeluaranKasKecil($tgl)
    {
		$rule1 = "KK";
		$rule2 = date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$rule3 = "0001";
		$thn = date('Y', strtotime($tgl));
		$codecolumn = 'kode';
		$table = 't_kas_kecil';
        $sql = "SELECT MAX(CAST(SUBSTR(kode,5,4) AS integer)) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$rule1.$rule2."%') AND EXTRACT(year FROM tanggal) = '{$thn}'";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $rule1.$rule2.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($rule3), 0,STR_PAD_LEFT)) : $rule3);
        return $kode_baru;
    }
	
	public static function kodeKasBesar($tgl)
    {
		$rule1 = "KB";
		$rule2 = date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$rule3 = date("d",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$rule4 = "01";
		$codecolumn = 'kode';
		$table = 't_kas_besar';
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,7,2)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$rule1.$rule2.$rule3."%') AND EXTRACT(year FROM tanggal) = '".date("Y")."'";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $rule1.$rule2.$rule3.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($rule4), 0,STR_PAD_LEFT)) : $rule4);
        return $kode_baru;
    }
	
	public static function kodeKasBon()
    {
		return self::ciptanaDefaultCodeFormat('t_kas_bon', 'kode', 'B/S');
    }
	
	public static function kodePpk()
    {
		return self::ciptanaDefaultCodeFormat('t_ppk', 'kode', 'PPK');
    }
	
	public static function kodeKasBesarSetor()
    {
		return self::ciptanaDefaultCodeFormat('t_kas_besar_setor', 'kode', 'KBS');
    }
	
	public static function kodeReturBHP()
    {
		return self::ciptanaDefaultCodeFormat('t_retur_bhp', 'kode', 'RPB');
    }
	
	public static function sequenceKasKecil($tgl)
    {
		$codecolumn = 'seq';
		$table = 't_kas_kecil';
        $sql = "SELECT CAST(MAX($codecolumn) AS integer) nomaksimal FROM $table 
				WHERE tanggal = '{$tgl}'";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
		if(!empty($no)){
			$no_baru = $no['nomaksimal']+1;
		}else{
			$no_baru = 1;
		}
        return $no_baru;
    }
	
	public static function kodeBKK()
    {
		return self::ciptanaDefaultCodeFormat('t_bkk', 'kode', 'BKK');
    }
	public static function kodeGKK()
    {
		return self::ciptanaDefaultCodeFormat('t_gkk', 'kode', 'GKK');
    }
	public static function kodeDKG()
    {
		return self::ciptanaDefaultCodeFormat('t_dkg', 'kode', 'DKG');
    }
	public static function kodePDG()
    {
		return self::ciptanaDefaultCodeFormat('t_ajuandinas_grader', 'kode', 'PDG');
    }
	public static function kodeRDG()
    {
		return self::ciptanaDefaultCodeFormat('t_realisasidinas_grader', 'kode', 'RDG');
    }
	public static function kodePMG()
    {
		return self::ciptanaDefaultCodeFormat('t_ajuanmakan_grader', 'kode', 'PMG');
    }
	public static function kodeRMG()
    {
		return self::ciptanaDefaultCodeFormat('t_realisasimakan_grader', 'kode', 'RMG');
    }
	
	public static function kodeKasBesarNontunai($tgl)
    {
		$model = \app\models\TKasBesarNontunai::findOne(['tanggal'=>$tgl]);
		if(!empty($model)){
			$kode_generate = $model->kode; 
		}else{
			$rule1 = "LPGC";
			$rule2 = date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
			$rule3 = "01";
            $tahun = date("Y",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
			$codecolumn = 'kode';
			$table = 't_kas_besar_nontunai';
			$sql = "SELECT CAST(MAX(SUBSTR($codecolumn,7,2)) AS integer) nomaksimal
					FROM $table 
					WHERE $codecolumn ILIKE ('%".$rule1.$rule2."%') AND EXTRACT(year FROM tanggal) = '{$tahun}'";
			$no = Yii::$app->db->createCommand($sql)->queryOne();
			$kode_generate = $rule1.$rule2.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($rule3), 0,STR_PAD_LEFT)) : $rule3);
		}
        return $kode_generate;
    }
	
	public static function sequenceKasBesarNontunai($tgl)
    {
		$codecolumn = 'seq';
		$table = 't_kas_besar_nontunai';
        $sql = "SELECT CAST(MAX($codecolumn) AS integer) nomaksimal FROM $table 
				WHERE tanggal = '{$tgl}'";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
		if(!empty($no)){
			$no_baru = $no['nomaksimal']+1;
		}else{
			$no_baru = 1;
		}
        return $no_baru;
    }
	
	public static function kodePenerimaanKayuOlahan()
    {
		return self::ciptanaDefaultCodeFormat('t_terima_ko', 'kode', 'TKO');
	}
	
	public static function mutasiKayuOlahan()
    {
		return self::ciptanaDefaultCodeFormat('t_mutasi_keluar', 'kode', 'MKO');
	}
	
	public static function orderPenjualan($jns)
    {
		switch ($jns){
                    case "Plywood":
			$prefix = "POP";
			break;
                    case "Veneer":
			$prefix = "VOP";
			break;
                    case "Sawntimber":
			$prefix = "SOP";
			break;
                    case "Moulding":
			$prefix = "MOP";
			break;
                    case "Log":
			$prefix = "LOP";
			break;
                    case "Lamineboard":
			$prefix = "BOP";
			break;
                    case "Platform":
			$prefix = "FOP";
			break;
                    case "Limbah":
			$prefix = "HOP";
			break;
                    case "JasaKD":
			$prefix = "KOP";
			break;
                    case "JasaGesek":
			$prefix = "GOP";
			break;
                    case "JasaMoulding":
			$prefix = "DOP";
			break;
					case 'FingerJointLamineBoard': 
			$prefix = 'JOP';
			break;
					case 'Flooring':
			$prefix = 'OOP';
			break;
					case 'FingerJointStick':
			$prefix = 'COP';
			break;
		}
		$codecolumn = 'kode';
		$table = 't_op_ko';
		$default = "001";
		$tgl = date('dmy');
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
	}
	
	public static function tempoBayarKayuolahan()
    {
		return self::ciptanaDefaultCodeFormat('t_tempobayar_ko', 'kode', 'TBL');
	}

	/**
	 * @throws Exception
	 */
	public static function kodeSpm($jns)
    {
		switch ($jns){
		case "Plywood":
			$prefix = "PLM";
			break;
		case "Veneer":
			$prefix = "VNM";
			break;
		case "Sawntimber":
			$prefix = "STM";
			break;
		case "Moulding":
			$prefix = "MDM";
			break;
		case "Log":
			$prefix = "LGM";
			break;
		case "Lamineboard":
			$prefix = "BGM";
			break;
		case "Platform":
			$prefix = "FGM";
			break;
        case "Limbah":
			$prefix = "MHM";
			break;
        case "JasaKD":
			$prefix = "KDM";
			break;
        case "JasaGesek":
			$prefix = "GEM";
			break;
        case "JasaMoulding":
			$prefix = "LDM";
			break;
		case 'FingerJointLamineBoard': 
			$prefix = 'JDM';
			break;
		case 'Flooring':
			$prefix = 'ODM';
			break;
		case 'FingerJointStick':
			$prefix = 'CDM';
			break;
		default:
			$prefix = '';
			break;
		}
		$codecolumn = 'kode';
		$table1 = 't_spm_ko';
		$table2 = 't_pengajuan_manipulasi';
		$default = "001";
		$tgl = date('dmy');
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM (
                    SELECT $codecolumn FROM $table1 WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')
                    UNION 
                    SELECT reff_no AS $codecolumn FROM $table2 WHERE reff_no ILIKE ('%".$prefix.$tgl."%')
                ) gabungan";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
		return $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
	}
	
	public static function kodeNotaPenjualan($jns)
    {
		switch ($jns){
		case "Plywood":
			$prefix = "PNP";
			break;
		case "Veneer":
			$prefix = "VNP";
			break;
		case "Sawntimber":
			$prefix = "SNP";
			break;
		case "Moulding":
			$prefix = "MNP";
			break;
		case "Log":
			$prefix = "LNP";
			break;
		case "Lamineboard":
			$prefix = "BNP";
			break;
		case "Platform":
			$prefix = "FNP";
			break;
        case "Limbah":
			$prefix = "HNP";
			break;
        case "JasaKD":
			$prefix = "KNP";
			break;
        case "JasaGesek":
			$prefix = "GNP";
			break;
        case "JasaMoulding":
			$prefix = "DNP";
			break;
		case "FingerJointLamineBoard": 
			$prefix = "JNP";
			break;
		case "Flooring": 
			$prefix = "ONP";
			break;
		case "FingerJointStick": 
			$prefix = "CNP";
			break;
		}
		$codecolumn = 'kode';
		$table = 't_nota_penjualan';
		$default = "001";
		$tgl = date('dmy');
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
	}
	
	public static function kodeSuratPengantar($jns)
    {
		switch ($jns){
		case "Plywood":
			$prefix = "PSP";
			break;
		case "Veneer":
			$prefix = "VSP";
			break;
		case "Sawntimber":
			$prefix = "SSP";
			break;
		case "Moulding":
			$prefix = "MSP";
			break;
		case "Log":
			$prefix = "LSP";
			break;
		case "Lamineboard":
			$prefix = "BSP";
			break;
		case "Platform":
			$prefix = "FSP";
			break;
        case "Limbah":
			$prefix = "HSP";
			break;
        case "JasaKD":
			$prefix = "KSP";
			break;
        case "JasaGesek":
			$prefix = "GSP";
			break;
        case "JasaMoulding":
			$prefix = "DSP";
			break;
		case "FingerJointLamineBoard":
			$prefix = "JSP";
			break;
		case "Flooring":
			$prefix = "OSP";
			break;
		case "FingerJointStick":
			$prefix = "CSP";
			break;
		}
		$codecolumn = 'kode';
		$table = 't_surat_pengantar';
		$default = "001";
		$tgl = date('dmy');
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
	}
	
	public static function kodeProdukKeluar($jns)
    {
		switch ($jns){
		case "Plywood":
			$prefix = "PKG";
			break;
		case "Veneer":
			$prefix = "VKG";
			break;
		case "Sawntimber":
			$prefix = "SKG";
			break;
		case "Moulding":
			$prefix = "MKG";
			break;
		case "Log":
			$prefix = "LKG";
			break;
		case "Lamineboard":
			$prefix = "BKG";
			break;
		case "Platform":
			$prefix = "FKG";
			break;
		case "FingerJointLamineBoard":
			$prefix = "JKG";
			break;
		case "Flooring":
			$prefix = "OKG";
			break;
		case "FingerJointStick":
			$prefix = "CKG";
			break;
		}
		$codecolumn = 'kode';
		$table = 't_produk_keluar';
		$default = "001";
		$tgl = date('dmy');
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
	}

	/**
	 * @param $jns
	 * @return string
	 */
	public static function kodeProdukkembali($jns)
	{
		try {
			$prefix = '';
			switch ($jns) {
				case "Plywood":
					$prefix = "PBG";
					break;
				case "Veneer":
					$prefix = "VBG";
					break;
				case "Sawntimber":
					$prefix = "SBG";
					break;
				case "Moulding":
					$prefix = "MBG";
					break;
				case "Log":
					$prefix = "LBG";
					break;
				case "Lamineboard":
					$prefix = "BBG";
					break;
				case "Platform":
					$prefix = "FBG";
				case "FingerJointLamineBoard":
					$prefix = "JBG";
					break;
				case "Flooring":
					$prefix = "OBG";
					break;
				case "FingerJointStick":
					$prefix = "CBG";
					break;
			}
			$codecolumn = 'kode';
			$table = 't_produk_kembali';
			$default = "001";
			$tgl = date('dmy');
			$sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
					FROM $table 
					WHERE $codecolumn ILIKE ('%" . $prefix . $tgl . "%')";
			$no = Yii::$app->db->createCommand($sql)->queryOne();
			return $prefix . $tgl . (isset($no['nomaksimal']) ? (str_pad($no['nomaksimal'] + 1, strlen($default), 0, STR_PAD_LEFT)) : $default);

		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}
	
	public static function dokumenPenjualan($jns)
    {
		switch ($jns){
		case "Plywood":
			$prefix = "CWM-NP";
			break;
		case "Veneer":
			$prefix = "CWM-NP";
			break;
		case "Sawntimber":
			$prefix = "DKO/CWM";
			break;
		case "Moulding":
			$prefix = "DKO/CWM";
			break;
		case "Log":
			$prefix = "DKB/CWM";
			break;
		case "Lamineboard":
			$prefix = "CWM-NP";
			break;
		case "Platform":
			$prefix = "CWM-NP";
            break;
        case "JasaKD":
			$prefix = "DKO/CWM";
            break;
        case "JasaGesek":
			$prefix = "DKO/CWM";
            break;
        case "JasaMoulding":
			$prefix = "DKO/CWM";
            break;
		case "FingerJointStick":
			$prefix = "CWM-NP";
			break;
		case "FingerJointLamineBoard":
			$prefix = "CWM-NP";
			break;
		case "Flooring":
			$prefix = "CWM-NP";
			break;
		}
		$codecolumn = 'nomor_dokumen';
		$table = 't_dokumen_penjualan';
		$default = "001";
		$thn = date('Y');
		$bln = DeltaFunctions::Romawi(date('m'));
		$prefix = "/".$prefix."/".$bln."/".$thn;
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,1,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
		$seq = (isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        $kode_baru = $seq.$prefix;
        return $kode_baru;
	}
	
	public static function kodePPC()
    {
		return self::ciptanaDefaultCodeFormat('t_piutang_penjualan', 'kode', 'PPC');
    }
    public static function kodePPD()
    {
		return self::ciptanaDefaultCodeFormat('t_piutang_penjualan', 'kode', 'PPD');
    }
	
	public static function kodeBuyer()
    {
		$codecolumn = 'cust_kode';
		$table = 'm_customer';
		$default = "0001";
		$prefix = "B";
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,2,4)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
    }
	
	public static function nomorPackinglist($tgl,$default)
    {
		$rule1 = "/CWM";
		$rule2 = "/".DeltaFunctions::Romawi(date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl))));
		$rule3 = "/".date("Y",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		return "XXXXX".$rule1.$rule2.$rule3;
    }
	
	public static function kodeVoucherPenerimaan()
    {
		return self::ciptanaDefaultCodeFormat('t_voucher_penerimaan', 'kode', 'FVM');
	}

	/* public static function kodeBuktiBankMasuk($acctbank_id,$tgl)
    {
		$modAcctRek = \app\models\MAcctRekening::findOne($acctbank_id);
		$rule1 = substr($modAcctRek->acct_nm, -3,3);
		$rule2 = "M";
		$rule3 = date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$default = "01";
		$sql = "SELECT CAST(MAX(SUBSTR(kode_bbm,7,2)) AS integer) nomaksimal
                FROM t_voucher_penerimaan 
				WHERE kode_bbm ILIKE ('%".$rule1.$rule2.$rule3."%') AND to_char(tanggal::DATE,'yyyy') = '".(date("Y"))."'";
        $rule4 = Yii::$app->db->createCommand($sql)->queryOne();
		$modVoucher = \app\models\TVoucherPenerimaan::find()->where("kode_bbm ILIKE '%".$rule1.$rule2.$rule3."%' AND tanggal = '".DeltaFormatter::formatDateTimeForDb($tgl)."'")->one();
		if(!empty($modVoucher)){
			$rule = $modVoucher->kode_bbm;
		}else{
			$rule = $rule1.$rule2.$rule3.(isset($rule4['nomaksimal']) ? (str_pad($rule4['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
		}

        return $rule;
    }*/
	public static function kodeBuktiBankMasuk($acctbank_id, $tgl)
	{
		$modAcctRek = \app\models\MAcctRekening::findOne($acctbank_id);
		$rule1 = substr($modAcctRek->acct_nm, -3,3);
		$rule2 = "M";
		$rule3 = date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$default = "01";
		$sql = "SELECT CAST(MAX(SUBSTR(kode_bbm,7,2)) AS integer) nomaksimal
					FROM t_voucher_penerimaan 
				WHERE kode_bbm ILIKE ('%".$rule1.$rule2.$rule3."%') AND to_char(tanggal::DATE,'yyyy') = '".(date("Y"))."'";
		$rule4 = Yii::$app->db->createCommand($sql)->queryOne();
		$sql_today = "SELECT MAX(CAST(SUBSTR(kode_bbm,7,2) AS integer)) as harimaks
                  		FROM t_voucher_penerimaan
                  	  WHERE kode_bbm ILIKE ('%".$rule1.$rule2.$rule3."%') AND tanggal = '".DeltaFormatter::formatDateTimeForDb($tgl)."'";
		$rule4a = Yii::$app->db->createCommand($sql_today)->queryOne();
		
		if(!empty($rule4a['harimaks'])){
			$day_counter = $rule4a['harimaks'];
		} else {
			$day_counter = isset($rule4['nomaksimal']) ? $rule4['nomaksimal'] + 1 : $default;
		}
		$nomaks = str_pad($day_counter, 2, '0', STR_PAD_LEFT);
		$prefix = $rule1.$rule2.$rule3.$nomaks;
		
		// ambil maks urutan
		$sql2 = "SELECT CAST(MAX(SUBSTR(kode_bbm,9,2)) AS integer) urutan
				FROM t_voucher_penerimaan
				WHERE LENGTH(kode_bbm) = 10
				AND kode_bbm ILIKE ('%".$rule1.$rule2.$rule3."%')
				AND tanggal = '".$tgl."'";
		$rule5 = Yii::$app->db->createCommand($sql2)->queryOne();
		$urutan = isset($rule5['urutan']) ? str_pad($rule5['urutan']+1, 2, '0', STR_PAD_LEFT) : $default;

		$rule = $prefix.$urutan;
		// cek apakah kode/rule sdh ada di db
		$modVoucher = \app\models\TVoucherPenerimaan::find()->where("kode_bbm = '".$rule."' AND tanggal = '".DeltaFormatter::formatDateTimeForDb($tgl)."'")->one();
		if(!empty($modVoucher)){
			$rule = $modVoucher->kode_bbm;
		}

		return $rule;
	}
	
	public static function orderPenjualanExport($jns)
    {
		switch ($jns){
		case "Plywood":
			$prefix = "POX";
			break;
		case "Veneer":
			$prefix = "VOX";
			break;
		case "Sawntimber":
			$prefix = "SOX";
			break;
		case "Moulding":
			$prefix = "MOX";
			break;
		case "Log":
			$prefix = "LOX";
			break;
		case "Lamineboard":
			$prefix = "BOX";
			break;
		case "Platform":
			$prefix = "FOX";
			break;
		case "FingerJointLamineBoard":
			$prefix = "JOX";
			break;
		case "Flooring":
			$prefix = "OOX";
			break;
		case "FingerJointStick":
			$prefix = "COX";
			break;
		}
		$codecolumn = 'kode';
		$table = 't_op_export';
		$default = "001";
		$tgl = date('dmy');
        $sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
        $no = Yii::$app->db->createCommand($sql)->queryOne();
        $kode_baru = $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $kode_baru;
	}
	
	public static function kodeProformaPackinglist($revisi)
    {
		return self::ciptanaDefaultCodeFormat('t_packinglist', 'kode', 'PPL')."-".$revisi;
    }
	
	public static function kodeReturProduk()
    {
		return self::ciptanaDefaultCodeFormat('t_retur_produk', 'kode', 'RPP');
    }
	public static function kodeTHS()
    {
		return self::ciptanaDefaultCodeFormat('t_penawaran_bhp', 'kode', 'THS');
    }
	public static function kodeHasilOrientasi()
    {
		return self::ciptanaDefaultCodeFormat('t_hasil_orientasi', 'kode', 'HOL');
    }
	public static function kodePengajuanPembelianLog()
    {
		return self::ciptanaDefaultCodeFormat('t_pengajuan_pembelianlog', 'kode', 'PBL');
    }
	public static function kodeMonitoringPembelianLog()
    {
		return self::ciptanaDefaultCodeFormat('t_monitoring_pembelianlog', 'kode', 'MBL');
    }
	public static function kodePOLogAlam()
    {
		return self::ciptanaDefaultCodeFormat('t_log_kontrak', 'kode', 'LPO');
    }
	public static function kodeIncomingPelabuhan()
    {
        return self::ciptanaDefaultCodeFormat('t_incoming_pelabuhan', 'kode', 'LMP'); // log masuk pelabuhan
    }
	public static function kodeIncomingDkb()
    {
        return self::ciptanaDefaultCodeFormat('t_incoming_pelabuhan', 'kode', 'LMP');
    }
	public static function kodeKuitansi($cara_bayar,$tgl)
    {
		if($cara_bayar=="Tunai"){
			$cb="C";
		}else{
			$cb="T";
		}
		$rule1 = "KUI-".$cb."-";
		$rule2 = date("m",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$rule3 = date("d",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$rule4 = date("Y",strtotime(DeltaFormatter::formatDateTimeForDb($tgl)));
		$default = "01";
		$sql = "SELECT CAST(MAX(SUBSTR(nomor,11,2)) AS integer) nomaksimal
                        FROM t_kuitansi 
                        WHERE nomor ILIKE '%".$rule1.$rule2.$rule3."%' 
                        and EXTRACT(YEAR FROM(tanggal)) = '".$rule4."' 
				";
        $mod = Yii::$app->db->createCommand($sql)->queryOne();
		$res = $rule1.$rule2.$rule3.(isset($mod['nomaksimal']) ? (str_pad($mod['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $res;
    }
	
	public static function kodePemotonganKayu()
    {
        return self::ciptanaDefaultCodeFormat('t_pemotongan_kayu', 'kode', 'PKB'); // potong kayu bulat
    }
	public static function kodeKeberangkatanTongkang()
    {
        return self::ciptanaDefaultCodeFormat('t_keberangkatan_tongkang', 'kode', 'KTB'); // keberangkatan tongkang berlayar
    }
	public static function kodePOCardpad()
    {
		$default = "001";
		$sql = "SELECT MAX(kode_cardpad::integer) AS nomaksimal
                FROM t_log_kontrak 
				WHERE EXTRACT(year FROM tanggal_po) = '".date("Y")."'";
        $mod = Yii::$app->db->createCommand($sql)->queryOne();
		$res = (isset($mod['nomaksimal']) ? (str_pad($mod['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
        return $res;
    }
    public static function kodePengajuanRepacking()
    {
        return self::ciptanaDefaultCodeFormat('t_pengajuan_repacking', 'kode', 'ARP'); // ajuan repacking produk
    }
    
    public static function kodeHasilRepacking()
    {
		return self::ciptanaDefaultCodeFormat('t_hasil_repacking', 'kode', 'HRP');
	}
	
    public static function terimaMutasiProduk()
    {
		return self::ciptanaDefaultCodeFormat('t_terima_mutasi', 'kode', 'TMP');
	}
    
    public static function kodeAjuanManipulasiData()
    {
		return self::ciptanaDefaultCodeFormat('t_pengajuan_manipulasi', 'kode', 'AMD');
    }
    
    public static function kodeHasilProduksi()
    {
		return self::ciptanaDefaultCodeFormat('t_hasil_produksi', 'kode', 'HPP');
	}
    
    public static function kodeKirimGudang()
    {
		return self::ciptanaDefaultCodeFormat('t_kirim_gudang', 'kode', 'BMG');
	}
    public static function kodeAgendaStockopname()
    {
		return self::ciptanaDefaultCodeFormat('t_stockopname_agenda', 'kode', 'ASO');
	}
    public static function kodeHasilStockopname()
    {
		return self::ciptanaDefaultCodeFormat('t_stockopname_hasil', 'kode', 'HSO');
	}
    public static function purchaseMaterialRequest()
    {
		return self::ciptanaDefaultCodeFormat('t_pmr', 'kode', 'PMR');
	}
    public static function rencanaPOSengon()
    {
		return self::ciptanaDefaultCodeFormat('t_posengon_rencana', 'kode', 'RBS');
	}
    public static function rencanaPOJabon()
    {
		return self::ciptanaDefaultCodeFormat('t_posengon_rencana', 'kode', 'RBJ');
	}
    public static function kodePOSengon()
    {
		return self::ciptanaDefaultCodeFormat('t_posengon', 'kode', 'PLS');
	}
    public static function kodePOJabon()
    {
		return self::ciptanaDefaultCodeFormat('t_posengon', 'kode', 'PLJ');
	}    
    public static function masterPenerimaVoucher()
    {
		return self::ciptanaDefaultCodeFormatMaster('m_penerima_voucher', 'kode', 'PV');
	}
    public static function kodeCustomer()
    {
		return self::ciptanaDefaultCodeFormat('m_customer', 'kode_customer', 'CUS');
	}
    public static function kodeHargaProduk()
    {
		return self::ciptanaDefaultCodeFormat('m_harga_produk', 'kode', 'PRP');
	}
    public static function kodeHargaLimbah()
    {
        return self::ciptanaDefaultCodeFormat('m_harga_limbah', 'kode', 'PRL');
    }
    /*public static function kodeHargaLimbah()
    {
		return self::ciptanaDefaultCodeFormat('m_customer', 'kode_customer', 'HRL');
	}
    public static function kodeHargaJasa()
    {
		return self::ciptanaDefaultCodeFormat('m_customer', 'kode_customer', 'HRJ');
	}
    public static function kodeHargaLog()
    {
		return self::ciptanaDefaultCodeFormat('m_customer', 'kode_customer', 'HRG');
	}*/
    public static function kodeAsuransi()
    {
		return self::ciptanaDefaultCodeFormat('t_asuransi', 'kode', 'ASR');
    }

	/**
	 * @throws Exception
	 */
	public static function kodeMonitoringInOut($kategori)
	{
		switch ($kategori){
			case "ROTARY":
				$prefix = "OMP";
				break;
			case "DRYING":
				$prefix = "DMP";
				break;
			case "CORE BUILDER":
				$prefix = "CMP";
				break;
			case "PLYTECH":
				$prefix = "PMP";
				break;
			case "REPAIR":
				$prefix = "RMP";
				break;
			case "SETTING":
				$prefix = "SMP";
				break;
			default:
				$prefix = '';
				break;
		}
		$codecolumn = 'kode';
		$table = 't_mtrg_in_out';
		$default = "001";
		$tgl = date('dmy');
		$sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
		$no = Yii::$app->db->createCommand($sql)->queryOne();
		return $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
	}

    /**
	 * @throws Exception
	 */
	public static function kodeMonitoringRotary()
	{
		$codecolumn = 'kode';
		$table = 't_mtrg_rotary';
		$default = "001";
		$tgl = date('dmy');
        $prefix = 'IMP';
		$sql = "SELECT CAST(MAX(SUBSTR($codecolumn,10,3)) AS integer) nomaksimal
                FROM $table 
				WHERE $codecolumn ILIKE ('%".$prefix.$tgl."%')";
		$no = Yii::$app->db->createCommand($sql)->queryOne();
		return $prefix.$tgl.(isset($no['nomaksimal']) ? (str_pad($no['nomaksimal']+1, strlen($default), 0,STR_PAD_LEFT)) : $default);
	}

	public static function kodeTerimaLogAlam()
	{
		return self::ciptanaDefaultCodeFormat('t_terima_logalam', 'kode', 'TSP');
	}

	public static function kodeCardPad()
	{
		// cek dulu kode terakhir
		$sql_kode_cardpad = "select max(kode_cardpad) from t_pengajuan_pembelianlog";
		$kode_cardpad = \Yii::$app->db->createCommand($sql_kode_cardpad)->queryScalar();
		// jika kode sebelumnya tidak kosong, ya nyekrip sek dul
		if ($kode_cardpad != '' && $kode_cardpad != NULL) {
			$huruf = substr($kode_cardpad, 0, 1);
			$angka = substr($kode_cardpad, 1, 2) * 1;
			// jika angka dari kode sebelumnya 99, nyekrip sek dul
			if ($angka == 99) {
				// jika huruf dari kode sebelumnya Z, maka reset huruf ke A dan angka pasti 1 la
				if ($huruf == "Z") {
					$huruf = "A";
					$angka = "01";
				}
				// jika huruf dari sebelumhya bukan Z, maka lanjutkan ke huruf berikutnya dan angka pasti 1 juga la
				else {
					$huruf++;
					$angka = "01";
				}
			}
			// jika angka dari kode sebelumnya bukan 99, ya lanjut ke angka berikutnya la
			// https://dzone.com/articles/increment-numeric-part-of-string baca ini la
			else {
				$huruf = $huruf;
				$angka = $angka + 1;
				// jika angka kurang dari 10, kasi leading zero la
				if ($angka < 10) {
					$angka = str_pad($angka, 2, '0', STR_PAD_LEFT);
				}
				// jika angka 10+ ya nggak perlu leading zero la
				else {
					$angka = $angka;
				}
			}
			// gabungkan huruf dan angka kalau sudah dul
			$kode_cardpad = $huruf . $angka;
		}
		// jika kode sebelumnya kosong ya kasi A01 la
		else {
			$huruf = "A";
			$angka = "01";
			$kode_cardpad = $huruf . $angka;
		}
		return $kode_cardpad;
	}

	public static function kodeSpmLog()
	{
		return self::ciptanaDefaultCodeFormat('t_spk_shipping', 'kode', 'SIP');
	}

	public static function kodeLapTerimaLogalam($kode_partai, $kode_potong, $no_btg, $no_grade, $no_produksi, $terima_logalam_id)
	{
		$nomor_urut	= 1;
		$result 	= '';
		$sql 		= " SELECT regexp_replace( SUBSTRING ( B.no_lap FROM '[^.]*\.(.*)' ), '[^0-9]', '', 'g' ) 
						FROM t_terima_logalam A 
						INNER JOIN t_terima_logalam_detail B ON A.terima_logalam_id = B.terima_logalam_id 
						WHERE A.peruntukan = 'Industri'
						AND A.no_dokumen ILIKE '%$kode_partai%' 
						ORDER BY regexp_replace( SUBSTRING ( B.no_lap FROM '[^.]*\.(.*)' ), '[^0-9]', '', 'g' ) :: INTEGER DESC
						LIMIT 1 -- update 2023-04-01 (tambahin limit) 
					  ";
		$query_lap = Yii::$app->db->createCommand($sql)->queryScalar();
		if($query_lap) {
			$nomor_urut = (int)$query_lap;
			$nomor_urut++;
		}

		if($kode_potong !== "") {
			$cek = TTerimaLogalamDetail::find()->where(['ilike', 'no_btg', explode('.', $no_btg)[0]])->andWhere(['no_grade' => $no_grade, 'no_produksi'=>$no_produksi, 'terima_logalam_id' => $terima_logalam_id])->count();
			if($cek > 0) {
				$nomor_urut--;
			}
			$result = $kode_partai . '.' . $nomor_urut . $kode_potong;
		} else {
			$result = $kode_partai . '.' . $nomor_urut;
		}
		return $result;
	}

	public static function kodeAdjustmentPenerimaanLog()
	{
		return self::ciptanaDefaultCodeFormat('t_adjustment_log', 'kode', 'ADL');
	}

	public static function kodeLogKeluar()
	{
		return self::ciptanaDefaultCodeFormat('t_log_keluar', 'kode', 'LOK');
	}
	public static function kodeHargaLog()
    {
        return self::ciptanaDefaultCodeFormat('m_harga_log', 'kode', 'LOG');
    }
	public static function kodeBudgeting()
    {
		return self::ciptanaDefaultCodeFormat('t_terima_bhp_sub', 'kode', 'TBB');
    }
	public static function kodeRealisasiPemakaianbhp()
	{
		return self::ciptanaDefaultCodeFormat('t_pemakaian_bhpsub', 'kode', 'PBP');
	}
	public static function kodePengajuanDrp()
	{
		return self::ciptanaDefaultCodeFormat('t_pengajuan_drp', 'kode', 'DRP');
	}

	public static function kodePengajuanMasterProduk()
	{
		return self::ciptanaDefaultCodeFormat('t_pengajuan_masterproduk', 'kode', 'MPR');
	}

	public static function kodePO()
	{
		return self::ciptanaDefaultCodeFormat('t_po_ko', 'kode', 'POC');
	}

	public static function kodePemotonganLog()
    {
        return self::ciptanaDefaultCodeFormat('t_pemotongan_log', 'kode', 'PLG'); // potong log
    }

	public static function kodeKembaliLog()
    {
        return self::ciptanaDefaultCodeFormat('t_pengembalian_log', 'kode', 'KLG'); // pengembalian log
    }

	public static function kodeSpkSawmill()
    {
        return self::ciptanaDefaultCodeFormat('t_spk_sawmill', 'kode', 'SSW');
    }

	public static function kodeBrakedown()
    {
        return self::ciptanaDefaultCodeFormat('t_brakedown', 'kode', 'BKD');
    }

	public static function kodeBandsaw()
    {
        return self::ciptanaDefaultCodeFormat('t_bandsaw', 'kode', 'BSW');
    }

	public static function kodeDefectswm()
    {
        return self::ciptanaDefaultCodeFormat('t_defect_swm', 'kode', 'DFS');
    }

	public static function kodeLosstimeswm(){
		return self::ciptanaDefaultCodeFormat('t_losstime_swm', 'kode', 'LSS');
	}

	public static function kodeRubahJenis(){
		return self::ciptanaDefaultCodeFormat('t_log_rubahjenis', 'kode', 'RJK');
	}
}

