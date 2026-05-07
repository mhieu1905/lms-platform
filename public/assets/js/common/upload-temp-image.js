document.addEventListener("DOMContentLoaded", function() {
    const chooseBtn = document.getElementById('chooseFileBtn');
    const fileInput = document.getElementById('image');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const previewImage = document.getElementById('preview-image');
    const hiddenPath = document.getElementById('imagePath');
    const previewContainer = document.getElementById('new-image-preview');
    const oldImageWrapper = document.getElementById('old-image-wrapper');
    window.errorImage = document.getElementById('error-image');

    chooseBtn.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (!this.files.length) return;
        if (chooseBtn) {
            window.uploadCompleted = false;
        } else {
            return window.uploadCompleted = true;
        }

        const file = this.files[0];
        fileNameDisplay.textContent = file.name;

        // Validate file type on client side first
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
        if (!allowedTypes.includes(file.type)) {
            setError(`Invalid image format: ${file.type || 'unknown'}. Only JPG, PNG, GIF, WEBP are accepted.`);
            fileInput.value = '';
            return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = e => {
            if (oldImageWrapper) oldImageWrapper.remove();
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);

        setLoading('Uploading image...');

        // Upload temp to server
        const formData = new FormData();
        formData.append('image', file);
        formData.append('type', fileInput.dataset.type || 'generate');

        fetch('/admin/upload-temp-image', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(errData => {
                    const msg = errData?.message
                        || errData?.errors?.image?.[0]
                        || `Server error (${res.status})`;
                    throw new Error(msg);
                });
            }
            return res.json();
        })
        .then(data => {
            if (data.path) {
                hiddenPath.value = data.path;
                setSuccess();
            } else {
                throw new Error('Server did not return an image path.');
            }
        })
        .catch(err => {
            console.error('Upload error:', err);
            setError(`Upload failed: ${err.message}`);
            hiddenPath.value = '';
            fileInput.value = '';
        });
    });

    function setLoading(msg) {
        window.uploadCompleted = false;
        if (window.errorImage) {
            window.errorImage.style.color = '#888';
            window.errorImage.innerText = msg;
        }
        dispatchUploadEvent();
    }

    function setError(msg) {
        window.uploadCompleted = false;
        if (window.errorImage) {
            window.errorImage.style.color = 'red';
            window.errorImage.innerText = '⚠️ ' + msg;
        }
        dispatchUploadEvent();
    }

    function setSuccess() {
        window.uploadCompleted = true;
        if (window.errorImage) {
            window.errorImage.style.color = 'green';
            window.errorImage.innerText = '✓ Image uploaded successfully';
            setTimeout(() => { window.errorImage.innerText = ''; }, 3000);
        }
        dispatchUploadEvent();
    }

    function dispatchUploadEvent() {
        document.dispatchEvent(new CustomEvent('upload-status-changed'));
    }
});