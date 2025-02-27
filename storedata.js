// Function to store form values in memory and redirect to another page
function storeFormValuesAndRedirect() {
    var name = document.getElementById('name').value;
    var dob = document.getElementById('dob').value;
    var email = document.getElementById('email').value;
    var mobile = document.getElementById('mobile').value;

    sessionStorage.setItem('storedName', name);
    sessionStorage.setItem('storedDOB', dob);
    sessionStorage.setItem('storedEmail', email);
    sessionStorage.setItem('storedMobile', mobile);

    // Redirect to another page
    window.location.href = "/register.html"; // Replace "anotherPage.html" with your desired page URL
}

// Attach event listener to the form for submission
document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    storeFormValuesAndRedirect(); // Call function to store form values and redirect
});