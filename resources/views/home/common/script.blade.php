<script data-cfasync="false" src="{{ asset('assets/js/common/email-decode.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/common/iconify.min.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/common/swiper-bundle.min.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/common/swiper-control.js?v=5.6') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/common/main.js?v=5.6') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/common/wow.min.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/common/lordicon.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/vendor/runtime.bf71a965.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/vendor/1500.9a09c40c.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/vendor/3116.4ec5e4f9.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/vendor/162.30ed95ee.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/vendor/966.e2e3d2ba.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/vendor/redesign_academy_home.7ae0ca0f.js') }}" defer></script>
<script src="{{ asset('assets/js/common/jquery-3.6.0.min.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new WOW().init();
  });
</script>
<script src="{{ asset('assets/js/common/sweetalert2@11.js')}}"></script>

<script>
  document.querySelectorAll('.action-button').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      const formId = this.dataset.form;
      const title = this.dataset.title;
      const text = this.dataset.text;
      const confirmText = this.dataset.confirm;

      Swal.fire({
        title: title,
        html: button.dataset.html,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e6a303",
        cancelButtonColor: "#ff0033",
        confirmButtonText: confirmText
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(formId).submit();
        }
      });
    });
  });
    document.addEventListener('DOMContentLoaded', function() {
        new WOW().init();
    });

</script>

@guest
<script>
    // Validation on register form
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_register');

        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');

        const errorName = document.getElementById('error-name');
        const errorEmail = document.getElementById('error-email');
        const errorPassword = document.getElementById('error-password');
        const errorConfirm = document.getElementById('error-password_confirmation');

        const namePattern = /^(?=.{2,50}$)(?:[A-Za-zÀ-ỹ]+(?:(?:[ ]+)|(?:[ ]*['-][ ]*)))*[A-Za-zÀ-ỹ]+$/;
        const emailPattern = /^(?=.{1,64}$)(?!\d+\.\d+)[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*@[a-zA-Z]+(?:(?:\-[a-zA-Z]+)|(?:\.[a-zA-Z]+))*(?:\.[a-zA-Z]+)+$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}\?\/><])\S{8,}$/;
        const decimalCheckEmail = /^\d+\.\d+/;
        
        const hiddenMsgName = document.getElementById('hidden-msg-name_register');
        const hiddenMsgEmail = document.getElementById('hidden-msg-email_register');
        const hiddenMsgPassword = document.getElementById('hidden-msg-password_register');
        const hiddenMsgConfirm = document.getElementById('hidden-msg-password_confirm_register');

        // Validate name when typing
        name.addEventListener('input', () => {
            const nameValue = name.value.trim();
            if (hiddenMsgName) {
                hiddenMsgName.style.display = 'none';
            }

            if (nameValue === '') {
                errorName.innerText = 'Full Name is required.';
            } else if (!namePattern.test(nameValue)) {
                errorName.innerText = 'Full Name: 2-50 letters, spaces, apostrophes, or hyphens; and cannot end with \' or \-.';
            } else {
                errorName.innerText = '';
            }
        });

        // Validate email when typing
        email.addEventListener('input', () => {
            const value = email.value.trim();
            if (hiddenMsgEmail) {
                hiddenMsgEmail.style.display = 'none';
            }

            if (value === '') {
                errorEmail.innerText = 'Email is required.';
            } else if (value.length > 64) {
                errorEmail.innerText = 'Email maximum 64 characters';
            } else if (decimalCheckEmail.test(value)) {
                errorEmail.innerText = 'Email must not start with a decimal-like format (e.g., 1.23).';
            } else if (!emailPattern.test(value)) {
                errorEmail.innerText = 'Only letters (a-z), numbers (0-9), and periods (.) are allowed.';
            } else {
                errorEmail.innerText = '';
            }
        });

        // Validate password when typing
        password.addEventListener('input', () => {
            const value = password.value;
            if (hiddenMsgPassword) {
                hiddenMsgPassword.style.display = 'none';
            }

            if (value === '') {
                errorPassword.innerText = 'Password is required.';
            } else if (value.startsWith(' ') || value.endsWith(' ')) {
                errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
            } else if (!passwordPattern.test(value)) {
                errorPassword.innerText = 'Password is invalid.';
            } else {
                errorPassword.innerText = '';
            }
        });

        // Validate password confirmation when typing
        passwordConfirm.addEventListener('input', () => {
            const confirmValue = passwordConfirm.value;
            const passwordValue = password.value;
            if (hiddenMsgConfirm) {
                hiddenMsgConfirm.style.display = 'none';
            }

            if (confirmValue === '') {
                errorConfirm.innerText = 'Password Confirm is required.';
            } else if (confirmValue !== passwordValue) {
                errorConfirm.innerText = 'Password Confirm does not match.';
            } else {
                errorConfirm.innerText = '';
            }
        });

        // Final validation on submit
        form.addEventListener('submit', function(e) {
            let valid = true;

            const nameValue = name.value.trim();
            const emailValue = email.value.trim();
            const passwordValue = password.value;
            const confirmValue = passwordConfirm.value;

            // Validate name when submit
            if (nameValue === '') {
                errorName.innerText = 'Full Name is required.';
                valid = false;
            } else if (!namePattern.test(nameValue)) {
                errorName.innerText = 'Full Name must only contain letters, spaces, apostrophes, or hyphens (at least 2 characters and at most 50 characters).';
                valid = false;
            } else {
                errorName.innerText = '';
            }

            // Validate email when submit
            if (!emailValue) {
                errorEmail.innerText = 'Email is required.';
                valid = false;
            } else if (!emailPattern.test(emailValue)) {
                errorEmail.innerText = 'Email is invalid.';
                valid = false;
            } else {
                errorEmail.innerText = '';
            }

            // Validate password when submit
            if (passwordValue === '') {
                errorPassword.innerText = 'Password is required.';
                valid = false;
            } else if (passwordValue.startsWith(' ') || passwordValue.endsWith(' ')) {
                errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
                valid = false;
            } else if (!passwordPattern.test(passwordValue)) {
                errorPassword.innerText = 'Password is invalid.';
                valid = false;
            } else {
                errorPassword.innerText = '';
            }

            // Validate password confirmation when submit
            if (confirmValue === '') {
                errorConfirm.innerText = 'Password Confirm is required.';
                valid = false;
            } else if (confirmValue !== passwordValue) {
                errorConfirm.innerText = 'Password Confirm does not match.';
                valid = false;
            } else {
                errorConfirm.innerText = '';
            }

            if (!valid) {
                e.preventDefault();
            }
        });

        // Password tooltip
        const passwordField = document.getElementById('password');
        const tooltip = document.getElementById('password-tooltip');

        passwordField.addEventListener('focus', () => {
            tooltip.style.display = 'block';
        });

        passwordField.addEventListener('blur', () => {
            tooltip.style.display = 'none';
        });
    });

