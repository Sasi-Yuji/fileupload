/**
 * Reusable Form Validation Utility
 * Usage: Import this file in your forms and use the validation functions
 */

class FormValidator {
    
    /**
     * Show error message
     * @param {string} errorId - ID of error message element
     * @param {string} inputId - ID of input element
     * @param {string} message - Error message
     */
    showError(errorId, inputId, message) {
        if (errorId) $(errorId).text(message).addClass('visible');
        if (inputId) $(inputId).addClass('error');
    }

    /**
     * Hide error message
     * @param {string} errorId - ID of error message element
     * @param {string} inputId - ID of input element
     */
    hideError(errorId, inputId) {
        if (errorId) $(errorId).text('').removeClass('visible');
        if (inputId) $(inputId).removeClass('error');
    }

    /**
     * Validate Full Name
     * Rules: No numbers, No special characters, Max 50 characters
     */
    validateName(value, errorId = '#nameError', inputId = '#name') {
        const val = value.trim();
        const nameRegex = /^[a-zA-Z\s]+$/;
        
        if (val === "") {
            this.showError(errorId, inputId, 'Full Name is required.');
            return false;
        }
        else if (!nameRegex.test(val)) {
            this.showError(errorId, inputId, 'Name must contain only letters and spaces.');
            return false;
        }
        else if (val.length > 50) {
            this.showError(errorId, inputId, 'Name must not exceed 50 characters.');
            return false;
        }
        else {
            this.hideError(errorId, inputId);
            return true;
        }
    }

    /**
     * Validate Email Address
     * Rules: Limit 100 characters total. 
     * No numbers after @. 
     * Gmail must be gmail.com.
     */
    validateEmail(value, errorId = '#emailError', inputId = '#email') {
        const val = value.trim();
        const emailParts = val.split('@');
        
        if (val === "") {
            this.showError(errorId, inputId, 'Email is required.');
            return false;
        } 
        else if (val.length > 100) {
            this.showError(errorId, inputId, 'Email must not exceed 100 characters.');
            return false;
        }
        else if (emailParts.length !== 2) {
            this.showError(errorId, inputId, 'Invalid email. Include an @ symbol.');
            return false;
        } 
        else if (/\d/.test(emailParts[1])) {
            this.showError(errorId, inputId, 'Numbers are not allowed in the domain after @.');
            return false;
        } 
        else if (emailParts[1].toLowerCase().includes('gmail') && emailParts[1].toLowerCase() !== 'gmail.com') {
            this.showError(errorId, inputId, 'Invalid domain. Only @gmail.com is allowed for Gmail.');
            return false;
        } 
        else if (!/^[a-zA-Z.-]+\.[a-zA-Z]{2,}$/.test(emailParts[1])) {
            this.showError(errorId, inputId, 'Domain must be a valid format (e.g., domain.com).');
            return false;
        }
        else {
            this.hideError(errorId, inputId);
            return true;
        }
    }

    /**
     * Validate Mobile Number
     * India: starts with 6,7,8,9 and exactly 10 digits.
     * Others: Max 15 digits.
     */
    validateMobile(iti, value, errorId = '#phoneError', inputId = '#phone') {
        const val = value.replace(/\s+/g, ''); // Remove spaces
        const countryData = iti.getSelectedCountryData();
        const iso = countryData && countryData.iso2 ? countryData.iso2.toLowerCase() : '';
        const dialCode = countryData && countryData.dialCode ? countryData.dialCode : '';
        
        if (val === "") {
            this.showError(errorId, inputId, 'Mobile number is required.');
            return false;
        }
        
        if (iso === 'in' || dialCode === '91') {
            if (!/^[6-9]/.test(val)) {
                this.showError(errorId, inputId, 'Indian numbers must start with 6, 7, 8, or 9.');
                return false;
            }
            if (val.length !== 10) {
                this.showError(errorId, inputId, 'Indian numbers must be exactly 10 digits.');
                return false;
            }
        } else {
            if (val.length > 15) {
                this.showError(errorId, inputId, 'International numbers must not exceed 15 digits.');
                return false;
            }
        }

        if (iti.isValidNumber()) {
            this.hideError(errorId, inputId);
            return true;
        } 
        else {
            this.showError(errorId, inputId, 'Invalid phone number format for selected country.');
            return false;
        }
    }

    /**
     * Validate Department
     * Rules: No numbers, Max 100 characters
     */
    validateDepartment(value, errorId = '#deptError', inputId = '#department') {
        const val = value.trim();
        
        if (val === "") {
            this.showError(errorId, inputId, 'Department is required.');
            return false;
        }
        else if (/\d/.test(val)) {
            this.showError(errorId, inputId, 'Department cannot contain numbers.');
            return false;
        }
        else if (val.length > 100) {
            this.showError(errorId, inputId, 'Department must not exceed 100 characters.');
            return false;
        }
        else {
            this.hideError(errorId, inputId);
            return true;
        }
    }

    /**
     * Validate Required File & Size
     * @param {string} inputId - Input selector
     * @param {string} errorId - Error selector
     * @param {string} label - Label for the field
     * @param {number} maxSizeMB - Max allowed size in MB (default 2)
     * @param {boolean} isRequired - Whether the file is mandatory (default true)
     */
    validateFile(inputId, errorId, label, maxSizeMB = 2, isRequired = true) {
        const input = $(inputId)[0];
        const hasFile = input && input.files.length > 0;

        if (!hasFile) {
            if (isRequired) {
                this.showError(errorId, inputId, label + ' is required.');
                return false;
            } else {
                this.hideError(errorId, inputId);
                return true;
            }
        }

        const maxSizeBytes = maxSizeMB * 1024 * 1024;
        let totalSize = 0;
        for (let file of input.files) {
            totalSize += file.size;
        }

        if (totalSize > maxSizeBytes) {
            this.showError(errorId, inputId, `${label} must not exceed ${maxSizeMB}MB.`);
            $(inputId).val(''); // 🚀 Clear the input so it's not "accepted"
            return false;
        }

        this.hideError(errorId, inputId);
        return true;
    }
}
