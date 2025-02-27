(function () {
  "use strict";

  // Select all forms with class 'php-email-form'
  let forms = document.querySelectorAll('.php-email-form');

  // Add event listener to each form
  forms.forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault(); // Prevent default form submission

      // Get form action and reCAPTCHA site key
      let thisForm = this;
      let action = thisForm.getAttribute('action');
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');

      // Display loading message
      thisForm.querySelector('.loading').classList.add('d-block');
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');

      // Create FormData object containing form data
      let formData = new FormData(thisForm);

      // Handle reCAPTCHA if enabled
      if (recaptcha) {
        if (typeof grecaptcha !== "undefined") {
          grecaptcha.ready(function () {
            try {
              // Execute reCAPTCHA and append response token to form data
              grecaptcha.execute(recaptcha, { action: 'php_email_form_submit' })
                .then(token => {
                  formData.set('recaptcha-response', token);
                  php_email_form_submit(thisForm, action, formData);
                })
            } catch (error) {
              displayError(thisForm, error);
            }
          });
        } else {
          displayError(thisForm, 'The reCaptcha JavaScript API URL is not loaded!')
        }
      } else {
        // Submit form without reCAPTCHA
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  // Function to submit form data via fetch API
  function php_email_form_submit(form, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(response => {
        if (response.ok) {
          return response.text();
        } else {
          throw new Error(`${response.status} ${response.statusText} ${response.url}`);
        }
      })
      .then(data => {
        // Handle successful form submission
        form.querySelector('.loading').classList.remove('d-block');
        if (data.trim() === 'OK') {
          form.querySelector('.sent-message').classList.add('d-block');
          form.reset();
        } else {
          throw new Error(data ? data : 'Form submission failed and no error message returned from: ' + action);
        }
      })
      .catch((error) => {
        // Handle form submission errors
        displayError(form, error);
      });
  }

  // Function to display error message
  function displayError(form, error) {
    form.querySelector('.loading').classList.remove('d-block');
    form.querySelector('.error-message').innerHTML = error;
    form.querySelector('.error-message').classList.add('d-block');
    // Hide success message if error occurs
    form.querySelector('.sent-message').classList.remove('d-block');
  }
})();

