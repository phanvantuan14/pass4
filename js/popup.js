$(document).ready(function () {
    const close = $(".close");
    const addProduct = $("#addProductModal");
    const addProperty = $("#addPropertyModal");
    const editProduct = $("#editProductModal");
    const deleteProduct = $("#deleteOneProduct");

    close.on("click", function () {
        addProperty.hide();
        addProduct.hide();
        editProduct.hide();
        deleteProduct.hide();
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
        $(".edit-btn").on("click", function () {
            const productId = $(this).data("id");
    
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
        $(".delete-one-icon").on("click", function () {
            const productId = $(this).data("id");
            console.log(productId);
            $("#product_id").val(productId);
            deleteProduct.show(); 
    
            $("#confirmDelete").off('click').on('click', function (e) {
                e.preventDefault(); 
    
                $.ajax({
                    method: "POST",
                    url: "./core.php",
                    data: {   
                        'click-delete-one-btn': true,
                        id: productId 
                    },
                    success: function (data) {
                        if (typeof data === "string") {
                            data = JSON.parse(data); 
                        }
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message);
                        }  
                    },
                    error: function () {
                        alert("Error occurred while deleting the product.");
                    }    
                });
            });

            $("#cancelDelete").on("click", function (e) {
                e.preventDefault(); 
                deleteProduct.hide(); 
            });
        });
    }
    
    popupDeleteOneProduct();
});


