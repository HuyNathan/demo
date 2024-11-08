$('#upload').change(function () {
    var formData = new FormData();
    formData.append('file', $('#upload')[0].files[0]);

    $.ajax({
        url: '/admin/upload/services',  // Đường dẫn đến UploadController
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success) {
                $('#image_show').html('<a href="' + response.url + '" target="_blank">' +
                    '<img src="' + response.url + '" width="100px"></a>');
                $('#thumb').val(response.url);  // Gán giá trị URL ảnh vào input hidden 'thumb'
            } else {
                alert('Upload failed, please try again.');
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
});
