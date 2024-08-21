<?php

namespace App\Http\Controllers\ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function index(Request $request)
    {

        // echoPrint($request);
            // echo "Hello world";
        $result['orders']=DB::table('orders')
        ->select('orders.*','orders_status.orders_status')
        ->leftJoin('orders_status','orders_status.id','=','orders.order_status')
        ->get();
        $datas = $result['orders'];


    // }
    $result = '';
        // return view('admin.order',$result);

        // $requestData= $_REQUEST;
		// $columns = array(
		// 	0 =>'id',
		// 	1 =>'category_name',
		// 	2 =>'create_date',
		// );
		// $sql="SELECT * from users where 1 ";
		// $totalData = getAffectedRowCount($sql);
		// $totalFiltered = $totalData;

		// if($requestData['search']['value'] ) {
		// 	$sql.=" AND ( 1 ";
		// 	$sql.=" OR `name` LIKE '%".$requestData['search']['value']."%' ";
		// 	$sql.=" OR `email` LIKE '%".$requestData['search']['value']."%' ";
		// 	$sql.=" OR `mobile` LIKE '%".$requestData['search']['value']."%' ";
		// 	$sql.= " )";
		// }
		// $totalFiltered = getAffectedRowCount($sql);

		// //$sql .="ORDER BY id desc";

		// $sql .=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
		// $i=1;
		// $arr = executeQuery($sql);

        $i = 0;
		foreach($datas as $list) {  // preparing an array
			$td = array();
			$td[] = $i;
			$td[] = $list->name;
			$td[] = $list->name;
			$td[] = $list->name;
			$td[] = $list->name;
			$td[] = $list->name;
			$td[] = $list->name;
			$td[] = $list->name;

			$action = '';

			// $td[] = $action;
			$data[] = $td;
			$i ++;
		}
        // print_r($data);
		$json_data = array(
			"draw"            => intval( 7 ),   // for every request/draw by clientside ,
			"recordsTotal"    => intval( 7),  // total number of records
			"recordsFiltered" => intval( 7), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		echo json_encode($json_data);
        die;
    }
}
