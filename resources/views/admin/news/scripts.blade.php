<script>
    let editorInstance;

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form.forms-sample');
        const submitBtn = document.getElementById('submitBtn');
        const imageInput = document.getElementById('image');
        const errorImage = document.getElementById('error-image');
        const oldImageWrapper = document.getElementById('old-image-wrapper');
        const newImagePreview = document.getElementById('new-image-preview');
        const previewImage = document.getElementById('preview-image');
        @php
            $hasOldImage = isset($newsEdit) && $newsEdit->image ? 'true' : 'false';
        @endphp
        const hasOldImage = {{ $hasOldImage }};

        const requiredField = [{
                id: 'title',
                name: 'Course Title'
            },
            {
                id: 'date',
                name: 'Date'
            },
            {
                id: 'image',
                name: 'Image'
            },
            {
                id: 'category_id',
                name: 'Category'
            },


        ];

        function isValid() {
            const description = editorInstance?.getData().trim() || '';

            const basicFieldsFilled = requiredField.every(field => {
                const el = document.getElementById(field.id);
                const value = el ? el.value.trim() : '';

                if (field.id === 'image') {
                    return hasOldImage || el.files.length > 0;
                }

                return value !== '';
            });

            if (imageInput.files.length > 0) {
                const file = imageInput.files[0];
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    return false;
                }
            }

            return basicFieldsFilled && description !== '';
        }

        function updateSubmitState() {
            submitBtn.disabled = !isValid();
        }

        requiredField.forEach(field => {
            const input = document.getElementById(field.id);
            if (!input) return;

            const errorContainer = document.getElementById(`error-${field.id}`);
            input.addEventListener('change', () => {
                let showError = false;

                const hiddenMsg = document.getElementById(`hidden-msg-${field.id}`);
                if (hiddenMsg) {
                    hiddenMsg.style.display = 'none';
                }

                if (field.id === 'image') {
                    showError = !hasOldImage && input.files.length === 0;
                } else {
                    const value = input.value.trim();
                    showError = !value;
                }

                if (errorContainer) {
                    errorContainer.innerText = showError ? `${field.name} is required.` : '';
                }
                updateSubmitState();
            });
        });


        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];

                if (file) {
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif',
                        'image/webp'
                    ];
                    if (!validTypes.includes(file.type)) {
                        errorImage.innerText =
                            'Only image files (jpg, jpeg, png, gif, webp) are allowed.';
                        this.value = '';

                        newImagePreview.style.display = 'none';

                        if (oldImageWrapper) {
                            oldImageWrapper.style.display = hasOldImage ? 'block' : 'none';
                        }

                    } else {
                        errorImage.innerText = '';
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            newImagePreview.style.display = 'block';
                            if (oldImageWrapper) {
                                oldImageWrapper.style.display = 'none';
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                } else {
                    errorImage.innerText = hasOldImage ? '' : 'Image is required.';
                    newImagePreview.style.display = 'none';

                    if (oldImageWrapper) {
                        oldImageWrapper.style.display = hasOldImage ? 'block' : 'none';

                        if (hasOldImage) {
                            const oldImg = oldImageWrapper.querySelector('img');
                            if (oldImg) {
                                previewImage.src = oldImg.src;
                            }
                        }
                    }
                }
                updateSubmitState();
            })
        }

        // attach input event for all fields
        if (form) {
            form.addEventListener('input', updateSubmitState);
            form.addEventListener('change', updateSubmitState);
        }

        // initialize CKEditor
        const editorElement = document.querySelector('#editor');
        if (editorElement) {
            ClassicEditor
                .create(editorElement)
                .then(editor => {
                    editorInstance = editor;

                    editor.model.document.on('change:data', updateSubmitState);

                    const errorContainer = document.getElementById('error-editor');
                    editor.editing.view.document.on('change', () => {
                        const hiddenMsg = document.getElementById('hidden-msg-description');
                        if (hiddenMsg) {
                            hiddenMsg.style.display = 'none';
                        }
                        const value = editor.getData().trim();
                        if (!value && errorContainer) {
                            errorContainer.innerText = 'Description is required.';
                        } else if (errorContainer) {
                            errorContainer.innerText = '';
                        }
                        updateSubmitState();
                    });


                    updateSubmitState();
                })
                .catch(error => {
                    console.error('Error CKEditor:', error);
                });
        }

    });
</script>
<script>
    $(document).ready(function() {
        $('.toggle-status').click(function() {
            var button = $(this);
            var newsId = button.data('id');
            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            $.ajax({
                url: '/admin/news/' + newsId + '/toggle-status',
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    button.text(res.status ? 'Public' : 'Hidden');
                    button.data('status', res.status);

                    if (res.status) {
                        button.removeClass('btn-gradient-dark').addClass(
                            'btn-gradient-success');
                    } else {
                        button.removeClass('btn-gradient-success').addClass(
                            'btn-gradient-dark');
                    }
                    Swal.close();
                },
                error: function() {
                    alert('An error occurred while changing status.');
                }
            });
        });
    });
</script>
