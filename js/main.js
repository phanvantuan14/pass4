$(document).ready(function() {

    function prevPages(){
        let currentPage = 1;
        let rowsPerPage = 5;
        let $rows = $('#productTable tbody tr');
    
        function displayPage(page) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;
    
            $rows.hide();
    
            $rows.slice(start, end).show();
        }
    
        displayPage(currentPage);
    
        $('.page-number').on('click', function() {
            currentPage = parseInt($(this).text());
            displayPage(currentPage);
        });
    
        $('#nextPage').on('click', function() {
            if (currentPage < Math.ceil($rows.length / rowsPerPage)) {
                currentPage++;
                displayPage(currentPage);
            }
        });
    
        $('#prevPage').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                displayPage(currentPage);
            }
        });
    }
    prevPages();
});
