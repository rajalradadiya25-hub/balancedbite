let captcha;
function generate() {
    // Clear old input
    document.getElementById("submit").value = "";

    // Access the element to store the generated captcha
    captcha = document.getElementById("image");
    let uniquechar = "";

    const randomchar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    // Generate captcha of length 5 with random characters
    for (let i = 0; i < 5; i++) {
        uniquechar += randomchar.charAt(
            Math.floor(Math.random() * randomchar.length)
        );
    }

    // Store generated input
    captcha.innerHTML = uniquechar;
}

function validateCaptcha(event) {
    const usr_input = document.getElementById("submit").value;
    let keyElement = document.getElementById("key");
    
    keyElement.innerHTML = "";
    keyElement.style.color = "";

    if (usr_input === "") {
        keyElement.innerHTML = "Please enter captcha";
        keyElement.style.color = "red";
        generate();
        return false; // Prevent form submission
    } else if (usr_input != captcha.innerHTML) {
        keyElement.innerHTML = "Not Matched";
        keyElement.style.color = "red";
        generate();
        return false; // Prevent form submission
    } else {
        keyElement.innerHTML = "Matched";
        keyElement.style.color = "green";
        return true; // Allow form submission
    }
}

document.getElementById("myForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the default form submission
    if (validateCaptcha(event)) {
        // If captcha is matched, you can manually submit the form here
        this.submit();
    }
});

// Generate the initial captcha when the page loads
window.onload = generate;
