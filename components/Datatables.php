<?php
namespace app\components;

use Yii;
use yii\base\Component;
 
class Datatables extends Component {
    
    public function setConfig($params=null){
        
//        $aColumns = array('id', 'reg_code', 'name', 'breakout_room', 'teambuilding_group', 'arrived', 'arrived_time');
        $aColumns = isset($params['column'])?$params['column']:'';
        $sIndexColumn = isset($params['index'])?$params['index']:'';
        $sTable = isset($params['tablename'])?$params['tablename']:'';
        $sJoin = isset($params['join'])?$params['join']:'';
        $sWhere = isset($params['where'])?$params['where']:'';
        $sOrder = isset($params['order'])?$params['order']:'';
        $sLimit = isset($params['limit'])?$params['limit']:'';

        /* 
         * Paging
         */
        (!isset($sLimit))?$sLimit = "":"";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
            $sLimit = "LIMIT ". $_GET['iDisplayStart'] .", ".
                 $_GET['iDisplayLength'] ;
        }

        /*
         * Ordering
         */
        if ( isset( $_GET['iSortCol_0'] ) )        
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
            {
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
                {
					$namacolomorder = explode(" ", $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]);
					$namacolomorder = $namacolomorder[0];
                    $sOrder .= $namacolomorder."
                        ". $_GET['sSortDir_'.$i] .", ";
                }
            }

            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }

		
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */

//        (!isset($sWhere))?$sWhere = "":"";
        if(isset($_GET['sSearch'])){
            if ( $_GET['sSearch'] != "" ){
				if(!empty($sWhere)){
					$sWhere = str_replace('WHERE', '', $sWhere);
					$sWhere = str_replace('where', '', $sWhere);
					$sWhere = "WHERE (".$sWhere.") AND (";
				}else{
					$sWhere = "WHERE (";
				}
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
					$namacolom = explode(" ", $aColumns[$i]);
					$namacolom = $namacolom[0];
                    $sWhere .= $namacolom." LIKE '%". $_GET['sSearch'] ."%' OR ";
                }
                $sWhere = substr_replace( $sWhere, "", -3 );
                $sWhere .= ')';
            }

            /* Individual column filtering */
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
                {
                    if ( $sWhere == "" )
                    {
                        $sWhere = "WHERE ";
                    }
                    else
                    {
                        $sWhere .= " AND ";
                    }
					$namacolom = explode(" ", $aColumns[$i]);
					$namacolom = $namacolom[0];
                    $sWhere .= $aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
                }
            }
        }
	
        /* costum group by */
        if(isset($sGroup)){
            $sGroup = $sGroup;
        }else{
            $sGroup=null;
        }
		
        /*
        * SQL queries
        * Get data to display
        */
        $sQuery = "
           SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
           FROM   $sTable
			$sJoin
           $sWhere
			$sGroup
           $sOrder
           $sLimit
        ";
//		echo "<pre>";
//		print_r($sQuery);
//		exit;
        $rResult = Yii::$app->db->createCommand($sQuery)->queryAll();
		
        /* Data set length after filtering */
        $sQuery = " SELECT FOUND_ROWS() 'iFilteredTotal'";
        $rResultFilterTotal = Yii::$app->db->createCommand($sQuery)->queryAll();
        $iFilteredTotal = $rResultFilterTotal[0]['iFilteredTotal'];

        /* Total data set length */
        $sQuery = "SELECT COUNT(".$sIndexColumn.") 'iTotal' FROM   $sTable ";
        $rResultTotal = Yii::$app->db->createCommand($sQuery)->queryAll();
        $iTotal = $rResultTotal[0]['iTotal'];

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        $output['aaData'] = $rResult;
        
        return \yii\helpers\Json::encode($output);
        
    }
    
}

?>