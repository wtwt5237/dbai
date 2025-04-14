@extends('layout')

@section('content')
    <section class="panel">
        <div style="padding: .8rem 2rem .1rem">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if($pre_page == 'profile')
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ url('/user-profile')}}">Profile</a></li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ url('/database') }}">Databases</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ url('/database').'/'.$db_name }}">{{ str_replace(['dbai_', '_'], ['', ' '], $db_name) }}</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ url('/all-history').'/'.$db_name }}">History Log</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ str_replace(['dbai_', '_'], ['', ' '], $file_name) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="panel">
        <h3 class="title">{{ str_replace(['dbai_', '_'], ['', ' '], $file_name) }}</h3>
        <div class="title" style="padding-top: 0">Updated by <span class="fw-bold">{{ $user_name }}</span> at <span class="fw-bold">{{ $time }}</span></div>
        <hr class="mb-1">
        <div class="wrapper">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <table id="dataTable" class="display nowrap" style="width:100%"></table>
        </div>
    </div>

    @include('footer-short')
    <!-- javascript
  ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    @include('js')

    <script>
        $(document).ready(function () {
            // let $db_name = $('#database_name').text();
            $.ajax({
                url: "{{ url('/fetch-cur-history').'/'.$id }}",
                success: function (data) {
                    var table = $('#dataTable').DataTable({
                        dom: "<B>tp",
                        // ajax: {'data': [['2','dawdwa','31231','e12dawdw']]},
                        data: data[1],
                        // columns: [{"title": "Description"}, {"title": "Creator"}, {"title": "Date"}, {"title": "View"}],
                        columns: data[0],
                        scrollX: true,
                        select: false,
                        "ordering": false,
                        // aaSorting: [],
                        // columnDefs: [
                        //     {orderable: false, targets: [0, 1, 2, 3, 4]}
                        // ],
                        "pageLength": 10,
                        lengthMenu: [
                            [10, 20, 50, 100 -1],
                            ['10', '20', '50', '100', 'All']
                        ],
                        language: {
                            buttons: {
                                pageLength: 'Page Size: %d'
                            },
                            oPaginate: {
                                sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
                                sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
                            }
                        },
                        buttons: [
                            {
                                text: '<i class="fa fa-download"></i> CSV',
                                extend: 'csv',
                            },
                            'pageLength',
                            {
                                text: 'Hide/Show Columns',
                                extend: 'colvis',
                                postfixButtons: ['colvisRestore'],
                            },
                        ]
                    });
                }
            });
        });
    </script>

    {{--    <script>--}}
    {{--        function editUser(data) {--}}
    {{--            $('#editForm').attr('action', {{ '/update-user/' }} + data[0]);--}}
    {{--            $('#role option').each(function () {--}}
    {{--                $(this).removeAttr('selected');--}}
    {{--                if (this.value == data[1]) {--}}
    {{--                    // console.log(this.value)--}}
    {{--                    $(this).attr("selected","selected");--}}
    {{--                }--}}
    {{--            });--}}
    {{--            $('#role').val(data[1]);--}}
    {{--            $('#username').val(data[2]);--}}
    {{--            $('#email').val(data[3]);--}}

    {{--            if (data[4] === 'all') {--}}
    {{--                $('.table_tabs').prop('checked', 'true');--}}
    {{--            } else if (data[4] == null) {--}}
    {{--                $('.table_tabs').prop('checked', '');--}}
    {{--            } else {--}}
    {{--                let permission = JSON.parse(data[4]);--}}
    {{--                $('.table_tabs').each(function () {--}}
    {{--                    $(this).prop('checked', '');--}}
    {{--                    if (permission.includes(this.name)) {--}}
    {{--                        // console.log(this.name);--}}
    {{--                        $(this).prop('checked', 'true');--}}
    {{--                    }--}}
    {{--                });--}}
    {{--            }--}}
    {{--        }--}}
    {{--        function deleteUser(data) {--}}
    {{--            $('#deleteForm').attr('action', {{ '/delete-user/' }} + data[0]);--}}
    {{--        }--}}

    {{--        // $('#status').click(function () {--}}
    {{--        //     if (this.checked) {--}}
    {{--        //     $('#send_note').prop('value', 'true');--}}
    {{--        // } else {--}}
    {{--        //     $('#send_note').prop('value', 'false');--}}
    {{--        // }--}}
    {{--        // })--}}
    {{--    </script>--}}

@endsection
