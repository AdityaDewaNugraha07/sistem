<?php 
namespace app\components;
/**
* Class untuk konvert params datatables ke raw query yang dapat di exec dengan DAO
* @author Arie Satriananta <ariesatriananta@yahoo.com>
*/

class DtParamsToRawQuery extends \yii\base\Component
{
    static function generate ( $param )
	{
        $request = $_REQUEST;
		$conn = null;
		$table = $param['table'];
		$primaryKey = $param['pk'];
		$whereResult = null;
		$whereAll = (isset($param['where'])?$param['where']:null);
		$join = (isset($param['join'])?$param['join']:null);
		$group = (isset($param['group'])?$param['group']:null);
		$orderAll = (isset($param['order'])?$param['order']:null);
		$having = (isset($param['having'])?$param['having']:null);
		$limit = (isset($param['limit'])?$param['limit']:null);
		foreach($param['column'] as $i => $valcol){
			if(is_array($valcol)){
				if(isset($valcol['formatter'])){
					$columns[] = ['db'=>$valcol['col_name'],'dt'=>$i,'formatter'=>$valcol['formatter']];
				}else{
					$columns[] = ['db'=>$valcol['col_name'],'dt'=>$i];
				}
			}else{
				$columns[] = ['db'=>$valcol,'dt'=>$i];
			}
		}
		
		$bindings = array();
		$bindings_params = array();
//		$db = self::db( $conn );
		$localWhereResult = array();
		$localWhereAll = array();
		$whereAllSql = '';
		
        // Build the SQL query string from the request
//		$limit = self::limit( $request, $columns );
//		$order = self::order( $request, $columns );
//		$where = self::filter( $request, $columns, $bindings );
		$order = "";
		$where = "";
        
		// Build the SQL query string from the request
		$whereResult = self::_flatten( $whereResult );
		$whereAll = self::_flatten( $whereAll );
		$orderAll = self::_flatten2( $orderAll );
		$join = self::_flatten( $join ,' ');
		$group = self::_flatten( $group ,' ');
        
        if( $whereAll ){
			$where = $where ? $where : 'WHERE '.$whereAll;
		}
        if( $orderAll ){
			$order = $order ? $order : 'ORDER BY '.$orderAll;
		}
		
		// Main query to actually get the data
		$psql = "SELECT ".implode(", ", SSP::pluck($columns, 'db'))."
			FROM $table
			$join
			$where
			$group
			$having
			$order
			$limit";
        
        if ( is_array( $bindings ) ) {
			foreach ($bindings as $key => $bind){
				$bindings_params[$bind['key']] = strtolower($bind['val']);
			}
		}
		
        $data['data'] = "";
//		$data = SSP::sql_exec( $db, $bindings,$psql);
		if ( is_array( $bindings ) && (!empty( $bindings ))) {
			$data['data'] = \Yii::$app->db->createCommand($psql)->bindValues($bindings_params)->queryAll();
		}else{
			$data['data'] = \Yii::$app->db->createCommand($psql)->queryAll();
		}
        return $data;
    }
    
    static function filter ( $request, $columns, &$bindings )
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = SSP::pluck( $columns, 'dt' );

		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['searchable'] == 'true' ) {
					$binding = SSP::bind( $bindings, '%'.$str.'%', \PDO::PARAM_STR );
					$globalSearch[] = "LOWER((".self::cekTitik($column['db'],true).")::text) LIKE ".$binding;
				}
			}
		}
        
		// Individual column filtering
		for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
			$requestColumn = $request['columns'][$i];
			$columnIdx = array_search( $requestColumn['data'], $dtColumns );
			$column = $columns[ $columnIdx ];

			$str = $requestColumn['search']['value'];

			if ( $requestColumn['searchable'] == 'true' &&
			 $str != '' ) {
				$binding = SSP::bind( $bindings, '%'.$str.'%', \PDO::PARAM_STR );
				$columnSearch[] = "".$column['db']." LIKE ".$binding;
			}
		}
		
		// Combine the filters into a single string
		$where = '';

		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}
        
		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}

		if ( $where !== '' ) {
			$where = 'WHERE '.$where;
		}
        
		return $where;
	}
    
    static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}
	
	static function _flatten2 ( $a, $join = ', ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}
}
?>
