<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>KING OF W</title>
</head>


<body>
    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <h3>
            <?php echo $_SESSION['status']; ?>
        </h3>
    <?php
        unset($_SESSION['status']);
    } ?>
    <section class="container">
        <div class="top-container">
            <div class="top-button">

                <button class="button button-active" id="addProduct">
                    Add product
                </button>

                <button class="button" id="addProperty">
                    Add property
                </button>

                <button class="button">
                    Sync from VillaTheme
                </button>
            </div>
            <div class="search-container">
                <input placeholder="Search product..." type="text" />
            </div>
        </div>

        <!-- fillter product -->
        <div class="filter-container">
            <select>
                <option>Date</option>
            </select>
            <select>
                <option>ASC</option>
                <option>DESC</option>
            </select>
            <select>
                <option>Category</option>
            </select>
            <select>
                <option>Select tag</option>
            </select>
            <input placeholder="mm/dd/yyyy" type="date" />
            <input placeholder="mm/dd/yyyy" type="date" />
            <input placeholder="Price from" type="text" />
            <input placeholder="Price to" type="text" />

            <button>Filter</button>
        </div>
    </section>

    <table id="productTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Product name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Feature Image</th>
                <th>Gallery</th>
                <th>Categories</th>
                <th>Tags</th>
                <th>
                    Action
                    <i class="fas fa-trash-alt delete-all"></i>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "phantuan_sql");

            $sql = "SELECT  p.id,
                    p.sku,
                    p.title,
                    p.price,
                    p.featured_image,
                    GROUP_CONCAT(DISTINCT pg.image) AS gallery_images,
                    GROUP_CONCAT(DISTINCT c.name) AS category_names,
                    GROUP_CONCAT(DISTINCT t.name) AS tag_names,
                    p.created_date
                FROM products p
                LEFT JOIN product_gallery pg ON p.id = pg.product_id
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                LEFT JOIN product_tags pt ON p.id = pt.product_id
                LEFT JOIN tags t ON pt.tag_id = t.id
                GROUP BY p.id
                ORDER BY p.created_date DESC;
            ";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                        <td>' . $row['created_date'] . '</td>
                        <td>' . $row['title'] . '</td>
                        <td>' . $row['sku'] . '</td>
                        <td>$' . $row['price'] . '</td>
                        <td><img src="' . $row['featured_image'] . '" height="50" width="100" alt="Feature image"></td>';

                    $gallery_images = explode(',', $row['gallery_images']);

                    echo    '<td>';
                    foreach ($gallery_images as $image) {
                        echo '<img src="' . $image . '" 
                    height="50" width="50" alt="Gallery image"> ';
                    }
                    echo    '</td>';

                    echo    '<td>' . $row['category_names'] . '</td>';

                    echo    '<td>' . $row['tag_names'] . '</td>';

                    echo    '<td>
                        <i class="fas fa-edit edit-btn" data-id="' . $row['id'] . '"></i>
                        <i class="fas fa-trash-alt delete-one-icon" data-id="' . $row['id'] . '"></i>
                    </td>
                </tr>';
                }
            } else {
                echo "0 results";
            }
            ?>

        </tbody>
    </table>

    <div class="pagination">
        <button id="prevPage">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="page-number ">1</button>
        <button class="page-number">2</button>
        <button class="page-number">3</button>
        <button id="nextPage">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>


    <!-- Add Product Moadl -->
    <div class="modal" id="addProductModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Product</h2>
            <form id="addProductForm" method="POST" action="core.php">
                <?php include './util/get-tag-category.php' ?>

                <label for="sku">SKU:</label>
                <input type="text" name="sku" required>

                <label for="productName">Product Name:</label>
                <input type="text" name="title" required>

                <label for="price">Price:</label>
                <input type="text" name="price" required>

                <label for="featured_image">Feature Image URL:</label>
                <input type="text" name="featured_image" required>

                <label for="gallery_images">Gallery Images (URLs, separated by commas):</label>
                <input type="text" name="gallery_images" required>

                <label for="categories">Categories:</label>
                <?php foreach ($categories_list as $category): ?>
                    <div>
                        <input type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>">
                        <?php echo $category['name']; ?>
                    </div>
                <?php endforeach; ?>


                <label for="tags">Tags:</label>
                <?php foreach ($tags_list as $tag): ?>
                    <div>
                        <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>">
                        <?php echo $tag['name']; ?>
                    </div>
                <?php endforeach; ?>


                <button name="add-product" type="submit">Add Product</button>
            </form>

        </div>
    </div>
    <!--End Add Product Moadl -->


    <!--Add Property Moadl -->
    <div class="modal" id="addPropertyModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Property</h2>
            <form id="addPropertyForm" method="POST" action="core.php">
                <input type="hidden" name="form_type" value="add_property">


                <label for="category">Category:</label>
                <input type="text" name="categories">

                <label for="Tag">Tag:</label>
                <input type="text" name="tags">
                <button name="add-property" type="submit">Add Property</button>
            </form>
        </div>
    </div>
    <!--End Add Property Moadl -->


    <!-- Update Product Modal -->
    <div class="modal" id="editProductModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Product</h2>
            <form id="editProductForm" method="POST" action="core.php">
                <?php include './util/get-tag-category.php' ?>

                <input type="hidden" id="product_id" name="id">

                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" required>

                <label for="productName">Product Name:</label>
                <input type="text" id="title" name="title" required>

                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>

                <label for="featured_image">Feature Image URL:</label>
                <input type="text" id="featured_image" name="featured_image" required>

                <label for="gallery_images">Gallery Images:</label>
                <input type="text" id="gallery_images" name="gallery_images">

                <label for="categories">Categories:</label>
                <?php foreach ($categories_list as $category): ?>
                    <div>
                        <input type="checkbox" id="category_<?php echo $category['id']; ?>" name="categories[]" value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </div>
                <?php endforeach; ?>

                <label for="tags">Tags:</label>
                <?php foreach ($tags_list as $tag): ?>
                    <div>
                        <input type="checkbox" id="tag_<?php echo $tag['id']; ?>" name="tags[]" value="<?php echo $tag['id']; ?>">
                        <?php echo htmlspecialchars($tag['name']); ?>
                    </div>
                <?php endforeach; ?>

                <button name="edit-product" type="submit">Update Changes</button>
            </form>
        </div>
    </div>
    <!-- End Product Modal -->


    <!-- Delete One Product Modal -->
    <div class="modal" id="deleteOneProduct">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirm Delete</h2>
            <form id="deleteOneProductForm" method="POST" action="core.php">
                <p>Are you sure you want to delete this product?</p>
                <input type="hidden" id="product_id" name="id">
                <button id="confirmDelete">Yes, Delete</button>
                <button id="cancelDelete">Cancel</button>
            </form>
        </div>
    </div>
    <!-- End Delete One Product Modal -->

    <script src="./js/main.js"></script>
    <script src="./js/popup.js"></script>

</body>

</html>