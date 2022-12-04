@extends('layouts.app')

@section('content')
    <div class="@yield('container-class')">
        <div class="row justify-content-center">
            <div class="@yield('body-class')">
                <div class="card">
                    <div class="card-header">@yield('admin-title')</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @guest
                            @if (Route::has('login'))
                                <p>
                                    {{ __('You are not logged in!') }}
                                </p>
                                <a href="{{ route('login') }}">{{ __('Login') }}</a>
                            @endif
                        @else
                            @yield('admin-body')
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
