$(document).ready(function () {
    const close = $(".close");
    const addProduct = $("#addProductModal");
    const addProperty = $("#addPropertyModal");
    const editProduct = $("#editProductModal");
    const deleteProduct = $("#deleteConfirmModal");

    close.on("click", function () {
        addProperty.hide();
        addProduct.hide();
        editProduct.hide();
        deleteProduct.hide();
    });

    $(window).on("click", function (event) {
        if ($(event.target).hasClass("modal")) {
        $(".modal").hide();
        }
    });

    function popupAddProduct() {
        $("#addProduct").on("click", function () {
        addProduct.show();
        });

        // Xử lý khi form được gửi
        // $("#addProductForm").on("submit", function (event) {
        // event.preventDefault();
        // console.log('SKU:', $('#sku').val());
        // console.log('Product Name:', $('#productName').val());
        // console.log('Price:', $('#price').val());
        // console.log('Image:', $('#featured_image').val());
        // addProduct.hide();
        // });
    }
    popupAddProduct();

    function popupAddProperty() {
        $("#addProperty").on("click", function () {
        addProperty.show();
        });

        // $("#addPropertyForm").on("submit", function (event) {
        // event.preventDefault();
        //     console.log('Category:', $('#propertyCategory').val());
        //     console.log('Tag:', $('#propertyTag').val());
        //     // addProperty.hide();
        // });
    }
    popupAddProperty();

    function popupEditProduct() {
        $(".fa-edit").on("click", function () {
            const productId = $(this).data("id");
            console.log(productId);

        $.ajax({
            url: "core.php",
            type: "GET",
            data: { id: productId },
            dataType: "json",
            success: function (data) {
            if (data) {
                $("#sku").val(data.sku);
                $("#title").val(data.title);
                $("#price").val(data.price);
                $("#featured_image").val(data.featured_image);
                $("#gallery_images").val(data.gallery_images.join(","));
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

                editProduct.show();
            } else {
                alert("Không tìm thấy sản phẩm.");
            }
            },
            error: function () {
            alert("Đã xảy ra lỗi khi lấy thông tin sản phẩm.");
            },
        });
        });

        // $("#editProductForm").on("submit", function (event) {
            // event.preventDefault();

            // $.ajax({
            // url: "./core/update-product.php",
            // method: "POST",
            // data: $(this).serialize(),
            // success: function (response) {
            //     alert(response);
            //     location.reload();
            // },
            // error: function (xhr, status, error) {
            //     console.error("Error updating product:", error);
            // },
            // });
        // });
    }
    popupEditProduct();

    function popupDeleteOneProduct() {
        let productToDelete;

        $(".fa-trash-alt").on("click", function () {
            const row = $(this).closest("tr");
            productToDelete = row;

            deleteProduct.show();
        });

        $("#cancelDelete, .close-delete").on("click", function () {
        deleteProduct.hide();
        });

        $("#confirmDelete").on("click", function () {
        productToDelete.remove();

        deleteProduct.hide();
        });
    }
    popupDeleteOneProduct();
});
