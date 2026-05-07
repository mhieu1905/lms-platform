<script>
    let editorInstance;

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form.forms-sample');
        const submitBtn = document.getElementById('submitBtn');
        const imageInput = document.getElementById('image');
        const errorImage = document.getElementById('error-image');
        const wrapper = document.getElementById('content-wrapper');
        const hiddenInput = document.getElementById('content-json');
        const addBtn = document.getElementById('add-content');
        let contentIndex = document.querySelectorAll('.content-line').length || 0;
        const hiddenPath = document.getElementById('imagePath');
        window.uploadCompleted = true;

        @php
        $hasOldImage = isset($event) && $event -> image ? 'true' : 'false';
        @endphp
        const hasOldImage = @json($hasOldImage === 'true');
        const MAX_FILE_SIZE = 2 * 1024 * 1024;
        const intPartern = /^\d+$/;

        const requiredField = [
            {id: 'title', name: 'Event Title'},
            {id: 'start_time', name: 'Start Time'},
            {id: 'finish_time', name: 'Finish Time'},
            {id: 'image', name: 'Event Image'},
            {id: 'address', name: 'Address'},
            {id: 'cost', name: 'Cost'},
            {id: 'content_item', name: 'Content'},
            {id: 'total_slots', name: 'Total Slots'}
        ];

        // Check Valid
        function isValid() {
            const description = editorInstance ?.getData().trim() || '';
            const basicFieldsFilled = requiredField.every(field => {
                const el = document.getElementById(field.id);
                const value = el ? el.value.trim() : '';
                if (field.id === 'image') {
                    return (hasOldImage || (hiddenPath && hiddenPath.value.trim() !== '') || (el.files && el.files.length > 0))
                        && window.uploadCompleted;
                }
                return value !== '';
            });
            // Check time valid
            const timeValid = checkTimeValid();

            // Check title valid
            const titleValid = checkTitleValid();

            // Check number valid
            const numberValid = checkNumberValid();

            // Check address valid
            const addressValid = checkAddressValid();

            // Check integer valid
            const intValid = checkIntValid();

            // Check content-line valid
            const contentValid = [...document.querySelectorAll('.content-line')].every(i => i.value.trim() !== '');

            return basicFieldsFilled && timeValid && numberValid && intValid && titleValid && contentValid && description !== '';
        }

        // Disable submit button
        function updateSubmitState() {
            if (submitBtn) {
                submitBtn.disabled = !isValid();
            }
        }

        // check if input has data
        function toggleAddButton() {
            const inputs = document.querySelectorAll('.content-line');
            if (addBtn) {
                addBtn.disabled = ![...inputs].every(i => i.value.trim() !== '');
            }
        }
        // catch all .content-line input in wrapper
        if (wrapper) {
            wrapper.addEventListener('input', e => {
                if (e.target.classList.contains('content-line')) {
                    const input = e.target;

                    const errorContainer = input.closest('.content-item-wrapper').querySelector('.error-message');

                    if (input.value.trim() === '') {
                    errorContainer.innerText = 'Content is required.';
                    } else {
                    errorContainer.innerText = '';
                    }

                    toggleAddButton();
                    updateSubmitState();
                }
            });

            wrapper.addEventListener('click', e => {
                if (e.target.closest('.btn-remove_line')) {
                    const item = e.target.closest('.content-item-wrapper');
                    if (item) {
                        item.remove();
                        toggleAddButton();
                        updateSubmitState();
                    }
                }
            });
        }
        
        // when submit
        if (form) {
            form.addEventListener('submit', e => {
            console.log('Submit event triggered');
            const contents = [...document.querySelectorAll('.content-line')]
                .map(i => i.value.trim().replace(/\s+/g, ' '))
                .filter(v => v != '');

                if (contents.length === 0) {
                    e.preventDefault();
                }

                hiddenInput.value = JSON.stringify(contents);
            })

        }

        // Button to add line of content
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                const newId = `content_item_${contentIndex}`;
                const errorId = `error-${newId}`;

                wrapper.insertAdjacentHTML('beforeend',
                    `<div class="content-item-wrapper mb-2" id="wrapper-${newId}">
                        <div class="content-item d-flex align-items-center gap-2">
                            <input type="text" class="form-control content-line" id="${newId}" placeholder="Enter event content here">
                            <button type="button" class="btn btn-sm btn-danger btn-remove_line"><i class="fa fa-trash-o"></i></button>
                        </div>
                        <small class="text-danger error-message" id="${errorId}"></small>
                    </div>
                    `);

                    const newInput = document.getElementById(newId);
                    const newWrapper = document.getElementById(`wrapper-${newId}`);

                    newInput.focus();

                    toggleAddButton();
                    contentIndex++;
            });
        }

        toggleAddButton();

        // Check time valid
        function checkTimeValid() {
            const startInput = document.getElementById('start_time');
            const finishInput = document.getElementById('finish_time');
            const errorContainer = document.getElementById('error-finish_time');
            let valid = true;

            if (!startInput || !finishInput) {
                return true;
            }

            const startVal = startInput.value;
            const finishVal = finishInput.value;

            if (startVal && finishVal) {
                const startDate = new Date(startVal);
                const finishDate = new Date(finishVal);

                if (finishDate <= startDate) {
                    valid = false;
                    if (errorContainer) {
                        errorContainer.textContent = 'Finish time must be greater than start time';
                    }
                } else {
                    if (errorContainer) {
                        errorContainer.textContent = '';
                    }
                }
            }

            return valid;
        }

        // Check title valid
        function checkTitleValid() {
            const titleInput = document.getElementById('title');
            if (!titleInput) {
                return true;
            }

            const title = titleInput.value;
            let valid = true;

            if (title && title.length > 70) {
                valid = false;
            }

            return valid;
        }

        // Check address valid
        function checkAddressValid() {
            const addressInput = document.getElementById('address');
            if (!addressInput) {
                // Không có input thì bỏ qua
                return true;
            }

            const address = addressInput.value;
            let valid = true;

            if (address && address.length > 40) {
                valid = false;
            }

            return valid;
        }

        // Check address valid
        function checkIntValid() {
            input = document.getElementById('total_slots');
            let valid = true;
            if (input) {
                const value = input.value.trim();

                if (!intPartern.test(value)) {
                    valid = false;
                }
            }

            return valid;
        }

        // Validate min-max of number field
        function checkNumberValid() {
            let valid = true;

            [
                {id: 'cost', name: 'Cost', min: 0, max: 1000000},
                {id: 'total_slots', name: 'Total Slots', min: 0, max: 1000}
            ].forEach(field => {
                const input = document.getElementById(field.id);
                if (input) {
                    const value = parseFloat(input.value);

                    if (value < field.min) {
                        valid = false;
                    } else if (value > field.max) {
                        valid = false;
                    }
                }
            });

            return valid;
        }

        // Print error message
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

                if (errorContainer && field.id === 'title') {
                    const value = input.value.trim();
                    if (value.length > 70) {
                        errorContainer.innerText = 'Title must be less than 70 characters.';
                    }
                }

                if (errorContainer && field.id === 'address') {
                    const value = input.value.trim();
                    if (value.length > 40) {
                        errorContainer.innerText = 'Address must be less than 40 characters.';
                    }
                }

                if (errorContainer && field.id === 'total_slots') {
                    const value = parseInt(input.value.trim(), 10);
                    if (value > 1000) {
                        errorContainer.innerText = 'Total slots must be less than 1000.';
                    } else {
                        errorContainer.innerText = '';
                    }
                }

                if (errorContainer && field.id === 'cost') {
                    const value = input.value.trim();
                    if (value === '') {
                        errorContainer.innerText = 'Cost is required.';
                    } else {
                        const num = parseFloat(value);
                        if (isNaN(num)) {
                            errorContainer.innerText = 'Cost must be a number.';
                        } else if (num >= 1000000) {
                            errorContainer.innerText = 'Cost must be less than 1000000.';
                        } else {
                            errorContainer.innerText = '';
                        }
                    }
                }
                updateSubmitState();
            });
        });

        // Hidden error message of content
        const contentInput = document.getElementById('content_item');
        const hiddenMsg = document.getElementById('hidden-msg-content_json');
        if (hiddenMsg) {
            contentInput.addEventListener('input', () => {
                hiddenMsg.style.display = 'none';
            });
        }

        // Limit 4 digits for year
        ['start_time', 'finish_time'].forEach(id => {
            const input = document.getElementById(id);
            const errorContainer = document.getElementById('error-finish_time');
            if (!input) return;

            input.addEventListener('input', () => {
                let value = input.value;
                if (!value) return;

                const parts = value.split('-');
                if (parts[0].length > 4) {
                    parts[0] = parts[0].slice(0,4);
                    input.value = parts.join('-');
                }

                updateSubmitState();
            });
        });

        // Limit decimal digit of cost
        const decimalLimitedFields = ['cost'];
        decimalLimitedFields.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function() {
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

        // Check valid image and display image before submit
        if (imageInput) {
            imageInput.addEventListener('change', function() {
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

        // Validate integer for total slots field
        input = document.getElementById('total_slots');
        if (input) {
            input.addEventListener('input', function () {
                const value = input.value.trim();
                const errorContainer = document.getElementById('error-total_slots');
                const hiddenMsg = document.getElementById('hidden-msg-total_slots');
                if (hiddenMsg) {
                    hiddenMsg.style.display = 'none';
                }

                if (value === '') {
                    errorContainer.innerText = 'Total slots is required.';
                } else if (!intPartern.test(value)) {
                    errorContainer.innerText = 'Total slots must be an integer.';
                }

                updateSubmitState();
            });
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

    $(document).ready(function() {
        $('.toggle-status').click(function() {
            var button = $(this);
            var eventId = button.data('id');

            $.ajax({
                url: '/admin/events/' + eventId + '/toggle-status',
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

   // Add 0 before dot
    if (value.startsWith('.')) {
      value = '0' + value;
      this.value = value;
    }
  });
});
</script>