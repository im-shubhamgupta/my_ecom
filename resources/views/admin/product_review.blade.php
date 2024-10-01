@extends('admin/layout')
@section('page_title','Product Review')
@section('product_review_select','active')
@section('container')
    <div class="d-flex justify-content-between mb10">
        <div><h1 class="">Product Review</h1></div>
        <div>
            <button class="btn btn-primary"  data-toggle="tooltip" onclick="export_product_review(this)" data-placement="top" title="Export">Export</button>
            {{-- <button class="btn btn-primary"></button> --}}
            {{-- <button class="btn btn-primary"></button> --}}
        </div>

    </div>

    <div class="row m-t-30">
        <div class="col-md-12">
            <!-- DATA TABLE-->
            {{-- table-responsive m-b-40 --}}
            {{-- table-data3 --}}
            <div class="">
                <table id="reviewDatable" class="table table-borderless ">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Added On</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
            <!-- END DATA TABLE-->
        </div>
    </div>
    <script>
        window.onload = (event) =>{
            load_all_product_review();
        }
    </script>
@endsection

    {{-- foreach($data as $list)
    <tr>
        <td>{{$list->id}}</td>
        <td>{{$list->name}}</td>
        <td>{{$list->pname}}</td>
        <td>{{$list->rating}}</td>
        <td>{{$list->review}}</td>
        <td>{{getCustomDate($list->added_on)}}</td>
        <td>
            @if($list->status==1)
                <a href="{{url('admin/update_product_review_status/0')}}/{{$list->id}}"><button type="button" class="btn btn-primary">Active</button></a>
             @elseif($list->status==0)
                <a href="{{url('admin/update_product_review_status/1')}}/{{$list->id}}"><button type="button" class="btn btn-warning">Deactive</button></a>
            @endif
        </td>
    </tr>
    endforeach --}}
