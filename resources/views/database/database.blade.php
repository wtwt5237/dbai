@extends('layout')

@section('content')
    @if (session('success'))
        <div class="alert alert-success mb-3" style="margin: 0 150px 20px;" id="top-msg">
            <div class="d-flex justify-content-between">
                <div>{{ session('success') }}</div>
                <i class="dismiss fa-solid fa-xmark d-inline-block"></i>
            </div>
        </div>
    @endif
    @if (session('authError'))
        <div class="alert alert-danger mb-3" style="margin: 0 150px 20px;" id="top-msg">
            <div class="d-flex justify-content-between">
                <div>{{ session('authError') }}</div>
                <i class="dismiss fa-solid fa-xmark d-inline-block"></i>
            </div>
        </div>
    @endif

    <a onclick="openForm()"><i class="chat-icon fa-solid fa-robot"></i></a>

    <div class="chat-box" id="chat-box">
    <button class="close-btn" id="closeBtn" style="border-radius:.2rem">&times;</button>
        <!-- <div class="resize-handle"></div> -->
{{--        <iframe src="http://127.0.0.1:7860" title="W3Schools Free Online Web Tutorials" style="width:100%; height:100%"></iframe>--}}
        <iframe src="https://datalang.io/chatbot/cm6jo4wa216dupcg9fmbad777" title="My Chatbot" width="100%" style="height: 100%; min-height: 700px" frameborder="0" ></iframe>
    </div>

    <section class="panel">
        <div class="card">
            <div class="card-header">
                Database Browser
            </div>
            <div class="card-body">
                <div id="db_tabs" class="mb-5 btn-group">
                    @foreach($tabs as $tab)
                        @if(strtolower(str_replace(' ', '', $tab)) == $db_name)
                            <a href={{url("database")."/".$db_name }} class="selected btn
                               btn-tabs
                            ">{{ $tab }}</a>
                        @else
                            <a href={{url("database")."/".strtolower(str_replace(' ', '', $tab)) }} class="btn btn-tabs
                            ">{{ $tab }}</a>
                        @endif
                    @endforeach
                </div>

                <div>
                    <div class="divider w-50">Search</div>
                    <div class="" id="search-boxes">
                        @php
                            $tmp = '';
                            $col_index = 0;
                            foreach($dict as $k=>$v) {
                                $v_name = strtolower(str_replace('_', '', $v->database_name));
                                if($v_name == $tmp) $col_index++;
                                else $col_index = 0;

                                $tmp = $v_name;

                                if($v_name==$db_name && $v->display_search=='Yes')
                                    echo '<div class="d-inline-block me-3 mt-3">
                                        <label for="column0_search">'. $v->variable_name  .'</label> &nbsp;
                                        <input type="text" class="column_search form-control" name="'. $col_index .'">
                                    </div>';
                            }
                        @endphp
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="panel">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <div class="spinner-grow" role="status" style="width: 5rem; height: 5rem;" id="loading">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                @if (Session('action'))
                    <div class="alert alert-success action-alert" id="validation-success">
                        <span>{{ session('action') }}</span>
                    </div>
                @endif

                <table id="dataTable" class="table-main display nowrap" style="width:100%"></table>

            </div>
            <div class="card-footer py-3">
                <div class="text-center">Data Last Updated: {{env('LAST_UPDATED')}}</div>
            </div>
        </div>
    </section>




    @include('footer/footer-short')

    <!-- javascript
      ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    @include('js')

    <script>
        $(document).ready(function () {
            $.ajax({
                url: "{{ url('/fetch').'/'.$db_name }}",
                beforeSend: function () {
                    $('#validation-error').hide();
                    $('#validation-success').hide();
                    console.log('loading...');
                },
                complete: function () {
                    $("#loading").hide();
                    $('#validation-error').show();
                    $('#validation-success').show();
                    console.log('complete...');
                },
                success: function (data) {
                    // initially hidden cols
                    let hiddenCols = [];
                    let col_index = 0;
                    let tmp = '';
                    let v_name;
                    let dict = <?php echo json_encode($dict) ?>;

                    for (let v of dict) {
                        v_name = v['database_name'].replace('_', '').toLowerCase();
                        if (tmp != v_name) {
                            tmp = v_name;
                            col_index = 0;
                        } else col_index++;

                        if (v_name == '{{ $db_name }}' && v['display_initial'] != 'Yes') {
                            hiddenCols.push(col_index);
                        }
                    }

                    var table = $('#dataTable').DataTable({
                        columns: data[0],
                        data: data[1],
                        scrollX: true,
                        select: true,
                        // "ordering": false,
                        order: [[0, 'asc']],
                        dom: "<B>tp",
                        "pageLength": 10,
                        lengthMenu: [
                            [10, 20, 50, 100 - 1],
                            ['10', '20', '50', '100', 'All']
                        ],
                        columnDefs: [
                            {"visible": false, "targets": hiddenCols}
                        ],
                        language: {
                            search: "",
                            buttons: {
                                pageLength: 'Page Size: %d',
                                colvisRestore: "Reset"
                            },
                            oPaginate: {
                                sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
                                sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
                            }
                        },
                        buttons: [
                            'pageLength',
                            {
                                text: '<i class="fa-solid fa-list me-2"></i>Hide/Show Columns',
                                class: 'float-left',
                                extend: 'colvis',
                                postfixButtons: [
                                    'colvisRestore',
                                    {
                                        extend: 'colvisGroup',
                                        text: 'Show All',
                                        show: ':hidden'
                                    }],
                            },
                            {
                                text: '<i class="fa fa-download"></i> CSV',
                                extend: 'csv',
                                filename: '{{ $db_name }}'
                            },

                        ]
                    });

                    $('.dt-button.buttons-collection.buttons-colvis').on('click', function () {
                        if (!$('.dt-button-collection hr').length) {
                            $('.dt-button.buttons-colvisRestore').before('<hr>');
                        }
                    })

                    $('[data-toggle="tooltip"]').tooltip();

                    let total_records = table.page.info().recordsTotal;
                    $('.buttons-page-length').after('<div class="btn-group" style="width: 300px; vertical-align: top;"><button type="button" class="btn count-tab">Found: <span class="counter">' + total_records + '</span></button>' +
                        '<button type="button" class="btn count-tab">Total: <span class="counter">' + total_records + '</span></button></div>');

                    $('.counter').each(function () {
                        $(this).prop('Counter', 0).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 500,
                            easing: 'swing',
                            step: function (now) {
                                $(this).text(Math.ceil(now));
                            }
                        });
                    });
                    
                    $('.column_search').on('keyup', function () {
                        table.columns(this.name).search(this.value).draw();
                        let found_records = table.rows({search: 'applied'}).count();
                        $('#count-found').text('Found: ' + found_records);
                    });
                }
            });

            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    </script>

