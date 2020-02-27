@extends('layouts.admin')
@section('title', 'Manage Contents - Feeds :: Startev Africa')
@section('content')

    <style>
        .pagination{
            justify-content: center;

            font-size: smaller;
        }
    </style>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Manage Feeds</h4>
                    <div class="">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">

                                <div class="btn btn-group status">
                                <a class="btn btn-info" href="?status=">All</a>
                                    <a class="btn btn-success" href="?status=1">Published</a>
                                <a class="btn btn-warning" href="?status=0">Review</a>
                                <a class="btn btn-danger" href="?status=-1">Trashed</a>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <ul class="left-side-navbar d-flex align-items-center float-right">
                                    <li class="hide-phone app-search mr-15">

                                            <input type="text" name="search" id="search" placeholder="Search..." class="form-control">

                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive" id="table_data">
                            @include('pages.feed.feeds_data')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('footerScript')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.status a', function(event){

                event.preventDefault(); //Stop the page from refreshing
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();
                var query = $('#search').val();
                var status = $(this).attr('href').split('status=')[1];
                $('#hidden_status').val(status);

                fetch_data(page, sort_type, column_name, query, status);
            });


            $(document).on('click', '.pagination a', function(event){
               event.preventDefault(); //Stop the page from refreshing
               var page = $(this).attr('href').split('page=')[1];
               $('#hidden_page').val(page);
               var column_name = $('#hidden_column_name').val();
               var sort_type = $('#hidden_sort_type').val();

               var query = $('#search').val();

               $('li').removeClass('active');
               $(this).parent().addClass('active');

                var status = $('#hidden_status').val();

               fetch_data(page, sort_type, column_name, query, status);
            });

            function  clear_icon() {
                $('#id_icon').html('');
                $('#post_title_icon').html('');
            }

            function fetch_data(page, sort_type, sort_by, query, status){
                console.log(page+sort_type);
                $.ajax({
                    type:'get',
//                    url:"/manage-contents/get-feeds?page="+page+"$query="+query,
                    url:"/manage-contents/fetch_data?page="+page+"&sorttype="+sort_type+"&sortby="+sort_by+"&query="+query+"&status="+status,
                    success:function(data){
                        $('#table_data').html('');
                        $('#table_data').html(data);
                    }
                })
            }

            $(document).on('keyup', '#search', function(){
                var query = $('#search').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();

                var status = $('#hidden_status').val();

                fetch_data(page, sort_type, column_name, query, status);
            });

            $(document).on('click', '.sorting', function(){
               var column_name = $(this).data('column_name');
               var order_type = $(this).data('sorting_type');
               console.log(order_type);
               var reverse_order = '';
               if(order_type == 'asc'){
                   $(this).data('sorting_type', 'desc');
                   reverse_order = 'desc';

                   clear_icon();
                   $('#'+column_name+'_icon').html('<i class="fas' +
                       'fa-sort-down"></i>');
               }
               if(order_type == 'desc'){
                   $(this).data('sorting_type', 'asc');
                   reverse_order = 'asc';

                   clear_icon();
                   $('#'+column_name+'_icon').html('<i class="fas' +
                       'fa-sort-up"></i>');
               }
               $('#hidden_colum_name').val(column_name);
               $('#hidden_sort_type').val(reverse_order);

                var query = $('#search').val();
               var page = $('#hidden_page').val();
                var status = $('#hidden_status').val();

               fetch_data(page, reverse_order, column_name, query, status)
            });
            
            
            $(document).on('click', '.trash', function () {
                var id = $(this).attr('id');
                if(confirm("Are you sure you want to trash this data?")){
                    $.ajax({
                        type: "get",
                        url: "/manage-contents/trash_data?id="+id,
                        success:function (data) {
                            var column_name = $('#hidden_column_name').val();
                            var sort_type = $('#hidden_sort_type').val();
                            var page = $('#hidden_page').val();
                            var query = $('#search').val();
                            var status = $('#hidden_status').val();
                            fetch_data(page, sort_type, column_name, query, status);
                        }
                    })
                }else{
                    return false;
                }
            });


            $(document).on('click', '.review', function () {
                var id = $(this).attr('id');
                if(confirm("Are you sure you want to review this data?")){
                    $.ajax({
                        type: "get",
                        url: "/manage-contents/review_data?id="+id,
                        success:function (data) {
                            var column_name = $('#hidden_column_name').val();
                            var sort_type = $('#hidden_sort_type').val();
                            var page = $('#hidden_page').val();
                            var query = $('#search').val();
                            var status = $('#hidden_status').val();
                            fetch_data(page, sort_type, column_name, query, status);
                        }
                    })
                }else{
                    return false;
                }
            });

            $(document).on('click', '.publish', function () {
                var id = $(this).attr('id');
                if(confirm("Are you sure you want to publish this data?")){
                    $.ajax({
                        type: "get",
                        url: "/manage-contents/publish_data?id="+id,
                        success:function (data) {
                            var column_name = $('#hidden_column_name').val();
                            var sort_type = $('#hidden_sort_type').val();
                            var page = $('#hidden_page').val();
                            var query = $('#search').val();
                            var status = $('#hidden_status').val();
                            fetch_data(page, sort_type, column_name, query, status);
                        }
                    })
                }else{
                    return false;
                }
            });
        });
    </script>
@endsection