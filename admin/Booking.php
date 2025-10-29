<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>bookings - Travelista Admin</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Public Sans:300,400,500,600,700"]
      },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["assets/css/fonts.min.css"],
      },
      active: function () {
        sessionStorage.fonts = true;
      },
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/plugins.min.css" />
  <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  

  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link rel="stylesheet" href="assets/css/demo.css" />
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background: #fff;
      margin: 10% auto;
      padding: 22px;
      border-radius: 8px;
      width: 350px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      text-align: left;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 32px;
      cursor: pointer;
    }

    .close:hover {
      color: #000;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
  <?php
    include './includes/functions.php';
    include './includes/manageBooking.php';
     session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login' || $_SESSION['role'] != 'admin'){
        header("Location: ../Hotel-Booking_System/index.php");
        exit();
    }
    if (isset($_GET['message'])) {
    $msg = htmlspecialchars($_GET['message']); // sanitize for security
    echo "<script>alert('$msg');</script>";
    } elseif (isset($_GET['error'])) {
    $msg = htmlspecialchars($_GET['error']); // sanitize for security
    echo "<script>alert('$msg');</script>"; 
    }   
    $bookings = getBookings($conn);
  ?>
  <script src="includes/Booking.js"></script>

  <div class="modal" id="editDetailModal" tabindex="-1" aria-labelledby="editDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content w-100">
      <div class="modal-header">
        <h5 class="modal-title" id="editDetailsModalLabel">Edit Room Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="bookingId" name="bookingId" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
        
        <form id="editRoomDatesForm" action="update-room-dates.php" method="POST">
          <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
          <div class="modal-body" id="roomDetailList"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


  <div class="wrapper">
  <?php  include 'includes/Sidebar.php';
  include 'includes/Header.php'; ?>

      <div class="container">
        <div class="page-inner">
          <div class="page-header">
            <h3 class="fw-bold mb-3">Manage Bookings</h3>
            
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Booking List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Name</th>
                          <th>Hotel Name</th>
                          <th>Booking Date</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($bookings as $booking): ?>
                          <td class="details-control"><a class="btn btn-primary btn-sm">Details</a></td>
                          <td><?php echo htmlspecialchars($booking['name']) ?></td>
                          <td><?php echo htmlspecialchars($booking['hotel_name']) ?></td>
                          <td><?php echo htmlspecialchars($booking['booking_date']) ?></td>
                          <td><?php $status = htmlspecialchars($booking['status']); $booking_id = $booking['booking_id']; $badgeClass = ''; switch (strtolower($status)) 
                          { case 'pending': $badgeClass = 'badge-warning'; break; case 'confirmed': 
                          $badgeClass = 'badge-success'; break; case 'cancelled':
                           $badgeClass = 'badge-danger'; break; case 'completed':
                            $badgeClass = 'badge-primary'; break; default: $badgeClass = 'badge-secondary'; } echo "<span class='badge $badgeClass' id='status-badge-$booking_id'>$status</span>"; ?></td>
                          <td>
                              <select class="form-select status-select" data-booking-id="<?php echo $booking['booking_id']; ?>">
                                <option value="pending" <?php echo (strtolower($booking['status']) == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo (strtolower($booking['status']) == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="cancelled" <?php echo (strtolower($booking['status']) == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                <option value="completed" <?php echo (strtolower($booking['status']) == 'completed') ? 'selected' : ''; ?>>Completed</option>
                              </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <footer class="footer">
        <div class="container-fluid d-flex justify-content-between">
          <nav class="pull-left">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link" href="http://www.themekita.com">
                  ThemeKita
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"> Help </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"> Licenses </a>
              </li>
            </ul>
          </nav>
          <div class="copyright">
            2024, made with <i class="fa fa-heart heart text-danger"></i> by
            <a href="http://www.themekita.com">ThemeKita</a>
          </div>
          <div>
            Distributed by
            <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
          </div>
        </div>
      </footer>
    </div>


    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src=" assets/js/setting-demo2.js"></script>
    
    <script>
      var bookingDetails = <?php echo json_encode(array_values($bookings)); ?>;
      const idr = new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0, // Rupiah typically has no minor units
      maximumFractionDigits: 0,
      });
// Function to format child row content
function format(details, totalAmount) {
    var html = '<div></div>';
    if(details && details.length) {
        html += '<table class=" table table-striped no-footer" style="">';
        html += '<tr><th></th><th>Room Type</th><th>Price/Night</th><th>Check In</th><th>Check Out</th><th></th></tr>';
        details.forEach(function(det){
            html += '<tr>'
                 + '<td>' + '' + '</td>'
                 + '<td>' + det.room_type + '</td>'
                 + '<td>' + idr.format(det.price_per_night) + '</td>'
                 + '<td>' + det.check_in + '</td>'
                 + '<td>' + det.check_out + '</td>'
                //  + '<td>' + '<a onclick="openDetailModal(1)" class="btn btn-primary me-2" style="user-select: auto;">Edit</a>' + '</td>'
                 + '</tr>';
        });
        html += '</table>';
    } else {
        html += '<div>No extra details.</div>';
    }
    return html;
}

$(document).ready(function () {
    var table = $("#basic-datatables").DataTable();

    // Add child row toggle event
    $('#basic-datatables tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var rowIdx = row.index();

        if (row.child.isShown()) {
            // Close the child row
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open child row
            var booking = bookingDetails[rowIdx];
            row.child(format(booking.details, booking.total_amount)).show();
            tr.addClass('shown');
        }
    });

    // ----------------------
    // Your other DataTables code can follow below
    // ----------------------

    $("#multi-filter-select").DataTable({
      pageLength: 5,
      initComplete: function () {
        this.api()
          .columns()
          .every(function () {
            var column = this;
            var select = $(
                '<select class="form-select"><option value=""></option></select>'
                )
                .appendTo($(column.footer()).empty())
                .on("change", function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
                });
            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append(
                  '<option value="' + d + '">' + d + "</option>"
                );
              });
          });
        },
    });

    $("#add-row").DataTable({
      pageLength: 5,
    });

    var action =
      '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

    $("#addRowButton").click(function () {
      $("#add-row")
        .dataTable()
        .fnAddData([
          $("#addName").val(),
          $("#addPosition").val(),
          $("#addOffice").val(),
          action,
        ]);
      $("#addRowModal").modal("hide");
    });

