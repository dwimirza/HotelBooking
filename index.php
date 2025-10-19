<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travelista - Register</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
			<link rel="stylesheet" href="Hotel-Booking-System/css/linearicons.css">
			<link rel="stylesheet" href="Hotel-Booking-System/css/font-awesome.min.css">
			<link rel="stylesheet" href="Hotel-Booking-System/css/bootstrap.css">
			<link rel="stylesheet" href="Hotel-Booking-System/css/magnific-popup.css">
			<link rel="stylesheet" href="Hotel-Booking-System/css/jqueawry-ui.css">				
			<link rel="stylesheet" href="Hotel-Booking-System/css/nice-select.css">							
			<link rel="stylesheet" href="Hotel-Booking-System/css/animate.min.css">
			<link rel="stylesheet" href="Hotel-Booking-System/css/owl.carousel.css">				
			<link rel="stylesheet" href="Hotel-Booking-System/css/main.css">
</head>

<body style="margin:0; height:100vh; display:flex; justify-content:center; align-items:center; background-color: #f4f4f4;">
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow" style="width: 24rem;">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">Login</h3>
        <form action="/HotelBooking/functions/login.php" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Username</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Username">
          </div>    
          <div class="mb-3">
            <label for="password_hash" class="form-label">Password</label>
            <input type="password" class="form-control" name="password_hash" id="password_hash" placeholder="Enter password">
          </div>
          <a href="register.php">Didnt have an account? Register</a>
          <button type="submit" class="primary-btn text-uppercase w-100">Login</button>
        </form>
        <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
              <?php endif; ?>
      </div>
    </div>
  </div>

</body>

</html>