$(document).ready(function () {
  let currentPage = 1;
  let totalPages = 0;
  let maxProduct = 5;
  let isFilter = false;
  let isSearch = false;
  let formDataUpdate = null;

  function viewProductList(products) {
    let html = "";

    $.each(products, function (index, product) {
      let date = "N/A";
      if (product.created_date) {
        date = product.created_date.split(" ")[0];
      }

      html += "<tr>";
      html += "<td>" + date + "</td>";
      html += "<td>" + product.title + "</td>";
      html += "<td>" + product.sku + "</td>";
      html += "<td>$" + product.price + "</td>";
      html +=
        "<td><img src='./uploads/" +
        product.featured_image +
        "' height='100' width='100%' alt='Feature image'></td>";

      if (product.gallery_images) {
        let galleryImages = product.gallery_images.split(",");
        html += "<td><div class='gallery-cell'>";
        $.each(galleryImages, function (i, image) {
          html +=
            "<img src='./uploads/" +
            image +
            "' class='gallery-img' alt='Gallery image'>";
        });
        html += "</div></td>";
      } else {
        html += "<td>No images available</td>";
      }

      html += "<td>" + product.category_names + "</td>";
      html += "<td>" + product.tag_names + "</td>";
      html +=
        "<td class='center-column'><i class='fas fa-edit edit-btn' data-id='" +
        product.id +
        "'></i> <i class='fas fa-trash-alt delete-one-icon' data-id='" +
        product.id +
        "'></i></td>";
      html += "</tr>";
    });

    $(".productResults").html(html);
  }

  function loadProducts(currentPage) {
    isFilter = false;
    isSearch = false;

    $.ajax({
      url: "core.php",
      type: "GET",
      data: { "view-product": true },
      success: function (response) {
        const data = JSON.parse(response);
        const products = data.products;

        const { productsToShow, totalPages } = getProductsForPage(
          products,
          currentPage
        );

        viewProductList(productsToShow);
        updatePagination(totalPages, currentPage);
      },
      error: function () {
        $(".notification").text("Lỗi tải dữ liệu sản phẩm.").show();
        showNotification();
      },
    });
  }

  function loadTagAndCategory() {
    isFilter = false;
    isSearch = false;

    $.ajax({
      url: "core.php",
      type: "GET",
      data: { "get_tag-category": true },
      success: function (data) {
        data = JSON.parse(data);

        // categories
        let categoriesFilter = $("#categoryCheckboxes");
        let categoriesAdd = $(".categories_container-add");
        let categoriesUpdate = $(".categories_container-update");

        let categoriesFilterItem = $(".categories_filter").first();
        let categoriesAddItem = $(".categories_add-item").first();
        let categoriesUpdateItem = $(".categories_update-item").first();

        let addedCategoryIds = new Set();

        categoriesFilter.empty();
        categoriesAdd.empty();
        categoriesUpdate.empty();

        data["categories"].forEach((category) => {
          if (!addedCategoryIds.has(category.id)) {
            let newCategoryFilter = categoriesFilterItem.clone().show();
            let newCategoryAdd = categoriesAddItem.clone().show();
            let newCategoryUpdate = categoriesUpdateItem.clone().show();

            newCategoryFilter.find(".categories_input-filter").val(category.id);
            newCategoryFilter.find(".categories_name").text(category.name);

            newCategoryAdd.find(".categories_add-input").val(category.id);
            newCategoryAdd.find(".categories_name").text(category.name);

            newCategoryUpdate
              .find(".categories_update-input")
              .val(category.id)
              .attr("id", `category_${category.id}`);
            newCategoryUpdate.find(".categories_name").text(category.name);

            categoriesFilter.append(newCategoryFilter);
            categoriesAdd.append(newCategoryAdd);
            categoriesUpdate.append(newCategoryUpdate);

            addedCategoryIds.add(category.id);
          }
        });

        // tags
        let tagsFilter = $("#tagCheckboxes");
        let tagsAdd = $(".tags_container-add");
        let tagsUpdate = $(".tags_container-update");

        let tagsFilterItem = $(".tags_filter").first();
        let tagsAddItem = $(".tags_add-item").first();
        let tagsUpdateItem = $(".tags_update-item").first();

        let addedTagIds = new Set();

        tagsFilter.empty();
        tagsAdd.empty();
        tagsUpdate.empty();

        data["tags"].forEach((tag) => {
          if (!addedTagIds.has(tag.id)) {
            let newTagFilter = tagsFilterItem.clone().show();
            let newTagAdd = tagsAddItem.clone().show();
            let newTagUpdate = tagsUpdateItem.clone().show();

            newTagFilter.find(".tags_input-filter").val(tag.id);
            newTagFilter.find(".tags_name").text(tag.name);

            newTagAdd.find(".tags_add-input").val(tag.id);
            newTagAdd.find(".tags_name").text(tag.name);

            newTagUpdate
              .find(".tags_update-input")
              .val(tag.id)
              .attr("id", `tag_${tag.id}`);
            newTagUpdate.find(".tags_name").text(tag.name);

            tagsFilter.append(newTagFilter);
            tagsAdd.append(newTagAdd);
            tagsUpdate.append(newTagUpdate);

            addedTagIds.add(tag.id);
          }
        });
      },
      error: function () {
        alert("Lỗi tải danh mục hoặc thẻ");
      },
    });
  }

  function searchProduct(currentPage) {
    isFilter = false;
    isSearch = true;
    let query = $("#searchInput").val();

    $.ajax({
      url: "core.php",
      type: "GET",
      data: {
        search: query,
      },
      success: function (response) {
        let products = JSON.parse(response);

        const { productsToShow, totalPages } = getProductsForPage(
          products,
          currentPage
        );

        viewProductList(productsToShow);
        updatePagination(totalPages, currentPage);
      },
      error: function () {
        $(".notification").text("Lỗi truy vấn dữ liệu tìm kiếm.").show();
        showNotification();
      },
    });
  }
  function initializeSearchInput() {
    let debounceTimer;
    $("#searchInput").on("keyup", function () {
      clearTimeout(debounceTimer);

      debounceTimer = setTimeout(function () {
        currentPage = 1;
        checkLoadPages(currentPage);
      }, 300);
    });
  }
  initializeSearchInput();

  function filterProduct() {
    $("#filterButton").on("click", function (e) {
      e.preventDefault();
      isFilter = true;
      isSearch = false;

      loadFilteredProducts(currentPage);
    });
  }

  function loadFilteredProducts(currentPage) {
    let selectedCategories = [];
    $('input[class="categories_input-filter"]:checked').each(function () {
      selectedCategories.push($(this).val());
    });

    let selectedTags = [];
    $('input[class="tags_input-filter"]:checked').each(function () {
      selectedTags.push($(this).val());
    });

    let date = $("#sortByDate").val();
    let order = $("#sortOrder").val();
    let dateFrom = $("#dateFrom").val();
    let dateTo = $("#dateTo").val();
    let priceFrom = $("#priceFrom").val();
    let priceTo = $("#priceTo").val();

    let formData = {
      sort_by: date,
      sort_order: order,
      categories: selectedCategories,
      tags: selectedTags,
      date_from: dateFrom,
      date_to: dateTo,
      price_from: priceFrom,
      price_to: priceTo,
      // page: currentPage,
    };

    $.ajax({
      url: "core.php",
      type: "GET",
      data: $.extend({ "filter-product": true }, formData),
      success: function (response) {
        const data = JSON.parse(response);
        const products = data.products;

        const { productsToShow, totalPages } = getProductsForPage(
          products,
          currentPage
        );

        viewProductList(productsToShow);
        updatePagination(totalPages, currentPage);
      },
      error: function () {
        $(".notification").text("Lỗi tải dữ liệu sản phẩm.").show();
        showNotification();
      },
    });
  }

  function getProductsForPage(products, currentPage) {
    totalPages = Math.ceil(products.length / maxProduct);
    let startIndex = (currentPage - 1) * maxProduct;
    let endIndex = startIndex + maxProduct;
    let productsToShow = products.slice(startIndex, endIndex);
    return { productsToShow, totalPages };
  }

  function updatePagination(totalPages, currentPage) {
    $(".pagination").empty();

    $(".pagination").append(
      '<button id="prevPage" ' +
        (currentPage === 1 ? "disabled" : "") +
        '><i class="fas fa-chevron-left"></i></button>'
    );

    if (totalPages <= 5) {
      for (let i = 1; i <= totalPages; i++) {
        $(".pagination").append(
          '<button class="page-number ' +
            (i === currentPage ? "active" : "") +
            '">' +
            i +
            "</button>"
        );
      }
    } else {
      let startPage, endPage;

      if (currentPage <= 3) {
        startPage = 1;
        endPage = 5;
      } else if (currentPage + 2 >= totalPages) {
        startPage = totalPages - 4;
        endPage = totalPages;
      } else {
        startPage = currentPage - 2;
        endPage = currentPage + 2;
      }

      $(".pagination").append(
        '<button class="page-number ' +
          (currentPage === 1 ? "active" : "") +
          '" data-page="1">1</button>'
      );

      if (startPage > 2) {
        $(".pagination").append("<span>...</span>");
      }

      for (let i = startPage + 1; i < endPage; i++) {
        $(".pagination").append(
          '<button class="page-number ' +
            (i === currentPage ? "active" : "") +
            '" data-page="' +
            i +
            '">' +
            i +
            "</button>"
        );
      }

      if (endPage < totalPages - 1) {
        $(".pagination").append("<span>...</span>");
      }

      if (totalPages > 1) {
        $(".pagination").append(
          '<button class="page-number" data-page="' +
            totalPages +
            '">' +
            totalPages +
            "</button>"
        );
      }
    }

    $(".pagination").append(
      '<button id="nextPage" ' +
        (currentPage === totalPages ? "disabled" : "") +
        '><i class="fas fa-chevron-right"></i></button>'
    );
  }

  function checkLoadPages(currentPage) {
    if (isFilter) {
      loadFilteredProducts(currentPage);
    } else if (isSearch) {
      searchProduct(currentPage);
    } else {
      loadProducts(currentPage);
    }
  }

  function handlePageNum() {
    $(document).on("click", ".page-number", function () {
      currentPage = parseInt($(this).text());
      checkLoadPages(currentPage);
    });
  }
  handlePageNum();

  function prevPage() {
    $(document).on("click", "#prevPage", function () {
      if (currentPage > 1) {
        currentPage--;
        checkLoadPages(currentPage);
      }
    });
  }
  prevPage();

  function nextPage() {
    $(document).on("click", "#nextPage", function () {
      if (currentPage < totalPages) {
        currentPage++;
        checkLoadPages(currentPage);
      }
    });
  }
  nextPage();

  function addProduct() {
    $("#addProductForm").on("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);

      formData.append("action", "add-product");

      $.ajax({
        type: "POST",
        url: "core.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          try {
            const data = JSON.parse(response);
            if (data.status === "success") {
              $(".notification").text("Thêm thành công").show();
              showNotification();

              $("#addProductForm")[0].reset();
              $("#featured_image_preview").attr("src", "");
              $("#gallery_images_preview").empty();

              loadProducts(currentPage);
            } else {
              $(".notification")
                .text(data.message || "Thêm thất bại")
                .show();
              showNotification();
            }
          } catch (error) {
            $(".notification").text("Có lỗi xảy ra.").show();
            showNotification();
          }
        },
        error: function () {
          alert("Có lỗi xảy ra trong quá trình thêm sản phẩm.");
        },
      });
    });
  }
  addProduct();

  function addProperty() {
    $("#addPropertyForm").on("submit", function (event) {
      event.preventDefault();
      let formData = new FormData(this);
      formData.append("action", "add-property");

      $.ajax({
        type: "POST",
        url: "core.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          try {
            console.log(response);
            const data = JSON.parse(response);
            if (data.status === "success") {
              $(".notification").text("Thêm thành công.").show();
              showNotification();

              loadTagAndCategory();

              $("#addPropertyForm")[0].reset();
            } else {
              $(".notification").text("Thêm thất bại.").show();
              showNotification();
            }
          } catch (error) {
            $(".notification").text("Có lỗi xảy ra.").show();
            showNotification();
          }
        },
        error: function () {
          $(".notification")
            .text("Có lỗi xảy ra trong quá trình thêm thuộc tính.")
            .show();
          showNotification();
        },
      });
    });
  }
  addProperty();

  function updateProduct() {
    $("#editProductForm").on("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);

      if (formDataUpdate && formDataEquals(formDataUpdate, formData)) {
        $(".notification").text("Không có thay đổi nào để cập nhật.").show();
        showNotification();
        return;
      }

      formData.append("action", "update-product");

      $.ajax({
        type: "POST",
        url: "core.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          console.log(data);
          try {
            if (typeof data === "string") {
              data = JSON.parse(data);
            }
            if (data.status === "success") {
              $(".notification").text("Update thành công.").show();
              showNotification();

              loadProducts(currentPage);
            } else {
              $(".notification")
                .text(data.message || "Update thất bại.")
                .show();
              showNotification();
            }
          } catch (error) {
            $(".notification")
              .text(data.message || "Có lỗi xảy ra.")
              .show();
            showNotification();
          }
        },
        error: function () {
          $(".notification")
            .text("Có lỗi xảy ra trong quá trình edit sản phẩm.")
            .show();
          showNotification();
        },
      });

      const close = $(".close");
      const editProduct = $("#editProductModal");
      close.on("click", function () {
        $("#editProductForm")[0].reset();
        $("#featured_image_preview-edit").attr("src", "");
        $("#gallery_images_preview-edit").empty();
        editProduct.hide();
      });
    });
  }
  updateProduct();

  function loadDataUpdate() {
    $(".productResults").on("click", ".edit-btn", function () {
      const productId = $(this).data("id");
      if (!productId) return;

      $.ajax({
        method: "POST",
        url: "./core.php",
        data: {
          "click-edit-btn": true,
          id: productId,
        },
        dataType: "json",
        success: function (data) {
          formDataUpdate = data;
          if (data) {
            $("#product_id").val(data.id);
            $("#sku").val(data.sku);
            $("#title").val(data.title);
            $("#price").val(data.price);

            if (data.featured_image) {
              $("#featured_image_preview-edit")
                .attr("src", "./uploads/" + data.featured_image)
                .show();
              $("#featured_image_file-edit").val(""); // Đặt lại giá trị file input
            } else {
              $("#featured_image_preview-edit").hide();
            }

            if (Array.isArray(data.gallery_images)) {
              $("#gallery_images_preview-edit").empty();
              data.gallery_images.forEach((image) => {
                $("#gallery_images_preview-edit").append(
                  $("<img>")
                    .attr("src", "./uploads/" + image)
                    .css({ width: "100px", height: "auto", margin: "5px" })
                );
              });
            }

            // Cập nhật checkbox cho categories
            $('input[name="categories[]"]').prop("checked", false);
            if (data.category_ids) {
              data.category_ids.forEach((id) => {
                $('input.categories_update-input[value="' + id + '"]').prop(
                  "checked",
                  true
                );
              });
            }

            // Cập nhật checkbox cho tags
            $('input[name="tags[]"]').prop("checked", false);
            if (data.tag_ids) {
              data.tag_ids.forEach((id) => {
                $('input.tags_update-input[value="' + id + '"]').prop(
                  "checked",
                  true
                );
              });
            }
          } else {
            alert("Không tìm thấy sản phẩm.");
          }
        },
        error: function () {
          alert("Đã xảy ra lỗi khi lấy thông tin sản phẩm.");
        },
      });
    });
  }
  loadDataUpdate();

  function formDataEquals(formData1, formData2) {
    const obj1 =
      formData1 instanceof FormData
        ? Object.fromEntries(formData1.entries())
        : formData1;
    const obj2 =
      formData2 instanceof FormData
        ? Object.fromEntries(formData2.entries())
        : formData2;

    console.log("form 1", obj1);
    console.log("form 2", obj2);

    if (Object.keys(obj1).length !== Object.keys(obj2).length) {
      return false;
    }

    for (let key in obj1) {
      if (obj1[key] !== obj2[key]) {
        return false;
      }
    }

    return true;
  }

  function deleteOneProduct() {
    $(".productResults").on("click", ".delete-one-icon", function () {
      const productId = $(this).data("id");
      console.log(productId);
      $("#product_id").val(productId);

      $("#confirmDeleteOne").on("click", function (e) {
        e.preventDefault();

        $.ajax({
          method: "POST",
          url: "core.php",
          data: {
            "click-delete-one": true,
            id: productId,
          },
          success: function (data) {
            console.log("delete one ", data);
            if (typeof data === "string") {
              data = JSON.parse(data);
            }
            if (data.success) {
              const deleteProduct = $("#deleteOneProduct");
              deleteProduct.hide();
              loadProducts(currentPage);
            } else {
              alert("Xoa that bai");
            }
          },
          error: function () {
            $(".notification")
              .text("Có lỗi xảy ra trong quá trình xoá sản phẩm.")
              .show();
            showNotification();
          },
        });
      });
    });
  }
  deleteOneProduct();

  function deleteAllProducts() {
    $("#confirmDeleteAll").on("click", function (e) {
      e.preventDefault();

      $.ajax({
        method: "POST",
        url: "core.php",
        data: {
          "click-delete-all": true,
        },
        success: function (data) {
          console.log(data);
          if (typeof data === "string") {
            data = JSON.parse(data);
          }
          if (data.success) {
            $("#deleteAllProduct").hide();
            loadProducts(currentPage);
          } else {
            $(".notification").text("Xóa tất cả sản phẩm thất bại!").show();
            showNotification();
          }
        },
        error: function () {
          $(".notification")
            .text("Đã xảy ra lỗi khi xóa tất cả sản phẩm.")
            .show();
          showNotification();
        },
      });
    });
  }
  deleteAllProducts();

  function showNotification() {
    const $notification = $("#successNotification");
    $notification.fadeIn(500);

    setTimeout(function () {
      $notification.fadeOut(500);
    }, 3000);
  }

  loadTagAndCategory();
  loadProducts(currentPage);
  filterProduct();
  searchProduct(currentPage);
});
