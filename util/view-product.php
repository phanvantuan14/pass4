<?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "phantuan_sql");

    $itemsPerPage = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $itemsPerPage;

    $sql = "SELECT 
                p.id,
                p.sku,
                p.title,
                p.price,
                p.featured_image,
                p.created_date,
                GROUP_CONCAT(DISTINCT pg.image) AS gallery_images,
                GROUP_CONCAT(DISTINCT c.name) AS category_names,
                GROUP_CONCAT(DISTINCT t.name) AS tag_names
            FROM products p
            LEFT JOIN product_gallery pg ON p.id = pg.product_id
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            LEFT JOIN product_tags pt ON p.id = pt.product_id
            LEFT JOIN tags t ON pt.tag_id = t.id
            WHERE 1=1"; 

    
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchQuery = $conn->real_escape_string($_GET['search']);
        $sql .= " AND p.title LIKE '%$searchQuery%'";
    }

    
    if (isset($_GET['filter-product'])) {
        $sortBy = $_GET['sort_by'] ?? '';
        $sortOrder = $_GET['sort_order'] ?? 'ASC';
        $categories = $_GET['categories'] ?? [];
        $tags = $_GET['tags'] ?? [];
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $priceFrom = $_GET['price_from'] ?? '';
        $priceTo = $_GET['price_to'] ?? '';

        
        if (!empty($categories) && is_array($categories)) {
            $categories = array_map('intval', $categories);
            $categoriesList = implode(",", $categories);
            $sql .= " AND c.id IN ($categoriesList)";
        }

        
        if (!empty($tags) && is_array($tags)) {
            $tags = array_map('intval', $tags);
            $tagsList = implode(",", $tags);
            $sql .= " AND t.id IN ($tagsList)";
        }

        
        if (!empty($dateFrom)) {
            $sql .= " AND p.created_date >= '" . $conn->real_escape_string($dateFrom) . "'";
        }
        if (!empty($dateTo)) {
            $sql .= " AND p.created_date <= '" . $conn->real_escape_string($dateTo) . "'";
        }

        
        if (!empty($priceFrom)) {
            $sql .= " AND p.price >= " . (float)$priceFrom;
        }
        if (!empty($priceTo)) {
            $sql .= " AND p.price <= " . (float)$priceTo;
        }

        
        $allowedSortColumns = ['price', 'created_date', 'title'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'p.created_date';
        }
        $sql .= " GROUP BY p.id ORDER BY $sortBy $sortOrder";
    } else {
        $sql .= " GROUP BY p.id ORDER BY p.created_date DESC";
    }

    
    $sql .= " LIMIT $offset, $itemsPerPage";

    
    $result = $conn->query($sql);

    if ($result === false) {
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        exit;
    }

    
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    
    $totalSql = "SELECT COUNT(DISTINCT p.id) as total FROM products p";
    $totalResult = $conn->query($totalSql);
    $totalRow = $totalResult->fetch_assoc();
    $totalPages = ceil($totalRow['total'] / $itemsPerPage);

    
    echo json_encode([
        'products' => $products,
        'totalPages' => $totalPages,
    ]);
?>