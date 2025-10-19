<?php session_start();
include '../database.php'; 

$isLoggedIn = isset($_SESSION['status']) && $_SESSION['status'] === 'login';
$userName = $isLoggedIn ? $_SESSION['name'] : '';

$show_payment_modal = false;
$booking_data = null;

if (isset($_GET['show_payment']) && $_GET['show_payment'] == '1' && isset($_SESSION['booking_info'])) {
    $show_payment_modal = true;
    $booking_data = $_SESSION['booking_info'];
}

// Get hotel_id from URL parameter
$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0;

//validate hotel_id
if ($hotel_id <= 0) {
    header("Location: hotels.php");
    exit();
}

// Fetch hotel data
$sql_hotel = "SELECT h.*, 
              hf.swimming_pool, hf.gymnasium, hf.wifi, 
              hf.room_service, hf.air_condition, hf.breakfast
              FROM hotels h
              LEFT JOIN hotel_facilities hf ON h.hotel_id = hf.hotel_id
              WHERE h.hotel_id = ?";

$stmt = $conn->prepare($sql_hotel);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result_hotel = $stmt->get_result();
$hotel = $result_hotel->fetch_assoc();

if (!$hotel) {
    header("Location: hotels.php");
    exit();
}

// Get hotel image
$hotelImages = [
    1 => 'd1.jpg',
    2 => 'd2.jpg',
    3 => 'd3.jpg',
    4 => 'd4.jpg',
    5 => 'd5.jpg',  
    6 => 'd6.jpg',
    7 => 'd1.jpg',
    8 => 'd2.jpg',
    9 => 'd3.jpg',
    10 => 'd4.jpg',
    11 => 'd5.jpg',
    12 => 'd6.jpg',
];

function getHotelImageDetail($hotel_id, $hotelImages) {
    if (isset($hotelImages[$hotel_id])) {
        return $hotelImages[$hotel_id];
    }
    $imageIndex = (($hotel_id - 1) % 6) + 1;
    return 'd' . $imageIndex . '.jpg';
}

$hotelImageName = getHotelImageDetail($hotel_id, $hotelImages);


// Fetch room types for this hotel
$sql_rooms = "SELECT * FROM rooms WHERE hotel_id = ? AND availability > 0";
$stmt_rooms = $conn->prepare($sql_rooms);
$stmt_rooms->bind_param("i", $hotel_id);
$stmt_rooms->execute();
$result_rooms = $stmt_rooms->get_result();
$rooms = $result_rooms->fetch_all(MYSQLI_ASSOC);


// Set default dates
$default_checkin = date('D, d M Y');
$default_checkout = date('D, d M Y', strtotime('+1 day'));
$default_nights = 1;

// Calculate default price
$default_price = !empty($rooms) ? $rooms[0]['price'] : 0;
$tax = 150000;
$default_total = ($default_price * $default_nights) + $tax;

