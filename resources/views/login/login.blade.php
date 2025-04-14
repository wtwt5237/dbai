@extends('layout')

@section('content')
    <section class="panel w-50 mt-5 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="m-auto">
                        <div class="form-group row">
                            <label for="email_address"
                                   class="col-md-2 offset-md-2 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input type="text" id="email_address" class="form-control" name="email" required
                                       autofocus>
                                @if ($errors->any())
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-2 offset-md-2 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input type="password" id="password" class="form-control" name="password" required>
                                @if ($errors->any())
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    <span class="text-danger">{{ $errors->first('auth') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4 mt-3">
                            <button type="submit" class="btn btn-primary">
                                Log In
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @include('footer/footer-short')

    <!-- javascript
  ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    @include('js')
@endsection


