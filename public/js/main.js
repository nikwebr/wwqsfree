if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

(function() {

var clipboard = new ClipboardJS('.copyclip');

clipboard.on('success', function(e) {
    toastr.success('Copied to clipboard!');
    e.clearSelection();
});

clipboard.on('error', function(e) {
    toastr.error('Something went wrong!');
});

function getTotalPreviousUploadedFilesSize(){
   var totalSize = 0;
   var zipFileDropZone = Dropzone.forElement('#zipfileme-awesome-dropzone');
   zipFileDropZone.getFilesWithStatus(Dropzone.SUCCESS).forEach(function(file){
      totalSize = totalSize + file.size;
   });
   return totalSize;
}

// Dropzone Config
Dropzone.options.zipfilemeAwesomeDropzone = {
    paramName: "zipFileData", // The name that will be used to transfer the file
    maxFilesize: window.appMaxFileSize, // MB
    createImageThumbnails: false,
    autoProcessQueue: false,
    maxFiles: window.appMaxFileQty,
    parallelUploads : window.appMaxFileQty,
    uploadMultiple: false,
    clickable: ["#add-more-file", "#zipfileme-awesome-dropzone"],
    timeout: window.appUploadTimeOut, // 20 min
    init: function() {

        //console.log('Dropzone', 'Initializing...');

        this.timeStamp = Date.now();
        this.prevProgress = 0;

        // Check if someone wants to share files
        if (window.shareCodeData.length > 0) {
            window.shareCodeData = JSON.parse(window.shareCodeData);
            var filesToShare = window.shareCodeData.allFiles;
            //console.log("Wants to Share :", filesToShare);

            var filesToAddArray = [];
            if( typeof(filesToShare) == 'object' ){
                $.each( filesToShare, function( key, data ) {
                  filesToAddArray.push({"name":data.file_url,"size":data.file_size, "fileID":data.id});

                  var existingFile = '<div class="dz-preview dz-file-preview dz-complete" id="file-old-'+data.id+'"> \
                      <div class="dz-image">\
                          <img data-dz-thumbnail=""/>\
                      </div>\
                      <div class="dz-details">\
                          <div class="dz-size">\
                              <span data-dz-size="">\
                                    <input type="hidden" name="oldFile[]" value="'+data.id+'"/> \
                                  <strong>\
                                      '+filesize(data.file_size, {bits: true, round: 0})+' \
                                  </strong>\
                              </span>\
                          </div>\
                          <div class="dz-filename">\
                              <span data-dz-name="">\
                                  '+data.file_original_name+'\
                              </span>\
                          </div>\
                          <a title="Remove this file" class="dz-remove-file old deleteOldFile" data-file-id="file-old-'+data.id+'"><i class="fas fa-times"></i></a> \
                      </div>\
                  </div>';

                  $('.dropzone').append(existingFile);

                });

                $('.dz-message').css('display', 'none');
                $('.add-more-files').css('display', 'block');
                window.shareCodeData = [];
                window.shareCodeData = filesToAddArray;
                //console.log('Genareted :', filesToAddArray);

                //var existingFileCount = filesToAddArray.length;
                //this.options.maxFiles = existingFileCount;
                //this.removeEventListeners();
            }
        }

        // On Add file ------------------------------------------------------------------------------------------------------
        this.on("addedfile", function(file) {
            // Create the remove button
            var removeButton = Dropzone.createElement('<a title="Remove this file" class="dz-remove-file"><i class="fas fa-times"></i></a>');
            // Capture the Dropzone instance as closure.
            var _this = this;
            // Listen to the click event
            removeButton.addEventListener("click", function(e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();
                // Remove the file preview.
                _this.removeFile(file);
                // If you want to the delete the file on the server as well,
                // you can do the AJAX request here.
            });
            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);
            $('.add-more-files').css('display', 'block');
            // Check for duplicate files
            if (this.files.length) {
                var _i, _len;
                for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
                {
                    if (this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString()) {
                        this.removeFile(file);
                    }
                }
            }
        });

        // On Upload Progress ------------------------------------------------------------------------------------------------------
        this.on('uploadprogress', (file, progress, bytesSent) => {
            var time = Date.now() - this.timeStamp;
            var percent = (progress - this.prevProgress) / 100;
            var chunk = percent * file.size;
            var speed = ((chunk / 1024 / 1024) / (time / 1000)).toFixed(2);
            this.timeStamp = Date.now();
            this.prevProgress = progress;
            this.speed = speed;
            $(".total-upload-speed-data").html(speed+" MB/s");
        });

        // Total Progress
        this.on("totaluploadprogress", function(progress) {
            var allProgress = 0;
            var allFilesBytes = 0;
            var allSentBytes = 0;
            for(var a=0;a<this.files.length;a++) {
                allFilesBytes = allFilesBytes + this.files[a].size;
                allSentBytes = allSentBytes + this.files[a].upload.bytesSent;
                allProgress = (allSentBytes / allFilesBytes) * 100;
            }
            if(allSentBytes <= allFilesBytes){
                $('.total-uploaded-data').html( filesize(allSentBytes) +"/"+filesize(allFilesBytes) );
            }
            if(allProgress >= 100){
                $('.total-uploaded-data').html( filesize(allFilesBytes) +"/"+filesize(allFilesBytes) );
            }
            window.totalProgressDone = allProgress;
            $('.upload-progress-style').css('width', allProgress+'%').attr('aria-valuenow', allProgress);

            if (window.totalProgressDone >= 100){
                $('.upload-sub-text').html('Preparing...');
                $('.upload-progress-style').addClass('done');
            }


        });

        // On Sending start
        this.on('sending', function(file, xhr, formData){
            var getReciverEmail = $('#reciversEmail').val();
            var getSenderEmail = $('#senderEmail').val();
            var getSenderNote = $('#senderNote').val();
            formData.append("_token", window.ZipFileZSRF)
            formData.append('reciversEmail', getReciverEmail);
            formData.append('senderEmail', getSenderEmail);
            formData.append('fileSize', file.size);
            formData.append('senderNote', window.btoa( getSenderNote ) );
            formData.append('shareCode', window.getCurrentShareCode);

        });

        // All files are uploaded
        this.on("complete", function (file) {
            console.log("File uploaded compleated.");

            if (window.totalProgressDone >= 100){
                $('.upload-sub-text').html('Finalizing...');
                $('.upload-progress-style').addClass('done');
            }
        });

        // All files are uploaded with success
        this.on("success", function (file) {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                //console.log("All uploaded");

                axios.post('/confirm-upload', {
                    shareCode: window.getCurrentShareCode,
                    _token: window.ZipFileZSRF,
                    oldData: window.shareCodeData
                })
                .then(function (response) {
                    //console.log(response);
                    if (response.data.uploadStatus == 'Confirmed') {
                        $(".secoundScreen").fadeOut(300, function(){
                            $(".thirdScreen").fadeIn(300, function(){
                                $('.email-sent-to').html($('#reciversEmail').val());
                                $('.share-link-input').val(response.data.data.downloadLink);
                            });
                        });
                    }
                })
                .catch(function (error) {
                    alert("Something went worng. Please try again!");
                });
            }
        });

        // Upload Error
        this.on("error", function (file, errorMessage) {
            console.log(errorMessage);
            if(errorMessage.message){
                alert("Upload has been canceled because of a file error.\n\nFile : "+file.name+"\n\nError : "+errorMessage.message+"\nPossible Solution : Try zipping the file on your computer and add the zipped file instead.");
            }else{
                alert(errorMessage);
            }
            //window.location.href = "/";
        });
    }
};

