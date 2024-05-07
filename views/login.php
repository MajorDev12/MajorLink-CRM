<?php session_start(); ?>
<?php
require_once  '../modals/adminandclientsetup.php';
require_once  '../database/pdo.php';
require_once "../modals/setup_mod.php";
require_once "style.config.php";
require_once "header.php";

$connect = connectToDatabase($host, $dbname, $username, $password);

$country = get_setup($connect);
?>
<style>
    body {
        background-color: var(--grey);
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    #spinner {
        display: none;
    }

    .login-box {
        background-color: var(--light);
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .companyName {
        color: var(--blue);
        font-weight: 700;
    }
</style>


<div class="login-container">
    <div class="login-box col-md-4">
        <div class="text-center mb-5">
            <img src="../img/default-profile-image.png" alt="Company Logo" width="100" height="100" class="rounded-circle">
            <h4 class="companyName mt-3">MajorLink</h4>
        </div>
        <form>
            <div class="form-group mb-3 mt-5">
                <label for="inputEmail" class="text-start">Email address</label>
                <input type="email" class="form-control" id="inputEmail" placeholder="example@gmail.com">
            </div>


            <div class="form-group mb-3">
                <label for="inputEmail" class="text-start">Password</label>
                <div class="input-group">
                    <input type="password" id="inputPassword" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" placeholder="********">
                    <span class="input-group-text" id="togglePassword"><i class='bx bxs-low-vision'></i></span>
                </div>
            </div>


            <div id="errorMsg" class="text-center"></div>
            <div class="form-group mb-3">
                <a href="forgotPassword.php">Forgot Password?</a>
            </div>

            <div class="form-group text-center">
                <button type="button" id="loginButton" class="btn btn-primary">Login</button>
            </div>

        </form>
    </div>
</div>

<?php require_once "footer.php"; ?>


<script>
    var togglePassword = document.getElementById("togglePassword");
    var loader = document.getElementById("spinner");


    togglePassword.addEventListener("click", function() {
        showhidePswd(togglePassword);
    });


    function showhidePswd(element) {
        var passwordInput = element.previousElementSibling;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }







    // Event listener for the login button
    document.getElementById('loginButton').addEventListener('click', function() {
        // Get input values
        var email = document.getElementById('inputEmail').value;
        var password = document.getElementById('inputPassword').value;
        var country = <?php echo json_encode($country); ?>;

        if (!email || !password) {
            displayMessage("errorMsg", "Please Fill in all fields.", true);
            return;
        }

        // Validate email format
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            displayMessage("errorMsg", "Invalid email format.", true);
            return;
        }

        // Validate password length
        if (password.length < 6) {
            displayMessage("errorMsg", "Password must be at least 6 characters long.", true);
            return;
        }
        loader.style.display = "flex";
        // Send data through Fetch API to PHP for logic
        fetch('../controllers/login_contr.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Handle the response from the server
                    if (data.role === 'admin') {
                        if (!country) {
                            displayMessage("errorMsg", `${data.message}`, false);
                            window.location.href = 'index.php';
                        } else {
                            setTimeout(() => {
                                window.location.href = 'setup.php';
                            }, 2000);
                        }
                    }
                    if (data.role === 'client') {
                        displayMessage("errorMsg", `${data.message}`, false);
                        window.location.href = '../user/index.php';
                    }
                    if (data.role === 'test_client') {
                        if (!country) {
                            displayMessage("errorMsg", `${data.message}`, false);
                            window.location.href = 'index.php';
                        } else {
                            setTimeout(() => {
                                window.location.href = 'setup.php';
                            }, 2000);
                        }
                    }
                    if (data.role === 'undefined') {
                        loader.style.display = "none";
                        displayMessage("errorMsg", "Invalid email or password", true);
                    }
                    if (data.role === 'error') {
                        loader.style.display = "none";
                        displayMessage("errorMsg", "something went wrong", true);
                    }
                }
            })
            .catch(error => {
                console.error('Error in sending data:', error);
            });
    });



    function displayMessage(messageElement, message, isError) {
        // Get the HTML element where the message should be displayed
        var targetElement = document.getElementById(messageElement);

        // Set the message text
        targetElement.innerText = message;

        // Add styling based on whether it's an error or success
        if (isError) {
            targetElement.style.color = 'red';
        } else {
            targetElement.style.color = 'green';
        }

        // Set a timeout to hide the message with the fade-out effect
        setTimeout(function() {
            targetElement.innerText = '';
        }, 2000);
    }
</script>