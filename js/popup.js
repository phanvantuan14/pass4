$(document).ready(function () {
    const close = $(".close");
    const addProduct = $("#addProductModal");
    const addProperty = $("#addPropertyModal");
    const editProduct = $("#editProductModal");
    const deleteProduct = $("#deleteOneProduct");
    const deleteAllProduct = $("#deleteAllProduct");

    close.on("click", function () {
        addProperty.hide();
        addProduct.hide();
        editProduct.hide();
        deleteProduct.hide();
        deleteAllProduct.hide();
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

    $(".action-column").on("click", ".delete-all", function () {
        deleteAllProduct.show();
    });

    $("#cancelDelete").on("click", function(){
        deleteProduct.hide();     
    });
    
    
});
