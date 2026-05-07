@guest
<div class="modal-form-register main-form-register">
    <div class="container">
        <div class="row">
            <div class="form-register">
                <div class="form-register__wrapper p-50px pb-80px text-center">
                    <h5 class="fw-bolder mb-30px">Register a new account</h5>
                    <form action="{{ route('register.store') }}" method="POST" id="form_register">
                        @csrf
                        <div class="group-field-message">
                            <input type="text" name="name" id="name" placeholder="Full Name" value="{{ old('name') }}">
                            <small class="text-danger error-message mb-3" id="error-name"></small>
                            <small class="text-danger error-message" id="hidden-msg-name_register" >{{ $errors->register->first('name') }}</small>
                        </div>

                        <div class="group-field-message">
                            <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                            <small class="text-danger error-message mb-3" id="error-email"></small>
                            <small class="text-danger error-message" id="hidden-msg-email_register">{{ $errors->register->first('email') }}</small>
                        </div>

                        <div class="group-field-message">
                            <div class="password-container-register">
                                <input type="password" name="password" id="password" placeholder="Password">
                                <span id="pw-show-hide-register" class="pw-show-hide-register">
                                    <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                    <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                                </span>
                            </div>
                            <small class="text-danger error-message mb-3" id="error-password"></small>
                            <small class="text-danger error-message" id="hidden-msg-password_register">{{ $errors->register->first('password') }}</small>

                            {{-- Tooltip password --}}
                            <div id="password-tooltip" style="display: none; text-align: left; font-size: 12px; color: #6c757d; margin-top: 15px;">
                                <strong>Password must contain:</strong>
                                <ul style="margin: 5px 0 0 15px; padding-left: 0;">
                                    <li>At least 8 characters</li>
                                    <li>1 uppercase & 1 lowercase letter</li>
                                    <li>1 number</li>
                                    <li>1 special character (!@#$...)</li>
                                    <li>No spaces</li>
                                </ul>
                            </div>
                        </div>

                        <div class="group-field-message">
                            <div class="password-container-register">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Password Confirm">
                                <span id="pw-show-hide-confirm" class="pw-show-hide-confirm">
                                    <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                    <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                                </span>
                            </div>
                            <small class="text-danger error-message mb-3" id="error-password_confirmation"></small>
                            <small class="text-danger error-message" id="hidden-msg-password_confirm_register">{{ $errors->register->first('password_confirmation') }}</small>
                        </div>
                        <input type="submit" id="submitBtn" value="Sign Up" class="form-register__submit transition-all">
                    </form>
                    <div class="d-flex align-items-center justify-content-center gap-10px mb-10px">
                        <p>Are you a member?</p>
                        <a href="#" class="form-register__register transition-all handle-login">Login now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('open_register_form'))
        const modalForm = document.querySelector('.modal-form-register');
        if (modalForm) {
            modalForm.classList.add('active');
            modalForm.style.display = 'block';
        }
        @endif
    });

</script>
    
@endguest
