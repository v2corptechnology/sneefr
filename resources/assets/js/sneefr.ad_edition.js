$(function(){
    if (document.getElementsByClassName('ad-create').length) {
        // When publishing as a shop, display options
        $('.js-publish-as').on('change', function (event) {
            this.value ? displayShopOptions() : hideShopOptions();
            fillLocation($(this).children('option:selected'));
        });

        if ($('.js-publish-as').val()) {
            displayShopOptions();
        } else {
            hideShopOptions();
        }
    }
});

function displayShopOptions() {
    $('.js-delivery-options').show().find('input').attr('disabled', false);
    // for publishing as a shop
    $('.js-publish-as').attr('name', 'shop_id');
}

function hideShopOptions() {
    $('.js-delivery-options').hide().find('input').attr('disabled', true);
    //publishing as a user
    $('.js-publish-as').attr('name', '');
}
/**
 * Fill location when seller change (shot, user)
 * @param item
 */
function fillLocation(item){
    $('.js-location').val(item.data('location'));
    $('.js-latitude').val(item.data('latitude'));
    $('.js-longitude').val(item.data('longitude'));
}

/**
 * Image uploaded list
 * @type {Array}
 */
var uploaded_images = [];

if (document.getElementById('dropzone')) {
    Dropzone.options.dropzone = {

        // Maximum allowed file size, in megabytes.
        maxFilesize: 10,

        // Maximum amount of files that can be uploaded. Number.
        maxFiles: 10,

        // Whether or not a link should be added to each preview to
        // allow for the deletion of the related file. Boolean.
        addRemoveLinks: true,

        // Text that has to be used for the links allowing to remove files.
        dictRemoveFile: '×',

        // A comma separated list of acceptable media types or file
        // extensions. Wilcards can be used, e.g. 'image/*'.
        // Limits are bound to GD PHP Lib
        acceptedFiles: 'image/jpeg,image/png,image/gif',

        // Whether Dropzone should send multiple files in one request.
        uploadMultiple: false,

        // Generated preview thumbnail width
        thumbnailWidth: 80,

        // Generated preview thumbnail height
        thumbnailHeight: 80,

        // Callback to register events
        init: function() {

            if (typeof adImages !== 'undefined') {
                for (var i = 0; i < adImages.length; i++) {

                    var image_name = adImages[i].substr(adImages[i].lastIndexOf("/") + 1);

                    addImageToList(image_name);

                    var mockFile = {
                        serverId: image_name,
                        delete_url: deleteUrls[i],
                        name: adImages[i],
                        size: 0
                    };

                    // Call the default addedfile event handler
                    this.emit("addedfile", mockFile);

                    // Show the thumbnail of the file:
                    this.emit("thumbnail", mockFile, adImages[i]);

                    // Make sure that there is no progress bar, etc...
                    this.emit("complete", mockFile);

                    this.files.push(mockFile);

                    // Remove the progressbar
                    mockFile.previewElement.querySelector('.dz-remove').style.display = 'block';
                }

                // If you use the maxFiles option, make sure you adjust it to the
                // correct amount:
                var existingFileCount = adImages.length; // The number of files already uploaded
                this.options.maxFiles = this.options.maxFiles - existingFileCount;

                setLockOnSubmit(false);
            }

            // Callback triggered just before a file is sent.
            this.on("sending", function(file, xhr, formData) {
                // Disable the submit button.
                setLockOnSubmit(true);
                // Send the CSRF token
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="_token"]').attr('content'));
            });

            // Callback for when the file has been successfully uploaded.
            this.on("success", function(file, response) {
                file.serverId = response.file;
                file.delete_url = response.delete_url;
                // Hide the warning message if there is one
                toggleImageWarning(this.files.length);

                if (typeof adImages !== 'undefined' && this.files.length == 1) {
                    $('#dropzone .dz-remove').hide();
                } else {
                    $('#dropzone .dz-remove').show();
                }

                if(response.status == "success"){
                    addImageToList(response.file)
                }
            });

            // Callback for when the upload is finished, even if an error happened.
            this.on('complete', function(file) {
                // Discard the file if the server was not able to process it.
                if (file.status === 'error') {
                    alert(this.getAttribute('data-error-uploading'));
                    this.removeFile(file);
                }

                file.previewElement.querySelector('.dz-remove').style.display = 'block';

                setLockOnSubmit(false);
            });

            // Callback triggered when a file is removed.
            this.on("removedfile", function(file) {
                var hasFiles = this.files.length;

                // Show/hide warning message if not enough images
                toggleImageWarning(hasFiles);

                // (de)activate the save button
                document.getElementById('save').disabled = ! hasFiles;

                if (typeof adImages !== 'undefined' && this.files.length == 1) {
                    $('#dropzone .dz-remove').hide();
                } else {
                    $('#dropzone .dz-remove').show();
                }

                // Send call only file has been uploaded.
                if (file.delete_url) {

                    $.ajax({'url': file.delete_url, 'type': 'delete'});
                    
                    deleteImageFromTheList(file.serverId)
                }
            });
        }

    }
}

function setLockOnSubmit(disable) {
    var submit = document.getElementById('save');

    if (disable) {
        submit.innerHTML = submit.getAttribute('data-upload-in-progress');
    } else {
        submit.innerHTML = submit.getAttribute('data-save');
    }

    submit.disabled = disable;
}

function toggleImageWarning(num) {
    var $warning = $('.js-no-image-error');

    if (num >= 1) {
        $warning.addClass('hidden');
    } else {
        $warning.removeClass('hidden');
    }
}

/**
 * delete image from list and update the images container (input list)
 * @param  {[type]} image      
 */
function deleteImageFromTheList(image){
    uploaded_images.splice( $.inArray(image, uploaded_images), 1 );
    addImagesToHTML();
}

/**
 * add image to the list and to images input container
 * @param {[type]} image 
 */
function addImageToList(image){
    uploaded_images.push(image);
    addImagesToHTML();
}

/**
 * add input images list in HTMl container
 */
function addImagesToHTML(){
    var uploadedList = $('#uploaded-images-list');
    uploadedList.children().remove();
    for (var i = uploaded_images.length - 1; i >= 0; i--) {
        uploadedList.append($('<input type="hidden" name="images[]" value="'+ uploaded_images[i] +'" />'));
    }
}
