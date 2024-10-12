$(document).ready(function(){
    // Khi trang tải, gửi AJAX để load dữ liệu mặc định
    $.ajax({
        url: 'core.php',
        type: 'GET',
        data: {
            'view-product': true
        },
        success: function(response){
            // Kiểm tra xem có dữ liệu trả về không
            var products = JSON.parse(response);

            // Hiển thị kết quả vào phần tử tbody
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

                // Action buttons
                html += "<td>";
                html += '<i class="fas fa-edit edit-btn" data-id="' + product.id + '"></i>';
                html += '<i class="fas fa-trash-alt delete-one-icon" data-id="' + product.id + '"></i>';
                html += "</td>";

                html += "</tr>";
            });

            // Đưa nội dung HTML vào tbody
            $('#productResults').html(html);
        },
        error: function(){
            alert('Error occurred while fetching products.');
        }
    });
});
