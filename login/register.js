document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const msgBox = document.getElementById('response-message');
        const submitBtn = this.querySelector('button');

        submitBtn.disabled = true;
        submitBtn.textContent = "Checking...";
        
        msgBox.style.display = 'none';

        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) 
        .then(data => {
            msgBox.style.display = 'block'; 
            
            if (data.success) {
                msgBox.style.color = '#28a745'; 
                msgBox.textContent = "Registration succesful! Redirecting...";
                
                setTimeout(() => {
                    window.location.href = 'index.html'; 
                }, 3000);
            } else {
                msgBox.style.color = '#dc3545'; 
                msgBox.textContent = data.message; 
                
                submitBtn.disabled = false;
                submitBtn.textContent = "Sign Up";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            msgBox.style.display = 'block';
            msgBox.style.color = 'red';
            msgBox.textContent = "Server error. Try again later!";
            submitBtn.disabled = false;
            submitBtn.textContent = "Sign Up";
        });
    });