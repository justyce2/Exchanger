@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $content = getContent('login.content', true);
    @endphp
    <section class="padding-top padding-bottom  section-bg">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-4">
                <div class="card card-form custom--card">
                    <div class="card-body">
                        <h4 class="form__title mg-5">{{ __(@$content->data_values->heading) }}</h4>
                        <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="form-label">@lang('Username Or Email')</label>
                                <input type="text" name="username" value="{{ old('username') }}" class="form-control form--control" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">@lang('Password')</label>
                                <input id="password" type="password" class="form-control form--control" name="password" required>
                            </div>
                            <x-captcha />
                            <div class="form-group form--check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember"> @lang('Remember Me')</label>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Login') </button>
                            </div>
                            <div class="d-flex flex-wrap justify-content-between">
                                <a class="text--base" href="{{ route('user.password.request') }}">
                                    @lang('Forgot Password?')
                                </a>
                                <span>
                                    @lang("Don't have an account?") <a class="text--base" href="{{ route('user.register') }}">@lang('Register')</a>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.captcha div').css({
                "background-color": "transparent",
                "border": "1px dashed #ebebeb",
                "height": "55px",
                "line-height": "55px",
                "width": "260px"
            });
        })(jQuery);
    </script>
@endpush
