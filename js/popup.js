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

    $("#addProduct").on("click", function () {
        addProduct.show();
    });

    $("#addProperty").on("click", function () {
        addProperty.show();
    });

    $("#productResults").on("click", ".edit-btn", function () {
        editProduct.show();
    });

    $("#productResults").on("click", ".delete-one-icon", function () {
        deleteProduct.show();
    });
    
});
