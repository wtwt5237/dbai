@extends('layout')

@section('content')
    <section class="panel">
        <div class="row">
            <div class="col-5">
                <div class="card mb-4">
                    <div class="card-header">
                        Account Information
                    </div>
                    <div class="card-body">
                        <table class='table text-center table-withBorder table-user-info'>
                            <tbody>
                            <tr>
                                <td class='info-cell-1'>Username</td>
                                <td class='info-cell-2'>{{ Auth::user()->username }}</td>
                            </tr>
                            <tr>
                                <td class='info-cell-1'>Email</td>
                                <td class='info-cell-2'>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <td class='info-cell-1'>Role</td>
                                <td class='info-cell-2'>{{ Auth::user()->role }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Permissions
                    </div>
                    <div class="card-body">
                        <table class="table text-center table-withBorder">
                            <tbody>
                            @php
                                $per_arr = ["dbai_CyTOF","dbai_Patient","dbai_Receptor","dbai_Sample","dbai_ScRNA","dbai_B_interaction","dbai_T_interaction"];
                                $permissions = Auth::user()->permission;
                                if($permissions!='all' and !empty($permissions)) {
                                    $user_per_arr = json_decode($permissions);
                                }
                            @endphp
                            {{--                        <div>{{ Auth::user()->permission }}</div>--}}
                            @foreach($per_arr as $per)
                                <tr>
                                    <td>{{ str_replace(['dbai_', '_'], ['', ' '], $per) }}</td>
                                    <td>
                                        <div class="form-check form-switch mb-1">
                                            @if($permissions=='all')
                                                <input class="form-check-input profile-tabs" type="checkbox"
                                                       name="{{ $per }}" checked disabled>
                                            @elseif(empty($permissions))
                                                <input class="form-check-input profile-tabs" type="checkbox"
                                                       name="{{ $per }}" disabled>
                                            @else
                                                @if(in_array($per, $user_per_arr))
                                                    <input class="form-check-input profile-tabs"
                                                           type="checkbox"
                                                           name="{{ $per }}" checked disabled>
                                                @else
                                                    <input class="form-check-input profile-tabs"
                                                           type="checkbox"
                                                           name="{{ $per }}" disabled>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Update History
                    </div>
                    <div class="card-body">
                        <table id="dataTable" class="display nowrap" style="width:100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="panel">

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
                ajax: "{{ url('history') }}",
                columns: [{"title": "id"}, {"title": "version"}, {"title": "updated_time"}, {"title": "editor"}],
                select: false,
                "ordering": false,
                // aaSorting: [],
                // columnDefs: [
                //     {orderable: false, targets: [0, 1, 2, 3, 4]}
                // ],
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
                ]
            })
        })
    </script>
@endsection
