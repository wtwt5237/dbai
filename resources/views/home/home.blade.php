@extends('layout')

@section('content')

<section class="panel">
    <div class="alert alert-warning" role="alert">
        Dr. Tao Wang will move from UT Southwestern to MDACC in May/2025. The DBAI website will also move to MDACC domain along with Dr. Wang soon.
    </div>
</section>

<section class="panel">
    <div class="card w-100">
        <div class="overlay-background"></div>
        <div class="card-body intro">
            <h1 class="text-center pt-4 px-3 z-50"><strong>Database for <span class="colored">Actionable
                        Immunology</span></strong>
            </h1>
            <div style="padding: 10px 100px 30px">
                <div class="text-center fw-bold">The Database for Actionable Immunology (dbAI) is a freely
                    available resource
                    contributed by Dr. Tao Wang’s laboratory in UT Southwestern Medical Center. It
                    catalogs experimental data on T/B cell epitopes, immune receptors, HLA alleles, etc,
                    in the context of cancers, infectious diseases and other human diseases. dbAI also
                    hosts cloud-based computational services for immunological problems, such as
                    predictions of T cell receptor-T cell epitope interactions. dbAI aims to provide
                    knowledge and resource for facilitating translational applications of
                    immunotherapeutics
                </div>
            </div>
        </div>
    </div>
</section>

<section class="panel">
    <div class="card w-100">
        <div class="card-header">
            Databases
        </div>
        <div class="card-body">
            @foreach(config('global.db_to_display.user') as $db)
            <div class="db-wrapper">
                <a href="{{ url('/database').'/'.strtolower(str_replace(' ', '', $db)) }}"><i
                        class="fa-solid fa-database db-icon mb-3"></i></a>
                <div class="mb-2">{{$db}}</div>
                <div class="db-size">Records:
                    <strong><span
                            class="counter">{{$db_size[strtolower(str_replace(' ', '', $db))]}}</span></strong>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="panel" style="margin-bottom: 50px">
    <div class="card w-100">
        <div class="card-header">
            Tools
        </div>
        <div class="card-body">
            @foreach(config('global.tools') as $tool)
            <div class="tool-wrapper mb-5 mb-lg-0">
                <div class="tool-icon mb-3">
                    @if(isset($tool['url']))
                    <a href="{{ $tool['url']['link'] }}"><img src="{{ $tool['img'] }}" alt=""></a>
                    @else
                    <a href="{{ $tool['git_url'] }}"><img src="{{ $tool['img'] }}" alt=""></a>
                    @endif
                </div>
                <div class="mb-3">{{ $tool['name'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('footer/footer-long')

<!-- javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
@include('js')

<!-- <script type="module" src="{{ asset("js/gradioAPI.js") }}"></script> -->

<script>
    $('.counter').each(function() {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 500,
            easing: 'swing',
            step: function(now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
</script>

@endsection