$('.status-select').on('change', function () {
            var bookingId = $(this).data('booking-id');
            var newStatus = $(this).val();
            var badge = $('#status-badge-' + bookingId);

            // Show SweetAlert confirm before updating
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to change the booking status to '" + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + "'?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './includes/manageBooking.php',
                        type: 'POST',
                        data: {
                            action: 'updateBooking',
                            booking_id: bookingId,
                            status: newStatus
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                // Update badge class and text
                                badge.removeClass('badge-success badge-danger badge-warning badge-secondary badge-primary');
                                if (newStatus === 'confirmed') {
                                    badge.addClass('badge badge-success');
                                } else if (newStatus === 'cancelled') {
                                    badge.addClass('badge badge-danger');
                                } else if (newStatus === 'pending') {
                                    badge.addClass('badge badge-warning');
                                } else if (newStatus === 'completed') {
                                    badge.addClass('badge badge-primary');
                                } else {
                                    badge.addClass('badge-secondary');
                                }
                                badge.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                                Swal.fire('Updated!', 'Booking status has been updated.', 'success');
                            } else {
                                Swal.fire('Error!', 'Failed to update status: ' + data.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Error updating status.', 'error');
                        }
                    });
                } else {
                    // Reset the select to original value if cancelled
                    $(this).val($(this).find('option[selected]').val());
                }
            });
        });
});
    </script>
</body>

</html>