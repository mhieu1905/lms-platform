<!DOCTYPE html>
<html lang="en">
@include('admin.common.header')
<body>
    <script src="{{ asset('assets/js/common/ckeditor.js') }}"></script>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        @include('admin.common.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            @include('admin.common.sidebar')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Event List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Event</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.events.update', $event->id) }}" class="forms-sample form-confirm-submit" id="myForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="title">Event Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter event title here" value="{{ old('title', $event->title) }}">
                                            <small class="text-danger error-message mb-3" id="error-title"></small>
                                            @error('title')
                                            <small class="text-danger" id="hidden-msg-title">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Event Image (<2MB)<span class="text-danger">*</span></label>
                                            {{-- Old image --}}
                                            @if($event->image && !old('image_path'))
                                            <div class="mb-2" id="old-image-wrapper">
                                                <img src="{{ asset('storage/' . $event->image) }}" alt="Current Event Image" id="old-image">
                                            </div>
                                            @endif

                                            {{-- Preview image --}}
                                            <div class="mb-2" id="new-image-preview" style="{{ old('image_path') ? 'display:block;' : 'display:none;' }}">
                                                <img src="{{ old('image_path') ? asset(old('image_path')) : '' }}" alt="New Image Preview" id="preview-image">
                                            </div>

                                            <div class="custom-file-container">
                                                <button type="button" id="chooseFileBtn" class="btn btn-gradient-primary">Choose File</button>
                                                <span id="fileNameDisplay" class="file-name">{{ old('image_path') ? basename(old('image_path')) : 'No file chosen' }}</span>
                                                <input type="hidden" name="image_path" id="imagePath" value="{{ old('image_path') }}">
                                                <input type="file" id="image" style="display:none;" accept=".jpeg,.jpg,.png,.gif,.webp" data-type="events" data-preview="#preview-image">
                                            </div>
                                            <small class="text-danger error-message mb-3" id="error-image"></small>
                                            @error('image')
                                            <small class="text-danger" id="hidden-msg-image">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="start_time">Start Time<span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $event->start_time) }}">
                                                    <small class="text-danger error-message mb-3" id="error-start_time"></small>
                                                    @error('start_time')
                                                    <small class="text-danger" id="hidden-msg-start_time">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="finish_time">Finish Time<span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="finish_time" id="finish_time" class="form-control" value="{{ old('finish_time', $event->finish_time) }}">
                                                    <small class="text-danger error-message mb-3" id="error-finish_time"></small>
                                                    @error('finish_time')
                                                    <small class="text-danger" id="hidden-msg-finish_time">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="address">Address<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter event address here" value="{{ old('address', $event->address) }}">
                                                    <small class="text-danger error-message mb-3" id="error-address"></small>
                                                    @error('address')
                                                    <small class="text-danger" id="hidden-msg-address">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Description<span class="text-danger">*</span></label>
                                            <textarea id="editor" name="description">{{ old('description', $event->description) }}</textarea>
                                            <small class="text-danger error-message mb-3" id="error-editor"></small>
                                            @error('description')
                                            <small class="text-danger" id="hidden-msg-description">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Content<span class="text-danger">*</span></label>
                                            <div id="content-wrapper">
                                                @php
                                                $array = safe_json_decode($event->content);
                                                @endphp
                                                <div class="content-item-wrapper mb-2" id="wrapper-0">
                                                    <div class="content-item mb-2">
                                                        <input type="text" class="form-control content-line" id="content_item" placeholder="Enter event content here" value="{{ $array[0] }}">
                                                    </div>
                                                    <small class="text-danger error-message mb-3" id="error-content_item"></small>
                                                </div>
                                                @for($i = 1; $i < count($array); $i++)
                                                <div class="content-item-wrapper mb-2" id="wrapper-content_item_{{ $i }}">
                                                    <div class="content-item d-flex align-items-center gap-2">
                                                        <input type="text" class="form-control content-line" id="content_item_{{ $i }}" placeholder="Enter event content here" value="{{ $array[$i] }}">
                                                        <button type="button" class="btn btn-sm btn-danger btn-remove_line"><i class="fa fa-trash-o"></i></button>
                                                    </div>
                                                    <small class="text-danger error-message" id="error-{{ $i }}"></small>
                                                </div>
                                                @endfor
                                        </div>
                                        @error('content_json')
                                        <small class="text-danger" id="hidden-msg-content_json">{{ $message }}</small><br>
                                        @enderror
                                        <button type="button" id="add-content" class="btn btn-sm btn-primary">+ Add line</button>
                                        <input type="hidden" name="content_json" id="content-json">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="cost">Cost ($)<span class="text-danger">*</span></label>
                                            <input type="number" step="any" class="form-control" id="cost" name="cost" min="0" max="999999.99" placeholder="Enter cost of event ticket here ($)" value="{{ old('cost', $event->cost) }}">
                                            <small class="text-danger error-message mb-3" id="error-cost"></small>
                                            @error('cost')
                                            <small class="text-danger" id="hidden-msg-cost">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="total_slots">Total Slots<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="total_slots" name="total_slots" min="0" max="1000" placeholder="Enter total slots of event here" value="{{ old('total_slots', $event->total_slots) }}">
                                            <small class="text-danger error-message mb-3" id="error-total_slots"></small>
                                            @error('total_slots')
                                            <small class="text-danger" id="hidden-msg-total_slots">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <p>Status<span class="text-danger">*</span></p>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label><input type="radio" name="status" value="1" {{ $event->status ? 'checked' : '' }}>Active</label>
                                        </div>
                                        <div class="col-lg-2">
                                            <label><input type="radio" name="status" value="0" {{ $event->status ? '' : 'checked' }}>Inactive</label>
                                        </div>
                                    </div>
                                    <small class="text-danger error-message mb-3" id="error-status"></small>
                                </div>
                                <button type="submit" class="btn btn-gradient-primary me-2" id="submitBtn">Submit</button>
                                <a href="{{ route('admin.events.index')}}" class="btn btn-light">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:../../partials/_footer.html -->
            @include('admin.common.footer')
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    @include('admin.common.scripts')
    @include('admin.events.scripts')
    <script src="{{ asset('assets/js/common/upload-temp-image.js') }}"></script>
</body>
</html>