$conn->close();
?>
<!DOCTYPE html>
	<html lang="zxx" class="no-js">
	<head>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="img/fav.png">
		<!-- Author Meta -->
		<meta name="author" content="colorlib">
		<!-- Meta Description -->
		<meta name="description" content="">
		<!-- Meta Keyword -->
		<meta name="keywords" content="">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>Travel</title>

		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
			<!--
			CSS
			============================================= -->
			<link rel="stylesheet" href="css/linearicons.css">
			<link rel="stylesheet" href="css/font-awesome.min.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/magnific-popup.css">
			<link rel="stylesheet" href="css/jquery-ui.css">				
			<link rel="stylesheet" href="css/nice-select.css">							
			<link rel="stylesheet" href="css/animate.min.css">
			<link rel="stylesheet" href="css/owl.carousel.css">				
			<link rel="stylesheet" href="css/main.css">
			<link rel="stylesheet" href="css/detail.css">
            <link rel="stylesheet" href="css/payment.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		</head>
		<body>	
			<header id="header" class="header-static">
				<div class="header-top">
					<div class="container">
						<div class="row align-items-center">
							<div class="col-lg-6 col-sm-6 col-6 header-top-left">
								<ul>
									<li><a href="#">Visit Us</a></li>
									<li><a href="#">Buy Tickets</a></li>
								</ul>			
							</div>
							<div class="col-lg-6 col-sm-6 col-6 header-top-right">
								<div class="header-social">
									<a href="#"><i class="fa fa-facebook"></i></a>
									<a href="#"><i class="fa fa-twitter"></i></a>
									<a href="#"><i class="fa fa-dribbble"></i></a>
									<a href="#"><i class="fa fa-behance"></i></a>
								</div>
							</div>
						</div>			  					
					</div>
				</div>
				<div class="container main-menu">
					<div class="row align-items-center justify-content-between d-flex">
						<div id="logo">
							<a href="index.html"><img src="img/logo.png" alt="" title="" /></a>
						</div>
						<nav id="nav-menu-container">
							<ul class="nav-menu">
								<li><a href="index.html">Home</a></li>
								<li><a href="about.html">About</a></li>
								<li><a href="packages.html">Packages</a></li>
								<li><a href="hotels.html">Hotels</a></li>
								<li><a href="insurance.html">Insurence</a></li>
								<li class="menu-has-children"><a href="">Blog</a>
									<ul>
										<li><a href="blog-home.html">Blog Home</a></li>
										<li><a href="blog-single.html">Blog Single</a></li>
									</ul>
								</li>	
								<li class="menu-has-children"><a href="">Pages</a>
									<ul>
										<li><a href="elements.html">Elements</a></li>
										<li class="menu-has-children"><a href="">Level 2 </a>
											<ul>
												<li><a href="#">Item One</a></li>
												<li><a href="#">Item Two</a></li>
											</ul>
										</li>					                		
									</ul>
								</li>					          					          		          
								<?php if ($isLoggedIn): ?>
							<li class="menu-has-children">
								<a href="">
									<i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($userName); ?>
								</a>
								<ul>
									<li><a href="../functions/logout.php">Log Out</a></li>
								</ul>
							</li>
						<?php else: ?>
							<li><a href="#" onclick="openLoginModal(); return false;">Login</a></li>
						<?php endif; ?>
							</ul>
						</nav><!-- #nav-menu-container -->					      		  
					</div>
				</div>
			</header>
			<!-- #header -->
