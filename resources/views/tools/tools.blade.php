@extends('layout')

@section('content')
<section class="panel">
    <div class="card">
        <div class="card-header">
            Tools
        </div>
        <div class="card-body">
            @foreach(config('global.tools') as $tool)
            <div class="d-flex">
                <div class="tool-img">
                    <img src="{{ $tool['img'] }}" alt="">
                </div>
                <div class="ms-5">
                    <h3 class="colored fw-bolder mb-3">{{ $tool['name'] }}</h3>
                    <div class="mb-4">{{ $tool['description'] }}</div>
                    <div>
                        @if(isset($tool['url']))
                        <a href="{{ $tool['url']['link'] }}">
                            <button class="btn btn-primary">{{ $tool['url']['name'] }}</button>
                        </a>
                        @endif
                        @if(isset($tool['git_url']))
                        <a href="{{ $tool['git_url'] }}">
                            <button class="btn btn-success">Github</button>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="hr-divider my-5">
            @endforeach
        </div>
    </div>
</section>

@include('footer/footer-short')
<!-- javascript
  ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
@include('js')

@endsection