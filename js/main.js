$(document).ready(function () {
    let currentPage = 1;
    let totalPages = 0;

    function viewProductList(products) {
        var html = "";
        $.each(products, function (index, product) {
        html += "<tr>";
        html += "<td>" + product.created_date + "</td>";
        html += "<td>" + product.title + "</td>";
        html += "<td>" + product.sku + "</td>";
        html += "<td>$" + product.price + "</td>";
        html +=
            "<td><img  src='" +
            product.featured_image +
            "' height='50' width='100' alt='Feature image'></td>";

        // Gallery images (check for null or empty string)
        if (product.gallery_images) {
            var galleryImages = product.gallery_images.split(",");
            html += "<td class='gallery-cell'>"; // Thêm lớp để áp dụng CSS
            $.each(galleryImages, function (i, image) {
                html += "<img src='" + image + "' class='gallery-image' alt='Gallery image'>";
            });
            html += "</td>";
        } else {
            html += "<td>No images available</td>";
        }
        

        html += "<td>" + product.category_names + "</td>";
        html += "<td>" + product.tag_names + "</td>";

        html += "<td>";
        html +=
            '<i class="fas fa-edit edit-btn" data-id="' + product.id + '"></i>';
        html +=
            '<i class="fas fa-trash-alt delete-one-icon" data-id="' +
            product.id +
            '"></i>';
        html += "</td>";

        html += "</tr>";
        });

        $("#productResults").html(html);
    }

    function loadProducts(page) {
        $.ajax({
        url: "core.php",
        type: "GET",
        data: {
            "view-product": true,
            page: page,
        },
        success: function (response) {
            console.log(response);
            const data = JSON.parse(response);
            const products = data.products;
            totalPages = data.totalPages;

            console.log(products.gallery_images)

            viewProductList(products);
            updatePagination(totalPages, currentPage);
        },
        error: function () {
            alert("Error occurred while fetching products.");
        },
        });
    }

    function searchProduct() {
        $("#searchInput").on("keyup", function () {
        var query = $(this).val();

        if (query !== "") {
            $.ajax({
            url: "core.php",
            type: "GET",
            data: {
                search: query,
            },
            success: function (response) {
                var products = JSON.parse(response);
                console.log(products);
                viewProductList(products);
            },
            error: function () {
                $("#searchResults").html("<p>Error fetching data</p>");
            },
            });
        } else {
            $("#searchResults").html("");
        }
        });
    }

    function filterProduct(page) {
        $("#filterButton").on("click", function (e) {
        e.preventDefault();

        var selectedCategories = [];
        $('input[name="categories[]"]:checked').each(function () {
            selectedCategories.push($(this).val());
        });

        var selectedTags = [];
        $('input[name="tags[]"]:checked').each(function () {
            selectedTags.push($(this).val());
        });

        var formData = {
            sort_by: $("#sortByDate").val(),
            sort_order: $("#sortOrder").val(),
            categories: selectedCategories,
            tags: selectedTags,
            date_from: $("#dateFrom").val(),
            date_to: $("#dateTo").val(),
            price_from: $("#priceFrom").val(),
            price_to: $("#priceTo").val(),
        };

        $.ajax({
            url: "core.php",
            type: "GET",
            data:  $.extend({
                "filter-product": true
            }, formData),
            success: function (response) {
                const data = JSON.parse(response);
                const products = data.products;

                viewProductList(products);
            },
            error: function () {
            alert("Error occurred while fetching products.");
            },
        });
        });
    }

    function updatePagination(totalPages, currentPage) {
        $(".pagination").empty();

        $(".pagination").append(
        '<button id="prevPage" ' +
            (currentPage === 1 ? "disabled" : "") +
            '><i class="fas fa-chevron-left"></i></button>'
        );

        for (let i = 1; i <= totalPages; i++) {
        $(".pagination").append(
            '<button class="page-number ' +
            (i === currentPage ? "active" : "") +
            '">' +
            i +
            "</button>"
        );
        }

        $(".pagination").append(
        '<button id="nextPage" ' +
            (currentPage === totalPages ? "disabled" : "") +
            '><i class="fas fa-chevron-right"></i></button>'
        );
    }

    $(document).on("click", ".page-number", function () {
        currentPage = parseInt($(this).text());
            loadProducts(currentPage); 
            if(isFilter){
                filterProduct(currentPage);
            }
    });

    $(document).on("click", "#prevPage", function () {
        if (currentPage > 1) {
            currentPage--;
            loadProducts(currentPage);
            if(isFilter){
                filterProduct(currentPage);
            }
        }
    });

    $(document).on("click", "#nextPage", function () {
        if (currentPage < totalPages) {
            currentPage++;

            loadProducts(currentPage); 
            if(isFilter){
                filterProduct(currentPage);
            }
        }
    });

    loadProducts(currentPage);
    filterProduct();
    searchProduct();
});

window.onload = function() {
    var errorContainer = document.getElementById('errorContainer');
    var status = document.getElementById('status');

    if (errorContainer) {
        errorContainer.classList.add('show');

        setTimeout(function() {
            errorContainer.classList.remove('show');
        }, 3000);
    }

    if (status) {
        setTimeout(function() {
            status.style.display = 'none'; 
        }, 3000);
    }
};
