const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");
const sign_in_btn2 = document.querySelector("#sign-in-btn2");
const sign_up_btn2 = document.querySelector("#sign-up-btn2");
sign_up_btn.addEventListener("click", () => {
    container.classList.add("sign-up-mode");
});
sign_in_btn.addEventListener("click", () => {
    container.classList.remove("sign-up-mode");
});
sign_up_btn2.addEventListener("click", () => {
    container.classList.add("sign-up-mode2");
});
sign_in_btn2.addEventListener("click", () => {
    container.classList.remove("sign-up-mode2");
});

document.getElementById("signup-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const loader = document.getElementById("loader");
    const msg = document.getElementById("response-msg");

    msg.textContent = "";
    loader.style.display = "block";

    const startTime = Date.now(); // Start timing

    try {
        const response = await fetch("db/register.php", {
            method: "POST",
            body: formData
        });

        const result = await response.text();
        const elapsedTime = Date.now() - startTime;
        const remainingTime = 5000 - elapsedTime;

        setTimeout(() => {
            loader.style.display = "none";

            if (response.ok) {
                form.reset();
                showAlert(result); // Show animated alert
            } else {
                msg.style.color = "red";
                msg.textContent = result;
            }
        }, remainingTime > 0 ? remainingTime : 0);
    } catch (error) {
        const elapsedTime = Date.now() - startTime;
        const remainingTime = 5000 - elapsedTime;

        setTimeout(() => {
            loader.style.display = "none";
            msg.style.color = "red";
            msg.textContent = "An error occurred. Please try again.";
        }, remainingTime > 0 ? remainingTime : 0);
    }
});

// Slide-down alert animation
function showAlert(messageText) {
    const alert = document.getElementById("alert-container");
    const message = alert.querySelector("p");

    message.textContent = messageText;
    alert.classList.add("show");

    setTimeout(() => {
        alert.classList.remove("show");
    }, 3000); // Display alert for 3 seconds
}

document.getElementById("login-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const loader = document.getElementById("login-loader");
    const msg = document.getElementById("login-response-msg");

    loader.style.display = "block";
    msg.textContent = "";

    try {
        const response = await fetch("db/login.php", {
            method: "POST",
            body: formData
        });

        const result = await response.text();
        loader.style.display = "none";

        if (response.ok) {
            if (result.trim().toLowerCase() === "success") {
                showAlert("Login successful!");
                setTimeout(() => {
                    window.location.href = "./screens/index.php"; // Change to your desired destination
                }, 2000); // Wait for alert animation to finish
            } else {
                msg.textContent = result;
            }
        } else {
            msg.textContent = "Login failed.";
        }
    } catch (error) {
        loader.style.display = "none";
        msg.textContent = "An error occurred. Please try again.";
    }
});
