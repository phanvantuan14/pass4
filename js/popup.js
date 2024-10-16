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
    $("#cancelDeleteAll").on("click", function(){
        deleteAllProduct.hide();  
    });
    

    // filter product
    $('#categoryDropdown').on('click', function() {
        $('#categoryCheckboxes').toggle(); 
    });

    $('#tagDropdown').on('click', function() {
        $('#tagCheckboxes').toggle(); 
    });

    $(window).on("click", function (event) {
        if (!$(event.target).closest("#categoryContainer").length) {
            $("#categoryCheckboxes").hide();
        }
        if (!$(event.target).closest("#tagContainer").length) {
            $("#tagCheckboxes").hide();
        }
    });
    
});
