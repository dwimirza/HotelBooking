    let roomData = [];
    let editingIdx = null;

    function openModal(bookingId) {
        // Show modal
        document.getElementById('editModal').style.display = 'block';


        // Call PHP and autofill modal inputs
        fetch('includes/manageBooking.php?action=getBookingData&id=' + bookingId)
            .then(response => response.json())
            .then(data => {
                // data is an array of booking objects, each with details array
                data.forEach(item => {
                    console.log(item);
                    // Fill modal fields with data from PHP
                    document.getElementById('bookingId').value = item.booking_id || '';
                    document.getElementById('name').value = item.name || '';
                    document.getElementById('hotelName').value = item.hotel_name || '';
                    document.getElementById('status').value = (item.status && item.status.charAt(0).toUpperCase() + item.status.slice(1).toLowerCase()) || '';

                    // Since details may have multiple, take the first check_in and check_out
                    if (item.details && item.details.length > 0) {
                        document.getElementById('checkIn').value = item.details[0].check_in || '';
                        document.getElementById('checkOut').value = item.details[0].check_out || '';
                    }
                });
            })
            .catch(error => console.error('Error:', error));
    }



    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('editRoomModal').style.display = 'none';
    }
    window.onclick = function (event) {
        var modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function openDetailModal(bookingId) {
        const booking = bookingDetails[bookingIndex];
        document.getElementById('bookingId').value = booking.booking_id;

        let html = '';
        booking.details.forEach((detail, idx) => {
            html += `
      <div class="card mb-2">
        <div class="card-body">
          <div><strong>Room Type:</strong> ${detail.room_type}</div>
          <div><strong>Price/Night:</strong> ${detail.price_per_night}</div>
          <input type="hidden" name="details[${idx}][room_id]" value="${detail.room_id}">
          <div class="mb-2">
            <label>Check In</label>
            <input type="date" class="form-control" name="details[${idx}][check_in]" value="${detail.check_in}">
          </div>
          <div class="mb-2">
            <label>Check Out</label>
            <input type="date" class="form-control" name="details[${idx}][check_out]" value="${detail.check_out}">
          </div>
        </div>
      </div>
    `;
        });
        document.getElementById('roomDetailList').innerHTML = html;
        $('#editRoomModal').modal('show');

    }

    window.onclick = function (event) {
        var modal = document.getElementById('editRoomModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    function UpdateHotel() {
        // Collect input values
        const hotelId = document.getElementById('hotelId').value;
        const hotelName = document.getElementById('hotelName').value;
        const hotelAddress = document.getElementById('hotelAddress').value;
        const hotelPhone = document.getElementById('hotelPhone').value;
        const hotelEmail = document.getElementById('hotelEmail').value;
        const hotelRating = document.getElementById('hotelRating').value;

        const swimmingPool = document.querySelector('input[name="swimming_pool"]:checked').value;
        const gymnasium = document.querySelector('input[name="gymnasium"]:checked').value;
        const wifi = document.querySelector('input[name="wifi"]:checked').value;
        const roomService = document.querySelector('input[name="room_service"]:checked').value;
        const airCondition = document.querySelector('input[name="air_condition"]:checked').value;
        const breakfast = document.querySelector('input[name="breakfast"]:checked').value;

        // Build form data
        const formData = new FormData();
        formData.append('action', 'updateHotel');
        formData.append('hotelId', hotelId);
        formData.append('name', hotelName);
        formData.append('address', hotelAddress);
        formData.append('phoneNo', hotelPhone);
        formData.append('email', hotelEmail);
        formData.append('starRating', hotelRating);
        formData.append('swimming_pool', swimmingPool);
        formData.append('gymnasium', gymnasium);
        formData.append('wifi', wifi);
        formData.append('room_service', roomService);
        formData.append('air_condition', airCondition);
        formData.append('breakfast', breakfast);
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

    function renderRoomTable() {
        const container = document.getElementById('roomRowsPreview');
        let html = "<table class='table'><thead><tr>" +
            "<th>Room Type</th><th>Price</th><th>Availability</th><th>Actions</th></tr></thead><tbody>";
        roomData.forEach((room, idx) => {
            if (editingIdx === idx) {
                // Editing: show input fields and Save/CANCEL
                html += `<tr>
        <td>
        <input class="form-control form-control-sm" type="text" id="edit_room_type" value="${room.room_type}"></td>
        <td><input class="form-control form-control-sm" type="number" id="edit_price" value="${room.price}"></td>
        <td><input class="form-control form-control-sm" type="number" id="edit_availability" value="${room.availability}"></td>
        <td>
          <button class='btn btn-success btn-sm' onclick='UpdateRoom(${room.room_id}, ${room.hotel_id})'>Save</button>
          <button class='btn btn-secondary btn-sm' onclick='cancelRoomEdit()'>Cancel</button>
        </td>
      </tr>`;
            } else {
                // Default: show data and EDIT/DELETE
                html += `<tr>
        <td>${room.room_type}</td>
        <td>${room.price}</td>
        <td>${room.availability}</td>
        <td>
          <button class='btn btn-info btn-sm' onclick='editRoomRow(${idx})'>Edit</button>
          <button class='btn btn-danger btn-sm' onclick='deleteRoom(${room.room_id}, ${room.hotel_id})'>Delete</button>
        </td>
      </tr>`;
            }
        });
        html += "</tbody></table>";
        container.innerHTML = html;
    }

    function editRoomRow(idx) {
        editingIdx = idx;
        renderRoomTable();
    }

    function cancelRoomEdit() {
        editingIdx = null;
        renderRoomTable();
    }

    function AddRoom() {
        // Collect input values
        const hotelId = document.getElementById('hotelId').value;
        const roomType = document.getElementById('room_type').value;
        const price = document.getElementById('price').value;
        const availability = document.getElementById('availability').value;
        // Build form data
        const formData = new FormData();
        formData.append('action', 'addRoom');
        formData.append('hotel_id', hotelId);
        formData.append('room_type', roomType);
        formData.append('price', price);
        formData.append('availability', availability);

        // AJAX POST to manageHotel.php
        fetch('includes/manageHotel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    // Hotel updated successfully
                    alert("Hotel room added!");
                    // Optionally hide modal or refresh list
                    reloadRoomTable(hotelId);
                } else {
                    alert("Failed to add room.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error submitting room. : " + error);
            });
    };

    function reloadRoomTable(hotelId) {
        fetch('includes/managehotel.php?action=getRoomData&id=' + hotelId)
            .then(response => response.json())
            .then(data => {
                roomData = data;
                renderRoomTable(); // Your function to update HTML table
            })
            .catch(error => console.error('Error:', error));
    }

    function deleteRoom(room_id, hotelId) {
        // Delete via AJAX GET request
        fetch('includes/delete.php?type=room&id=' + room_id)
            .then(response => {
                if (response.ok) {
                    // After successful delete, reload the room table
                    reloadRoomTable(hotelId);
                } else {
                    alert('Failed to delete room.');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Error deleting room.');
            });
    }

    function UpdateRoom(room_id, hotel_id) {
        // Collect current edit input values
        const roomType = document.getElementById('edit_room_type').value;
        const price = document.getElementById('edit_price').value;
        const availability = document.getElementById('edit_availability').value;

        // Build form data
        const formData = new FormData();
        formData.append('action', 'updateRoom');
        formData.append('room_id', room_id);
        formData.append('hotel_id', hotel_id);
        formData.append('room_type', roomType);
        formData.append('price', price);
        formData.append('availability', availability);

        // AJAX POST to manageHotel.php (or manageRoom.php)
        fetch('includes/manageHotel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Room updated!");
                    editingIdx = null;
                    reloadRoomTable(hotel_id); // Re-fetch the latest table data
                } else {
                    alert("Failed to update room.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error submitting update. : " + error);
            });
    }