<script>
function openForm() {
  document.getElementById("chat-box").style.display = "block";
}

function closeForm() {
  document.getElementById("chat-box").style.display = "none";
}

const resizableDiv = document.getElementById('chat-box');
const resizeHandle = document.querySelector('.resize-handle');
const iframe = document.getElementById('iframe');

let isResizing = false;

// Throttling utility to limit resize frequency
function throttle(callback, delay) {
  let lastCall = 0;
  return function (...args) {
    const now = new Date().getTime();
    if (now - lastCall < delay) return;
    lastCall = now;
    return callback(...args);
  };
}

// resizeHandle.addEventListener('mousedown', (e) => {
//   e.preventDefault();
//   isResizing = true;

//   // Store initial mouse position and the div's initial size
//   const initialX = e.clientX;
//   const initialY = e.clientY;
//   const initialWidth = resizableDiv.offsetWidth;
//   const initialHeight = resizableDiv.offsetHeight;
//   const initialLeft = resizableDiv.offsetLeft;
//   const initialTop = resizableDiv.offsetTop;

//   // Throttled mousemove event to prevent performance issues
//   const onMouseMove = throttle((moveEvent) => {
//     if (!isResizing) return;

//     const dx = moveEvent.clientX - initialX;
//     const dy = moveEvent.clientY - initialY;

//     // Calculate the new width and height based on mouse movement
//     const newWidth = initialWidth - dx;
//     const newHeight = initialHeight - dy;

//     // Apply the resizing to the div's width and height (using transform for smoothness)
//     resizableDiv.style.width = `${newWidth}px`;
//     resizableDiv.style.height = `${newHeight}px`;

//     // Step 1: Fix bottom-right corner by adjusting the position of the div
//     const newLeft = initialLeft + dx;
//     const newTop = initialTop + dy;

//     // Step 2: Apply the new position (left/top) to the div to fix bottom-right corner
//     resizableDiv.style.left = `${newLeft}px`;
//     resizableDiv.style.top = `${newTop}px`;
//   }, 10); // 10ms throttle interval for smoother resizing

//   // Stop resizing when mouse button is released
//   const onMouseUp = () => {
//     isResizing = false;
//     document.removeEventListener('mousemove', onMouseMove);
//     document.removeEventListener('mouseup', onMouseUp);
//   };

//   // Add event listeners for mousemove and mouseup to the entire document
//   document.addEventListener('mousemove', onMouseMove);
//   document.addEventListener('mouseup', onMouseUp);
// });

// Close button functionality
closeBtn.addEventListener('click', () => {
    console.log('xxx')
  resizableDiv.style.display = "none"; // Removes the entire resizable div from the DOM
});



</script>

@endsection


