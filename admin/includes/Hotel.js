    let roomData = [];
    let editingIdx = null;

    function openModal(hotelId) {
        // Show modal
        document.getElementById('editModal').style.display = 'block';


        // Call PHP and autofill modal inputs
        fetch('includes/managehotel.php?action=getHotelData&id=' + hotelId)
            .then(response => response.json())
            .then(data => {
                console.log(data[0].breakfast);
                // Fill modal fields with data from PHP
                document.getElementById('hotelId').value = data[0].hotel_id || '';
                document.getElementById('hotelName').value = data[0].hotel_name || '';
                document.getElementById('hotelAddress').value = data[0].address || '';
                document.getElementById('hotelPhone').value = data[0].phone_no || '';
                document.getElementById('hotelEmail').value = data[0].email || '';
                document.getElementById('hotelRating').value = data[0].star_rating || '';
      
                document.getElementById('edit_pool_' + data[0].swimming_pool).checked = true;
                document.getElementById('edit_gym_' + data[0].gymnasium).checked = true;
                document.getElementById('edit_wifi_' + data[0].wifi).checked = true;
                document.getElementById('edit_room_' + data[0].room_service).checked = true;
                document.getElementById('edit_ac_' + data[0].air_condition).checked = true;
                document.getElementById('edit_breakfast_' + data[0].breakfast).checked = true;


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

    function openRoomModal(hotelId) {
        document.getElementById('editRoomModal').style.display = 'block';
        document.getElementById('hotelId').value = hotelId;

        fetch('includes/managehotel.php?action=getRoomData&id=' + hotelId)
            .then(response => response.json())
            .then(data => {
                roomData = data;
                renderRoomTable();

            })
            .catch(error => console.error('Error:', error));

    }

    window.onclick = function (event) {
        var modal = document.getElementById('editRoomModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    function UpdateHotel() {
        // Show SweetAlert confirm before updating
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this hotel?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
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
                            Swal.fire('Updated!', 'Hotel has been updated.', 'success');
                            // Optionally hide modal or refresh list
                            location.reload();
                        } else {
                            Swal.fire('Error!', 'Failed to update hotel.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Error submitting update.', 'error');
                    });
            }
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
        const roomType = document.getElementById('room_type').value.trim();
        const price = parseFloat(document.getElementById('price').value);
        const availability = parseInt(document.getElementById('availability').value);

        // Client-side validation
        if (!roomType) {
            Swal.fire('Error!', 'Room type is required.', 'error');
            return;
        }
        if (isNaN(price) || price <= 0) {
            Swal.fire('Error!', 'Price must be a number greater than 0.', 'error');
            return;
        }
        if (isNaN(availability) || availability < 0) {
            Swal.fire('Error!', 'Availability must be a number 0 or greater.', 'error');
            return;
        }

        // Show SweetAlert confirm before adding
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to add this room?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.isConfirmed) {
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
                            Swal.fire('Added!', 'Room has been added.', 'success');
                            // Optionally hide modal or refresh list
                            reloadRoomTable(hotelId);
                        } else {
                            Swal.fire('Error!', 'Failed to add room.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Error submitting room.', 'error');
                    });
            }
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
        // Show SweetAlert confirm before deleting
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
                // Delete via AJAX GET request
                fetch('includes/delete.php?type=room&id=' + room_id)
                    .then(response => {
                        if (response.ok) {
                            // After successful delete, reload the room table
                            Swal.fire('Deleted!', 'Room has been deleted.', 'success');
                            reloadRoomTable(hotelId);
                        } else {
                            Swal.fire('Error!', 'Failed to delete room.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        Swal.fire('Error!', 'Error deleting room.', 'error');
                    });
            }
        });
    }
    function UpdateRoom(room_id, hotel_id) {
        // Collect current edit input values
        const roomType = document.getElementById('edit_room_type').value.trim();
        const price = parseFloat(document.getElementById('edit_price').value);
        const availability = parseInt(document.getElementById('edit_availability').value);

        // Client-side validation
        if (!roomType) {
            Swal.fire('Error!', 'Room type is required.', 'error');
            return;
        }
        if (isNaN(price) || price <= 0) {
            Swal.fire('Error!', 'Price must be a number greater than 0.', 'error');
            return;
        }
        if (isNaN(availability) || availability < 0) {
            Swal.fire('Error!', 'Availability must be a number 0 or greater.', 'error');
            return;
        }

        // Show SweetAlert confirm before updating
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this room?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
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
                        Swal.fire('Updated!', 'Room has been updated.', 'success');
                        editingIdx = null;
                        reloadRoomTable(hotel_id);  // Re-fetch the latest table data
                    } else {
                        Swal.fire('Error!', 'Failed to update room.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Error submitting update.', 'error');
                });
            }
        });
    }
