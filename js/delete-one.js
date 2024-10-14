$(document).ready(function () {
    $("#productResults").on("click", ".delete-one-icon", function () {
        const productId = $(this).data("id");

        $("#product_id").val(productId);

        $("#confirmDelete").on("click", function (e) {
            e.preventDefault();

            $.ajax({
            method: "POST",
            url: "./core.php",
            data: {
                "click-delete-one-btn": true,
                id: productId,
            },
            success: function (data) {
                console.log(data);
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
            },
            });
        });


    });
});
