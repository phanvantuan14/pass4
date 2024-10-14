$(document).ready(function () {
    let currentPage = 1; 
    let totalPages = 0;

    function loadProducts(page) {
        $.ajax({
            url: 'core.php',
            type: 'GET',
            data: {
                'view-product': true,
                'page': page
            },
            success: function (response) {
                // console.log(response);
                const data = JSON.parse(response);
                const products = data.products;
                totalPages = data.totalPages;

                var html = "";
                $.each(products, function (index, product) {
                    html += "<tr>";
                    html += "<td>" + product.created_date + "</td>";
                    html += "<td>" + product.title + "</td>";
                    html += "<td>" + product.sku + "</td>";
                    html += "<td>$" + product.price + "</td>";
                    html += "<td><img src='" + product.featured_image + "' height='50' width='100' alt='Feature image'></td>";

                    // Gallery images (check for null or empty string)
                    if (product.gallery_images) {
                        var galleryImages = product.gallery_images.split(',');
                        html += "<td>";
                        $.each(galleryImages, function(i, image) {
                            html += "<img src='" + image + "' height='50' width='50' alt='Gallery image'>";
                        });
                        html += "</td>";
                    } else {
                        html += "<td>No images available</td>";
                    }

                    html += "<td>" + product.category_names + "</td>";
                    html += "<td>" + product.tag_names + "</td>";

                    html += "<td>";
                    html += '<i class="fas fa-edit edit-btn" data-id="' + product.id + '"></i>';
                    html += '<i class="fas fa-trash-alt delete-one-icon" data-id="' + product.id + '"></i>';
                    html += "</td>";

                    html += "</tr>";
                });

                $('#productResults').html(html);

                updatePagination(totalPages, currentPage);
            },
            error: function () {
                alert('Error occurred while fetching products.');
            }
        });
    }

    function updatePagination(totalPages, currentPage) {
        $('.pagination').empty(); 

        
        $('.pagination').append('<button id="prevPage" ' + (currentPage === 1 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>');

        
        for (let i = 1; i <= totalPages; i++) {
            $('.pagination').append('<button class="page-number ' + (i === currentPage ? 'active' : '') + '">' + i + '</button>');
        }

        
        $('.pagination').append('<button id="nextPage" ' + (currentPage === totalPages ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>');
    }

    $(document).on('click', '.page-number', function () {
        currentPage = parseInt($(this).text());
        loadProducts(currentPage);
    });

    $(document).on('click', '#prevPage', function () {
        if (currentPage > 1) {
            currentPage--;
            loadProducts(currentPage);
        }
    });

    $(document).on('click','#nextPage',function () {
        console.log(currentPage);
        if (currentPage < totalPages) {
            currentPage++;
            loadProducts(currentPage);
        }
    });

    loadProducts(currentPage);
});
