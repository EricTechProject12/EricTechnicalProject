document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission
    
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    // Simulating login (replace with actual authentication logic)
    if (email === "test@example.com" && password === "password123") {
        alert("Login successful!");
        // Redirect to dashboard (replace with actual page)
        window.location.href = "dashboard.html";
    } else {
        alert("Invalid email or password.");
    }
});

document.getElementById("forgotPassword").addEventListener("click", function() {
    let email = prompt("Enter your email to reset password:");
    
    if (email) {
        alert("Password reset link sent to " + email);
        // Here you would typically call an API to handle password reset
    }
});
