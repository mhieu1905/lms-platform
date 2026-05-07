<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')
<script type="text/javascript" src="{{ asset('assets/js/common/iconify.min.js') }}" defer></script>
<link rel="stylesheet" href="{{ asset('assets/css/common/cmsfooter.css') }}">

<body>
    <div class="container-scroller">
        @include('admin.common.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('admin.common.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ asset('cms/footers') }}">Footer</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Footer Edit</li>
                        </ol>
                    </nav>
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('cms.footers.update', $footers->id) }}" method="POST"
                                        data-section="{{ $footers->key->name }}" enctype="multipart/form-data" class="forms-sample footerForm">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="key_id" value="{{ $footers->key_id }}">
                                        @php
                                            $placeholderLabel = "Item Name";
                                            $placeholderLink = "Item Link";
                                            $typeDynamic = "url";
                                            $classDynamic = "url-field";
                                            $nameDynamic = "link";
                                        @endphp
                                        @if ($key == 1)
                                            <div class="row mb-3">
                                                <h2 class="text-muted">Main Edit</h2>
                                                <hr>
                                                <label>Title<span class="text-danger">*</span></label>
                                                <div class="col-12 mb-3">
                                                    <input type="text" class="form-control dynamic-field"
                                                        value={{ $footers->content->title }} name="title"
                                                        placeholder="Title" data-field="main" data-index="0">
                                                    <div class="error-message" id="error-main-0"></div>
                                                </div>
                                            </div>
                                            
                                        @elseif ($key === 2)
                                        @php
                                        $placeholderLabel = "Enter Iconify icon name (e.g., mdi:home)";
                                        $placeholderLink = "Enter text to display";
                                        $typeDynamic = "text";
                                        $classDynamic = "";
                                        $nameDynamic = "text";
                                        @endphp
                                            <div class="row mb-3">
                                                <h2 class="text-muted">Logo Edit</h2>
                                                <hr>
                                                <label>Upload Logo<span class="text-danger">*</span></label>
                                                <div class="col-12">
                                                    @if ($footers->content->logo)
                                                        <div class="mb-2" id="old-image-wrapper">
                                                            <img src="{{ asset('assets/images/home/logo/' . $footers->content->logo) }}"
                                                                alt="Current Course Image" id="old-image"
                                                                style="max-width: 200px; border-radius: 4px;">
                                                        </div>
                                                    @endif
                                                    {{-- Preview image --}}
                                                    <div class="mb-2" id="logo-preview-wrapper"
                                                        style="display: none;">
                                                        <img src="" alt="New Logo Preview" id="logo-preview"
                                                            style="max-width: 200px; border-radius: 4px;">
                                                    </div>
                                                    <input type="file" class="form-control dynamic-field logo-input"
                                                        value="{{ $footers->content->logo }}" name="logo"
                                                        id="image" data-field="logo" data-index="0"
                                                        accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
                                                    <div class="error-message" id="error-logo-0"></div>
                                                </div>

                                            </div>
                                        @elseif ($key === 3)
                                            <div class="row mb-3">
                                                <h2 class="text-muted">Copyright Edit</h2>
                                                <hr>
                                                <label>Copyright<span class="text-danger">*</span></label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control dynamic-field"
                                                        name="copyright" value="{{ $footers->content->copyright }}"
                                                        data-field="copyright" data-index="0" placeholder="Copyright">
                                                    <div class="error-message" id="error-copyright-0"></div>
                                                    @error('copyright')
                                                        <div class="text-danger" id="laravel_title_error_copyright">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif($key === 4)
                                        @php
                                        $placeholderLabel = "Enter Iconify icon name (e.g., mdi:home)";
                                        $placeholderLink = "Enter link";
                                        @endphp         
                                            <div class="row mb-3">
                                                <h2 class="text-muted">Social Edit</h2>
                                                <hr>
                                            </div>
                                        @endif
                                        @php
                                            $i = 0;
                                        @endphp
                                        <div class="dynamicFields" id="dynamicFields">
                                            @if (isset($footers->content->items))
                                                @foreach ($footers->content->items as $item)
                                                    @php
                                                        $i = $i + 1;
                                                    @endphp
                                                    <div class="row mb-2 dynamic-row" data-index="{{ $i }}">
                                                        <label for="exampleInputName1">Item
                                                            {{ $i }}<span
                                                                class="text-danger">*</span></label>
                                                        <div class="col-5 mb-2">
                                                            <input type="text" class="form-control dynamic-field"
                                                                name="items[{{ $i }}][label]"
                                                                placeholder="{{ $placeholderLabel }}" value="{{ $item->label }}"
                                                                data-field="label" data-index="{{ $i }}">
                                                            <div class="error-message"
                                                                id="error-label-{{ $i }}"></div>
                                                        </div>
                                                        <div class="col-5 mb-2">
                                                            <input type="{{ $typeDynamic }}"
                                                                class="form-control dynamic-field {{ $classDynamic }}"
                                                                name="items[{{ $i }}][{{ $nameDynamic }}]"
                                                                placeholder="{{ $placeholderLink }}" value="{{ $item->link ?? $item->text }}"
                                                                data-field="{{ $nameDynamic }}" data-index="{{ $i }}">
                                                            <div class="error-message"
                                                                id="error-link-{{ $i }}"></div>
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="button"
                                                                class="btn btn-danger btn-remove w-100">
                                                                <i class="fa fa-trash-o"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-10"></div>
                                            <div class="col-2 d-flex justify-content-end">
                                                <button type="button" class="btn btn-success btnAddRow w-100"
                                                    id="btnAddRow">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary mt-4" id="submitBtn">
                                            Submit
                                        </button>
                                        <a href="{{ route('cms.footers.index') }}"
                                            class="btn btn-light mt-4 ms-2">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.common.footer')
            </div>
        </div>
    </div>
    @include('admin.common.scripts')
    @include('cms.footers.scripts')
</body>
<script>
    window.isEdit = true;
</script>

</html>
