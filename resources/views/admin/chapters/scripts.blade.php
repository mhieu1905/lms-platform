<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form.forms-sample');
        const submitBtn = document.getElementById('submitBtn');

        const requiredField = [
            { id: 'title', name: 'Chapter Title', min: 1, max: 100 }, 
            { id: 'course_id', name: 'Course' } 
        ];

        function isValid() {
            return requiredField.every(field => {
                const el = document.getElementById(field.id); 
                const value = el ? el.value.trim() : ''; 
                return value !== '' && (!field.min || value.length >= field.min) && (!field.max || value.length <= field.max);
            });
        }

        function updateSubmitState() {
            submitBtn.disabled = !isValid(); 
        }

        requiredField.forEach(field => {
            const input = document.getElementById(field.id); 
            if (!input) return; 

            const errorContainer = document.getElementById(`error-${field.id}`); 

            input.addEventListener('blur', () => {
                const value = input.value.trim(); 
                let errorMessage = ''; 

                if (!value && errorContainer) {
                    errorMessage = `${field.name} is required.`; 
                } else if (field.min && value.length < field.min && errorContainer) {
                    errorMessage = `${field.name} must be at least ${field.min} characters.`; 
                } else if (field.max && value.length > field.max && errorContainer) {
                    errorMessage = `${field.name} may not be greater than ${field.max} characters.`; 
                }

                if (errorContainer) {
                    const laravelErrorEl = document.getElementById(`laravel_title_error_course_id`); 
                    if (errorMessage && laravelErrorEl) laravelErrorEl.remove(); 
                    errorContainer.innerText = errorMessage;
                    errorContainer.style.display = errorMessage ? 'block' : 'none'; 
                }

                updateSubmitState(); 
            });

            if (field.id === 'course_id') {
                input.addEventListener('change', () => {
                    const value = input.value.trim(); 
                    const errorContainer = document.getElementById(`error-${field.id}`); 
                    const laravelErrorEl = document.getElementById(`laravel_title_error_course_id`);

                    // Remove Laravel error if a valid (non-empty) value is selected
                    if (value && laravelErrorEl) {
                        laravelErrorEl.remove();
                    }

                    let errorMessage = '';
                    if (!value && errorContainer) {
                        errorMessage = `${field.name} is required.`;
                    }

                    if (errorContainer) {
                        errorContainer.innerText = errorMessage; 
                        errorContainer.style.display = errorMessage ? 'block' : 'none'; 
                    }

                    updateSubmitState(); 
                });
            }
        });

        form.addEventListener('input', updateSubmitState);
        form.addEventListener('change', updateSubmitState);

        updateSubmitState();
    });
</script>