// Обробник підтвердження форми входу
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this); 
    const errorMsg = document.getElementById('error-message'); 
    const submitBtn = this.querySelector('button'); 

    // Блокування кнопки на час запит аби уникнути подвійного підтвердження 
    submitBtn.disabled = true;
    submitBtn.textContent = "Logging in...";
    
    errorMsg.style.display = 'none';
    errorMsg.textContent = '';

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network error');
        }
        return response.json(); 
    })
    .then(data => {
        if (data.success) {

            // Збреження даних сесії у localStorage
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('username', data.username);
            
            // Встновлення або видалення прапора "Адмін" в залежності від ролі користувача
            if (data.isAdmin == 1) {
                localStorage.setItem('isAdmin', 'true');
            } else {
                localStorage.removeItem('isAdmin'); 
            }

            errorMsg.style.display = 'block';
            errorMsg.style.color = '#28a745'; 
            errorMsg.textContent = "Success! Redirecting...";

            // Невелика затримка перед перенаправленням 
            setTimeout(() => {
                window.location.assign(window.location.origin + "/index.html"); 
            }, 800); 

        } else {
            // Помідомлення про помилку з сервера 
            errorMsg.style.display = 'block';
            errorMsg.style.color = '#dc3545'; 
            errorMsg.textContent = data.message;

            submitBtn.disabled = false;
            submitBtn.textContent = "Log In";
        }
    })
    .catch(error => {
        // Мережева помилка або некоректна відповідь від сервера
        console.error('Error:', error);
        errorMsg.style.display = 'block';
        errorMsg.style.color = 'red';
        errorMsg.textContent = "Server Error. Please try again later.";
        
        submitBtn.disabled = false;
        submitBtn.textContent = "Log In";
    });
});