$(document).ready(function () {
  $("#featured_image_file").on("change", function () {
    var file = this.files[0];

    if (file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#featured_image_preview").attr("src", e.target.result).show();
      };

      reader.readAsDataURL(file);
    }
  });

  $("#featured_image_file-edit").on("change", function () {
    var file = this.files[0];

    if (file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#featured_image_preview-edit").attr("src", e.target.result).show();
      };

      reader.readAsDataURL(file);
    }
  });

  $("#gallery_images_file").on("change", function () {
    $("#gallery_images_preview").empty();

    $.each(this.files, function (index, file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        var img = $("<img>")
          .attr("src", e.target.result)
          .css({ width: "100px", height: "auto", margin: "5px" });
        $("#gallery_images_preview").append(img);
      };

      reader.readAsDataURL(file);
    });
  });

  $("#gallery_images_file-edit").on("change", function () {
    $("#gallery_images_preview-edit").empty();

    $.each(this.files, function (index, file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        var img = $("<img>")
          .attr("src", e.target.result)
          .css({ width: "100px", height: "auto", margin: "5px" });
        $("#gallery_images_preview-edit").append(img);
      };

      reader.readAsDataURL(file);
    });
  });
});
