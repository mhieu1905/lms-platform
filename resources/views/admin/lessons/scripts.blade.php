<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form.forms-sample');
        const submitBtn = document.querySelector('button[type="submit"]');

        const fields = [{
                id: 'title',
                name: 'Lesson Title',
                min: 1,
                max: 100
            },
            {
                id: 'course_id',
                name: 'Course'
            },
            {
                id: 'chapter_id',
                name: 'Chapter'
            },
            {
                id: 'duration',
                name: 'Duration'
            },
            {
                id: 'ck_editor',
                name: 'Content',
                min: 1,
            },
            {
                id: 'video',
                name: 'Video',
                type: 'file',
                accept: ['mp4', 'avi', 'mov', 'mkv', 'webm'],
                required: false
            }
        ];

        let ckEditor;
        window.touchedFields = new Set(); // Make global
        window.validateLessonField = validateField; // Export function
        const getElement = id => document.getElementById(id);
        const getErrorContainer = id => document.getElementById(`error-${id}`);
        const removeLaravelError = id => document.getElementById(`laravel_title_error_${id}`)?.remove();

        function showError(id, message) {
            const el = getErrorContainer(id);
            if (!el) return;
            if (message) removeLaravelError(id);
            el.innerText = message;
            el.style.display = message ? 'block' : 'none';
        }

        function validateField(field, showErrors = true) {
            const {
                id,
                name,
                min,
                max,
                type,
                accept,
                required = true
            } = field;
            let value = '',
                error = '';

            if (id === 'ck_editor' && ckEditor) {
                const htmlContent = ckEditor.getData().trim();
                value = htmlContent.replace(/<[^>]*>/g, '').replace(/&nbsp;|&[a-zA-Z0-9#]+;/g, '').trim();
            } else if (type === 'file') {
                const input = getElement(id);
                const file = input?.files[0];
                if (!file && required) {
                    error = `${name} is required.`;
                } else if (file) {
                    const ext = file.name.split('.').pop().toLowerCase();
                    if (!accept.includes(ext)) {
                        error = `${name} must be one of: ${accept.join(', ').toUpperCase()}.`;
                    }
                }
            } else if (id === 'duration') {
                const el = getElement(id);
                value = el.value;
                if (Number(value) < 0) {
                    error = `${name} must be a non-negative number.`;
                }
                else if (Number(value) > 999) {
                    error = `${name} may not be greater than 999.`;
                }
            } else {
                const el = getElement(id);
                value = el?.value.trim() || '';
            }

            if (type !== 'file') {
                if (!value && required) {
                    error = `${name} is required.`;
                } else if (value.length < (min || 0)) {
                    error = `${name} must be at least ${min} characters.`;
                } else if (max && value.length > max) {
                    error = `${name} may not be greater than ${max} characters.`;
                }
            }

            if (showErrors && touchedFields.has(id)) {
                showError(id, error);
            }

            return !error;
        }

        function isFormValid() {
            return fields.every(f => validateField(f, false));
        }

        function updateSubmitBtn() {
            if (submitBtn) submitBtn.disabled = !isFormValid();
        }

        ClassicEditor
            .create(getElement('ck_editor'))
            .then(editor => {
                ckEditor = editor;
                editor.model.document.on('change:data', () => {
                    touchedFields.add('ck_editor');
                    updateSubmitBtn();
                });
                editor.editing.view.document.on('blur', () => {
                    touchedFields.add('ck_editor');
                    validateField(fields.find(f => f.id === 'ck_editor'));
                    updateSubmitBtn();
                });
            });

        fields.forEach(field => {
            const {
                id,
                type
            } = field;
            const input = getElement(id);
            if (!input || id === 'ck_editor') return;

            if (type === 'file') {
                input.addEventListener('change', () => {
                    touchedFields.add(id);
                    validateField(field);
                    updateSubmitBtn();
                });
            } else {
                input.addEventListener('blur', () => {
                    touchedFields.add(id);
                    validateField(field);
                    updateSubmitBtn();
                });
                input.addEventListener('focus', () => touchedFields.add(id));
            }

            if (['course_id', 'chapter_id'].includes(id)) {
                input.addEventListener('change', () => {
                    touchedFields.add(id);
                    if (input.value.trim()) removeLaravelError(id);
                    validateField(field);
                    updateSubmitBtn();
                });
            }
        });

        ['input', 'change'].forEach(evt =>
            form.addEventListener(evt, updateSubmitBtn)
        );

        form.addEventListener('submit', e => {
            fields.forEach(f => touchedFields.add(f.id));
            const isValid = isFormValid();
            fields.forEach(f => validateField(f));
            if (!isValid) e.preventDefault();
        });

        updateSubmitBtn();
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

  input.addEventListener('input', function() {
    this.value = this.value.replace(/[eE+\-]/g, "");
  });
});
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('#video');
            const uploadButton = document.querySelector('.file-upload-browse');
            const fileInfo = document.querySelector('.file-upload-info');
            const preview = document.querySelector('#preview');
            const hiddenInput = document.querySelector('#video_url');
            const errorDiv = document.querySelector('#error-video');
            const submitBtn = document.querySelector('#submitBtn');

            uploadButton.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                const file = fileInput.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('video', file);

                fileInfo.value = file.name;
                errorDiv.style.display = 'none';

                fetch('{{ route('admin.lessons.upload_video') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            preview.src = data.url;
                            preview.style.display = 'block';
                            hiddenInput.value = data.url;
                        } else {
                            errorDiv.textContent = data.message || 'Upload Failed.';
                            submitBtn.disabled = true;
                            errorDiv.style.display = 'block';
                            preview.style.display = 'none';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        errorDiv.textContent = 'Upload Failed.';
                        errorDiv.style.display = 'block';
                    });
            });
        });
    </script>

    @if (old('video_url'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const oldVideo = "{{ old('video_url') }}";
                const preview = document.querySelector('#preview');
                const hiddenInput = document.querySelector('#video_url');
                const fileInfo = document.querySelector('.file-upload-info'); 

                if (oldVideo) {
                    preview.src = oldVideo;
                    preview.style.display = 'block';
                    hiddenInput.value = oldVideo;
                    const fileName = oldVideo.split('/').pop();
                    fileInfo.value = fileName;
                }
            });
        </script>
    @endif
