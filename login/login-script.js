document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this); 
    const errorMsg = document.getElementById('error-message'); 
    const submitBtn = this.querySelector('button'); 

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
            throw new Error('Мережева помилка');
        }
        return response.json(); 
    })
    .then(data => {
        if (data.success) {

            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('username', data.username);
            
            if (data.isAdmin == 1) {
                localStorage.setItem('isAdmin', 'true');
            } else {
                localStorage.removeItem('isAdmin'); 
            }

            errorMsg.style.display = 'block';
            errorMsg.style.color = '#28a745'; 
            errorMsg.textContent = "Success! Redirecting...";

            setTimeout(() => {
                window.location.assign(window.location.origin + "/index.html"); 
            }, 1000); 

        } else {
            errorMsg.style.display = 'block';
            errorMsg.style.color = '#dc3545'; 
            errorMsg.textContent = data.message;

            submitBtn.disabled = false;
            submitBtn.textContent = "Log In";
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMsg.style.display = 'block';
        errorMsg.style.color = 'red';
        errorMsg.textContent = "Server Error. Please try again later.";
        
        submitBtn.disabled = false;
        submitBtn.textContent = "Log In";
    });
});