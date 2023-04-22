@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $content = getContent('login.content', true);
    @endphp
    <div class="account-section">
        <a class="account-section__close" href="{{ route('home') }}"> <i class="las la-times"></i></a>
        <div class="account-wrapper  d-flex justify-content-between w-100 flex-wrap flex-md-nowrap">
            <div class="account-left pe-lg-5 pe-md-4">
                <div class="account-content">
                    <div class="pb-60">
                        <a href="{{ route('home') }}" class="logo">
                            <img src="{{ siteLogo() }}" alt="{{ __($general->site_name) }}" title="{{ __($general->site_name) }}">
                        </a>
                    </div>
                    <h2 class="title">{{ __(@$content->data_values->heading) }}</h2>
                </div>
                <div class="account-thumb pt-80">
                    <img src="{{ getImage('assets/images/frontend/login/' . @$content->data_values->login_image, '800x400') }}" class="mt-auto mw-100">
                </div>
            </div>
            <div class="account-right my-auto">
                <div class="form-wrapper">
                    <div class="mb-4 mb-lg-5">
                        <h3>@lang('Sign in')</h3>
                    </div>
                    <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                        @csrf
                        <div class="floating-label form-group">
                            <input class="floating-input form-control form--control" name="username" type="text" required placeholder="none">
                            <label class="form-label-two fw-semibold">@lang('Username Or Email ')</label>
                        </div>
                        <div class="floating-label form-group">
                            <input class="floating-input form-control form--control" name="password" type="password" required placeholder="none">
                            <label class="form-label-two fw-semibold">@lang('Password')</label>
                        </div>
                        <x-captcha />
                        <div class="remember-wrapper d-flex flex-wrap justify-content-between my-3">
                            <div class="form-check">
                                <input type="checkbox" id="remember" class="form-check-input" name="remember">
                                <label for="remember" class="fw-semibold form-check-label">@lang('Remember Me')</label>
                            </div>
                            <p class="text-end"> <a href="{{ route('user.password.request') }}" class="text--base ms-2">@lang('Forgot Password?')</a></p>
                        </div>
                        <button class="btn--base btn  w-100" type="submit">@lang('Login')</button>
                        <div class="mt-3">
                            <p class="text-center">@lang("Don't have account?")<a href="{{ route('user.register') }}" class="text--base ms-1">@lang('Sign Up')</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            let captcha = $("input[name=captcha]");
            if (parseInt(captcha.length) > 0) {
                let html = `
                        <div class="floating-label form-group mb-0">
                                <input type="text" name="captcha" class="floating-input form-control form--control" placeholder="none" required>
                                <label class="form-label-two fw-semibold" for="">@lang('Captcha')</label>
                        </div>
                        `;
                $(captcha).remove();
                $(".captchaInput").html(html);
            }

            $('.customCaptcha').find('label').first().remove();
        })(jQuery);
    </script>
@endpush
