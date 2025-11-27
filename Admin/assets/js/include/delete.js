permenantdeleteData = (id, table, id_fild) => {
  Swal.fire({
    title: "Are you sure? this recode will delete permenantly",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      var data = {
        id: id,
        table: table,
        id_fild: id_fild,
      };

      console.log(data);

      $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=permanantDeleteData",
        data: data,
        success: function ($data) {
          console.log($data);
          successToastDelete();
        },
        error: function (error) {
          console.log(`Error ${error}`);
        },
      });
      Swal.fire("Deleted!", "Your file has been deleted.", "success");
    }
  });
};

deleteData = (id, table, id_fild) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      var data = {
        id: id,
        table: table,
        id_fild: id_fild,
      };

      console.log("Delete data:", data);
      
      // Direct AJAX call (callDeleteRequest may not be loaded yet)
      $.ajax({
        method: "POST",
        url: "../server/api.php?function_code=deleteData",
        data: data,
        dataType: "json",
        success: function (response) {
          console.log("Delete success response:", response);
          if (response && response.success) {
            successToastDelete();
          } else {
            errorMessage(response.error || "Failed to delete item.");
          }
        },
        error: function (xhr, status, error) {
          console.log("Delete error:", error);
          console.log("Response:", xhr.responseText);
          // Try to parse response even if error
          try {
            var response = JSON.parse(xhr.responseText);
            if (response && response.success) {
              successToastDelete();
              return;
            }
          } catch (e) {
            // Not JSON, continue with error message
          }
          errorMessage("Failed to delete item. Please try again.");
        },
      });
    }
  });
};
