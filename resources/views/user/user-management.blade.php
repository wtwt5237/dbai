@extends('layout')

@section('content')
    <div class="panel">
        {{--        @if (session('success'))--}}
        {{--            <div class="alert alert-success" role="alert">--}}
        {{--                {{ session('success') }}--}}
        {{--            </div>--}}
        {{--        @endif--}}
        <div class="card">
            <div class="card-header">
                User Management
            </div>
            <div class="card-body">
                <!-- User Add Modal -->
                <div class="modal fade" id="add_user" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <form action="{{route('addUser')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username">
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Email</label>
                                        <input type="text" class="form-control" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    {{--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
                                    <button type="submit" name="submit" class="btn btn-success">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table id="dataTable" class="display nowrap" style="width:100%"></table>

                <!-- Edit User Modal-->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data" id="editForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="role" class="form-label fw-bold">Role</label>
                                        <select class="form-select" aria-label="Default select example" id="role"
                                                name="role">
                                            <option value="admin">Admin</option>
                                            <option value="editor">Editor</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label fw-bold">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" value="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-bold">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <div for="permission" class="form-label fw-bold mb-1">Permissions (Function Currently Disabled)</div>
                                        <hr class="mb-2">
                                        <table class="table" id="user-permission-table">
                                            <tbody>
                                            @foreach(config('global.db_to_display.user') as $v)
                                                <tr>
                                                    @php
                                                        $db_name = strtolower(str_replace(' ', '', $v));
                                                    @endphp
                                                    <td>{{ $v }}</td>
                                                    <td>
                                                        <div class="form-check form-switch mb-1"><input
                                                                class="form-check-input table_tabs" type="checkbox"
                                                                name="{{ $db_name }}" disabled>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    {{--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
                                    <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete User Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Upload File</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this record?
                            </div>
                            <form action="" method="post" enctype="multipart/form-data" id="deleteForm">
                                {{--                        @method('delete')--}}
                                @csrf
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                    </button>
                                    <button type="submit" name="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
                ajax: "{{ url('all-users') }}",
                columns: [{"title": "Actions"}, {"title": "Role"}, {"title": "Username"}, {"title": "Email"}, {"title": "Permissions"}],
                scrollX: true,
                select: false,
                order: [[4, "desc"]],
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
                    {
                        text: '<i class="fa-solid fa-user-plus"></i> Add',
                        attr: {
                            id: 'add_tab',
                            class: 'btn btn-success',
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#add_user',
                            'data-toggle': 'tooltip',
                            'title': 'add new user'
                        }
                    },
                    // 'pageLength',
                ]
            })

            $('[data-toggle="tooltip"]').tooltip();

        });
    </script>

    <script>
        function editUser(data) {
            $('#editForm').attr('action', "{{ url('/update-user') }}" + '/' + data[0]);

            $('#role option').each(function () {
                $(this).removeAttr('selected');
                if (this.value == data[1]) {
                    // console.log(this.value)
                    $(this).attr("selected", "selected");
                }
            });
            $('#role').val(data[1]);
            $('#username').val(data[2]);
            $('#email').val(data[3]);

            if (data[4] === 'all') {
                $('.table_tabs').prop('checked', 'true');
            } else if (data[4] == null) {
                $('.table_tabs').prop('checked', '');
            } else {
                let permission = JSON.parse(data[4]);
                $('.table_tabs').each(function () {
                    $(this).prop('checked', '');
                    if (permission.includes(this.name)) {
                        // console.log(this.name);
                        $(this).prop('checked', 'true');
                    }
                });
            }
        }

        function deleteUser(data) {
            $('#deleteForm').attr('action', "{{ url('/delete-user') }}" + '/' + data[0]);
        }

        // $('#status').click(function () {
        //     if (this.checked) {
        //     $('#send_note').prop('value', 'true');
        // } else {
        //     $('#send_note').prop('value', 'false');
        // }
        // })
    </script>

@endsection