</script>

<script>
    // Validation on login form
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_login');
        const email = document.getElementById('email_login');
        const password = document.getElementById('password_login');
        const errorEmail = document.getElementById('error-email_login');
        const errorPassword = document.getElementById('error-password_login');
        const emailPattern = /^(?=.{1,64}$)(?!\d+\.\d+)[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*@[a-zA-Z]+(?:(?:\-[a-zA-Z]+)|(?:\.[a-zA-Z]+))*(?:\.[a-zA-Z]+)+$/;

        const hiddenMsgEmail = document.getElementById('hidden-msg-email_login');
        const hiddenMsgPassword = document.getElementById('hidden-msg-password_login');
        const hiddenMsg = document.getElementById('hidden-msg_login');

        // Validation email when typing
        email.addEventListener('input', () => {
            if (hiddenMsgEmail) {
                hiddenMsgEmail.style.display = 'none';
            }
            if (hiddenMsg) {
                hiddenMsg.style.display = 'none';
            }
            const value = email.value.trim();

            if (value === '') {
                errorEmail.innerText = 'Email is required.';
            } else if (!emailPattern.test(value)) {
                errorEmail.innerText = 'Email is invalid.';
            } else {
                errorEmail.innerText = '';
            }
        });
        
        // Validation password when typing
        password.addEventListener('input', () => {
            if (hiddenMsgPassword) {
                hiddenMsgPassword.style.display = 'none';
            }
            const value = password.value;

            if (value === '') {
                errorPassword.innerText = 'Password is required.';
            } else {
                errorPassword.innerText = '';
            }
        });

        // Validation when submit
        form.addEventListener('submit', function(e) {
            let valid = true;
            const emailValue = email.value.trim();
            const passwordValue = password.value;

            // Validation email submit
            if (emailValue === '') {
                errorEmail.innerText = 'Email is required.';
                valid = false;
            } else if (!emailPattern.test(emailValue)) {
                errorEmail.innerText = 'Email is invalid.';
                valid = false;
            } else {
                errorEmail.innerText = '';
            }

            // Validation password submit
            if (passwordValue === '') {
                errorPassword.innerText = 'Password is required.';
                valid = false;
            } else {
                errorPassword.innerText = '';
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    });

</script>

<script>
    // Validation on forgot password form
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_forgot');
        const email = document.getElementById('email_forgot');
        const errorEmail = document.getElementById('error-email_forgot');
        const hiddenMessageEmail = document.getElementById('hidden-msg-email_forgot');
        const emailPattern = /^(?=.{1,64}$)(?!\d+\.\d+)[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*@[a-zA-Z]+(?:(?:\-[a-zA-Z]+)|(?:\.[a-zA-Z]+))*(?:\.[a-zA-Z]+)+$/;

        if (form) {
            // Validation email when typing
            email.addEventListener('input', () => {
            const value = email.value;

            if (hiddenMessageEmail) {
                hiddenMessageEmail.style.display = 'none';
            }

            if (value === '') {
                errorEmail.innerText = 'Email is required.';
            } else if (value.startsWith(' ') || value.endsWith(' ')) {
                errorEmail.innerText = 'Your email can\'t start or end with a blank space.';
            } else if (!emailPattern.test(value)) {
                errorEmail.innerText = 'Email is invalid.';
            } else {
                errorEmail.innerText = '';
            }
            });

            //Validation email when submit
            form.addEventListener('submit', function(e) {
                let valid = true;
                const emailValue = email.value.trim();

                if (emailValue === '') {
                    errorEmail.innerText = 'Email is required.';
                    valid = false;
                } else if (!emailPattern.test(emailValue)) {
                    errorEmail.innerText = 'Email is invalid.';
                    valid = false;
                } else {
                    errorEmail.innerText = '';
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        }
    });

</script>

<script>
    // Validation on reset password form
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_reset-password');
        const password = document.getElementById('password_reset');
        const passwordConfirm = document.getElementById('password_confirmation_reset');
        const errorPassword = document.getElementById('error-password_reset');
        const errorPasswordConfirm = document.getElementById('error-password_confirmation_reset');
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}\?\/><])\S{8,}$/;

        const hiddenMsgPassword = document.getElementById('hidden-msg-password_reset');
        const hiddenMsgPasswordConfirm = document.getElementById('hidden-msg-password_confirm_reset');

        // Validation password when typing
        if (form) {
            if (hiddenMsgPassword) {
                hiddenMsgPassword.style.display = 'none';
            }
            password.addEventListener('input', () => {
            const value = password.value;

            if (value === '') {
                errorPassword.innerText = 'Password is required.';
            } else if (value.startsWith(' ') || value.endsWith(' ')) {
                errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
            } else if (!passwordPattern.test(value)) {
                errorPassword.innerText = 'Password is invalid.';
            } else {
                errorPassword.innerText = '';
            }
        });

        // Validation password confirmation when typing
        passwordConfirm.addEventListener('input', () => {
            if (hiddenMsgPasswordConfirm) {
                hiddenMsgPasswordConfirm.style.display = 'none';
            }
            const confirmValue = passwordConfirm.value;
            const passwordValue = password.value;

            if (confirmValue === '') {
                errorPasswordConfirm.innerText = 'Password Confirm is required.';
            } else if (confirmValue !== passwordValue) {
                errorPasswordConfirm.innerText = 'Password Confirm does not match.';
            } else {
                errorPasswordConfirm.innerText = '';
            }
        });

        // Final validation on submit
        form.addEventListener('submit', function(e) {
            let valid = true;

            const passwordValue = password.value;
            const confirmValue = passwordConfirm.value;

            // Validation password when submit
            if (passwordValue === '') {
                errorPassword.innerText = 'Password is required.';
                valid = false;
            } else if (passwordValue.startsWith(' ') || passwordValue.endsWith(' ')) {
                errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
                valid = false;
            } else if (!passwordPattern.test(passwordValue)) {
                errorPassword.innerText = 'Password is invalid.';
                valid = false;
            } else {
                errorPassword.innerText = '';
            }

            // Validation password confirmation when submit
            if (confirmValue === '') {
                errorPasswordConfirm.innerText = 'Password Confirm is required.';
                valid = false;
            } else if (confirmValue !== passwordValue) {
                errorPasswordConfirm.innerText = 'Password Confirm does not match.';
                valid = false;
            } else {
                errorPasswordConfirm.innerText = '';
            }

            if (!valid) {
                e.preventDefault();
            }
        });

            // Password tooltip
            const passwordField = document.getElementById('password_reset');
            const tooltip = document.getElementById('password-tooltip_reset');

            passwordField.addEventListener('focus', () => {
                tooltip.style.display = 'block';
            });

            passwordField.addEventListener('blur', () => {
                tooltip.style.display = 'none';
            });
        }
    });