<!-- Booking Progress -->
    <div class="booking-progress">
        <div class="container">
            <div class="progress-steps">
                <div class="progress-step active">
                    <div class="step-number">1</div>
                    <span>Pesan</span>
                </div>
                <div class="progress-step">
                    <div class="step-number">2</div>
                    <span>Bayar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Title -->
    <div class="container page-title">
        <h2>Pemesanan Akomodasi</h2>
        <p>Pastikan kamu mengisi semua informasi di halaman ini benar sebelum melanjutkan ke pembayaran.</p>
    </div>

    <!-- Main Content -->
    <div class="container pb-5">
        <div class="row">
            <!-- Left Side - Booking Form -->
            <div class="col-lg-7">
                <!-- Promo Banner -->

                <!-- Guest Information Form -->
                <div class="booking-form-section">
                    <h3 class="form-section-title">Data Pemesan (untuk E-voucher)</h3>
                    <p class="form-hint mb-4">Isi semua kolom dengan benar untuk memastikan kamu dapat menerima voucher konfirmasi pemesanan di email yang dicantumkan.</p>

                    <form id="bookingForm" method="POST" action="../functions/process-booking.php">
                        <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
                        <input type="hidden" name="checkin_date" id="checkinInput">
                        <input type="hidden" name="checkout_date" id="checkoutInput">
                        <input type="hidden" name="nights" id="nightsInput" value="1">
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap (sesuai KTP/Paspor/SIM)</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Contoh: John Maeda" required>
                            <div class="form-hint">Gunakan huruf alfabet (A-Z), tanpa tanda baca, dan gelar.</div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: email@example.com" required>
                            <div class="form-hint">E-voucher akan dikirim ke email ini.</div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label class="form-label">Nomor Handphone</label>
                            <div class="phone-input-group">
                                <select class="form-select country-code" name="country_code">
                                    <option value="+62">+62</option>
                                    <option value="+1">+1</option>
                                    <option value="+44">+44</option>
                                    <option value="+61">+61</option>
                                </select>
                                <input type="tel" name="phone" class="form-control" placeholder="81234567890" required>
                            </div>
                            <div class="form-hint">Contoh: +62812345678, untuk Kode Negara (+62) dan No. Handphone 081234567</div>
                        </div>
                        
                        <!-- Room Type Selection -->
                        <div class="mb-4">
                            <label class="form-label mb-3">Pilih Tipe Kamar</label>
                            <?php if (!empty($rooms)): ?>
                                <div class="room-type-selection">
                                    <?php foreach ($rooms as $index => $room): ?>
                                        <div class="room-type-card">
                                            <label class="room-type-label">
                                                <input type="radio" 
                                                       name="room_id" 
                                                       value="<?php echo $room['room_id']; ?>" 
                                                       data-price="<?php echo $room['price']; ?>"
                                                       data-type="<?php echo htmlspecialchars($room['room_type']); ?>"
                                                       class="room-radio"
                                                       <?php echo $index === 0 ? 'checked' : ''; ?>
                                                       required>
                                                <div class="room-type-content">
                                                    <div class="room-type-header">
                                                        <h4 class="room-type-name"><?php echo htmlspecialchars($room['room_type']); ?></h4>
                                                        <div class="room-type-price">
                                                            <span class="price-label">Harga per malam</span>
                                                            <span class="price-value">Rp <?php echo number_format($room['price'], 0, ',', '.'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="room-type-status">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span><?php echo htmlspecialchars($room['availability']); ?></span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Maaf, tidak ada kamar yang tersedia saat ini.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Number of Guests -->
                        <!-- Number of Guests -->
                        <div class="mb-4">
                            <label class="form-label mb-3">Jumlah Tamu</label>
                            
                            <div class="guest-counter">
                                <label>Dewasa</label>
                                <div class="counter-controls">
                                    <button type="button" class="counter-btn" id="adultMinus">−</button>
                                        <span class="counter-value" id="adultCount">2</span>
                                    <button type="button" class="counter-btn" id="adultPlus">+</button>
                                </div>
                            </div>
                            <input type="hidden" name="adult_count" id="adultInput" value="2">

                            <div class="guest-counter">
                                <label>Anak-anak (0-17 tahun)</label>
                                <div class="counter-controls">
                                    <button type="button" class="counter-btn" id="childMinus">−</button>
                                        <span class="counter-value" id="childCount">1</span>
                                    <button type="button" class="counter-btn" id="childPlus">+</button>
                                </div>
                            </div>
                            <input type="hidden" name="child_count" id="childInput" value="1">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side - Hotel Detail -->
            <div class="col-lg-5">
                <div class="hotel-detail-card">
                    <!-- Hotel Images -->
                    <!-- Hotel Images -->
                    <div class="hotel-images">
                        <img src="img/hotels/<?php echo $hotelImageName; ?>" alt="<?php echo htmlspecialchars($hotel['hotel_name']); ?>">
                    </div>
                    <!-- Hotel Details -->
                    <div class="hotel-detail-content">
                        <div class="hotel-header">
                            <h3 class="hotel-name"><?php echo htmlspecialchars($hotel['hotel_name']); ?></h3>
                            <div class="hotel-rating">
                                <div class="stars">
                                    <?php 
                                    for ($i = 0; $i < $hotel['star_rating']; $i++) {
                                        echo '<i class="fa fa-star checked"></i>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="reviews-count">(5,172 reviews)</div>
                        </div>

                        <!-- Booking Details -->
                        <div class="booking-details">
                            <div class="booking-detail-row">
                                <span class="label">Check-in</span>
                                <div class="value">
                                    <input type="text" class="date-picker-input" id="checkinDate" placeholder="Pilih tanggal" readonly>
                                    <small class="time-info">Dari 14:00</small>
                                </div>
                            </div>
                            <div class="booking-detail-row">
                                <span class="label">Check-out</span>
                                <div class="value">
                                    <input type="text" class="date-picker-input" id="checkoutDate" placeholder="Pilih tanggal" readonly>
                                    <small class="time-info">Sebelum 12:00</small>
                                </div>
                            </div>
                            <div class="booking-detail-row">
                                <span class="label">Duration</span>
                                <span class="value" id="durationDisplay"><?php echo $default_nights; ?> Night</span>
                            </div>
                        </div>

                        <!-- Room Information -->
                        <div class="room-info">
                            <div class="room-type" id="selectedRoomType">
                                    <?php echo !empty($rooms) ? '(1x) ' . htmlspecialchars($rooms[0]['room_type']) : 'Pilih tipe kamar'; ?>
                            </div>
                            <div class="room-features">
                                <div class="room-feature">
                                    <i class="fas fa-user"></i>
                                    <span id="totalGuests">2 Tamu</span>
                                </div>
                                <div class="room-feature">
                                    <i class="fas fa-child"></i>
                                    <span id="totalChildren">1 anak</span>
                                </div>
                            </div>
                            <?php if ($hotel['swimming_pool'] == 1 || $hotel['gymnasium'] == 1): ?>
                            <div class="room-features">
                                <?php if ($hotel['swimming_pool'] == 1 ): ?>
                                <div class="room-feature">
                                    <i class="fa-solid fa-person-swimming"></i>
                                    <span>Swimming Pool</span>
                                </div>
                                <?php endif; ?>

                                <?php if ($hotel['gymnasium'] == 1): ?>
                                <div class="room-feature">
                                    <i class="fa-solid fa-dumbbell"></i>
                                    <span>Gym</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($hotel['wifi'] == 1 || $hotel['room_service'] == 1): ?>
                            <div class="room-features">
                                <?php if ($hotel['wifi'] == 1): ?>
                                <div class="room-feature">
                                    <i class="fas fa-wifi"></i>
                                    <span>WiFi Gratis</span>
                                </div>
                                <?php endif; ?>

                                <?php if ($hotel['room_service'] == 1): ?>
                                <div class="room-feature">
                                    <i class="fa-solid fa-suitcase"></i>
                                    <span>Room Service</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($hotel['air_condition'] == 1 || $hotel['breakfast'] == 1): ?>
                            <div class="room-features">
                                <?php if ($hotel['air_condition'] == 1): ?>
                                <div class="room-feature">
                                    <i class="fas fa-snowflake"></i>
                                    <span>AC</span>
                                </div>
                                <?php endif; ?>

                                <?php if ($hotel['breakfast'] == 1): ?>
                                <div class="room-feature">
                                    <i class="fa-solid fa-bell-concierge"></i>
                                    <span>Breakfast</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Price Summary -->
                        <div class="price-summary">
                            <div class="price-row">
                                <span class="label">Harga per malam</span>
                                <span class="value" id="pricePerNight">Rp <?php echo number_format($default_price, 0, ',', '.'); ?></span>
                            </div>
                            <div class="price-row">
                                <span class="label">Pajak dan biaya lainnya</span>
                                <span class="value">Rp <?php echo number_format($tax, 0, ',', '.'); ?></span>
                            </div>
                            <div class="price-row total">
                                <span class="label">Total</span>
                                <span class="value" id="totalPrice">Rp <?php echo number_format($default_total, 0, ',', '.'); ?></span>
                            </div>
                        </div>

                        <button type="submit" class="btn-continue" form="bookingForm">Booking</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Payment Modal Overlay -->
<div id="paymentModal" class="payment-modal-overlay" style="display:none;">
    <div class="payment-modal-card">
        <span class="modal-close" onclick="closePaymentModal()">&times;</span>
        
        <div class="payment-header">
            <h2><i class="fas fa-credit-card"></i> Pembayaran</h2>
            <p>Booking ID: #<span id="bookingIdValue"><?php echo $show_payment_modal ? $booking_data['booking_id'] : ''; ?></span></p>
        </div>

        <!-- Booking Summary -->
        <div class="booking-summary">
            <h3>Detail Pemesanan</h3>
            <div class="summary-row">
                <span class="label">Hotel:</span>
                <span class="value"><?php echo htmlspecialchars($hotel['hotel_name']); ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Tipe Kamar:</span>
                <span class="value" id="modalRoomType"><?php echo $show_payment_modal ? htmlspecialchars($booking_data['room_type']) : ''; ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Check-in:</span>
                <span class="value" id="modalCheckin"><?php echo $show_payment_modal ? date('d M Y', strtotime($booking_data['checkin'])) : ''; ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Check-out:</span>
                <span class="value" id="modalCheckout"><?php echo $show_payment_modal ? date('d M Y', strtotime($booking_data['checkout'])) : ''; ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Jumlah Malam:</span>
                <span class="value" id="modalNights"><?php echo $show_payment_modal ? $booking_data['nights'] . ' malam' : ''; ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Tamu:</span>
                <span class="value"><span id="modalAdults"><?php echo $show_payment_modal ? $booking_data['adults'] : ''; ?></span> dewasa, <span id="modalChildren"><?php echo $show_payment_modal ? $booking_data['children'] : ''; ?></span> anak</span>
            </div>
            <hr>
            <div class="summary-row">
                <span class="label">Harga Kamar:</span>
                <span class="value" id="modalRoomPrice"><?php echo $show_payment_modal ? 'Rp ' . number_format($booking_data['room_price'] * $booking_data['nights'], 0, ',', '.') : ''; ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Pajak & Biaya:</span>
                <span class="value">Rp 150.000</span>
            </div>
            <div class="summary-row total">
                <span class="label">Total Pembayaran:</span>
                <span class="value" id="modalTotalPrice"><?php echo $show_payment_modal ? 'Rp ' . number_format($booking_data['total'], 0, ',', '.') : ''; ?></span>
            </div>
        </div>

        <!-- Payment Form -->
        <form id="paymentFormModal" method="POST" action="../functions/process-payment.php">
            <input type="hidden" name="booking_id" id="modalBookingIdInput" value="<?php echo $show_payment_modal ? $booking_data['booking_id'] : ''; ?>">
            <input type="hidden" name="amount" id="modalAmountInput" value="<?php echo $show_payment_modal ? $booking_data['total'] : ''; ?>">

            <h3 class="payment-method-title">Metode Pembayaran</h3>
            
            <div class="payment-methods">
                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="credit_card" checked required>
                    <div class="method-content">
                        <i class="fas fa-credit-card"></i>
                        <span>Kartu Kredit / Debit</span>
                    </div>
                </label>

                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="bank_transfer" required>
                    <div class="method-content">
                        <i class="fas fa-university"></i>
                        <span>Transfer Bank</span>
                    </div>
                </label>

                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="e_wallet" required>
                    <div class="method-content">
                        <i class="fas fa-wallet"></i>
                        <span>E-Wallet (OVO, GoPay, Dana)</span>
                    </div>
                </label>
            </div>

            <div class="payment-actions">
                <button type="button" class="btn-back" onclick="closePaymentModal()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="submit" class="btn-pay">
                    <i class="fas fa-check"></i> Bayar Sekarang
                </button>
            </div>
        </form>
    </div>
    </div>
			<script src="js/vendor/jquery-2.2.4.min.js"></script>
			<script src="js/popper.min.js"></script>
			<script src="js/vendor/bootstrap.min.js"></script>			
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>		
 			<script src="js/jquery-ui.js"></script>					
  			<script src="js/easing.min.js"></script>			
			<script src="js/hoverIntent.js"></script>
			<script src="js/superfish.min.js"></script>	
			<script src="js/jquery.ajaxchimp.min.js"></script>
			<script src="js/jquery.magnific-popup.min.js"></script>						
			<script src="js/jquery.nice-select.min.js"></script>					
			<script src="js/owl.carousel.min.js"></script>							
			<script src="js/mail-script.js"></script>	
			<script src="js/main.js"></script>
            <script src="js/detail.js"></script>
		</body>
    </html>