/**
 * [validateEmail description]
 * @param  {[type]} Email [description]
 * @return {[type]}       [description]
 */
function validateEmail(Email) {
    var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return $.trim(Email).match(pattern) ? true : false;
}

/**
 * [clearAllErrors description]
 * @return {[type]} [description]
 */
function clearAllErrorsSendForm(){
    $('input[name^="reciversEmail"]').prev('.zfl-intput-lebel').removeClass('error');
    $('input[name^="reciversEmail"]').removeClass('error');
    $('#senderEmail').prev('.zfl-intput-lebel').removeClass('error');
    $('#senderEmail').removeClass('error');
    $('.upload-logo').removeClass('error');
    $('.dz-pre-upload-title').removeClass('error');
}

/**
 * [clearAllFormData description]
 * @return {[type]} [description]
 */
function clearAllFormData(){
    $('input[name^="reciversEmail"]').val('');
    $('#reciversEmail"').val('');
    $('#senderEmail').val('');
    $('#senderNote').val('');
}

$(document).on('click', '.newShareFile', function(event) {
    event.preventDefault();
    var zipFileDropZone = Dropzone.forElement('#zipfileme-awesome-dropzone');
    zipFileDropZone.off('error');
    zipFileDropZone.removeAllFiles(true);

    axios.get('/share/code/new')
    .then(function (response) {
        //console.log(response);
        if(response.data.code){
            window.getCurrentShareCode = response.data.code;

            $(".thirdScreen").fadeOut(300, function(){
                $(".firstScreen").fadeIn(300, function(){
                    clearAllErrorsSendForm();
                    clearAllFormData();
                    window.totalFileSize = 0;
                    window.totalUploaded = 0;
                    window.totalProgress = 0;
                    window.totalProgressDone = 0;
                    window.totalFileCount = 0;
                });
            });
        }
    })
    .catch(function (error) {
        //console.log(error);
        alert("Something went worng, please try again!");
    });


});