</script>
@endguest

{{-- Alert popup --}}
@if(session('success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: "{{ session('success') }}"
    });
</script>
 @endif

@if(session('fail'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "error",
        title: "{{ session('fail') }}"
    });
</script>
@endif

@if(session('error'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "error",
        title: "{{ session('error') }}"
    });
</script>
@endif

@if(session('status'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: " {{ session('status') }}"
    });
</script>
 @endif

{{-- Loading popup --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.querySelector('.btn-logout');
    const logoutForm = document.getElementById('logout_form');

    if (logoutBtn && logoutForm) {
        logoutBtn.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, logout"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Signing out...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    setTimeout(() => {
                        logoutForm.submit();
                    }, 300);
                }
            });
        });
    }
});
</script>

{{-- Validation on register teacher form --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_register_teacher');

        const name = document.getElementById('name_teacher');
        const email = document.getElementById('email_teacher');
        const password = document.getElementById('password_teacher');
        const passwordConfirm = document.getElementById('password_confirmation_teacher');
        const cvFile = document.getElementById('cv_file');
        const majorsSelects = document.querySelectorAll('select[name="majors[]"]');

        const errorName = document.getElementById('error-name_teacher');
        const errorEmail = document.getElementById('error-email_teacher');
        const errorPassword = document.getElementById('error-password_teacher');
        const errorConfirm = document.getElementById('error-password_confirmation_teacher');
        const errorCv = document.getElementById('error-cv');
        const errorMajors = document.getElementById('error-major');

        const namePattern = /^(?=.{2,50}$)(?:[A-Za-zÀ-ỹ]+(?:(?:[ ]+)|(?:[ ]*['-][ ]*)))*[A-Za-zÀ-ỹ]+$/;
        const emailPattern = /^(?=.{1,64}$)(?!\d+\.\d+)[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*@[a-zA-Z]+(?:(?:\-[a-zA-Z]+)|(?:\.[a-zA-Z]+))*(?:\.[a-zA-Z]+)+$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}\?\/><])\S{8,}$/;
        const decimalCheckEmail = /^\d+\.\d+/;
        
        const hiddenMsgName = document.getElementById('hidden-msg-name_teacher');
        const hiddenMsgEmail = document.getElementById('hidden-msg-email_teacher');
        const hiddenMsgPassword = document.getElementById('hidden-msg-password_teacher');
        const hiddenMsgConfirm = document.getElementById('hidden-msg-password_confirm_teacher');
        const hiddenMsgCv = document.getElementById('hidden-msg-cv');
        const hiddenMsgMajor = document.getElementById('hidden-msg-major');

        const MAX_FILE_SIZE = 2 * 1024 * 1024;

        // Validate name when typing
        if (name) {
            name.addEventListener('input', () => {
                const nameValue = name.value.trim();
                if (hiddenMsgName) {
                    hiddenMsgName.style.display = 'none';
                }

                if (nameValue === '') {
                    errorName.innerText = 'Full Name is required.';
                } else if (!namePattern.test(nameValue)) {
                    errorName.innerText = 'Full Name: 2-50 letters, spaces, apostrophes, or hyphens; and cannot end with \' or \-.';
                } else {
                    errorName.innerText = '';
                }
            });
        }
        
        // Validate email when typing
        if (email) {
            email.addEventListener('input', () => {
                const value = email.value.trim();
                if (hiddenMsgEmail) {
                    hiddenMsgEmail.style.display = 'none';
                }

                if (value === '') {
                    errorEmail.innerText = 'Email is required.';
                } else if (value.length > 64) {
                    errorEmail.innerText = 'Email maximum 64 characters';
                } else if (decimalCheckEmail.test(value)) {
                    errorEmail.innerText = 'Email must not start with a decimal-like format (e.g., 1.23).';
                } else if (!emailPattern.test(value)) {
                    errorEmail.innerText = 'Only letters (a-z), numbers (0-9), and periods (.) are allowed.';
                } else {
                    errorEmail.innerText = '';
                }
            });
        }
        
        // Validate cv file when input
        if (cvFile) {
            cvFile.addEventListener('input', function () {
                if (hiddenMsgCv) {
                    hiddenMsgCv.style.display = 'none';
                }
                const file = this.files[0];
                if(file) {
                    const validTypes = ['application/pdf'];
                    if (!validTypes.includes(file.type)) {
                        errorCv.innerText = 'Only .pdf files are allowed.';
                        this.value = '';
                    } else if (file.size > MAX_FILE_SIZE) {
                        errorCv.innerText = 'File must be smaller than 2MB.';
                        this.value = '';
                    } else {
                        errorCv.innerText = '';
                    }
                } else {
                    errorCv.innerText = 'CV file is required.'
                }
            });
        }

        // Validate majors when input
        let selectedMajors = 0;
        if (majorsSelects) {
            majorsSelects.forEach(select => {
                select.addEventListener('input', function () {
                    if (hiddenMsgMajor) {
                        hiddenMsgMajor.style.display = 'none';
                    }

                    selectedMajors = 0;
                    majorsSelects.forEach(s => {
                        if (s.value !== '') {
                            selectedMajors++;
                        }
                    });

                    if (selectedMajors === 0) {
                        errorMajors.innerText = 'Please select at least 1 subject.';
                    } else if (selectedMajors > 3) {
                        errorMajors.innerText = 'You can select maximum 3 subjects.';
                    } else {
                        errorMajors.innerText = '';
                    }
                });
            });
        }

        // Validate password when typing
        if (password) {
            password.addEventListener('input', () => {
                const value = password.value;
                if (hiddenMsgPassword) {
                    hiddenMsgPassword.style.display = 'none';
                }

                if (value === '') {
                    errorPassword.innerText = 'Password is required.';
                } else if (value.startsWith(' ') || value.endsWith(' ')) {
                    errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
                } else if (!passwordPattern.test(value)) {
                    errorPassword.innerText = 'Password is invalid.';
                } else {
                    errorPassword.innerText = '';
                }
            });
        }
        
        // Validate password confirmation when typing
        if (passwordConfirm) {
            passwordConfirm.addEventListener('input', () => {
                const confirmValue = passwordConfirm.value;
                const passwordValue = password.value;
                if (hiddenMsgConfirm) {
                    hiddenMsgConfirm.style.display = 'none';
                }

                if (confirmValue === '') {
                    errorConfirm.innerText = 'Password Confirm is required.';
                } else if (confirmValue !== passwordValue) {
                    errorConfirm.innerText = 'Password Confirm does not match.';
                } else {
                    errorConfirm.innerText = '';
                }
            });
        }

        // Final validation on submit
        if (form) {
            form.addEventListener('submit', function(e) {
                let valid = true;

                const nameValue = name.value.trim();
                const emailValue = email.value.trim();
                const passwordValue = password.value;
                const confirmValue = passwordConfirm.value;

                // Validate name when submit
                if (nameValue === '') {
                    errorName.innerText = 'Full Name is required.';
                    valid = false;
                } else if (!namePattern.test(nameValue)) {
                    errorName.innerText = 'Full Name must only contain letters, spaces, apostrophes, or hyphens (at least 2 characters and at most 50 characters).';
                    valid = false;
                } else {
                    errorName.innerText = '';
                }

                // Validate email when submit
                if (!emailValue) {
                    errorEmail.innerText = 'Email is required.';
                    valid = false;
                } else if (!emailPattern.test(emailValue)) {
                    errorEmail.innerText = 'Email is invalid.';
                    valid = false;
                } else {
                    errorEmail.innerText = '';
                }

                // Validate password when submit
                if (passwordValue === '') {
                    errorPassword.innerText = 'Password is required.';
                    valid = false;
                } else if (passwordValue.startsWith(' ') || passwordValue.endsWith(' ')) {
                    errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
                    valid = false;
                } else if (!passwordPattern.test(passwordValue)) {
                    errorPassword.innerText = 'Password is invalid.';
                    valid = false;
                } else {
                    errorPassword.innerText = '';
                }

                // Validate password confirmation when submit
                if (confirmValue === '') {
                    errorConfirm.innerText = 'Password Confirm is required.';
                    valid = false;
                } else if (confirmValue !== passwordValue) {
                    errorConfirm.innerText = 'Password Confirm does not match.';
                    valid = false;
                } else {
                    errorConfirm.innerText = '';
                }

                // Validate cv file when submit
                if (cvFile) {
                    const file = cvFile.files[0];
                    if(file) {
                        const validTypes = ['application/pdf'];
                        if (!validTypes.includes(file.type)) {
                            errorCv.innerText = 'Only .pdf files are allowed.';
                            this.value = '';
                            valid = false;
                        } else {
                            errorCv.innerText = '';
                        }
                    } else {
                        errorCv.innerText = 'CV file is required.'
                        valid = false;
                    }
                }

                // Validate major when submit
                let selectedMajors = 0;
                majorsSelects.forEach(select => {
                    if (select.value !== '') {
                        selectedMajors++;
                    }
                });

                if (selectedMajors === 0) {
                    errorMajors.innerText = 'Please select at least 1 subject.';
                    valid = false;
                } else if (selectedMajors > 3) {
                    errorMajors.innerText = 'You can select maximum 3 subjects.';
                    valid = false;
                } else {
                    errorMajors.innerText = '';
                }
                
                if (!valid) {
                    e.preventDefault();
                }
            });

            // Password tooltip
            const passwordField = document.getElementById('password_teacher');
            const tooltip = document.getElementById('password-tooltip-teacher');

            passwordField.addEventListener('focus', () => {
                tooltip.style.display = 'block';
            });

            passwordField.addEventListener('blur', () => {
                tooltip.style.display = 'none';
            });
        }
    });

</script>

{{-- Validation on edit profile form --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_edit_profile');

        const name = document.getElementById('name_profile');
        const phone = document.getElementById('phone_profile');
        const address = document.getElementById('address_profile');
        const avatar = document.getElementById('avatar');

        const errorName = document.getElementById('error-name_profile');
        const errorPhone = document.getElementById('error-phone_profile');
        const errorAddress = document.getElementById('error-address_profile');
        const errorAvatar = document.getElementById('error-avatar');

        const namePattern = /^(?=.{2,50}$)(?:[A-Za-zÀ-ỹ]+(?:(?:[ ]+)|(?:[ ]*['-][ ]*)))*[A-Za-zÀ-ỹ]+$/;
        const phonePattern = /^0\d{9}$/;
        const addressPattern = /^.{5,150}$/;
        
        const hiddenMsgName = document.getElementById('hidden-msg-name_profile');
        const hiddenMsgPhone = document.getElementById('hidden-msg-phone_profile');
        const hiddenMsgAddress = document.getElementById('hidden-msg-address_profile');
        const hiddenMsgAvatar = document.getElementById('hidden-msg-avatar');

        const oldImageWrapper = document.getElementById('old-image-wrapper');
        const newImagePreview = document.getElementById('new-image-preview');
        const previewImage = document.getElementById('preview-image');
        @php
            $hasOldImage = isset($course) && $course->image ? 'true' : 'false';
        @endphp
        const hasOldImage = {{ $hasOldImage ? 'true' : 'false' }};

        const MAX_FILE_SIZE = 2 * 1024 * 1024;

        // Validate name when typing
        if (name) {
            name.addEventListener('input', () => {
                const nameValue = name.value.trim();
                if (hiddenMsgName) {
                    hiddenMsgName.style.display = 'none';
                }

                if (nameValue === '') {
                    errorName.innerText = 'Full Name is required.';
                } else if (!namePattern.test(nameValue)) {
                    errorName.innerText = 'Full Name: 2-50 letters, spaces, apostrophes, or hyphens; and cannot end with \' or \-.';
                } else {
                    errorName.innerText = '';
                }
            });
        }
        
        // Validate phone number when typing
        if (phone) {
            phone.addEventListener('input', () => {
                const phoneValue = phone.value.trim();
                if (hiddenMsgPhone) {
                    hiddenMsgPhone.style.display = 'none';
                }

                if (phoneValue === '') {
                    errorPhone.innerText = '';
                } else if (!phonePattern.test(phoneValue)) {
                    errorPhone.innerText = 'Phone number must start with 0 and contain exactly 10 digits.';
                } else {
                    errorPhone.innerText = '';
                }
            });
        }

        // Validate address number when typing
        if (address) {
            address.addEventListener('input', () => {
                const addressValue = address.value.trim();
                if (hiddenMsgAddress) {
                    hiddenMsgAddress.style.display = 'none';
                }

                if (addressValue === '') {
                    errorAddress.innerText = '';
                } else if (!addressPattern.test(addressValue)) {
                    errorAddress.innerText = 'Address must be between 5 and 150 characters.';
                } else {
                    errorAddress.innerText = '';
                }
            });
        }
        
        // Validate avatar when input
        if (avatar) {
            avatar.addEventListener('input', function () {
                if (hiddenMsgAvatar) {
                    hiddenMsgAvatar.style.display = 'none';
                }
                const file = this.files[0];
                if(file) {
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        errorAvatar.innerText = 'Only image files (jpg, jpeg, png, gif, webp) are allowed.';
                        this.value = '';
                        newImagePreview.style.display = 'none';
                        if (oldImageWrapper) {
                            oldImageWrapper.style.display = hasOldImage ? 'block' : 'none';
                        }
                    } else if (file.size > MAX_FILE_SIZE) {
                        errorAvatar.innerText = 'Image must be smaller than 2MB.';
                        this.value = '';
                        newImagePreview.style.display = 'none';
                        if (oldImageWrapper) {
                            oldImageWrapper.style.display = hasOldImage ? 'block' : 'none';
                        }
                    } else {
                        errorAvatar.innerText = '';
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewImage.src = e.target.result;
                            newImagePreview.style.display = 'block';
                            if (oldImageWrapper) {
                                oldImageWrapper.style.display = 'none';
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                }
            });
        }

        // Final validation on submit
        if (form) {
            form.addEventListener('submit', function(e) {
                let valid = true;

                const nameValue = name.value.trim();
                const phoneValue = phone.value.trim();
                const addressValue = phone.value.trim();

                // Validate name when submit
                if (nameValue === '') {
                    errorName.innerText = 'Full Name is required.';
                    valid = false;
                } else if (!namePattern.test(nameValue)) {
                    errorName.innerText = 'Full Name must only contain letters, spaces, apostrophes, or hyphens (at least 2 characters and at most 50 characters).';
                    valid = false;
                } else {
                    errorName.innerText = '';
                }

                // Validate phone number when submit
                if (phoneValue === '') {
                    errorPhone.innerText = '';
                } else if (!phonePattern.test(phoneValue)) {
                    errorPhone.innerText = 'Phone number must start with 0 and contain exactly 10 digits.';
                    valid = false;
                } else {
                    errorPhone.innerText = '';
                }

                // Validate address when submit
                if (addressValue === '') {
                    errorAddress.innerText = '';
                } else if (!addressPattern.test(addressValue)) {
                    errorAddress.innerText = 'Address must be between 5 and 150 characters.';
                    valid = false;
                } else {
                    errorAddress.innerText = '';
                }

                // Validate cv file when submit
                if (avatar) {
                    const file = avatar.files[0];
                    if(file) {
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            errorAvatar.innerText = 'Only image files (jpg, jpeg, png, gif, webp) are allowed.';
                            this.value = '';
                            valid = false;
                        } else {
                            errorAvatar.innerText = '';
                        }
                    }
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        }
    });

</script>

{{-- Validation on change password form --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_change_password');

        const oldPassword = document.getElementById('password_old');
        const password = document.getElementById('password_change');
        const passwordConfirm = document.getElementById('password_confirmation_change');

        const errorOldPassword = document.getElementById('error-old_password_change');
        const errorPassword = document.getElementById('error-password_change');
        const errorConfirm = document.getElementById('error-password_confirmation_change');

        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}\?\/><])\S{8,}$/;
        
        const hiddenMsgOldPassword = document.getElementById('hidden-msg-old_password_change');
        const hiddenMsgPassword = document.getElementById('hidden-msg-password_change');
        const hiddenMsgConfirm = document.getElementById('hidden-msg-password_confirm_change');

        // Validate old password when typing
        if (oldPassword) {
            oldPassword.addEventListener('input', () => {
                const value = oldPassword.value;
                if (hiddenMsgOldPassword) {
                    hiddenMsgOldPassword.style.display = 'none';
                }

                if (value === '') {
                    errorOldPassword.innerText = 'Old Password is required.';
                } else {
                    errorOldPassword.innerText = '';
                }
            });
        }

        // Validate password when typing
        if (password) {
            password.addEventListener('input', () => {
                const value = password.value;
                if (hiddenMsgPassword) {
                    hiddenMsgPassword.style.display = 'none';
                }

                if (value === '') {
                    errorPassword.innerText = 'Password is required.';
                } else if (value.startsWith(' ') || value.endsWith(' ')) {
                    errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
                } else if (!passwordPattern.test(value)) {
                    errorPassword.innerText = 'Password is invalid.';
                } else {
                    errorPassword.innerText = '';
                }
            });
        }
        
        // Validate password confirmation when typing
        if (passwordConfirm) {
            passwordConfirm.addEventListener('input', () => {
                const confirmValue = passwordConfirm.value;
                const passwordValue = password.value;
                if (hiddenMsgConfirm) {
                    hiddenMsgConfirm.style.display = 'none';
                }

                if (confirmValue === '') {
                    errorConfirm.innerText = 'Password Confirm is required.';
                } else if (confirmValue !== passwordValue) {
                    errorConfirm.innerText = 'Password Confirm does not match.';
                } else {
                    errorConfirm.innerText = '';
                }
            });
        }

        // Final validation on submit
        if (form) {
            form.addEventListener('submit', function(e) {
                let valid = true;

                const oldPasswordValue = password.value;
                const passwordValue = password.value;
                const confirmValue = passwordConfirm.value;

                // Validate old password when submit
                if (oldPasswordValue === '') {
                    errorOldPassword.innerText = 'Old Password is required.';
                    valid = false;
                } else {
                    errorOldPassword.innerText = '';
                }

                // Validate password when submit
                if (passwordValue === '') {
                    errorPassword.innerText = 'Password is required.';
                    valid = false;
                } else if (passwordValue.startsWith(' ') || passwordValue.endsWith(' ')) {
                    errorPassword.innerText = 'Your password can\'t start or end with a blank space.';
                    valid = false;
                } else if (!passwordPattern.test(passwordValue)) {
                    errorPassword.innerText = 'Password is invalid.';
                    valid = false;
                } else {
                    errorPassword.innerText = '';
                }

                // Validate password confirmation when submit
                if (confirmValue === '') {
                    errorConfirm.innerText = 'Password Confirm is required.';
                    valid = false;
                } else if (confirmValue !== passwordValue) {
                    errorConfirm.innerText = 'Password Confirm does not match.';
                    valid = false;
                } else {
                    errorConfirm.innerText = '';
                }

                if (!valid) {
                    e.preventDefault();
                }
            });

            // Password tooltip
            const passwordField = document.getElementById('password_change');
            const tooltip = document.getElementById('password-tooltip-change');

            passwordField.addEventListener('focus', () => {
                tooltip.style.display = 'block';
            });

            passwordField.addEventListener('blur', () => {
                tooltip.style.display = 'none';
            });
        }
    });

</script>

{{-- Popup when fail --}}
@if ($errors->has('fail_msg'))
<script>
Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "{!! $errors->first('fail_msg') !!}",
});

</script>
@endif

{{-- Disable submit button when the form loading --}}
<script>
const forms = [
    'form_forgot',
    'form_register',
    'form_login',
    'form_reset-password',
    'form_edit_profile',
    'form_register_teacher',
    'form_change_password',
];

forms.forEach(id => {
    const form = document.getElementById(id);

    if (form) {
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        form.addEventListener('submit', function(e) {
            if (submitBtn) {
                submitBtn.disabled = true;
            }
        });

        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            });
        });
    }
});
</script>

