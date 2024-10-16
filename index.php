<?php
session_start();
include './util/get-tag-category.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://kit.fontawesome.com/051c46ace9.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>KING OF W</title>
</head>


<body>
    <div class="container">

        <?php
        if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
        ?>
            <h3 id="status">
                <?php echo $_SESSION['status']; ?>
            </h3>
        <?php
            unset($_SESSION['status']);
        } ?>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert-danger" id="errorContainer">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <span><?php echo $error; ?></span>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <section class="header">
            <div class="top-header">
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
                    <input id="searchInput" placeholder="Search product..." type="text" />
                </div>
            </div>

            <!-- Filter product -->
            <div class="filter-container">
                <form id="filterForm">

                    <select id="sortByDate" name="sort_by">
                        <option value="date">Date</option>
                    </select>

                    <select id="sortOrder" name="sort_order">
                        <option value="ASC">ASC</option>
                        <option value="DESC">DESC</option>
                    </select>

                    <!-- Category selection -->
                    <div id="categoryContainer">
                        <div id="categoryDropdown">
                            <span>Category</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>

                        <div id="categoryCheckboxes">
                            <?php foreach ($categories_list as $category): ?>
                                <div>
                                    <input type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>">
                                    <?php echo $category['name']; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Tag selection -->
                    <div id="tagContainer">
                        <div id="tagDropdown">
                            <span value="">Select tag</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div id="tagCheckboxes">
                            <?php foreach ($tags_list as $tag): ?>
                                <div>
                                    <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>">
                                    <?php echo $tag['name']; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <input id="dateFrom" name="date_from" placeholder="mm/dd/yyyy" type="date" />
                    <input id="dateTo" name="date_to" placeholder="mm/dd/yyyy" type="date" />
                    <input id="priceFrom" name="price_from" placeholder="Price from" type="text" />
                    <input id="priceTo" name="price_to" placeholder="Price to" type="text" />

                    <button id="filterButton">Filter</button>
                </form>
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
                    <th class="action-column">
                        Action
                        <i class="fas fa-trash-alt delete-all"></i>
                    </th>
                </tr>
            </thead>
            <tbody id="productResults"></tbody>
        </table>

        <div class="pagination">
        </div>
    </div>


    <!-- Add Product Moadl -->
    <div class="modal" id="addProductModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Product</h2>
            <form id="addProductForm" method="POST" action="core.php">
                <label for="sku">SKU:</label>
                <input type="text" name="sku">

                <label for="productName">Product Name:</label>
                <input type="text" name="title" required>

                <label for="price">Price:</label>
                <input type="text" name="price" required>

                <label for="featured_image">Feature Image URL:</label>
                <input type="text" name="featured_image" required>

                <label for="gallery_images">Gallery Images (URLs, separated by commas):</label>
                <input type="text" name="gallery_images">

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
            <div class="modal-delete">
                <form id="deleteOneProductForm" method="POST" action="core.php">
                    <p>Are you sure you want to delete this product?</p>
                    <input type="hidden" id="product_id" name="id">
                    <button n class="btn-delete" id="confirmDelete">Yes, Delete</button>
                </form>
                <button id="cancelDelete">Cancel</button>
            </div>
        </div>
    </div>
    <!-- End Delete One Product Modal -->


    <!-- Delete All Product Modal -->
    <div class="modal" id="deleteAllProduct">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirm Delete All</h2>
            <div class="modal-delete">
                <form id="deleteAllProductForm" method="POST" action="core.php">
                    <p>Are you sure you want to delete all product?</p>
                    <button name="delete-all" class="btn-delete">Yes, Delete</button>
                </form>
                <button id="cancelDelete">Cancel</button>
            </div>
        </div>
    </div>
    <!-- End Delete All Product Modal -->

    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['show_modal'])): ?>
                document.getElementById('addProductModal').style.display = 'block';
                <?php unset($_SESSION['show_modal']); ?>
            <?php endif; ?>
            // <?php if (isset($_SESSION['show_modal_edit'])): ?>
            //     document.getElementById('editProductModal').style.display = 'block';
            //     <?php unset($_SESSION['show_modal_edit']); ?>
            // <?php endif; ?>

            // Close modal when clicking on the close button
            document.querySelector(".close").addEventListener("click", function() {
                document.getElementById('addProductModal').style.display = 'none';
                document.getElementById('editProductModal').style.display = 'none';
            });
        });
    </script> -->


    <script src="./js/main.js"></script>
    <script src="./js/popup.js"></script>
    <script src="./js/edit.js"></script>
    <script src="./js/delete-one.js"></script>
    <!-- <script src="./js/filter.js"></script> -->
    <!-- <script src="./js/search.js"></script> -->

</body>

</html>