$(document).on('click', '.deleteOldFile', function(event) {
    event.preventDefault();
    var getDivName = $(this).attr('data-file-id');
    //console.log(getDivName);
    $('#'+getDivName).remove();
});


$(document).on('click', '.cancelFileUpload', function(event) {
    event.preventDefault();
    var zipFileDropZone = Dropzone.forElement('#zipfileme-awesome-dropzone');
    zipFileDropZone.off('error');
    zipFileDropZone.removeAllFiles(true);

    $(".secoundScreen").fadeOut(300, function(){
        $(".firstScreen").fadeIn(300, function(){
            clearAllErrorsSendForm();
            clearAllFormData();
            window.totalFileSize = 0;
            window.totalUploaded = 0;
            window.totalProgress = 0;
            window.totalProgressDone = 0;
            window.totalFileCount = 0;
        });
    });
});
// Send files
$(document).on('click', '#startSendingFiles', function(event) {
    event.preventDefault();



    clearAllErrorsSendForm();

    var getReciverEmail = [];

    if(window.sendEmail){
        $('input[name^="reciversEmail"]').each(function() {
            if ( validateEmail($(this).val()) ) {
                shouldSendData = true;
                getReciverEmail.push($(this).val());
            }else{
                $(this).prev('.zfl-intput-lebel').addClass('error');
                $(this).addClass('error');
                return false;
            }
        });
    }

    $('#reciversEmail').val( getReciverEmail.join() );
    var getSenderEmail = $('#senderEmail').val();
    var getSenderNote = $('#senderNote').val();
    var zipFileDropZone = Dropzone.forElement('#zipfileme-awesome-dropzone');
    var shouldSendData = false;

    if ( validateEmail(getSenderEmail) ) {
        shouldSendData = true;
    }else{
        $('#senderEmail').prev('.zfl-intput-lebel').addClass('error');
        $('#senderEmail').addClass('error');
        shouldSendData = false;
        return false;
    }

    if( !zipFileDropZone.files || !zipFileDropZone.files.length ){
        $('.upload-logo').addClass('error');
        $('.dz-pre-upload-title').addClass('error');
        shouldSendData = false;

        // Check if user is trying to share
        if (window.shareCodeData.length > 0){
            if (    typeof(window.shareCodeData) == 'object'  ) {

                $('.number-of-files').html(window.totalFileCount.length);
                $('.upload-progress-style').css('width', '100%').attr('aria-valuenow', '100');

                // Config page
                $(".firstScreen").fadeOut(300, function(){
                    $(".secoundScreen").fadeIn(300, function(){
                        var getReciverEmail = $('#reciversEmail').val();
                        var getSenderEmail = $('#senderEmail').val();
                        var getSenderNote = $('#senderNote').val();

                        $('.upload-sub-text').html('Processing files');
                        $('.upload-progress-style').addClass('done');

                        axios.post('/share/old/files', {
                            shareCode: window.oldShareCode,
                            _token: window.ZipFileZSRF,
                            reciversEmail: getReciverEmail,
                            senderEmail: getSenderEmail,
                            senderNote: window.btoa( getSenderNote )
                        })
                        .then(function (response) {
                            //console.log(response);
                            if (response.data.uploadStatus == 'Confirmed') {
                                $(".secoundScreen").fadeOut(300, function(){
                                    $(".thirdScreen").fadeIn(300, function(){
                                        $('.email-sent-to').html($('#reciversEmail').val());
                                    });
                                });
                            }
                        })
                        .catch(function (error) {
                            alert("Something went worng. Please try again!");
                        });
                    });
                });


            }
        }

    }else{
        shouldSendData = true;
    }

    if (shouldSendData) {
        //console.log('zipfile', 'Starting upload...');

        $.each( zipFileDropZone.files, function( key, file ) {
            window.totalFileSize = window.totalFileSize + file.size;
            window.totalFileCount++;
        });
        window.totalProgress = 100*window.totalFileCount;

        $('.number-of-files').html(window.totalFileCount);
        $('.upload-progress-style').css('width', '0%').attr('aria-valuenow', '0');

        // Config page
        $(".firstScreen").fadeOut(300, function(){
            $(".secoundScreen").fadeIn(300, function(){
                zipFileDropZone.processQueue();
            });
        });
        return true;
    }else{
        return false;
    }

});

