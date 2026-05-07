<script src="{{ asset('assets/js/common/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('assets/js/common/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/common/chart.js') }}"></script>
{{--
<script src="{{asset('assets/admin/vendors/typeahead.js/typeahead.bundle.min.js')}}"></script> --}}
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('assets/js/common/off-canvas.js') }}"></script>
{{--
<script src="{{asset('assets/admin/js/misc.js')}}"></script> --}}
{{--
<script src="{{asset('assets/admin/js/settings.js')}}"></script> --}}
{{--
<script src="{{asset('assets/admin/js/todolist.js')}}"></script> --}}
<script src="{{ asset('assets/js/common/jquery.cookie.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page -->
<script src="{{ asset('assets/js/common/dashboard.js') }}"></script>
{{-- <script src="{{ asset('assets/js/common/file-upload.js') }}"></script> --}}
{{--
<script src="{{asset('assets/admin/js/typeahead.js')}}"></script> --}}
<script src="{{ asset('assets/js/common/select2.js') }}"></script>
<!-- End custom js for this page -->
<script src="{{ asset('assets/js/common/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/common/dataTables.min.js') }}"></script>

{{-- Popup when delete --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger me-4"
            },
            buttonsStyling: false
        });

        document.querySelectorAll('.btn-delete').forEach(function(button) {
            button.addEventListener('click', function() {
                swalWithBootstrapButtons.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    confirmButtonColor: "#4cd5c2",
                    cancelButtonColor: "#fe91ab",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.disabled = true,
                        Swal.fire({
                            title: 'Deleting...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        button.closest('form').submit();

                    }
                });
            });
        });
    });
</script>

{{-- <script>
    $(document).ready(function() {
        var table;

        if (
            $('#myTable').length &&
            $('#myTable tbody tr').length > 0 &&
            $('#myTable tbody tr:first td').length === $('#myTable thead tr th').length
        ) {
            table = $('#myTable').DataTable({
                lengthChange: false,
                info: false,
                dom: 'rt',
                language: {
                    search: ""
                },
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: [-1, 0]
                }]
            });
        }
    });
</script> --}}
{{-- popup when logout --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.querySelector('.btn-logout');
        const logoutForm = document.getElementById('logout_form');

        if (logoutBtn && logoutForm) {
            logoutBtn.addEventListener('click', function(e) {
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

{{-- Loading popup when submit form --}}
<script>
   document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.form-confirm-submit');
    const chooseBtn = document.getElementById('chooseFileBtn'); 
    if (!chooseBtn) {
        window.uploadCompleted = true;
    }
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!window.uploadCompleted) {
                e.preventDefault();
                if (window.errorImage) window.errorImage.innerText = 'Please wait until the image finishes uploading.';
                return;
                }

                e.preventDefault();

            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            setTimeout(() => {
                console.log('About to submit form'); 
                form.submit();
            }, 300);
        });
    });
});
</script>

@if (session('success'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
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
@if (session('error'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
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

@if (session('no_access'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "error",
            title: "{{ session('no_access') }}"
        });
    </script>
@endif

{{-- Disable button when approve or reject teacher application --}}
<script>
function disableButtons() {
    document.querySelectorAll('.btn-action').forEach(btn => {
        if (btn.tagName.toLowerCase() === 'a') {
            btn.classList.add('disabled');
        } else {
            btn.disabled = true;
        }
    });
}

document.querySelectorAll('.approveForm').forEach(form => {
    form.addEventListener('submit', function() {
        disableButtons();
    });
});
</script>

{{-- Function to reject a teacher application --}}
<script>
function rejectTeacher(userId) {
    Swal.fire({
        title: 'Enter rejection reason',
        input: 'text',
        inputPlaceholder: 'Type the reason for rejection here...',
        inputAttributes: {
            maxlength: 500
        },
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('You must enter a rejection reason');
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            disableButtons();
            const rejectForm = document.querySelector(`#rejectForm-${userId}`);
            const rejectReason = rejectForm.querySelector('.rejectReason');
            rejectReason.value = result.value;
            rejectForm.submit();
        }
    });
}

</script>

{{-- Disable submit button when the form loading --}}
<script>
const form = document.getElementById('myForm');
const submitBtn = document.getElementById('submitBtn');

if (form) {
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
    });
}
</script>
