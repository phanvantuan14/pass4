$(document).ready(function () {
  $(".productResults").on("click", ".edit-btn", function () {
    const productId = $(this).data("id");
    if (!productId) return;

    $.ajax({
      method: "POST",
      url: "./core.php",
      data: {
        "click-edit-btn": true,
        id: productId,
      },
      dataType: "json",
      success: function (data) {
        console.log(data);
        if (data) {
          $("#product_id").val(data.id);
          $("#sku").val(data.sku);
          $("#title").val(data.title);
          $("#price").val(data.price);

          if (data.featured_image) {
            $("#featured_image_preview-edit")
              .attr("src", "./uploads/" + data.featured_image)
              .show();
            $(".featured_image_title").val(data.featured_image);
          } else {
            $("#featured_image_preview-edit").hide();
          }

          if (Array.isArray(data.gallery_images)) {
            $("#gallery_images_preview-edit").empty();
            data.gallery_images.forEach((image) => {
              $("#gallery_images_preview-edit").append(
                $("<img>")
                  .attr("src", "./uploads/" + image)
                  .css({ width: "100px", height: "auto", margin: "5px" })
              );
            });
            $(".featured_image_num").val(data.gallery_images.length);
          }

          $('input[name="categories[]"]').prop("checked", false);
          if (data.category_ids) {
            data.category_ids.forEach((id) => {
              $('input[name="categories[]"][value="' + id + '"]').prop(
                "checked",
                true
              );
            });
          }

          $('input[name="tags[]"]').prop("checked", false);
          if (data.tag_ids) {
            data.tag_ids.forEach((id) => {
              $('input[name="tags[]"][value="' + id + '"]').prop(
                "checked",
                true
              );
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
