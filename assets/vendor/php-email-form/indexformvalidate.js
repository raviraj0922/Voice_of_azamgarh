document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contact-form");
    const loading = document.querySelector(".loading");
    const errorMessage = document.querySelector(".error-message");
    const successMessage = document.querySelector(".sent-message");

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "forms/submit.php", true);
        xhr.setRequestHeader("Accept", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== XMLHttpRequest.DONE) return;
            loading.style.display = "none";

            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response === "success") {
                    successMessage.style.display = "block";
                    form.reset();
                } else {
                    errorMessage.style.display = "block";
                }
            } else {
                errorMessage.style.display = "block";
            }
        };

        xhr.send(formData);
        loading.style.display = "block";
    });
});
