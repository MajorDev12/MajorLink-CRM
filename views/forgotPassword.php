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

    h5 {
        color: var(--dark-grey);
    }
</style>


<div id="loader">Loading...</div>
<div class="login-container">
    <div class="login-box col-md-4">
        <div class="text-center mb-5">
            <h4 class="companyName mt-3">Reset Password</h4>
        </div>
        <div class="div">
            <h5 class="mb-3">Apologies for any inconvenience experienced. Please enter your email address to search for your account.</h5>
            <form>
                <div class="form-group m-4">
                    <input type="email" class="form-control" id="inputEmail" placeholder="example@gmail.com">
                </div>
                <p id="errormsg" class="text-danger text-center"></p>
                <div class="form-group text-center">
                    <button type="button" id="searchButton" class="btn btn-primary">Search</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php require_once "footer.php"; ?>


<script>
    var searchButton = document.getElementById('searchButton');
    // Regular expression to validate email format




    searchButton.addEventListener("click", function() {
        var searchEmail = document.getElementById('inputEmail').value;
        var errormsg = document.getElementById('errormsg');

        var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!searchEmail) {
            errormsg.textContent = "Enter Email First";
            setTimeout(() => {
                errormsg.textContent = "";
            }, 3000);
            return;
        } else {
            if (!email_pattern.test(searchEmail)) {
                errormsg.textContent = "Invalid email format";
                setTimeout(() => {
                    errormsg.textContent = "";
                }, 3000);
                return;
            }
        }





        loader.style.display = "flex";

        var formData = new FormData();
        formData.append("searchEmail", searchEmail);
        fetch('../controllers/searchEmail_contr.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Handle the response from the server

                    window.location.href = `sendPassword.php`;
                } else {
                    window.location.href = `sendPassword.php?e=none`;
                }
            })

            .catch(error => {
                console.error('Error in sending data:', error);
            });








    })
</script>