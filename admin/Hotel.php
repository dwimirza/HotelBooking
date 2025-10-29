<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Hotels - Travelista Admin</title>
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
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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
    include './includes/functions.php';
    include './includes/manageHotel.php';
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
    $hotels = getHotels($conn);
  ?>
  <script src="includes/Hotel.js"></script>

  <div class="modal" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content w-100">
        <div class="modal-header">
          <h5 class="modal-title" id="editRoomModalLabel">Edit Rooms</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Hidden input to attach current hotel id -->
          <input type="hidden" id="hotelId" name="hotelId">
          <!-- Room form fields for adding/editing a room -->
          <form id="editRoomForm" onsubmit="return false;">
            <input type="hidden" id="roomId" name="roomId">
            <div class="mb-3">
              <label for="room_type" class="form-label">Room Type</label>
              <input required type="text" class="form-control" id="room_type" name="room_type" required>
            </div>
            <div class="mb-3">
              <label for="price" class="form-label">Price</label>
              <input required type="number" min="0" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
              <label for="availability" class="form-label">Room Availability</label>
              <input type="number" min="0" class="form-control" id="availability" name="availability" required>
            </div>
            <!-- Add room button to append row to preview list -->
            <button type="button" onclick='AddRoom()' class="btn btn-dark mb-2" id="addRoomBtn">Add Room</button>
          </form>
          <!-- Preview table/list of existing and newly added rooms -->
          <h6 class="mt-3">Current Rooms</h6>
          <div id="roomRowsPreview">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick='closeModal()' data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div id="editModal" class="modal">
    <div class="modal-dialog modal-lg ">
      <div class="modal-content w-100 h-100">
        <div class="modal-header">
          <h5 class="modal-title" id="editHotelModalLabel">Edit Hotel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" id="editHotelForm">
            <input type="hidden" id="hotelId" name="hotelId">

            <div class="mb-3">
              <label for="hotelName" class="form-label">Name</label>
              <input type="text" class="form-control" id="hotelName" name="hotelName" required>
            </div>

            <div class="mb-3">
              <label for="hotelAddress" class="form-label">Address</label>
              <textarea class="form-control" id="hotelAddress" name="hotelAddress" rows="2" required></textarea>
            </div>

            <div class="mb-3">
              <label for="hotelPhone" class="form-label">Phone No.</label>
              <input type="tel" class="form-control" id="hotelPhone" name="hotelPhone" required>
            </div>

            <div class="mb-3">
              <label for="hotelEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="hotelEmail" name="hotelEmail" required>
            </div>

            <div class="mb-3">
              <label for="hotelRating" class="form-label">Star Rating</label>
              <select class="form-select" id="hotelRating" name="hotelRating" required>
                <option value="">Select rating</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
            <div class="mb-3">
              <h5 class="modal-header">Hotel Facilities</h5>

              <div class="addon-group mb-3 mt-3">
                <h6>Swimming Pool</h6>
                <input type="radio" name="swimming_pool" id="edit_pool_0" value="0">
                <label for="edit_pool_0">Not Available</label>
                <input type="radio" name="swimming_pool" id="edit_pool_1" value="1">
                <label for="edit_pool_1">Available</label>
              </div>

              <div class="addon-group mb-3">
                <h6>Gymnasium</h6>
                <input type="radio" name="gymnasium" id="edit_gym_0" value="0">
                <label for="edit_gym_0">Not Available</label>
                <input type="radio" name="gymnasium" id="edit_gym_1" value="1">
                <label for="edit_gym_1">Available</label>
              </div>

              <div class="addon-group mb-3">
                <h6>Wi-fi</h6>
                <input type="radio" name="wifi" id="edit_wifi_0" value="0">
                <label for="edit_wifi_0">Not Available</label>
                <input type="radio" name="wifi" id="edit_wifi_1" value="1">
                <label for="edit_wifi_1">Available</label>
              </div>

              <div class="addon-group mb-3">
                <h6>Room Service</h6>
                <input type="radio" name="room_service" id="edit_room_0" value="0">
                <label for="edit_room_0">Not Available</label>
                <input type="radio" name="room_service" id="edit_room_1" value="1">
                <label for="edit_room_1">Available</label>
              </div>

              <div class="addon-group mb-3">
                <h6>Air Conditioning</h6>
                <input type="radio" name="air_condition" id="edit_ac_0" value="0">
                <label for="edit_ac_0">Not Available</label>
                <input type="radio" name="air_condition" id="edit_ac_1" value="1">
                <label for="edit_ac_1">Available</label>
              </div>

              <div class="addon-group mb-3">
                <h6>Breakfast</h6>
                <input type="radio" name="breakfast" id="edit_breakfast_0" value="0">
                <label for="edit_breakfast_0">Not Available</label>
                <input type="radio" name="breakfast" id="edit_breakfast_1" value="1">
                <label for="edit_breakfast_1">Available</label>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()" data-bs-dismiss="modal">Close</button>
          <button type="button" onclick="UpdateHotel()" class="btn btn-primary" id="saveHotelBtn">Save changes</button>
        </div>
        </form>
      </div>
    </div>


  </div>

  <div class="wrapper">
    <?php  include 'includes/Sidebar.php'; include 'includes/Header.php'; ?>

   

      <div class="container">
        <div class="page-inner">
          <div class="page-header">
            <h3 class="fw-bold mb-3">Manage Hotels</h3>
            <div class="ms-md-auto py-2 py-md-0">
              <a href="add-hotel.php" class="btn btn-primary btn-round">New Hotel</a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Hotel List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Adress</th>
                          <th>Phone No.</th>
                          <th>Email</th>
                          <th>Star Rating</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($hotels as $hotel): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($hotel['hotel_name']) ?></td>
                          <td><?php echo htmlspecialchars($hotel['address']) ?></td>
                          <td><?php echo htmlspecialchars($hotel['phone_no']) ?></td>
                          <td><?php echo htmlspecialchars($hotel['email']) ?></td>
                          <td><?php echo htmlspecialchars($hotel['star_rating']) ?></td>
                          <td><a onclick="openModal(<?php echo $hotel['hotel_id']?>)"
                              class="btn btn-primary me-2">Edit</a>
                            <a onclick="openRoomModal(<?php echo $hotel['hotel_id']?>)" class="btn btn-dark me-2">Edit
                              Rooms</a><a onclick="confirmDeleteHotel(<?php echo $hotel['hotel_id']?>)"
                              class="btn btn-danger">Delete</a></td>
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
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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

        // SweetAlert confirm for hotel delete
        window.confirmDeleteHotel = function(hotelId) {
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = './includes/delete.php?type=hotel&id=' + hotelId;
            }
          });
        };
      });
    </script>
</body>

</html>