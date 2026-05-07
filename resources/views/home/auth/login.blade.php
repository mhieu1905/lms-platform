@guest
<div class="modal-form-login main-form-login">
    <div class="container">
        <div class="row">
            <div class="form-login">
                <div class="form-login__wrapper p-50px pb-80px text-center">
                    <h5 class="fw-bolder mb-30px">Login with your site account</h5>
                    @error('wrong')
                    <p class="text-danger mt-1" id="hidden-msg_login">{{ $message }}</p>
                    @enderror
                    <form action="{{ route('login') }}" method="POST" id="form_login">
                        @csrf
                        <input type="hidden" name="intended_url" id="intended_url">
                        <div class="group-field-message">
                            <input type="email" id="email_login" name="email" placeholder="Email" value="{{ old('email') }}">
                            <small class="text-danger error-message mb-3" id="error-email_login"></small>
                            @error('email')
                            <p class="text-danger error-message" id="hidden-msg-email_login">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="group-field-message">
                            <div class="password-container">
                                <input type="password" id="password_login" name="password" placeholder="Password">
                                <span id="pw-show-hide" class="pw-show-hide">
                                    <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                    <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                                </span>
                            </div>

                            <small class="text-danger error-message mb-3" id="error-password_login"></small>
                            @error('password')
                            <p class="text-danger error-message" id="hidden-msg-password_login">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-login__remember d-flex align-items-center justify-content-between">
                            <label class="d-flex align-items-center gap-5px cursor-pointer">
                                <input type="checkbox" name="remember"> Remember Me
                            </label>

                            <a href="{{ route('password.request') }}" class="form-login__lost transition-all">Lost your password?</a>
                        </div>
                        <input type="submit" id="submitBtn_login" value="Login" class="form-login__submit transition-all">
                    </form>
                    <div class="d-flex align-items-center justify-content-center gap-10px mb-10px">
                        <p>Not a member yet?</p>
                        <a href="#" class="form-login__register transition-all handle-register">Register now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('open_login_form'))
        const modalLogin = document.querySelector('.modal-form-login');
        if (modalLogin) {
            modalLogin.classList.add('active');
            modalLogin.style.display = 'block';
        }
        @endif
    });

</script>
    
@endguest
