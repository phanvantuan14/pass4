$(document).ready(function () {
  let currentPage = 1;
  let totalPages = 0;
  let maxProduct = 5;
  let isFilter = false;

  function viewProductList(products) {
    console.log(products);

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
        alert("Error occurred while fetching products.");
      },
    });
  }

  function searchProduct() {
    $("#searchInput").on("keyup", function () {
      isFilter = false;
      let query = $(this).val();

      if (query !== "") {
        $.ajax({
          url: "core.php",
          type: "GET",
          data: {
            search: query,
          },
          success: function (response) {
            let products = JSON.parse(response);
            console.log(products);
            viewProductList(products);
          },
          error: function () {
            $("#searchResults").html("<p>Error fetching data</p>");
          },
        });
      } else {
        $("#searchResults").html("");
        loadProducts(currentPage);
      }
    });
  }

  function filterProduct() {
    $("#filterButton").on("click", function (e) {
      e.preventDefault();
      isFilter = true;
      loadFilteredProducts(1);
    });
  }

  function loadFilteredProducts(currentPage) {
    let selectedCategories = [];
    $('input[name="categories[]"]:checked').each(function () {
      selectedCategories.push($(this).val());
    });

    let selectedTags = [];
    $('input[name="tags[]"]:checked').each(function () {
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
      page: currentPage,
    };

    // if (formData.sort_by === "date" && formData.sort_order === "DESC") {
    //   if (
    //     formData.categories.length === 0 &&
    //     formData.tags.length === 0 &&
    //     formData.date_from === "" &&
    //     formData.date_to === "" &&
    //     formData.price_from === "" &&
    //     formData.price_to === ""
    //   ) {
    //     isFilter = false;
    //     loadProducts(formData.currentPage);
    //   }
    // }

    $.ajax({
      url: "core.php",
      type: "GET",
      data: $.extend({ "filter-product": true }, formData),
      success: function (response) {
        const data = JSON.parse(response);
        const products = data.products;

        console.log(products);

        const { productsToShow, totalPages } = getProductsForPage(
          products,
          currentPage
        );

        viewProductList(productsToShow);
        updatePagination(totalPages, currentPage);
      },
      error: function () {
        alert("Error occurred while fetching products.");
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

  function handlePageNum() {
    $(document).on("click", ".page-number", function () {
      currentPage = parseInt($(this).text());
      if (isFilter) {
        loadFilteredProducts(currentPage);
      } else {
        loadProducts(currentPage);
      }
    });
  }
  handlePageNum();

  function prevPage() {
    $(document).on("click", "#prevPage", function () {
      if (currentPage > 1) {
        currentPage--;
        if (isFilter) {
          loadFilteredProducts(currentPage);
        } else {
          loadProducts(currentPage);
        }
      }
    });
  }
  prevPage();

  function nextPage() {
    $(document).on("click", "#nextPage", function () {
      if (currentPage < totalPages) {
        currentPage++;
        if (isFilter) {
          loadFilteredProducts(currentPage);
        } else {
          loadProducts(currentPage);
        }
      }
    });
  }
  nextPage();

  function addProduct() {
    $("#addProductForm").on("submit", function (event) {
      event.preventDefault();

      var formData = new FormData(this);

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
              $(".modal-succ").text("Thêm thành công").show();
              getStatus();

              $("#addProductForm")[0].reset();
              $("#featured_image_preview").attr("src", "");
              $("#gallery_images_preview").empty();

              formData = {};
              loadProducts(currentPage);
            } else {
              $(".modal-succ")
                .text(data.message || "Thêm thất bại")
                .show();
              getStatus();
            }
          } catch (error) {
            $(".modal-succ").text("Có lỗi xảy ra.").show();
            getStatus();
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

      var formData = new FormData(this);
      formData.append("action", "add-property");

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
              $(".modal-succ").text("Thêm thành công").show();
              getStatus();

              $("#addPropertyForm")[0].reset();
              formData = {};
            } else {
              $(".modal-succ").text("Thêm thất bại").show();
              getStatus();
            }
          } catch (error) {
            $(".modal-succ").text("Có lỗi xảy ra.").show();
            getStatus();
          }
        },
        error: function () {
          alert("Có lỗi xảy ra trong quá trình thêm thuộc tính.");
        },
      });
    });
  }
  addProperty();

  function updateProduct() {
    $("#editProductForm").on("submit", function (event) {
      event.preventDefault();

      var formData = new FormData(this);

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
            // const data = JSON.parse(response);
            if (data.status === "success") {
              $(".modal-succ").text("Update thành công").show();
              getStatus();
              formData = {};
              loadProducts(currentPage);
            } else {
              $(".modal-succ")
                .text(data.message | "Update thất bại")
                .show();
              getStatus();
            }
          } catch (error) {
            $(".modal-succ").text("Có lỗi xảy ra.").show();
            getStatus();
          }
        },
        error: function () {
          alert("Có lỗi xảy ra trong quá trình edit sản phẩm.");
        },
      });
    });
  }
  updateProduct();

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
            alert("Error occurred while deleting the product.");
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
            alert("Xóa tất cả sản phẩm thất bại!");
          }
        },
        error: function () {
          alert("Đã xảy ra lỗi khi xóa tất cả sản phẩm.");
        },
      });
    });
  }
  deleteAllProducts();

  function getStatus() {
    let $succ = $(".modal-succ");
    if ($succ.length) {
      setTimeout(function () {
        $succ.fadeOut(1000);
      }, 2000);
    }
  }

  loadProducts(currentPage);
  filterProduct();
  searchProduct();
});
