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
    }
    popupAddProduct();

    function popupAddProperty() {
        $("#addProperty").on("click", function () {
        addProperty.show();
        });
    }
    popupAddProperty();

    function popupEditProduct() {
        $(".fa-edit").on("click", function () {
            const productId = $(this).data("id");
            console.log(productId);

            $.ajax
            ({
                method: "POST",
                url: "./core.php",
                data: { id: productId },
                success: function (data) {
                    console.log(data); 
                    if (data) {
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
                            $('input[name="categories[]"][value="' + id + '"]').prop(
                            "checked",true);
                        });
                        }
    
                        $('input[name="tags[]"]').prop("checked", false);
                        if (data.tag_ids) {
                        data.tag_ids.forEach((id) => {
                            $('input[name="tags[]"][value="' + id + '"]').prop(
                            "checked",true);
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


