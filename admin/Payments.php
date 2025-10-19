<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Payments - Travelista Admin</title>
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
</head>

<body>
  <?php
   session_start();
    include './includes/functions.php';
    include './includes/manageHotel.php';
    include './includes/database.php';
    if (isset($_GET['message'])) {
    $msg = htmlspecialchars($_GET['message']); // sanitize for security
    echo "<script>alert('$msg');</script>";
    } elseif (isset($_GET['error'])) {
    $msg = htmlspecialchars($_GET['error']); // sanitize for security
    echo "<script>alert('$msg');</script>"; 
    }   
    $payments = getPayments($conn);
  ?>
  <script src="includes/Hotel.js"></script>

  <div class="wrapper">
    <?php  include 'includes/Sidebar.php'; include 'includes/Header.php';?>

      <div class="container">
        <div class="page-inner">
          <div class="page-header">
            <h3 class="fw-bold mb-3">Payments</h3>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Transaction List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Booking Id</th>
                          <th>Payment Method</th>
                          <th>Amount</th>
                          <th>Payment date</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($payment['booking_id']) ?></td>
                          <td><?php echo htmlspecialchars($payment['payment_method']) ?></td>
                          <td>RP <?php echo number_format($payment['amount'], '0' ,',', '.') ?></td>
                          <td><?php echo htmlspecialchars($payment['payment_date']) ?></td>
                            <td> <span class="badge <?php
                              $status = strtolower($payment['status']);
                              if ($status == 'paid') {
                                echo 'badge-success';
                              } elseif ($status == 'failed') {
                                echo 'badge-danger';
                              } elseif ($status == 'pending') {
                                echo 'badge-warning';
                              } elseif ($status == 'refunded') {
                                echo 'badge-secondary';
                              } else {
                                echo 'badge-secondary';
                              }
                            ?>" id="status-badge-<?php echo $payment['booking_id']; ?>"><?php echo htmlspecialchars($payment['status']); ?></span></td>
                            <td>
                              <select class="form-select status-select" data-booking-id="<?php echo $payment['booking_id']; ?>">
                                <option value="paid" <?php echo (strtolower($payment['status']) == 'paid') ? 'selected' : ''; ?>>Paid</option>
                                <option value="pending" <?php echo (strtolower($payment['status']) == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="failed" <?php echo (strtolower($payment['status']) == 'failed') ? 'selected' : ''; ?>>Failed</option>
                                <option value="refunded" <?php echo (strtolower($payment['status']) == 'refunded') ? 'selected' : ''; ?>>Refunded</option>
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
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

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

        // Add Row
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

        // Handle status select change
        $('.status-select').on('change', function() {
          var bookingId = $(this).data('booking-id');
          var newStatus = $(this).val();
          var badge = $('#status-badge-' + bookingId);

          // Show SweetAlert confirm before updating
          Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to change the payment status to '" + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + "'?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: './includes/managePayments.php',
                type: 'POST',
                data: {
                  action: 'updatePayment',
                  payment_id: bookingId,
                  status: newStatus
                },
                success: function(response) {
                  var data = JSON.parse(response);
                  if (data.success) {
                    // Update badge class and text
                    badge.removeClass('badge-success badge-danger badge-warning badge-secondary');
                    if (newStatus === 'paid') {
                      badge.addClass('badge-success');
                    } else if (newStatus === 'failed') {
                      badge.addClass('badge-danger');
                    } else if (newStatus === 'pending') {
                      badge.addClass('badge-warning');
                    } else if (newStatus === 'refunded') {
                      badge.addClass('badge-secondary');
                    }
                    badge.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    Swal.fire('Updated!', 'Payment status has been updated.', 'success');
                  } else {
                    Swal.fire('Error!', 'Failed to update status: ' + data.message, 'error');
                  }
                },
                error: function() {
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