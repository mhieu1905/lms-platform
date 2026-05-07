<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form.forms-sample');
    const nameInput = document.getElementById('name');
    const errorContainer = document.getElementById('error-name');
    const submitBtn = document.getElementById('submitBtn');

    const min = 1;
    const max = 100;

    function validateName(showError = true) {
        const value = nameInput.value.trim();
        let errorMessage = '';

        if (!value) {
            errorMessage = 'Level Name is required.';
        } else if (value.length < min) {
            errorMessage = `Level Name must be at least ${min} characters.`;
        } else if (value.length > max) {
            errorMessage = `Level Name may not be greater than ${max} characters.`;
        }


        if (showError && errorContainer) {
            errorContainer.innerText = errorMessage;
            errorContainer.style.display = errorMessage ? 'block' : 'none';
        }

        return !errorMessage;
    }

    function updateSubmitState() {
        submitBtn.disabled = !validateName(false); // Check validity silently
    }

    nameInput.addEventListener('focus', () => {
        const laravelError = document.getElementById('laravel_title_error_name');
        if (laravelError) {
            laravelError.remove();
        }
    });

    nameInput.addEventListener('blur', () => {
        validateName(true);
        updateSubmitState();
    });

    nameInput.addEventListener('input', updateSubmitState);

    form.addEventListener('submit', function (e) {
        if (!validateName(true)) {
            e.preventDefault();
        }
    });

    // Initial check
    updateSubmitState();
});
</script>
