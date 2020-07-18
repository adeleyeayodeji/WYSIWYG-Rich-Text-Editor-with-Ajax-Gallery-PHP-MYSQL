function checkImage(images) {
    var rand = function() {
        return Math.random().toString(36).substr(2); // remove `0.`
    };

    var token = function() {
        return rand() + rand(); // to make it longer
    };

    var tokenans = token();

    var reader = new FileReader();
    reader.onload = function() {
        if (reader.readyState == 2) {
            // console.log(reader.result);
            const addmore = `<div id="${tokenans}"><img class='gallerypic2' src='${reader.result}'
            '/><div style="height:130px;background:#000000a8;margin-top: -132px;position: relative;width: 130px;margin-left: 1px;box-shadow: 1px 2px 5px 0px lightgray;"><img style="height: 28px;
            margin-left: 50px;
            margin-top: 50px;" src="img/loader.gif" />
            </div></div>`;
            $("#clickme").fadeOut(() => {
                $("#ajazresponse3").prepend(addmore);
            });
        }
    }
    reader.readAsDataURL(images);

    setTimeout(() => {
        //Send to base
        createFormData(images, tokenans);
    }, 1000);
}

function createFormData(image, tokenans) {
    var formImage = new FormData();
    formImage.append('userImage', image);
    uploadFormData(formImage, tokenans);
    // console.log(tokenans);
}

function image_completed(id, src, name) {
    $('#' + id).fadeOut(() => {
        console.log(id + 'Fadeout successfully');
        const result = `
        <div>
        <img class="gallerypic2 lazy" src="${API_URL}image/${src}" id="${src}featured" onclick="insertIMG2(this.src, this.id, '${name}','${new Date()}')" data-src="${API_URL}image/${src}" data-srcset="${API_URL}image/${src}" />
        </div>
        `;
        $("#ajazresponse2").prepend(result);
        //Remove div
        $('#' + id).remove();
        //Check if the container is empty
        var main = $('#ajazresponse3').html();
        var clean = main.replace(/\s/g, '');
        setTimeout(() => {
            if (clean.length < 1) {
                $('#clickme').fadeIn();
            }
        }, 100);

    });
}

function uploadFormData(formData, tokenans) {
    $.ajax({
        url: API_URL + "include/ajaxupload.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
            // console.log(data, tokenans);
            if (data.info == 'Successfully uploaded') {
                image_completed(tokenans, data.src, data.name);
            }
        }
    });
}

$(document).ready(function() {
    $(".formhome").submit(function(e) {
        e.preventDefault();
        $("#bodywa").addClass("bodywa");
        $("#bodywa").html($("#summernote").val());
    });

    //Upload image onclick
    $("#uploadForm3").on('submit', (function(e) {
        e.preventDefault();
        $.ajax({
            url: API_URL + "include/ajaxupload.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                // console.log("Uploading. . .");
                $("#button_push").removeClass("btn-primary");
                $("#button_push").addClass("btn-info");
                $("#button_push").val("Uploading . . .");
            },
            success: function(data) {
                if (data.info === "Limit Exceeded") {
                    $("#button_push").removeClass("btn-info");
                    $("#button_push").addClass("btn-danger");
                    $("#button_push").val(data.info);
                    setTimeout(function() {
                        $("#button_push").removeClass("btn-danger");
                        $("#button_push").addClass("btn-primary");
                        $("#button_push").val("Upload Image");
                    }, 3000);
                    // console.log(data.info);
                } else if (data.info === "null") {
                    $("#button_push").removeClass("btn-info");
                    $("#button_push").addClass("btn-danger");
                    $("#button_push").val("Select Image");
                    setTimeout(function() {
                        $("#button_push").removeClass("btn-info btn-danger");
                        $("#button_push").addClass("btn-primary");
                        $("#button_push").val("Upload Image");
                    }, 3000);
                    // console.log(data.info);
                } else {
                    $('#uploadForm3').trigger("reset");
                    $("#uploadimage2").attr("src", API_URL + "img/featured.gif");
                    $("#ajazresponse2").prepend(`<div><img class='gallerypic2' id="${data.src}featured" onclick="insertIMG2(this.src, this.id, '${data.name}', '${new Date()}')" src='${API_URL}image/${data.src}'/></div>
                `);
                    $("#button_push").removeClass("btn-info btn-danger");
                    $("#button_push").addClass("btn-success");
                    $("#button_push").val("Successfully Uploaded");
                    setTimeout(function() {
                        $("#recent").trigger("click");
                        var link = document.getElementById(data.src + 'featured');
                        link.click();
                        $("#button_push").removeClass("btn-success");
                        $("#button_push").addClass("btn-primary");
                        $("#button_push").val("Upload Image");
                    }, 1500);
                    // console.log(data);
                }
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
    }));
    //Upload on click ends here

    $("#upload-tab").on('dragenter', function(e) {
        e.preventDefault();

    });

    $("#upload-tab").on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass("dropzone");
        $("#clickme p, #clickme h2").addClass("text-white");
        // alert("Baba");
    });

    $("#upload-tab").on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass("dropzone");
        $("#clickme p, #clickme h2").removeClass("text-white");
        // alert("Baba");
    });

    $("#upload-tab").on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass("dropzone");
        $("#clickme p, #clickme h2").removeClass("text-white");
        var image = e.originalEvent.dataTransfer.files;
        // console.log(image);

        for (let index = 0; index < image.length; index++) {
            //Check if the preview is loaded
            // console.log(image[index]);
            checkImage(image[index]);
        }
        //for loop ends here
    });
});