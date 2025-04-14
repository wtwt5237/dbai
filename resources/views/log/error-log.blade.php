@extends('layout')

@section('content')
    <section class="panel">
        <div style="padding: .8rem 2rem .1rem">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/database') }}">Databases</a></li>
                    @if($pre_page == 'profile')
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ url('/user-profile')}}">Profile</a></li>
                    @else
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ url('/database').'/'.$db_name }}">{{ str_replace(['dbai_', '_'], ['', ' '], $db_name) }}</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ url('/all-history').'/'.$db_name }}">History Log</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">Error Log</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="panel">
        <h3 class="title">Error Log</h3>
        <div class="title" style="padding-top: 0">Updated by <span class="fw-bold">{{ $user_name }}</span> at <span
                class="fw-bold">{{ $time }}</span></div>
        <hr class="mb-1">
        <div class="wrapper">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{--        <h3 class="mb-3">{{ $file_name }}</h3>--}}

            @foreach($errors as $error)
                @foreach($error as $key=>$val)
                    @if(str_contains($val, 'label:'))
                        @php $val = str_replace('label:', '', $val) @endphp
                        @if($key==0)
                            <div class="alert alert-danger mb-3 error-msg">
                                <div class="d-flex justify-content-between"><span class="text-danger fw-bolder"><i
                                            class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;{{ $val }}</span>
                                    <i class="fa-solid fa-chevron-down"></i></div>
                                @endif
                                @else
                                    @if($key==count($error)-1)
                                        <div class="error-content"><span class="text-danger">- &nbsp; {{ $val }}</span>
                                        </div></div>
                        @else
                            <div class="error-content"><span class="text-danger">- &nbsp; {{ $val }}</span></div>
                        @endif
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    @include('footer/footer-short')
    <!-- javascript
  ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    @include('js')

    <script>
        $(document).ready(function () {
            $('.error-msg').on('click', function () {
                $(this).children('.error-content').toggle();
                $(this).find(".fa-chevron-down, .fa-chevron-up").toggleClass("fa-chevron-down fa-chevron-up");
            })
        });
    </script>

@endsection
