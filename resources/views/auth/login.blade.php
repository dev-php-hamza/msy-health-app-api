@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="login">
              <div>
                <div class="login_wrapper">
                  <div class="form">
                    <section class="login_content">
                      <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <h1>{{ __('Login') }}</h1>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus>
                                @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary Login_btn">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                      </form>
                     <div class="separator"><div>
                        <div>
                           <h2><img src="{{asset('assets/images/logo.png')}}" width="40" height="40" class="mr-2"><span>{{config('app.name', 'Laravel') }}</span></h2>
                           <p>
                            <span id="copyright">&copy;<script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script></span>
                            All Rights Reserved.Privacy and Terms</p>
                         </div>
                    </section>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
