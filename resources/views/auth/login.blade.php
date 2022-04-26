@extends('layouts.app')

@section('content')


    <div class="container pt-5 mt-5 ">
        <div class="row no-gutter shadow rounded bg-white">
            <div class="col-md-6  d-md-flex  my-auto justify-content-center ">
                <div class="text-center">
<img src="/assets/img/logo/lo.png"  class="p-5" alt="" width="70%">
<div class="pb-5 mb-3">
                    <h2 class="text-primary font-weight-bolder">S M S</h2>
                    <h5><span class="font-weight-bold">S</span>ale <span class="font-weight-bold ">M</span>anagement <span class="font-weight-bold ">S</span>ystem</h5>
                </div>
                </div>



            </div>
            <div class="col-md-6  py-5 px-2">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-10 mx-auto">
                                <h5 class="text-center">LOGIN</h5> <br>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group mb-3">

                                        <input id="username" type="text"
                                            class="form-control rounded-pill border-0 shadow-sm px-4 @error('username') is-invalid @enderror"
                                            name="username" value="{{ old('username') }}" required autocomplete="username"
                                            autofocus placeholder="Enter Your Username">

                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">


                                        <input id="password" type="password"
                                            class="form-control rounded-pill border-0 shadow-sm px-4 @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="current-password"
                                            placeholder="Enter Your Password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1"
                                                {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="btn btn-primary btn-block text-uppercase mb-2 mt-4 rounded-pill shadow-sm">Sign
                                        in</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
