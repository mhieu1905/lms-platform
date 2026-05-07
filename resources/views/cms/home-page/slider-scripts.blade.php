<script>
        let editorInstance;

        document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form.forms-sample');
        const submitBtn = document.getElementById('submitBtn');
        const imageInput = document.getElementById('image');
        const errorImage = document.getElementById('error-image');
        const linkRegex = /^(https?:\/\/\S+|(\/\S+)|(\.{1,2})\/\S+)$/;
        const hiddenPath = document.getElementById('imagePath');
        window.uploadCompleted = true;
        @php
            $hasOldImage = isset($slider) && $slider->image ? 'true' : 'false';
        @endphp
        const hasOldImage = @json($hasOldImage === 'true');
        const MAX_FILE_SIZE = 2 * 1024 * 1024;

            const requiredField = [
                {id: 'title', name: 'Slider Title'},
                {id: 'subtitle', name: 'Subtitle'},
                {id: 'image', name: 'Slider Image'},
                {id: 'button_text', name: 'Button Text'},
                {id: 'button_link', name: 'Button Link'},
            ];

            function isValid() {
                const basicFieldsFilled = requiredField.every(field => {
                    const el = document.getElementById(field.id);
                    const value = el ? el.value.trim() : '';

                    if (field.id === 'image') {
                        return (hasOldImage || (hiddenPath && hiddenPath.value.trim() !== '') || (el.files && el.files.length > 0))
                            && window.uploadCompleted;
                    }

                    if (field.id === 'button_link') {
                      return linkRegex.test(value);
                    }

                    return value !== '';
                });

                if (imageInput.files.length > 0) {
                    const file = imageInput.files[0];
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type) || file.size > MAX_FILE_SIZE) {
                        return false;
                    }
                }

                // Check text field valid
                const textFieldValid = checkLengthValid();
                let salePriceValid = validateSalePrice();
                let dateEndValid = validateDateEnd();

                return basicFieldsFilled && textFieldValid && salePriceValid && dateEndValid;
            }

            // Validate length of text field
            function checkLengthValid() {
                let valid = true;
                [
                    {id: 'title', name: 'Title', maxLength: 255},
                    {id: 'subtitle', name: 'Subtitle', maxLength: 255},
                    {id: 'button_text', name: 'Button text', maxLength: 255},
                    {id: 'button_link', name: 'Button link', maxLength: 255}
                ].forEach(field => {
                    const input = document.getElementById(field.id);
                    if (input) {
                        const value = parseFloat(input.value);

                        if (value.length > field.maxLength) {
                            return false;
                        }
                    }
                });

                return valid;
            }

            function updateSubmitState() {
                if (submitBtn) {
                    submitBtn.disabled = !isValid();
                }
            }

            requiredField.forEach(field => {
                const input = document.getElementById(field.id);
                if (!input) return;

                const errorContainer = document.getElementById(`error-${field.id}`);
                const hiddenMessage = document.getElementById(`hidden-msg-${field.id}`);
                input.addEventListener('input', () => {
                    let showError = false;
                    if (hiddenMessage) {
                        hiddenMessage.style.display = 'none';
                    }

                    if (errorContainer && field.id === 'title') {
                        const value = input.value.trim();
                        if (value === '') {
                            errorContainer.innerText = 'Title is required.';
                        } else if (value.length > 255) {
                            errorContainer.innerText = 'Title must be less than 255 characters.';
                        } else {
                            errorContainer.innerText = '';
                        }
                    }

                    if (errorContainer && field.id === 'subtitle') {
                        const value = input.value.trim();
                        if (value === '') {
                            errorContainer.innerText = 'Subtitle is required.';
                        } else if (value.length > 255) {
                            errorContainer.innerText = 'Subtitle must be less than 255 characters.';
                        } else {
                            errorContainer.innerText = '';
                        }
                    }
                    
                    if (errorContainer && field.id === 'button_text') {
                        const value = input.value.trim();
                        if (value === '') {
                            errorContainer.innerText = 'Button text is required.';
                        } else if (value.length > 255) {
                            errorContainer.innerText = 'Button text must be less than 255 characters.';
                        } else {
                            errorContainer.innerText = '';
                        }
                    }
                    
                    if (errorContainer && field.id === 'button_link') {
                        const value = input.value.trim();
                        showError = !linkRegex.test(input.value.trim());
                        if (value === '') {
                            errorContainer.innerText = 'Button link is required.';
                        } else if (value.length > 255) {
                            errorContainer.innerText = 'Button link must be less than 255 characters.';
                        } else if (showError) {
                            errorContainer.innerText = 'Button link is invalid format.';
                        } else {
                            errorContainer.innerText = '';
                        }
                    }

                    if (field.id === 'image') {
                        showError = !hasOldImage && input.files.length === 0;
                        if (errorContainer) {
                          errorContainer.innerText = showError ? `${field.name} is required.` : '';
                        }
                    }
                    
                    updateSubmitState();
                });
            });

            function validateDateEnd() {
                const dateEndInput = document.getElementById('date_end');
                const errorEl = document.getElementById('error-date_end');
                const hiddenMsg = document.getElementById('hidden-msg-date_end');
                
                if (!dateEndInput) return true;
                
                const selectedDate = new Date(dateEndInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Reset time to start of day
                
                const isInvalid = dateEndInput.value && selectedDate <= today;
                
                if (hiddenMsg && isInvalid) {
                    hiddenMsg.style.display = 'none';
                }
                
                if (errorEl) {
                    errorEl.innerText = isInvalid ? 'Date End must be greater than today.' : '';
                }
                
                return !isInvalid;
            }

             function validateSalePrice() {
                const regular = parseFloat(document.getElementById('regular_price')?.value);
                const sale = parseFloat(document.getElementById('sale_price')?.value);
                const errorEl = document.getElementById('error-sale_price');
                const hiddenMsg = document.getElementById('hidden-msg-sale_price');

                const isInvalid = !isNaN(regular) && !isNaN(sale) && sale >= regular;

                if (hiddenMsg && isInvalid) {
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

            if (imageInput) {
                imageInput.addEventListener('input', function () {
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
                        errorImage.innerText = hasOldImage ? '' : 'Image is required.';
                        newImagePreview.style.display = 'none';
                        if (oldImageWrapper) {
                            oldImageWrapper.style.display = hasOldImage ? 'block' :'none';
                        }
                    }
                    updateSubmitState();
                })
            }
            if (form) {
                // attach input event for all fields
                form.addEventListener('input', updateSubmitState);
            }

            document.addEventListener('upload-status-changed', updateSubmitState);
            updateSubmitState();
        });

    </script>

    <script>
        $(document).ready(function() {
            $('.toggle-status').click(function() {
                var button = $(this);
                var sliderId = button.data('id');

                $.ajax({
                    url: '/cms/home-page/slider/' + sliderId + '/toggle-status',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }, success: function(res) {
                        button.text(res.status ? 'Active' : 'Inactive');
                        button.data('status', res.status);

                        if (res.status) {
                            button.removeClass('btn-gradient-dark').addClass('btn-gradient-success');
                        } else {
                            button.removeClass('btn-gradient-success').addClass('btn-gradient-dark');
                        }
                    }, error: function() {
                        alert('An error occurred while changing status.');
                    }
                });
            });
        });

    </script>