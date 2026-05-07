<script>
document.addEventListener('DOMContentLoaded', function () {
    const majorName = document.getElementById('name');
    const submitBtn = document.getElementById('submitBtn');
    const errorMessage = document.getElementById('error-name');
    const hiddenMsg = document.getElementById('hidden-msg-name');

    // Validate for major name
    function validateMajorName() {
        const value = majorName ? majorName.value.trim() : '';
        
        if (!value) {
            return false;
        } else if (value.length >= 50) {
            return false;
        } else {
            return true;
        }
    }

    // Disable submit button when value input is invalid.
    function updateSubmitState() {
        submitBtn.disabled = !validateMajorName();
    }

    // Validate major name when typing
    majorName.addEventListener('input', function() {
        if(hiddenMsg) {
            hiddenMsg.style.display = 'none';
        }
        const value = majorName ? majorName.value.trim() : '';
        
        if (!value) {
            if (errorMessage) {
                errorMessage.innerText = 'Major Name is required.';
            }
        } else if (value.length >= 50) {
            if (errorMessage) {
                errorMessage.innerText = 'The name may not be greater than 50 characters.';
            }
        } else {
            if (errorMessage) {
                errorMessage.innerText = '';
            }
        }
        updateSubmitState();
    });

    updateSubmitState();
});

</script>