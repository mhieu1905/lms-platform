<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')
<link rel="stylesheet" href="{{ asset('assets/css/common/cmsfooter.css') }}">

<body>
    <div class="container-scroller">
        @include('admin.common.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('admin.common.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ asset('cms/footers') }}">Footer</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Footer Social Create</li>
                            </ol>
                        </nav>
                    </div>
                    @if (session('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: "error",
                                    title: "Action Failed!",
                                    text: "{{ session('error') }}",
                                    timer: 5000,
                                    showConfirmButton: false
                                });
                            });
                        </script>
                    @endif
                    <form id="footerForm" class="footerForm" method="POST"
                        action="{{ route('cms.footers.social.store') }}" data-section="social">
                        <input type="hidden" name="contentInput" class="contentInput" value="">
                        <input type="hidden" name="key_id" value="4">
                        @csrf
                        <div class="card" id="cardWrapper">
                            <div class="card-body">
                                <div id="dynamicFields" class="mt-3">
                                    <div class="row mb-2 dynamic-row" data-index="${i}">
                                        <label for="exampleInputName1">Item 1<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input type="text" class="form-control dynamic-field"
                                                name="items[1][label]"
                                                placeholder="Enter Iconify icon name (e.g., mdi:home)"
                                                data-field="label" data-index="1">
                                            <div class="error-message" id="error-label-1"></div>
                                        </div>
                                        <div class="col-5">
                                            <input type="url" class="form-control dynamic-field url-field"
                                                name="items[1][link]" placeholder="Item Link" data-field="link"
                                                data-index="1">
                                            <div class="error-message" id="error-link-1"></div>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-danger btn-remove w-100">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="dynamicButtons" class="mt-3">
                                    <div class="row">
                                        <div class="col-10"></div>
                                        <div class="col-2 d-flex justify-content-end">
                                            <button type="button" class="btn btn-success btnAddRow w-100"
                                                id="btnAddRow">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-gradient-primary mt-4" id="submitBtn"
                                        disabled>
                                        Submit
                                    </button>
                                    <a href="{{ route('cms.footers.index') }}"
                                        class="btn btn-light mt-4 ms-2">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @include('admin.common.footer')
            </div>
        </div>
    </div>
    @include('admin.common.scripts')
    @include('cms.footers.scripts')
</body>
<script>
    window.isEdit = false;
</script>

</html>
