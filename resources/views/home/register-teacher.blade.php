<!DOCTYPE html>
<html class="no-js" lang="en">
@include('home.common.header')
<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title" style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}'); padding-top: 97px">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">Register Teacher</h1>
        </div>
    </section>
    <section class="lost-password mt-60px mb-80px">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="lost-password__wrapper">
                        <form action="{{ route('register-teacher.submit') }}" enctype="multipart/form-data" method="POST" id="form_register_teacher">
                            @csrf
                            <div class="group-field-message text-start">
                                <label for="name_teacher">Full Name <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" name="name" id="name_teacher" value="{{ old('name') }}">
                                <small class="text-danger error-message mt-1" id="error-name_teacher"></small>
                                <small class="text-danger error-message mt-4" id="hidden-msg-name_teacher">{{ $errors->register_teacher->first('name') }}</small>
                            </div>

                            <div class="group-field-message text-start">
                                <label for="email_teacher">Email <span class="text-danger"> *</span></label>
                                <input type="email" class="form-control" name="email" id="email_teacher" value="{{ old('email') }}">
                                <small class="text-danger error-message mt-1" id="error-email_teacher"></small>
                                <small class="text-danger error-message mt-4" id="hidden-msg-email_teacher">{{ $errors->register_teacher->first('email') }}</small>
                            </div>

                            <div class="group-field-message text-start">
                                <label>Majors <span class="text-danger">*</span></label>
                                <small class="d-block text-muted">Select at least 1 subject (max 3)</small>

                                <select class="form-control mb-2" name="majors[]">
                                    <option value="">-- Select Major --</option>
                                    @foreach($majors as $major)
                                    <option value="{{ $major->id }}"
                                        {{ collect(old('majors'))->contains($major->id) ? 'selected' : '' }}>
                                        {{ $major->name }}
                                    </option>
                                    @endforeach
                                </select>

                                <select class="form-control mb-2" name="majors[]">
                                    <option value="">-- Select Major --</option>
                                    @foreach($majors as $major)
                                    <option value="{{ $major->id }}"
                                        {{ collect(old('majors'))->contains($major->id) ? 'selected' : '' }}>
                                        {{ $major->name }}
                                    </option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="majors[]">
                                    <option value="">-- Select Major --</option>
                                    @foreach($majors as $major)
                                    <option value="{{ $major->id }}"
                                        {{ collect(old('majors'))->contains($major->id) ? 'selected' : '' }}>
                                        {{ $major->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-danger error-message mt-1" id="error-major"></small>
                                <small class="text-danger error-message mt-4" id="hidden-msg-major">
                                    {{ $errors->register_teacher->first('majors') }}
                                    {{ $errors->register_teacher->first('majors.*') }}
                                </small>
                            </div>

                            <div class="group-field-message text-start">
                                <label for="cv_file">Upload CV (.pdf) <span class="text-danger"> *</span></label>
                                <input type="file" class="form-control" name="cv_file" id="cv_file" accept=".pdf">
                                <small class="text-danger error-message mt-1" id="error-cv"></small>
                                <small class="text-danger error-message mt-4" id="hidden-msg-cv">{{ $errors->register_teacher->first('cv_file') }}</small>
                            </div>

                            <div class="group-field-message text-start">
                                <label for="password_teacher">Password <span class="text-danger"> *</span></label>
                                <div class="password-container-register-teacher">
                                    <input type="password" class="form-control" name="password" id="password_teacher">
                                    <span id="pw-show-hide-register-teacher" class="pw-show-hide-register-teacher">
                                        <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                        <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                                    </span>
                                </div>
                                <small class="text-danger error-message mt-1" id="error-password_teacher"></small>
                                <small class="text-danger error-message mt-4" id="hidden-msg-password_teacher">{{ $errors->register_teacher->first('password') }}</small>

                                {{-- Tooltip password --}}
                                <div id="password-tooltip-teacher" style="display: none; text-align: left; font-size: 12px; color: #6c757d; margin-top: 20px;">
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

                            <div class="group-field-message text-start">
                                <label for="password_confirmation_teacher">Password Confirm <span class="text-danger"> *</span></label>
                                <div class="password-container-register-teacher">
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation_teacher">
                                    <span id="pw-show-hide-confirm-teacher" class="pw-show-hide-confirm-teacher">
                                        <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                        <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                                    </span>
                                </div>
                                <small class="text-danger error-message mt-1" id="error-password_confirmation_teacher"></small>
                                <small class="text-danger error-message mt-4" id="hidden-msg-password_confirm_teacher">{{ $errors->register_teacher->first('password_confirmation') }}</small>
                            </div>
                            <button type="submit" class="button-type-02 transition-all fw-medium text-uppercase">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('home.common.footer')
    <div class="scroll-progress d-none">
        <a href="" class="scroll-progress__link">
            <span class="iconify fs-18" data-icon="grommet-icons:up"></span>
            <span class="scroll-progress__line scroll-progress__main">
                <span id="scr-progress" class=""></span>
            </span>
        </a>
    </div>

    <div class="search-wrapper">
        <div class="search-overlay"></div>
        <div class="search-popup">
            <form action="#" method="GET">
                <input type="text" id="search-input" name="search-query" placeholder="Search courses...">
                <button type="submit">
                    <i class="iconify fs-22 text-white eye-on search-popup_icon" data-icon="iconamoon:search"></i>
                </button>
            </form>
        </div>
    </div>
    <script>
    // Get all select elements for majors
    const selects = document.querySelectorAll('select[name="majors[]"]');

    // Function to update visible options dynamically
    function updateSelectOptions() {
        // Get all selected (non-empty) values
        const selectedValues = Array.from(selects)
            .map(s => s.value)
            .filter(v => v !== "");

        // For each select, reset (show all options first)
        selects.forEach(select => {
            Array.from(select.options).forEach(option => {
                option.hidden = false; // Show all back
            });
        });

        // Hide already selected options in other selects
        selects.forEach(select => {
            Array.from(select.options).forEach(option => {
                if (
                    selectedValues.includes(option.value) &&
                    option.value !== select.value &&
                    option.value !== ""
                ) {
                    option.hidden = true; // Hide duplicate
                }
            });
        });
    }

    // Listen to change events
    selects.forEach(select => {
        select.addEventListener('change', updateSelectOptions);
    });

    // Initialize on load
    updateSelectOptions();
    </script>

    @include('home.auth.login')
    @include('home.auth.register')
    @include('home.common.script')

</body>

</html>
