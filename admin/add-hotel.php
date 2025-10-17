<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Add Hotel - Travelista Admin</title>
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
    if (isset($_GET['message'])) {
    $msg = htmlspecialchars($_GET['message']); // sanitize for security
    echo "<script>alert('$msg');</script>";
    } elseif (isset($_GET['error'])) {
    $msg = htmlspecialchars($_GET['error']); // sanitize for security
    echo "<script>alert('$msg');</script>"; 
    }   
  ?>
    <script>
        function openModal(hotelId) {
            // Show modal
            document.getElementById('editModal').style.display = 'block';


            // Call PHP and autofill modal inputs
            fetch('includes/managehotel.php?action=getHotelData&id=' + hotelId)
                .then(response => response.json())
                .then(data => {
                    // Fill modal fields with data from PHP
                    document.getElementById('hotelId').value = data[0].hotel_id || '';
                    document.getElementById('hotelName').value = data[0].hotel_name || '';
                    document.getElementById('hotelAddress').value = data[0].address || '';
                    document.getElementById('hotelPhone').value = data[0].phone_no || '';
                    document.getElementById('hotelEmail').value = data[0].email || '';
                    document.getElementById('hotelRating').value = data[0].star_rating || '';
                })
                .catch(error => console.error('Error:', error));
        }


        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        window.onclick = function (event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function UpdateHotel() {
            // Collect input values
            const hotelId = document.getElementById('hotelId').value;
            const hotelName = document.getElementById('hotelName').value;
            const hotelAddress = document.getElementById('hotelAddress').value;
            const hotelPhone = document.getElementById('hotelPhone').value;
            const hotelEmail = document.getElementById('hotelEmail').value;
            const hotelRating = document.getElementById('hotelRating').value;
            // Build form data
            const formData = new FormData();
            formData.append('action', 'updateHotel');
            formData.append('hotelId', hotelId);
            formData.append('name', hotelName);
            formData.append('address', hotelAddress);
            formData.append('phoneNo', hotelPhone);
            formData.append('email', hotelEmail);
            formData.append('starRating', hotelRating);
            console.log(formData);

            // AJAX POST to manageHotel.php
            fetch('includes/manageHotel.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hotel updated successfully
                        alert("Hotel updated!");
                        // Optionally hide modal or refresh list
                        location.reload();
                    } else {
                        alert("Failed to update hotel.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Error submitting update. : " + error);
                });
        };
    </script>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="../index.html" class="logo">
                        <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"
                            height="20" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse active" id="dashboard">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="index.php">
                                            <span class="sub-item">Dashboard 1</span>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="hotel.php">
                                            <span class="sub-item">Hotels</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Components</h4>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#base">
                                <i class="fas fa-layer-group"></i>
                                <p>Base</p>
                                <span class="caret"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarLayouts">
                                <i class="fas fa-th-list"></i>
                                <p>Sidebar Layouts</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="sidebarLayouts">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../sidebar-style-2.html">
                                            <span class="sub-item">Sidebar Style 2</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="../icon-menu.html">
                                            <span class="sub-item">Icon Menu</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#forms">
                                <i class="fas fa-pen-square"></i>
                                <p>Forms</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="forms">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../forms/forms.html">
                                            <span class="sub-item">Basic Form</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item submenu">
                            <a data-bs-toggle="collapse" href="#tables">
                                <i class="fas fa-table"></i>
                                <p>Tables</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse " id="tables">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../tables/tables.html">
                                            <span class="sub-item">Basic Table</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="../tables/datatables.html">
                                            <span class="sub-item">Datatables</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#maps">
                                <i class="fas fa-map-marker-alt"></i>
                                <p>Maps</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="maps">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../maps/googlemaps.html">
                                            <span class="sub-item">Google Maps</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="../maps/jsvectormap.html">
                                            <span class="sub-item">Jsvectormap</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#charts">
                                <i class="far fa-chart-bar"></i>
                                <p>Charts</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../charts/charts.html">
                                            <span class="sub-item">Chart Js</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="../charts/sparkline.html">
                                            <span class="sub-item">Sparkline</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="../widgets.html">
                                <i class="fas fa-desktop"></i>
                                <p>Widgets</p>
                                <span class="badge badge-success">4</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../../documentation/index.html">
                                <i class="fas fa-file"></i>
                                <p>Documentation</p>
                                <span class="badge badge-secondary">1</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#submenu">
                                <i class="fas fa-bars"></i>
                                <p>Menu Levels</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="submenu">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a data-bs-toggle="collapse" href="#subnav1">
                                            <span class="sub-item">Level 1</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse" id="subnav1">
                                            <ul class="nav nav-collapse subnav">
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="collapse" href="#subnav2">
                                            <span class="sub-item">Level 1</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse" id="subnav2">
                                            <ul class="nav nav-collapse subnav">
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="sub-item">Level 1</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Manage Hotels</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Add Hotel</div>
                                </div>
                                <div class="card-body">
                                    <form action="includes/manageHotel.php" method="post" id="addHotelForm">
                                        <input type="hidden" name="action" value="addHotel">
                                        <div class="form-group">
                                            <label for="hotel_name">Hotel Name</label>
                                            <input type="text" class="form-control" id="hotel_name" name="hotel_name"
                                                required placeholder="Enter hotel name">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="2" required
                                                placeholder="Enter address"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_no">Phone No</label>
                                            <input type="tel" class="form-control" id="phone_no" name="phone_no"
                                                required placeholder="Enter phone number">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required
                                                placeholder="Enter email">
                                        </div>
                                        <div class="form-group">
                                            <label for="star_rating">Star Rating</label>
                                            <select class="form-select" id="star_rating" name="star_rating" required>
                                                <option value="">Select rating</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" required
                                                placeholder="Enter city">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Hotel Addons</label>

                                            <div class="addon-group mb-3">
                                                <h6>Swimming Pool</h6>
                                                <input type="radio" name="swimming_pool" id="pool_no" value="0" checked>
                                                <label for="pool_no">Not Available</label>
                                                <input type="radio" name="swimming_pool" id="pool_yes" value="1"> <label
                                                    for="pool_yes">Available</label>
                                            </div>

                                            <div class="addon-group mb-3">
                                                <h6>Gymnasium</h6>
                                                <input type="radio" name="gymnasium" id="gym_no" value="0" checked>
                                                <label for="gym_no">Not Available</label>
                                                <input type="radio" name="gymnasium" id="gym_yes" value="1"> <label
                                                    for="gym_yes">Available</label>
                                            </div>

                                            <div class="addon-group mb-3">
                                                <h6>Wi-fi</h6>
                                                <input type="radio" name="wifi" id="wifi_no" value="0" checked> <label
                                                    for="wifi_no">Not Available</label>
                                                <input type="radio" name="wifi" id="wifi_yes" value="1"> <label
                                                    for="wifi_yes">Available</label>
                                            </div>

                                            <div class="addon-group mb-3">
                                                <h6>Room Service</h6>
                                                <input type="radio" name="room_service" id="room_no" value="0" checked>
                                                <label for="room_no">Not Available</label>
                                                <input type="radio" name="room_service" id="room_yes" value="1"> <label
                                                    for="room_yes">Available</label>
                                            </div>

                                            <div class="addon-group mb-3">
                                                <h6>Air Conditioning</h6>
                                                <input type="radio" name="air_condition" id="ac_no" value="0" checked>
                                                <label for="ac_no">Not Available</label>
                                                <input type="radio" name="air_condition" id="ac_yes" value="1"> <label
                                                    for="ac_yes">Available</label>
                                            </div>

                                            <div class="addon-group mb-3">
                                                <h6>Breakfast</h6>
                                                <input type="radio" name="breakfast" id="breakfast_no" value="0"
                                                    checked> <label for="breakfast_no">Not Available</label>
                                                <input type="radio" name="breakfast" id="breakfast_yes" value="1">
                                                <label for="breakfast_yes">Available</label>
                                            </div>
                                        </div>

                                        <div class="card-action mt-3">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="reset" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </form>
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
                                        var val = $.fn.dataTable.util.escapeRegex($(this)
                                            .val());

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
                                            '<option value="' + d + '">' + d +
                                            "</option>"
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
            });
        </script>
</body>

</html>