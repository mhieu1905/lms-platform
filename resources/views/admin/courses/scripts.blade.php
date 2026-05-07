<script>
        let editorInstance;

        document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form.forms-sample');
        const submitBtn = document.getElementById('submitBtn');
        const imageInput = document.getElementById('image');
        const errorImage = document.getElementById('error-image');
        const hiddenPath = document.getElementById('imagePath');
        window.uploadCompleted = true;

        const intPartern = /^\d+$/;
        @php
            $hasOldImage = isset($course) && $course->image ? 'true' : 'false';
        @endphp
        const hasOldImage = @json($hasOldImage === 'true');
        const MAX_FILE_SIZE = 2 * 1024 * 1024;

            const requiredField = [
                {id: 'title', name: 'Course Title'},
                {id: 'regular_price', name: 'Regular Price'},
                {id: 'duration', name: 'Duration'},
                {id: 'image', name: 'Image'},
                {id: 'category_id', name: 'Category'},
                {id: 'level_id', name: 'Level'},
                {id: 'language', name: 'Language'}
            ];

            function isValid() {
                const description = editorInstance ?.getData().trim() || '';

                const basicFieldsFilled = requiredField.every(field => {
                    const el = document.getElementById(field.id);
                    const value = el ? el.value.trim() : '';

                    if (field.id === 'image') {
                        return (hasOldImage || (hiddenPath && hiddenPath.value.trim() !== '') || (el.files && el.files.length > 0))
                                && window.uploadCompleted;
                    }

                    if (['regular_price', 'duration'].includes(field.id)) {
                        return value !== '' && !isNaN(value);
                    }

                    return value !== '';
                });

                let priceValid = validateSalePrice();

                if (imageInput && imageInput.files.length > 0) {
                    const file = imageInput.files[0];
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type) || file.size > MAX_FILE_SIZE) {
                        return false;
                    }
                }

                // Validate min-max of number field
                let numberValid = true;
                [
                    {id: 'regular_price', name: 'Regular Price', min: 1, max: 1000000},
                    {id: 'sale_price', name: 'Sale Price', min: 0, max: 1000000},
                    {id: 'duration', name: 'Duration', min: 1, max: 120}
                ].forEach(field => {
                    const input = document.getElementById(field.id);
                    if (input) {
                        const value = parseFloat(input.value);

                        if (value < field.min) {
                            numberValid = false;
                        } else if (value > field.max) {
                            numberValid = false;
                        }
                    }
                });

                let integerValid = true;
                const input = document.getElementById('duration');
                if (input) {
                    const value = input.value.trim();

                    if (!intPartern.test(value)) {
                        return false;
                    }
                }
                
                // Validate number of character of title
                let titleValid = true;
                const titleInput = document.getElementById('title');
                    if (titleInput) {
                        const value = titleInput.value.trim();
                        if (!value) {
                            titleValid = false;
                        } else if (value.length > 70) {
                            titleValid = false;
                        }
                    }

                return basicFieldsFilled && priceValid && numberValid && titleValid && integerValid && description !== '';
            }
            function validateSalePrice() {
                const regular = parseFloat(document.getElementById('regular_price')?.value);
                const sale = parseFloat(document.getElementById('sale_price')?.value);
                const errorEl = document.getElementById('error-sale_price');
                const hiddenMsg = document.getElementById('hidden-msg-sale_price');

                const isInvalid = !isNaN(regular) && !isNaN(sale) && sale >= regular;

                if (hiddenMsg) {
                    hiddenMsg.style.display = 'none';
                }
                if (errorEl) {
                    errorEl.innerText = isInvalid ? 'The sale price must be less than the regular price.' : '';
                }
                return !isInvalid;
            }

            ['regular_price', 'sale_price'].forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', () => {
                        validateSalePrice();
                        updateSubmitState();
                    });
                }
            });

            function updateSubmitState() {
                if (submitBtn) {
                    submitBtn.disabled = !isValid();
                }
            }

            requiredField.forEach(field => {
                const input = document.getElementById(field.id);
                if (!input) return;

                const errorContainer = document.getElementById(`error-${field.id}`);
                input.addEventListener('input', () => {
                    let showError = false;
                    
                    const hiddenMsg = document.getElementById(`hidden-msg-${field.id}`);
                    if (hiddenMsg) {
                        hiddenMsg.style.display = 'none';
                    }

                    if (field.id === 'image') {
                        showError = !hasOldImage && input.files.length === 0 && hiddenPath.value.trim() === '';
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

            // Validate min-max for number field
            [
                {id: 'regular_price', name: 'Regular Price', min: 1, max: 1000000},
                {id: 'sale_price', name: 'Sale Price', min: 0, max: 1000000},
                {id: 'duration', name: 'Duration', min: 1, max: 121}
            ].forEach(field => {
                const input = document.getElementById(field.id);
                
                if (input) {
                    input.addEventListener('input', function () {
                        const value = parseFloat(this.value);
                        const errorContainer = document.getElementById(`error-${field.id}`);
                        const hiddenMsg = document.getElementById(`hidden-msg-${field.id}`);
                        if (hiddenMsg) {
                            hiddenMsg.style.display = 'none';
                        }

                        if (value < field.min) {
                            errorContainer.innerText = `${field.name} must be at least ${field.min}.`;
                        } else if (['regular_price', 'sale_price'].includes(field.id) && value >= field.max) {
                            errorContainer.innerText = `${field.name} must be less than ${field.max}.`;
                        } else if (field.id === 'duration' && value >= field.max) {
                            errorContainer.innerText = `${field.name} must be less than ${field.max}.`;
                        }

                        updateSubmitState();
                    });
                }
            });

            // Validate integer for duration field
            input = document.getElementById('duration');
            if (input) {
                input.addEventListener('input', function () {
                    const value = input.value.trim();
                    const errorContainer = document.getElementById('error-duration');
                    const hiddenMsg = document.getElementById('hidden-msg-duration');
                    if (hiddenMsg) {
                        hiddenMsg.style.display = 'none';
                    }

                    if (value === '') {
                        errorContainer.innerText = 'Duration is required.';
                    } else if (!intPartern.test(value)) {
                        errorContainer.innerText = 'Duration must be an integer.';
                    }

                    updateSubmitState();
                });
            }

            const titleInput = document.getElementById('title');
            if (titleInput) {
                titleInput.addEventListener('input', function () {
                    const errorContainer = document.getElementById('error-title');
                    const value = this.value.trim();
                    if (!value) {
                        errorContainer.innerText = 'Course Title is required.';
                    } else if (value.length > 70) {
                    errorContainer.innerText = `The title may not be greater than 70 characters.`;
                    } else {
                    errorContainer.innerText = '';
                    }
                    updateSubmitState();
                });
            }

            const decimalLimitedFields = ['regular_price', 'sale_price'];

            decimalLimitedFields.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', function () {
                        let value = this.value;

                        if (value.includes('.')) {
                            const [intPart, decimalPart] = value.split('.');
                            if (decimalPart.length > 2) {
                                this.value = `${intPart}.${decimalPart.slice(0, 2)}`;
                            }
                        }
                    })
                }
            })

            if (imageInput) {
                imageInput.addEventListener('change', function () {
                    const file = this.files[0];

                    if (file) {
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            errorImage.innerText = 'Only image files (jpg, jpeg, png, gif, webp) are allowed.';
                            this.value = '';
                        } else if (file.size > MAX_FILE_SIZE) {
                            errorImage.innerText = 'Image file must be smaller than 2MB.';
                            this.value = '';
                        } else {
                            errorImage.innerText = '';
                        }
                    } else {
                        errorImage.innerText = (hasOldImage || hiddenPath.value.trim() !== '') ? '' : 'Image is required.';
                    }

                updateSubmitState();
                })
            }

            // attach input event for all fields
            if (form) {
                form.addEventListener('input', updateSubmitState);
            }

            // initialize CKEditor
            const editorElement = document.querySelector('#editor');
            if (editorElement) {
                ClassicEditor
                    .create(editorElement)
                    .then(editor => {
                        editorInstance = editor;

                        const errorContainer = document.getElementById('error-editor');

                        editor.model.document.on('change:data', () => {
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
        document.addEventListener('upload-status-changed', updateSubmitState);
        updateSubmitState();
        
        });

    </script>

{{-- Handle course status toggle --}}
<script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        var button = $(this);
        var courseId = button.data('id');

        $.ajax({
            url: '/admin/courses/' + courseId + '/toggle-status',
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                button.data('status', res.status);

                var actionMap = {
                    0: {class: 'btn-gradient-success', icon: 'fa-check'},   // pending -> approve
                    1: {class: 'btn-gradient-secondary', icon: 'fa-eye-slash'}, // publishing -> hide
                    2: {class: 'btn-gradient-success', icon: 'fa-eye'}       // hidden -> publish
                };

                var current = actionMap[res.status];

                button.removeClass('btn-gradient-success btn-gradient-secondary');
                button.addClass(current.class);
                button.html('<i class="fa ' + current.icon + '"></i> ');

                var statusMap = {
                    0: {class: 'badge-info', text: 'Pending'},
                    1: {class: 'badge-success', text: 'Publishing'},
                    2: {class: 'badge-secondary', text: 'Hidden'}
                };

                var currentLabel = statusMap[res.status];

                button.closest('tr').find('.badge_status')
                    .removeClass('badge-info badge-success badge-secondary')
                    .addClass(currentLabel.class)
                    .text(currentLabel.text);
            },
            error: function() {
                alert('An error occurred while changing status.');
            }
        });
    });
});
</script>

<script>
document.querySelectorAll('input[type=number]').forEach(input => {
  input.addEventListener('keydown', function(e) {
    if (["e", "E", "+", "-"].includes(e.key)) {
      e.preventDefault();
    }
    if (e.key === "." && this.value === "") {
      e.preventDefault();
      this.value = "0.";
    }
  });

  input.addEventListener('paste', function(e) {
    const pasted = (e.clipboardData || window.clipboardData).getData('text');
    if (/[eE+\-]/.test(pasted)) {
      e.preventDefault();
    }
  });

  input.addEventListener('input', function() {
    let value = this.value;

    // // Add 0 before dot
    if (value.startsWith('.')) {
      value = '0' + value;
      this.value = value;
    }
  });
});
</script>