// Add more emails
$(document).on('click', '.add-more-email', function(event) {
    //console.log('clicked');
    var inputHtml = '<div class="input-group zfl-input-grp"> \
                      <input type="email" class="form-control zfl-input-style multiple with-button" name="reciversEmail[]"  placeholder="Email address" value="">  \
                      <span class="zfl-input-style-grp input-group-btn"> \
                        <button class="btn zfl-button remove-email" type="button"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button> \
                      </span> \
                    </div>';

    $('#email-container').append(inputHtml);
});

// remove email
$(document).on('click', '.remove-email', function(event) {
    //console.log('clicked');
    $(this).closest(".zfl-input-grp").remove();
});


// Submit help msg
$(document).on('click', '.submitHelpMsg', function(event) {
    event.preventDefault();

    var getUserMailElm = $('#userEmailHlp');
    var getUserMsgElm = $('#userMsgHlp');
    var submitBtnElm = $('.submitHelpMsg');
    var formElm = $('#helpFrm');
    var isValidForm = true;

    getUserMailElm.removeClass('error');
    getUserMsgElm.removeClass('error');
    submitBtnElm.removeClass('error');

    if ( validateEmail(getUserMailElm.val()) ) {
    }else{
        getUserMailElm.addClass('error');
        isValidForm = false;
    }

    if (getUserMsgElm.val().length < 1) {
        getUserMsgElm.addClass('error');
        isValidForm = false;
        return false;
    }

    if(isValidForm){
        formElm.submit();
    }else{
        return false;
    }


});





}());
