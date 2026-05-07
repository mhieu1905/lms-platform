document.addEventListener("DOMContentLoaded", function() {
    const chooseBtn = document.getElementById('chooseFileBtn');
    const fileInput = document.getElementById('image');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const previewImage = document.getElementById('preview-image');
    const hiddenPath = document.getElementById('imagePath');
    const previewContainer = document.getElementById('new-image-preview');
    const oldImageWrapper = document.getElementById('old-image-wrapper');
    window.errorImage = document.getElementById('error-image');

    // Click open file button
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

        // Preview image
        const reader = new FileReader();
        reader.onload = e => {
            if (oldImageWrapper) {
                oldImageWrapper.remove();
            }
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);

        window.uploadCompleted = false;
        if (window.errorImage) {
            window.errorImage.innerText = 'Loading image...';
            dispatchUploadEvent();
        }

        // Upload temp to server
        const formData = new FormData();
        formData.append('image', file);
        formData.append('type', fileInput.dataset.type || 'generate');

        fetch('/admin/upload-temp-image', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.path) hiddenPath.value = data.path; // Save path in hidden input
            window.uploadCompleted = true;
            if (window.errorImage) {
                window.errorImage.innerText = '';
                dispatchUploadEvent();
            }
        })
        .catch(err => {
            console.error('Upload error:', err);
            window.uploadCompleted = true;
            if (window.errorImage) {
                window.errorImage.innerText = '';
                dispatchUploadEvent();
            }
        });
    });
    function dispatchUploadEvent() {
        document.dispatchEvent(new CustomEvent('upload-status-changed'));
    }
});