{{-- Save intended url --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('form_login');
        if (loginForm) {
            const intendedInput = document.getElementById('intended_url');
            if (intendedInput) {
                intendedInput.value = window.location.href;
            }
        }
    })
</script>

{{-- Load more data when scroll --}}
<script>
const container = document.querySelector('#courses-container');
const loadMoreBtn = document.querySelector('#load-more-btn');
const loadMoreContainer = document.querySelector('#load-more-container');

let nextPage = 2;
let loading = false;

const observer = new IntersectionObserver(async (entries) => {
    const entry = entries[0];
    if (!entry.isIntersecting || loading) return;

    try {
        if (entry.target) observer.unobserve(entry.target);
    } catch (e) { /* ignore */ }

    loading = true;

    const loadingWrapper = loadMoreBtn ? loadMoreBtn.querySelector('.button__loading-wrapper') : null;
    if (loadMoreContainer) loadMoreContainer.style.display = 'block';
    if (loadingWrapper) {
        loadingWrapper.style.display = 'inline-flex';
        loadingWrapper.classList.add('is-loading-active');
    }

    try {
        const queryString = window.location.search;
        const url = `/courses/load-more${queryString ? queryString + '&' : '?'}page=${nextPage}`;

        const res = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        if (data.html) {
            container.insertAdjacentHTML('beforeend', data.html);
        }

        if (!data.html || !data.next_page) {
            observer.disconnect();
            if (loadMoreContainer) loadMoreContainer.style.display = 'none';
            const block = document.querySelector('.block1b');
            if (block) block.classList.add('pb-80px');

            // reset status spinner + loading
            loading = false;
            if (loadingWrapper) {
                loadingWrapper.classList.remove('is-loading-active');
                loadingWrapper.style.display = 'none';
            }
        } else {
            // has more page next
            nextPage = data.next_page;

            const items = container.querySelectorAll('li.grid-academy__item');
            const lastItem = items[items.length - 1];
            if (lastItem) observer.observe(lastItem);

            // reset status spinner + loading
            loading = false;
            if (loadingWrapper) {
                loadingWrapper.classList.remove('is-loading-active');
                loadingWrapper.style.display = 'none';
            }
        }

    } catch(e) {
        console.error(e);
        loading = false;
        if (loadingWrapper) {
            loadingWrapper.classList.remove('is-loading-active');
            loadingWrapper.style.display = 'none';
        }
    }
}, {
    rootMargin: "200px"
});

// initial observe
if (container) {
    const initialItems = container.querySelectorAll('li.grid-academy__item');
    if (initialItems && initialItems.length > 0) {
        observer.observe(initialItems[initialItems.length - 1]);
    } else {
        if (loadMoreContainer) loadMoreContainer.style.display = 'none';
        const block = document.querySelector('.block1b');
        if (block) block.classList.add('pb-80px');
    }
}

</script>

{{-- Hide error message on console screen --}}
<script>
window.addEventListener("error", function (e) {
  if (String(e.message).includes("Cannot read properties of undefined (reading 'call')")) {
    e.preventDefault();
  }
});
</script>

{{-- Reload courses list --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("category") || urlParams.has("level")) {
        const coursesList = document.getElementById("nav-filters__container");
        if (coursesList) {
            coursesList.scrollIntoView({ behavior: "smooth" });
        }
    }
});
</script>
