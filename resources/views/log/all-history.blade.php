@extends('layout')

@section('content')
    <section class="panel">
        <div style="padding: .8rem 2rem .1rem">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/database') }}">Databases</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a
                            href="{{ url('/database').'/'.$db_name }}">{{ str_replace(['dbai_', '_'], ['', ' '], $db_name) }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">History Log</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="panel">
        <h3 class="title">History Log</h3>
        <hr class="mb-1">
        <div class="wrapper">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <table id="dataTable" class="display nowrap" style="width:100%"></table>
        </div>
    </section>

    @include('footer/footer-short')
    <!-- javascript
  ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    @include('js')

    <script>
        $(document).ready(function () {
            //load annotations table
            var table = $('#dataTable').DataTable({
                dom: "<B>tp",
                scrollX: true,
                select: false,
                order: [[5, "desc"]],
                ajax: "{{ url('fetch-history').'/'.$db_name }}",
                columns: [{"title": "action"}, {"title": "status"}, {"title": "username"}, {"title": "email"}, {"title": "modified_database"},
                    {"title": "modified_time"}, {"title": "source"}, {"title": "error_log"}],
                "pageLength": 10,
                lengthMenu: [
                    [10, 20, 50, 100 - 1],
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
                    'pageLength',
                ],
            })
        })
    </script>

@endsection
