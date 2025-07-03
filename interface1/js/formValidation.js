document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletterForm');
    const contactForm = document.getElementById('contactForm');
    
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        
        if (field.required && value === '') {
            isValid = false;
            errorMessage = 'Dit veld is verplicht';
        }
        else if (field.type === 'email' && value !== '') {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(value)) {
                isValid = false;
                errorMessage = 'Voer een geldig e-mailadres in';
            }
        }
        else if (field.name === 'postcode' && value !== '') {
            const postcodePattern = /^[0-9]{4}\s?[A-Za-z]{2}$/;
            if (!postcodePattern.test(value)) {
                isValid = false;
                errorMessage = 'Voer een geldige postcode in (bijv. 1234 AB)';
            } else if (value.length === 6 && !value.includes(' ')) {
                field.value = value.substring(0, 4) + ' ' + value.substring(4);
            }
        }
        
        let errorElement = field.nextElementSibling;

        if (!errorElement || !errorElement.classList.contains('error-message')) {
            errorElement = document.getElementById(`${field.name}Error`);
        }

        if (errorElement) {
            if (!isValid) {
                errorElement.textContent = errorMessage;
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
        }
        
        return isValid;
    }
    
    function setupForm(form) {
        if (!form) return;
        
        const formFields = form.querySelectorAll('input, textarea, select');
        
        formFields.forEach(field => {
            field.addEventListener('blur', function() {
                validateField(this);
            });
  
            field.addEventListener('input', function() {
                // Try both methods of finding the error element
                let errorElement = this.nextElementSibling;
                if (!errorElement || !errorElement.classList.contains('error-message')) {
                    errorElement = document.getElementById(`${this.name}Error`);
                }
                
                if (errorElement) {
                    errorElement.classList.add('hidden');
                }
            });
        });
        

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            
  
            formFields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });
            
            if (isValid) {

                if (form.id === 'contactForm') {
                    const successMessage = document.getElementById('successMessage');
                    if (successMessage) {
                        form.classList.add('hidden');
                        successMessage.classList.remove('hidden');
                    }
                } else {
                 
                    const formSuccess = document.getElementById('formSuccess');
                    if (formSuccess) {
                        formSuccess.classList.remove('hidden');
                   
                        setTimeout(() => {
                            form.reset();
                            setTimeout(() => {
                                formSuccess.classList.add('hidden');
                            }, 3000);
                        }, 1000);
                    } else {
                   
                        alert('Formulier succesvol verzonden');
                        form.reset();
                    }
                }
                
            }
        });
    }
    
    setupForm(newsletterForm);
    setupForm(contactForm);
});
