<script>
    $(document).on('click', '#btnAddRow', function () {
        const container = $('#dynamicFields');
        let i = container.find('.dynamic-row').length + 1;

        let sectionTemp = $('.footerForm').data('section');
        let section = sectionTemp.toLowerCase();

        let placeholderLabel = "Item Name";
        let placeholderLink = "Item Link"
        let typeDynamic = "url";
        let classDynamic = "url-field";
        let dataDynamic = "link";

        if (section === "logo") {
            placeholderLabel = "Enter Iconify icon name (e.g., mdi:home)";
            placeholderLink = "Enter text to display";
            typeDynamic = "text";
            classDynamic = "";
            dataDynamic = "text";
        } else if (section === "social") {
            placeholderLabel = "Enter Iconify icon name (e.g., mdi:home)";
            placeholderLink = "Enter link";
        }

        let oldItems = @json(old('items', []));
        let oldLabel = oldItems[i]?.label ?? "";
        let oldValue = oldItems[i]?.[dataDynamic] ?? "";
        console.log(oldItems, oldLabel, oldValue);
        container.append(`
        <div class="row mb-2 dynamic-row" data-index="${i}">
            <label>Item ${i}<span class="text-danger">*</span></label>
            <div class="col-5">
                <input type="text" class="form-control dynamic-field" 
                       name="items[${i}][label]" placeholder="${placeholderLabel}" 
                       data-field="label" data-index="${i}">
                <div class="error-message" id="error-label-${i}"></div>
            </div>
            <div class="col-5">
                <input type="${typeDynamic}" class="form-control dynamic-field ${classDynamic}" 
                       name="items[${i}][${dataDynamic}]" placeholder="${placeholderLink}" " 
                       data-field="${dataDynamic}" data-index="${i}">
                <div class="error-message" id="error-link-${i}"></div>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger btn-remove w-100">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
        </div>
        <hr>
    `);
        $('#submitBtn').prop('disabled', true);
    });
</script>
{{-- Store --}}
<script>
    $(document).ready(function () {
        $('.footerForm').on('submit', function (e) {
            e.preventDefault();

            const form = this;
            const section = $(this).data('section');
            let data = {};

            if (section === 'main') {
                data.title = $(this).find('[name="title_main"]').val();
                let items = [];
                $(this).find('#dynamicFields .dynamic-row').each(function () {
                    const label = $(this).find('[data-field="label"]').val();
                    const link = $(this).find('[data-field="link"]').val();
                    items.push({
                        label: label ?? "",
                        link: link ?? ""
                    });
                });
                if (items.length > 0) data.items = items;

            } else if (section === 'logo') {
                const logoInput = $(this).find('[name="logo"]')[0];
                if (logoInput && logoInput.files.length > 0) {
                    data.logo = logoInput.files[0].name;
                }
                let items = [];
                $(this).find('#dynamicFields .dynamic-row').each(function () {
                    const label = $(this).find('[data-field="label"]').val();
                    const text = $(this).find('[data-field="text"]').val();
                    items.push({
                        label: label ?? "",
                        text: text ?? ""
                    });
                });
                if (items.length > 0) data.items = items;

            } else if (section === 'copyright') {
                data.copyright = $(this).find('[name="copyright"]').val();
                let items = [];
                $(this).find('#dynamicFields .dynamic-row').each(function () {
                    const label = $(this).find('[data-field="label"]').val();
                    const link = $(this).find('[data-field="link"]').val();
                    items.push({
                        label: label ?? "",
                        link: link ?? ""
                    });
                });
                if (items.length > 0) data.items = items;


            } else if (section === 'social') {
                let items = [];
                $(this).find('#dynamicFields .dynamic-row').each(function () {
                    const label = $(this).find('[data-field="label"]').val();
                    const link = $(this).find('[data-field="link"]').val();
                    data.socials = "";
                    items.push({
                        label: label ?? "",
                        link: link ?? ""
                    });
                });
                if (items.length > 0) data.items = items;
            }

            $(this).find('.contentInput').val(JSON.stringify(data));
            console.log("Encoded JSON:", data);

            $('#submitBtn').prop('disabled', true);

            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            setTimeout(() => {
                form.submit.call(form);
            }, 300);
        });
    });
