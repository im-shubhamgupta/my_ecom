<?php

namespace App\Http\Controllers\ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function index(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'category_name',
            2 => 'create_date',
        ];

        try {
            $query = DB::table('orders');

            // Count the total number of records
            $totalData = $query->count();
            $data = array();
            $totalFiltered = $totalData;
            $draw = $request->input('draw');

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                        ->orWhere('email', 'like', "%{$searchValue}%")
                        ->orWhere('mobile', 'like', "%{$searchValue}%")
                        ->orWhere('address', 'like', "%{$searchValue}%")
                        ->orWhere('city', 'like', "%{$searchValue}%")
                        ->orWhere('state', 'like', "%{$searchValue}%")
                        ->orWhere('pincode', 'like', "%{$searchValue}%")
                        ->orWhere('total_amt', 'like', "%{$searchValue}%")
                        ->orWhere('order_status', 'like', "%{$searchValue}%")
                        ->orWhere('payment_status', 'like', "%{$searchValue}%")
                        ->orWhere('payment_type', 'like', "%{$searchValue}%")
                        ->orWhere('added_on', 'like', "%{$searchValue}%");
                });
            $totalFiltered = $query->count();
            }
            // Apply ordering and pagination
            $orderColumn = $columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');
            $start = $request->input('start');
            $length = $request->input('length');

            $users = $query->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($length)
                ->get();

            $i = 0;
            foreach($users as $list) {  // preparing an array
                $td = array();

                $td[] = "<a href='".url('/admin/order_detail').'/'.$list->id."'>$list->id</a>";
                $td[] = $list->name;
                // $td[] = $list->email;
                // $td[] = $list->mobile;
                // $td[] = $list->address.", ".$list->city.", ".$list->state.", ".$list->pincode;
                $td[] = $list->total_amt;
                $td[] = $list->order_status;
                $td[] = $list->payment_status;
                $td[] = $list->payment_type;
                $td[] = getCustomDate($list->added_on);

                // $action ='<span><a href="{{url("mod_user&id="'.$list['id'].')}}" class="btn btn-success btn-sm btn-icon waves-effect waves-themed"><i class="fal fa-edit"></i></a></span>';//'.$list['id'].'
                // $action = '  <span><a href="#" onclick="delete_user(this)" data-id="" class="btn btn-danger btn-sm btn-icon waves-effect waves-themed"><i class="fal fa-times"></i></a></span>';
                // $td[] = '';//$action;

                // $td[] = $action;
                $data[] = $td;
                $i ++;
            }
            // print_r($data);
            $json_data = array(
                "draw"            => intval($draw),   // for every request/draw by clientside ,
                "recordsTotal"    => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $data   // total data array
            );
        } catch (\Exception $e) {
            echo json_encode("Error: " . $e->getMessage());
        }
		echo json_encode($json_data);
        die;
    }

    public function product_review(Request $request)
    {
        try {
            $columns = [
                0 => 'product_review.id',
                1 => 'customers.name',
                2 => 'products.name',
            ];
            $query = DB::table('product_review')
                ->select('product_review.id','product_review.rating','product_review.review','product_review.added_on','customers.name','products.name as pname','product_review.status')
                ->leftJoin('customers', 'customers.id', '=', 'product_review.customer_id')
                ->leftJoin('products', 'products.id','=' , 'product_review.products_id');

            // Count the total number of records
            $totalData = $query->count();
            $data = array();
            $totalFiltered = $totalData;
            $draw = $request->input('draw');

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('customers.name', 'like', "%{$searchValue}%")
                    ->orWhere('products.name', 'like', "%{$searchValue}%")
                    ->orWhere('product_review.rating', 'like', "%{$searchValue}%")
                    ->orWhere('product_review.review', 'like', "%{$searchValue}%")
                    ->orWhere('product_review.added_on', 'like', "%{$searchValue}%");
                });
            $totalFiltered = $query->count();
            }
            // Apply ordering and pagination
            $orderColumn = $columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');
            $start = $request->input('start');
            $length = $request->input('length');

            $data_arr = $query->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($length)
                ->get();
            $i = 0;
            // dd($query->toSql());
            foreach($data_arr as $list) {  // preparing an array
                $td = array();
                $td[] = $list->id;
                $td[] = $list->name;
                $td[] = $list->pname;
                $td[] = $list->rating;
                $td[] = $list->review;
                $td[] = $list->review;
                $td[] = getCustomDate($list->added_on);
                if($list->status==1){
                    $action = "<a href='".url('admin/update_product_review_status/0').'/'.$list->id."'>
                                <button type='button' class='btn btn-primary btn-sm'>Active</button></a>";
                }elseif($list->status==0){
                    $action = "<a href='".url('admin/update_product_review_status/1').'/'.$list->id."'>
                                <button type='button' class='btn btn-warning btn-sm'>Deactive</button></a>";
                }
                $td[] = $action;
                $data[] = $td;
                // $i ++;
            }
            $json_data = array(
                "draw"            => intval($draw),   // for every request/draw by clientside ,
                "recordsTotal"    => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $data   // total data array
            );
            echo json_encode($json_data);
        } catch (\Exception $e) {
            echo json_encode("Error: " . $e->getMessage());
        }
        die;
    }
    public function fetchCategoryData(Request $request){
        try {
            $columns = [
                0 => 'id',
                1 => 'category_name',
                2 => 'category_slug',
            ];
            $query = DB::table('categories');
            // Count the total number of records
            $totalData = $query->count();
            $data = array();
            $totalFiltered = $totalData;

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('category_name', 'like', "%{$searchValue}%")
                    ->orWhere('category_slug', 'like', "%{$searchValue}%");
                });
            $totalFiltered = $query->count();
            }
            // Apply ordering and pagination
            $orderColumn = $columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');
            $start = $request->input('start');
            $length = $request->input('length');

            $data_arr = $query->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($length)
                ->get();
            $i = 0;
            // prx($data_arr);
            // dd($query->toSql());
            foreach($data_arr as $list) {  // preparing an array
                $td = array();
                $td[] = $list->id;
                $td[] = $list->category_name;
                $td[] = $list->category_slug;
                $Action =  "<div><a href=".url('admin/category/manage_category/').'/'.$list->id."><button type='button' class='btn btn-success btn-sm'>Edit</button></a>&nbsp;";
                if($list->status==1){
                    $Action .= '<a href="'.url('admin/category/status/0').'/'.$list->id.'"><button type="button" class="btn btn-primary btn-sm">Active</button></a>&nbsp;';
                }elseif($list->status==0){
                    $Action .=  '<a href="'.url('admin/category/status/1').'/'.$list->id.'"><button type="button" class="btn btn-warning btn-sm">Deactive</button></a>&nbsp;';
                }
                $Action .= '<a href="'.url('admin/category/delete/').'/'.$list->id.'"><button type="button" class="btn btn-danger btn-sm">Delete</button></a></div>';
                $td[] = $Action;
                $data[] = $td;
            }
            $json_data = array(
                "draw"            => intval($request->input('draw')),   // for every request/draw by clientside ,
                "recordsTotal"    => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $data   // total data array
            );
            echo json_encode($json_data);
        } catch (\Exception $e) {
            echo json_encode("Error: " . $e->getMessage());
        }
        die;
    }

    public function fetch_data(Request $request){
        $req = $request->post();
        if($req['ajax_action'] == 'fetchAllCategories'){
            $this->fetchCategoryData($request);
        }else{
            exit('ajax_action  not matched');
        }
    }
}
