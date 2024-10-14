$(document).ready(function (){
    $('#productResults').on('click', '.edit-btn', function () {
        const productId = $(this).data("id");
        // console.log(productId);

        $.ajax({
            method: "POST",
            url: "./core.php",
            data: {   
                'click-edit-btn': true,
                id: productId 
            },
            dataType: 'json', // Chắc chắn nhận về JSON
            success: function (data) {

                if (data) {
                    $("#product_id").val(data.id);
                    $("#sku").val(data.sku);
                    $("#title").val(data.title);
                    $("#price").val(data.price);
                    $("#featured_image").val(data.featured_image);

                    if (Array.isArray(data.gallery_images)) {
                        $("#gallery_images").val(data.gallery_images.join(","));
                    } else {
                        $("#gallery_images").val(data.gallery_images);
                    }

                    $('input[name="categories[]"]').prop("checked", false);
                    if (data.category_ids) {
                        data.category_ids.forEach((id) => {
                            $('input[name="categories[]"][value="' + id + '"]').prop("checked", true);
                        });
                    }

                    $('input[name="tags[]"]').prop("checked", false);
                    if (data.tag_ids) {
                        data.tag_ids.forEach((id) => {
                            $('input[name="tags[]"][value="' + id + '"]').prop("checked", true);
                        });
                    }

                } else {
                    alert("Không tìm thấy sản phẩm.");
                }
            },
            error: function () {
                alert("Đã xảy ra lỗi khi lấy thông tin sản phẩm.");
            },
        });
    });
});