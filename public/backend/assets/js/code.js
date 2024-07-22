// $(function () {
//     // CSRF Token
//     $.ajaxSetup({
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//     });

//     $(document).on("click", "#delete", function (e) {
//         e.preventDefault();
//         var link = $(this).attr("href");

//         Swal.fire({
//             title: "Are you sure?",
//             text: "You won't be able to revert this!",
//             icon: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#3085d6",
//             cancelButtonColor: "#d33",
//             confirmButtonText: "Yes, delete it!",
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 Swal.fire({
//                     title: "Deleted!",
//                     text: "Your file has been deleted.",
//                     icon: "success",
//                 });
//             }
//         });
//     });
// });
$(document).ready(function() {
  // CSRF Token
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  // Sweet alert
  $('body').on('click', '#delete', function(e) {
      e.preventDefault();
      let deleteUrl = $(this).attr('href');
      //console.log(deleteUrl);
      Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  type: "DELETE",
                  url: deleteUrl,
                  data: {_token: "{{csrf_token()}}"},
                  success: function(data) {
                      if (data.status == 'error') {
                          Swal.fire(
                              'You cannot delete this Item!',
                              'This Category contains items cant delete!',
                              'error',
                          )
                      } else {
                          Swal.fire({
                              title: "Deleted!",
                              text: "Your file has been deleted.",
                              icon: "success"
                          });
                          window.location.reload();
                      }

                  },
                  error: function(xhr, status, error) {
                      console.log(error);
                  }
              })


          }
      });
  })
})
