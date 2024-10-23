$(document).ready(function () {
  $.ajax({
    url: "./server-proxy.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.sizes.length === 0) {
        window.location.reload();
      }

      console.log(response);

      let id = 1;
      let baseSku = "1200004161159682";
      let title = response.title;
      let price = response.price;

      let productTableHTML = "";

      response.colors.forEach((color) => {
        response.sizes.forEach((size) => {
          let childProductName = `${title} - ${color} - ${size}`;
          let priceUSD = convertPriceToUsd(price);
          let imageUrl =
            response.images.length > 0 && id <= response.images.length
              ? response.images[id - 1]
              : "";

          let sku = baseSku + id;

          productTableHTML += `
                        <tr>
                            <td>${id}</td>
                            <td>${sku}</td>
                            <td>${childProductName}</td>
                            <td>${priceUSD} USD</td>
                            <td><img src="${imageUrl}" alt="Product Image" width="100%"></td>
                            <td>${color}</td>
                            <td>${size}</td>
                        </tr>`;
          id++;
        });
      });

      $("#productResults").html(productTableHTML);
      $("#demo").text("");
    },
    error: function () {
      alert("Error occurred while fetching the product info.");
    },
  });

  function convertPriceToUsd(priceString) {
    const regex = /\d+/;
    const match = priceString.match(regex);

    if (match) {
      const rubles = parseInt(match[0], 10);
      const priceUSD = rubles * 0.01;
      return priceUSD;
    }

    return 0;
  }
});