</script>
{{-- VALIDATION --}}
<script>
    $(document).ready(function() {

        function toggleSubmitButton() {
            const dynamicFields = $('.dynamic-field');
            if (dynamicFields.length === 0) {
                $('#submitBtn').prop('disabled', true);
                return;
            }

            let allFilled = true;
            $('.dynamic-field').each(function() {
                const type = $(this).attr('type');
                const field = $(this).data('field');
                const value = $(this).val().trim();


                if ($(this).hasClass('file-invalid')) {
                    if (field === 'logo' && window.isEdit) {
                        return
                    }
                    allFilled = false;
                    return false;
                }

                if (type === 'file') {
                    if (field === 'logo' && window.isEdit) {
                        if ($('#old-image-wrapper').length > 0) {
                            return;
                        }
                        return
                    }
                    if ($(this)[0].files.length === 0) {
                        allFilled = false;
                        return false;
                    }
                } else if (type === 'url') {
                    if (!value) {
                        allFilled = false;
                        return false;
                    }
                    if (!this.checkValidity()) {
                        allFilled = false;
                        return false;
                    }
                } else {
                    if (!$(this).val().trim()) {
                        allFilled = false;
                        return false;
                    }
                }
            });
            $('#submitBtn').prop('disabled', !allFilled);
        }
        toggleSubmitButton();

        $(document).on('blur input', '.dynamic-field', function () {
            const type = $(this).attr('type');
            if (type === 'file' && event.type === 'input') return

            const value = $(this).val().trim();
            const field = $(this).data('field');
            const index = $(this).data('index');
            const errorDiv = $(`#error-${field}-${index}`);

            if (type === 'file') {
                if ($(this)[0].files.length === 0) {
                    if (!window.isEdit) {
                        errorDiv.text('This field is required').show();
                    } else {
                        errorDiv.text('').hide();
                    }
                } else {
                    errorDiv.text('').hide();
                }
            } else {
                if (!value) {
                    errorDiv.text('This field is required').show();
                } else {
                    if ($(this).hasClass('url-field')) {
                        const urlPattern = /^https?:\/\/[^\s]+$/;
                        if (!urlPattern.test(value)) {
                            errorDiv.text('Please enter a valid URL').show();
                        } else {
                            errorDiv.text('').hide();
                        }
                    } else {
                        errorDiv.text('').hide();
                    }
                }
            }
            toggleSubmitButton();
        });

        $(document).on('change', 'input[type="file"].dynamic-field.logo-input', function () {
            const file = this.files[0];
            const field = $(this).data('field');
            const index = $(this).data('index');
            const errorDiv = $(`#error-${field}-${index}`);
            const oldWrapper = $('#old-image-wrapper');
            const previewWrapper = $('#logo-preview-wrapper');
            const previewImage = $('#logo-preview');

            if (!file) {
                if (!window.isEdit) {
                    errorDiv.text('This field is required').show();

                } else {
                    errorDiv.text('').hide();

                }
                previewImage.attr('src', '');
                previewWrapper.hide();
                if (oldWrapper.length) oldWrapper.show();
                $(this).removeClass('file-invalid');
                toggleSubmitButton();
                return
            }

            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {

                errorDiv.text('Only images: JPG, JPEG, PNG, GIF, WEBP').show();
                $(this).val('');
                $(this).addClass('file-invalid');

                previewImage.attr('src', '');
                previewWrapper.hide();
                if (oldWrapper.length) oldWrapper.show();

                toggleSubmitButton();
                return
            }

            errorDiv.text('').hide();
            $(this).removeClass('file-invalid');
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.attr('src', event.target.result);
                previewWrapper.show();
                if (oldWrapper.length) oldWrapper.hide();
            };
            reader.readAsDataURL(file);
            toggleSubmitButton();
        });


        $(document).on('click', '.btn-remove', function() {
            $(this).closest('.dynamic-row').next('hr').remove();
            $(this).closest('.dynamic-row').remove();
            toggleSubmitButton();
        });

        $(document).on('submit', '.footerForm', function(e) {
            let hasError = false;
            $(this).find('.dynamic-row input.dynamic-field').each(function() {
                const value = $(this).val().trim();
                const field = $(this).data('field');
                const index = $(this).data('index');
                const errorDiv = $(`#error-${field}-${index}`);
                if (!value) {
                    errorDiv.text('This field is required').show();
                    hasError = true;
                } else {
                    errorDiv.text('').hide();
                }
            });
            if (hasError) e.preventDefault();
        });

    });
</script>
