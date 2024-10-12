$(document).ready(function () {
    $('#searchInput').on('keyup', function () {
        var query = $(this).val();

        if (query !== '') {
            $.ajax({
                url: 'core.php', 
                type: 'GET',
                data: {
                    'search': query  
                },
                success: function (response) {
                    var products = JSON.parse(response);
    
                    var html = "";
                    $.each(products, function (index, product) {
                        html += "<tr>";
                        html += "<td>" + product.created_date + "</td>";
                        html += "<td>" + product.title + "</td>";
                        html += "<td>" + product.sku + "</td>";
                        html += "<td>$" + product.price + "</td>";
                        html += "<td><img src='" + product.featured_image + "' height='50' width='100' alt='Feature image'></td>";
    
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
                },
                error: function () {
                    $('#searchResults').html('<p>Error fetching data</p>');
                }
            });
        } else {
            $('#searchResults').html('');
        }
    });
});
