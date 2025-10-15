// Date Picker Configuration
$(document).ready(function() {
    // Initialize date pickers
    $('#checkinDate').datepicker({
        dateFormat: 'D, dd M yy',
        minDate: 0, // Tidak bisa pilih tanggal sebelum hari ini
        onSelect: function(dateText, inst) {
            // Set minimum checkout date = checkin date + 1 day
            var checkinDate = $(this).datepicker('getDate');
            checkinDate.setDate(checkinDate.getDate() + 1);
            $('#checkoutDate').datepicker('option', 'minDate', checkinDate);
            
            // Update display
            updateDateDisplay();
            calculateDuration();
        }
    });

    $('#checkoutDate').datepicker({
        dateFormat: 'D, dd M yy',
        minDate: 1, // Minimum 1 hari dari sekarang
        onSelect: function(dateText, inst) {
            updateDateDisplay();
            calculateDuration();
        }
    });

    // Set default dates (today and tomorrow)
    var today = new Date();
    var tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    $('#checkinDate').datepicker('setDate', today);
    $('#checkoutDate').datepicker('setDate', tomorrow);
    
    updateDateDisplay();
    calculateDuration();
});

// Update date display format
function updateDateDisplay() {
    var checkin = $('#checkinDate').val();
    var checkout = $('#checkoutDate').val();
    
    if (checkin) {
        $('#checkinDate').val(checkin);
    }
    if (checkout) {
        $('#checkoutDate').val(checkout);
    }
}

// Calculate duration between check-in and check-out
function calculateDuration() {
    var checkinDate = $('#checkinDate').datepicker('getDate');
    var checkoutDate = $('#checkoutDate').datepicker('getDate');
    
    if (checkinDate && checkoutDate) {
        var timeDiff = checkoutDate.getTime() - checkinDate.getTime();
        var nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (nights > 0) {
            $('#durationNights').text(nights + ' Night');
            updateTotalPrice(nights);
        }
    }
}

// Update total price based on nights
function updateTotalPrice(nights) {
    var pricePerNight = 850000;
    var tax = 150000;
    var totalPrice = (pricePerNight * nights) + tax;
    
    // Format to Rupiah
    var formattedPrice = 'Rp ' + totalPrice.toLocaleString('id-ID');
    $('.price-row.total .value').text(formattedPrice);
}