document.addEventListener('DOMContentLoaded', () => {
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    const currentUsername = localStorage.getItem('username');

    if (isLoggedIn !== 'true' || !currentUsername) {
        window.location.href = 'login/login.html';
        return;
    }

    document.getElementById('profUsername').value = currentUsername;
    document.getElementById('sidebarUsername').textContent = currentUsername;

    fetch(`get_profile.php?username=${currentUsername}&t=${Date.now()}`, { cache: "no-store" })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('profEmail').value = data.data.email;
                if (data.data.full_name) {
                    document.getElementById('profFullName').value = data.data.full_name;
                }
                
                const avatarPath = `uploads/avatars/${data.data.profile_picture}?t=${Date.now()}`;
                document.getElementById('sidebarAvatar').src = avatarPath;
                document.getElementById('mainAvatar').src = avatarPath;
            }
        })
        .catch(err => console.error("Error fetching profile:", err));

    const avatarInput = document.getElementById('avatarInput');
    avatarInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mainAvatar').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const msgBox = document.getElementById('profile-message');
        const submitBtn = this.querySelector('.save-btn');

        submitBtn.disabled = true;
        submitBtn.textContent = "Saving...";
        msgBox.style.display = 'none';

        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            msgBox.style.display = 'block';
            if (data.success) {
                msgBox.style.color = '#28a745';
                msgBox.textContent = data.message;
                
                if (data.new_avatar) {
                    document.getElementById('sidebarAvatar').src = `uploads/avatars/${data.new_avatar}`;
                }
            } else {
                msgBox.style.color = '#dc3545';
                msgBox.textContent = data.message;
            }
        })
        .catch(err => {
            msgBox.style.display = 'block';
            msgBox.style.color = 'red';
            msgBox.textContent = "Server Error!";
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = "Save Changes";
        });
    });

    document.getElementById('logoutBtn').addEventListener('click', () => {
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('username');
        localStorage.removeItem('isAdmin');
        window.location.href = 'login/register.html';
    });

    const menuItems = document.querySelectorAll('.sidebar-menu li');

    const profileSection = document.getElementById('profileSection'); 
    const savedCarsSection = document.getElementById('savedCarsSection');
    const testDrivesSection = document.getElementById('testDrivesSection');

    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            profileSection.style.display = 'none';
            savedCarsSection.style.display = 'none';
            testDrivesSection.style.display = 'none';

            if (this.textContent === 'Saved Vehicles') {
                savedCarsSection.style.display = 'block';
                loadSavedCars(); 
            } else if (this.textContent === 'Profile Info') {
                profileSection.style.display = 'block';
            } else if (this.textContent === 'Test Drives') {
                testDrivesSection.style.display = 'block';
                loadTestDrives();
            }
        });
    });

    function loadSavedCars() {
        const container = document.getElementById('savedCarsContainer');
        container.innerHTML = '<p>Loading your garage...</p>';

        fetch(`get_saved_cars.php?username=${currentUsername}&t=${Date.now()}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = ''; 
                
                if (data.success && data.cars.length > 0) {
                    data.cars.forEach(car => {
                        const carDiv = document.createElement('div');
                        carDiv.className = 'saved-car-item';
                        
                        carDiv.innerHTML = `
                            <span>${car.title}</span>
                            <span class="arrow-icon">→</span>
                        `;
                        
                        carDiv.style.cursor = "pointer";
                        carDiv.addEventListener('click', () => {
                            window.location.href = `pages/${car.page_filename}`;
                        });
                        
                        container.appendChild(carDiv);
                    });
                } else {
                    container.innerHTML = '<p>You have no saved cars yet. Go explore!</p>';
                }
            })
            .catch(err => {
                container.innerHTML = '<p style="color:red;">Error loading cars.</p>';
                console.error(err);
            });
    }

    function loadTestDrives() {
        const container = document.getElementById('testDrivesContainer');
        container.innerHTML = '<p>Loading your schedule...</p>';

        fetch(`get_test_drives.php?username=${currentUsername}&t=${Date.now()}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = ''; 
                
                if (data.success && data.drives.length > 0) {
                    data.drives.forEach(drive => {
                        const driveDiv = document.createElement('div');
                        driveDiv.className = 'test-drive-item';
                        
                        const dateObj = new Date(drive.date);
                        const formattedDate = dateObj.toLocaleDateString('uk-UA');
                        const formattedTime = drive.time.substring(0, 5); 
                        
                        const statusClass = drive.status.toLowerCase() === 'confirmed' ? 'status-confirmed' : 'status-pending';

                        driveDiv.innerHTML = `
                            <div class="test-drive-info">
                                <h3>${drive.car_title}</h3>
                                <p style="display: flex; align-items: center; gap: 5px; margin: 0;">
                                    <img src="SVGs/calendar.svg" alt="Дата" width="16" height="16">
                                    ${formattedDate} &nbsp;|&nbsp; 
                                    <img src="SVGs/clock.svg" alt="Час" width="16" height="16">
                                    ${formattedTime}
                                </p>
                            </div>
                            <div class="status-badge ${statusClass}">
                                ${drive.status}
                            </div>
                        `;
                        container.appendChild(driveDiv);
                    });
                } else {
                    container.innerHTML = '<p>You have no scheduled test drives yet.</p>';
                }
            })
            .catch(err => {
                container.innerHTML = '<p style="color:red;">Error loading test drives.</p>';
                console.error(err);
            });
    }
});