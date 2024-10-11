$(document).ready(function() {
    let currentRequest = null; // Biến để lưu yêu cầu AJAX hiện tại

    // Xử lý sự kiện khi người dùng nhập vào ô tìm kiếm
    $('.search-container input').on('input', function() {
        const query = $(this).val().trim(); // Lấy giá trị tìm kiếm

        // Hủy yêu cầu hiện tại nếu có
        if (currentRequest) {
            currentRequest.abort();
        }

        // Nếu ô tìm kiếm rỗng, không thực hiện yêu cầu
        if (query === '') {
            // Reset table to original state if needed
            resetProductTable(); // Hàm này sẽ đưa bảng về trạng thái ban đầu
            return;
        }

        // Gửi yêu cầu AJAX
        currentRequest = $.ajax({
            url: 'your-api-endpoint', // Địa chỉ API của bạn
            method: 'GET', // Hoặc 'POST' tùy vào API
            data: { search: query }, // Gửi từ khóa tìm kiếm
            success: function(response) {
                // Xử lý dữ liệu trả về từ server
                updateProductTable(response); // Hàm này sẽ cập nhật bảng sản phẩm
            },
            error: function(xhr, status, error) {
                if (status !== 'abort') {
                    console.error('Error fetching data:', error);
                }
            }
        });
    });

    function updateProductTable(data) {
        // Xóa dữ liệu cũ
        $('#productTable tbody').empty();

        // Giả định data là một mảng các sản phẩm
        data.forEach(function(product) {
            $('#productTable tbody').append(`
                <tr>
                    <td>${product.date}</td>
                    <td>${product.name}</td>
                    <td>${product.sku}</td>
                    <td>${product.price}</td>
                    <td><img src="${product.featureImage}" alt="Feature Image" height="50" width="50"/></td>
                    <td><img src="${product.galleryImage}" alt="Gallery Image" height="50" width="50"/></td>
                    <td>${product.categories.join(', ')}</td>
                    <td>${product.tags.join(', ')}</td>
                    <td>
                        <i class="fas fa-edit"></i>
                        <i class="fas fa-trash-alt"></i>
                    </td>
                </tr>
            `);
        });
    }

    function resetProductTable() {
        // Hàm này có thể được sử dụng để đưa bảng về trạng thái ban đầu
        // Ví dụ: tải lại tất cả sản phẩm từ API hoặc hiển thị sản phẩm mặc định
    }
});
