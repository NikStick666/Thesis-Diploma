document.addEventListener('DOMContentLoaded', () => {
    const adminPanel = document.getElementById('adminPanel');
    const isAdmin = localStorage.getItem('isAdmin'); 

    if (isAdmin === 'true') {
        if (adminPanel) adminPanel.classList.remove('hidden'); 
        document.body.classList.add('is-admin');
    } else {
        if (adminPanel) adminPanel.classList.add('hidden'); 
        document.body.classList.remove('is-admin');
    }

    loadCarsFromDB();
    setupDragAndDrop();
});

let fileToUpload = null; 

function loadCarsFromDB() {
    const listContainer = document.getElementById('carContainer'); 
    if (!listContainer) return;

    const categoryInput = document.getElementById('carCategory');
    const category = categoryInput ? categoryInput.value : 'electric';
    
    fetch(`get_cars.php?category=${category}`) 
        .then(response => response.json())
        .then(cars => {
            const currentUsername = localStorage.getItem('username');
            const isLoggedIn = localStorage.getItem('isLoggedIn');

            if (isLoggedIn === 'true' && currentUsername) {
                fetch(`get_saved_cars.php?username=${currentUsername}&t=${Date.now()}`)
                    .then(res => res.json())
                    .then(savedData => {
                        const savedCarIds = (savedData.success && savedData.cars) 
                            ? savedData.cars.map(c => c.id) 
                            : [];
                        
                        cars.forEach(car => {
                            const isSaved = savedCarIds.includes(car.id);
                            createCarHTML(car.id, car.title, car.image_path, car.page_filename, isSaved);
                        });
                    })
                    .catch(err => console.error("Помилка завантаження збережених авто:", err));
            } else {
                cars.forEach(car => {
                    createCarHTML(car.id, car.title, car.image_path, car.page_filename, false);
                });
            }
        })
        .catch(err => console.error("Error loading cars:", err));
}

function createCarHTML(id, title, imageSrc, filename, isSaved = false) { 
    const listContainer = document.getElementById('carContainer');
    const carDiv = document.createElement('div');
    
    carDiv.classList.add('car-card-dynamic', 'hover-effect-card');

    const hoverCarImageSrc = imageSrc.replace(/(\.[\w\d_-]+)$/i, '-hover$1');

    const heartEmpty = `<img src="SVGs/empty_heart.svg" alt="Save" width="6" height="6" style="pointer-events: none;">`;
    const heartFilled = `<img src="SVGs/fulled_heart.svg" alt="Saved" width="6" height="6" style="pointer-events: none;">`;

    const heartIcon = isSaved ? heartFilled : heartEmpty;

    let html = `
        <div class="default-content">
            <p>${title}</p>
            <img src="${imageSrc}" alt="${title}">
        </div>
        
        <div class="hover-content">
            <img src="${hoverCarImageSrc}" alt="${title} Hover" onerror="this.style.display='none';">
        </div>
        
        <button class="save-btn-heart" title="Save to favorites">${heartIcon}</button>
    `;
    
    carDiv.innerHTML = html;

    const deleteBtn = document.createElement('button');
    deleteBtn.innerHTML = '×'; 
    deleteBtn.className = 'delete-car-btn';
    deleteBtn.title = 'Видалити машину';
    
    deleteBtn.addEventListener('click', (e) => {
        e.stopPropagation(); 
        deleteCar(id, carDiv);
    });

    if (localStorage.getItem('isAdmin') === 'true') {
        carDiv.appendChild(deleteBtn);
    }

    const saveBtn = carDiv.querySelector('.save-btn-heart');
    saveBtn.addEventListener('click', (e) => {
        e.stopPropagation(); 

        const currentUsername = localStorage.getItem('username');
        const isLoggedIn = localStorage.getItem('isLoggedIn');

        if (isLoggedIn !== 'true' || !currentUsername) {
            alert("Please log in to save cars!");
            window.location.href = "login/register.html"; 
            return;
        }

        fetch('toggle_save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: currentUsername, car_id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                saveBtn.innerHTML = (data.action === 'added') ? heartFilled : heartEmpty;
            } else {
                console.error("Saving error:", data.message);
            }
        })
        .catch(err => console.error("Connection error:", err));
    });

    carDiv.style.cursor = "pointer";
    carDiv.addEventListener('click', () => {
        if (filename) {
            window.location.href = `pages/${filename}`;
        } else {
            alert("Page not found!");
        }
    });

    listContainer.prepend(carDiv); 
}

function deleteCar(id, carElement) {
    if (!confirm("Are you sure? It will delete the car and files permanently!")) return;

    fetch('delete_car.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            carElement.remove();
            alert("Car and files are deleted!");
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => alert("Connection error"));
}

function setupDragAndDrop() {
    const dropArea = document.getElementById('drop-area');
    const fileElem = document.getElementById('fileElem');
    const previewImg = document.getElementById('previewImg');
    const form = document.getElementById('carForm');

    if (!dropArea || !form) return;

    dropArea.addEventListener('click', () => { if(fileElem) fileElem.click(); });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault(); e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(() => dropArea.classList.add('highlight'));
    ['dragleave', 'drop'].forEach(() => dropArea.classList.remove('highlight'));

    dropArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        handleFiles(dt.files);
    }, false);
    
    if (fileElem) {
        fileElem.addEventListener('change', function() {
            if (this.files.length > 0) handleFiles(this.files);
        });
    }

    function handleFiles(files) {
        if (files.length > 0) {
            fileToUpload = files[0];
            const reader = new FileReader();
            reader.readAsDataURL(fileToUpload);
            reader.onloadend = function() {
                if (previewImg) {
                    previewImg.src = reader.result;
                    previewImg.style.display = 'block';
                }
            }
        }
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const titleInput = document.getElementById('titleInput');
        const descInput = document.getElementById('descInput');
        const categoryInput = document.getElementById('carCategory'); 

        if (!fileToUpload) { alert("Choose an image!"); return; }

        const formData = new FormData();
        formData.append('title', titleInput.value);
        formData.append('description', descInput ? descInput.value : '');
        formData.append('category', categoryInput ? categoryInput.value : 'electric'); 
        formData.append('image', fileToUpload);

        const btn = form.querySelector('button');
        btn.textContent = "Creating...";
        btn.disabled = true;

        fetch('add_car.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                createCarHTML(data.newCar.id, data.newCar.title, data.newCar.image, data.newCar.filename);
                form.reset();
                if (previewImg) { previewImg.style.display = 'none'; previewImg.src = ''; }
                fileToUpload = null;
                alert("Done!");
            } else { alert("Error: " + data.message); }
        })
        .catch(err => alert("Connection error"))
        .finally(() => { btn.textContent = "Add Car"; btn.disabled = false; });
    });
}