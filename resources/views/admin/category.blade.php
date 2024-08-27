@extends('admin/layout')
@section('page_title','Category')
@section('category_select','active')
@section('container')
    @if(session()->has('message'))
    <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
        {{session('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    @endif
    <h1 class="mb10">Category</h1>
    <a href="{{url('admin/category/manage_category')}}">
        <button type="button" class="btn btn-success">
            Add Category
        </button>
    </a>
    <div class="row m-t-30">
        <div class="col-md-12">
            <!-- DATA TABLE-->
            {{-- table-responsive --}}
            {{-- table-data3 --}}
            {{-- m-b-40 --}}
            <div class="">
                <table id="categoryDatatable" class="w-100 table table-borderless">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Category Slug</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- END DATA TABLE-->
        </div>
    </div>
    <script>
        window.onload = (event)=>{
            load_all_categories();
        }
    </script>

@endsection
{{-- <tbody>
    foreach($data as $list)
    <tr>
        <td>{{$list->id}}</td>
        <td>{{$list->category_name}}</td>
        <td>{{$list->category_slug}}</td>
        <td>
            <a href="{{url('admin/category/manage_category/')}}/{{$list->id}}"><button type="button" class="btn btn-success">Edit</button></a>

            @if($list->status==1)
                <a href="{{url('admin/category/status/0')}}/{{$list->id}}"><button type="button" class="btn btn-primary">Active</button></a>
             @elseif($list->status==0)
                <a href="{{url('admin/category/status/1')}}/{{$list->id}}"><button type="button" class="btn btn-warning">Deactive</button></a>
            @endif

            <a href="{{url('admin/category/delete/')}}/{{$list->id}}"><button type="button" class="btn btn-danger">Delete</button></a>
        </td>
    </tr>
    endforeach
</tbody> --}}
