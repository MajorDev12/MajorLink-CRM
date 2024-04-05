<?php
require_once "../controllers/session_Config.php";
require_once  '../database/pdo.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getSms_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$texts = getSmsTemplate($connect);
// $template = getEmailTemplateById($connect, $templateID);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
<style>
    .content .header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .modal-container {
        width: 60%;
    }

    .textContainer {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
    }

    .textContainer .options {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .textContainer .options button {
        height: 28px;
        width: 28px;
        display: grid;
        place-items: center;
        border-radius: 3px;
        border: none;
        background-color: #ffffff;
        outline: none;
        color: #020929;
    }


    .textContainer .options select {
        padding: 2px;
        border: 1px solid #020929;
        border-radius: 3px;
    }

    .textContainer .options label,
    .textContainer .options select {
        font-family: var(--poppins);
    }

    .textContainer .options .input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .textContainer .options input[type="color"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: transparent;
        width: 20px;
        height: 14px;
        border: none;
        cursor: pointer;
    }

    .textContainer .options input[type="color"]::-webkit-color-swatch {
        border-radius: 15px;
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 3px #020929;
    }

    .textContainer .options input[type="color"]::-moz-color-swatch {
        border-radius: 15px;
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 3px #020929;
    }

    .textContainer #text-input {
        margin-top: 10px;
        border: 1px solid #dddddd;
        padding: 20px;
        height: 50vh;
    }

    .textContainer .options .active {
        background-color: #e0e9ff;
    }

    .textContainer #bodyInput {
        margin-top: 10px;
        border: 1px solid #dddddd;
        padding: 20px;
    }

    /* .textContainer .bodyInput {
        width: 100%;
        border: none;
    } */

    .main-content .content table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-content .content table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .main-content .content table td {
        padding: 16px 0;
        text-align: center;
    }

    .main-content .content table td .icon {
        background-color: var(--blue);
        border-radius: 5px;
        padding: 4px;
        cursor: pointer;
    }

    .main-content .content table td .view {
        background-color: var(--blue);
    }

    .main-content .content table td .pdf {
        background-color: var(--yellow);
    }

    .main-content .content table td .print {
        background-color: var(--orange);
    }

    .main-content .content table td .icon img {
        width: 20px;
    }

    .main-content .content table tbody tr:hover {
        background: var(--grey);
    }

    .main-content .content .tablenav {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .main-content .content .tablenav p {
        display: flex;
        justify-content: start;
    }
</style>

<!-- SIDEBAR -->
<?php require_once "side_nav.php"; ?>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
    <!-- TOP-NAVBAR -->
    <?php require_once "top_nav.php"; ?>
    <!-- TOP-NAVBAR -->

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Hi, <?= $_SESSION['Username']; ?></h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="settings.php">Settings</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Sms Templates</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">


            <!-- modal -->
            <div id="overlay"></div>
            <div class="modal-container" id="deleteModal">
                <input type="hidden" id="deletemplateID">
                <div id="modalBackground"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <p id="title" class="modal-title">Delete Email Template</p>
                        <button type="button" id="delcancelBtn" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mt-3">
                        <h2 style="text-align: center; ">Are You Sure?</h2>
                    </div>
                    <div class="modal-footer">
                        <p id="modalerror"></p>
                        <button type="button" id="deleteEmailBtn" class="btn btn-danger">Delete</button>

                    </div>
                </div>

            </div>













            <div class="modal-container" id="changeModal">
                <input type="hidden" id="templateID">
                <div id="modalBackground"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="topTitle" class="modal-title"></h5>
                        <button type="button" id="cancel" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mt-3">
                        <input type="hidden" id="edit-PlanId" value="">
                        <label for="bodyInput" class="mt-5">Message Body:</label>
                        <!-- <div id="bodyInput" contenteditable="true"></div> -->
                        <div class="form-floating">
                            <textarea class="form-control" style="height: 400px;" id="bodyInput"></textarea>
                        </div>
                        <!-- <textarea contenteditable="true" style="outline: none;" id="bodyInput" class="p-3 b-none" cols="64" rows="15"></textarea> -->
                    </div>
                    <div class="modal-footer">
                        <p id="errorMsg"></p>
                        <button type="button" id="updateSmsBtn" class="btn btn-info">Save Changes</button>
                    </div>
                </div>

            </div>


            <div class="modal-container" id="newModal">
                <div id="modalBackground"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="newtitle" class="modal-title">Custom</h5>
                        <button type="button" id="cancelBtn" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mt-3">
                        <label for="bodyInput" class="mt-5">Message Body:</label>
                        <!-- <div id="bodyInput" contenteditable="true"></div> -->
                        <div class="form-floating">
                            <textarea class="form-control newMessageInput" style="height: 200px;" id="bodyInput"></textarea>
                        </div>
                        <!-- <textarea contenteditable="true" style="outline: none;" id="bodyInput" class="p-3 b-none" cols="64" rows="15"></textarea> -->
                    </div>
                    <div class="modal-footer">
                        <p id="newMsgerror"></p>
                        <button type="button" id="addNewBtn" class="btn btn-info">Save Email</button>

                    </div>
                </div>

            </div>













            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom header">
                    <h3>Create Sms Messages</h3>
                    <button id="NewBtn" class="btn btn-primary">New Sms</button>
                </div>

                <table class="mt-5">
                    <thead id="thead">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <!-- <th style="text-align:center">Name</th> -->
                            <th style="text-align:center">Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="tableBody">

                        <?php $counter = 1; ?>
                        <?php if ($texts) : ?>
                            <?php foreach ($texts as $index => $text) : ?>
                                <tr>
                                    <td class="index pe-3"><?= $index + 1; ?></td>
                                    <td class="pe-5"><?= $text['Category']; ?></td>

                                    <td style="text-align:left; width: 70%;" class="pe-3"><?= $text['Body']; ?></td>
                                    <td style="text-align:left;" class="">
                                        <a href="#" class="icon view" data-templateid="<?= $text["TemplateID"]; ?>"><img src="../img/eyeIcon.png" alt=""></a>
                                        <?php if ($text['Status'] === 'Custom') : ?>
                                            <abbr title="delete"><a href="#" data-deltemplateid="<?= $text["TemplateID"]; ?>" class="icon print"><img src="../img/deleteIcon.png" style="width: 18px; height: 20px;" alt=""></a></abbr>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php echo '<tr><td colspan="8" style="text-center"> No Data Yet</td></tr>'; ?>
                        <?php endif; ?>



                    </tbody>
                </table>



            </div>

            <?php require_once "footer.php"; ?>

            <script>
                var updateSmsBtn = document.querySelector("#updateSmsBtn");
                var bodyInput = document.querySelector("#bodyInput");
                var newMessage = document.querySelector(".newMessageInput");
                var newname = document.querySelector("#newnameInput");
                var title = document.querySelector("#topTitle");
                var category = document.querySelector("#newcategory");
                var templateid = document.querySelector("#templateID");
                var deltemplateid = document.querySelector("#deletemplateID");
                var addNewBtn = document.querySelector("#addNewBtn");
                var NewBtn = document.querySelector("#NewBtn");
                var newModal = document.querySelector("#newModal");
                var addEmailBtn = document.querySelector("#addEmailBtn");
                var deleteModal = document.querySelector("#deleteModal");
                var initialBodyValue;
                var initialSubjectValue;




                NewBtn.addEventListener("click", function() {
                    document.getElementById('overlay').style.display = 'block';
                    document.getElementById('newModal').style.display = 'block';
                })





                addNewBtn.addEventListener("click", function() {
                    if (!newMessage.value) {
                        displayMessage("newMsgerror", "Enter a message first", true);
                        return;
                    }


                    var formData = new FormData();
                    formData.append("newMessage", newMessage.value);

                    fetch("../controllers/addSmsTemplate_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Handle the response from the server
                                displayMessage("newMsgerror", "Added Successfully!!", false);
                                // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);

                            } else {
                                displayMessage("newMsgerror", "something went wrong!!", false);
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });


                })



                document.querySelectorAll('.print').forEach(function(link) {
                    link.addEventListener("click", function(event) {
                        event.preventDefault();

                        document.getElementById('deleteModal').style.display = 'block';
                        document.getElementById('overlay').style.display = 'block';

                        var templateID = this.dataset.deltemplateid;
                        // Set templateID in hidden input
                        deltemplateid.value = templateID;

                    })
                })




                const deleteEmailBtn = document.querySelector("#deleteEmailBtn");
                deleteEmailBtn.addEventListener("click", function() {

                    var templateId = document.querySelector("#deletemplateID").value;

                    // Check if templateId is not empty
                    if (!templateId) {
                        displayMessage("deleteModal", "something went wrong!!", false);
                        return;
                    }


                    var formData = new FormData();
                    formData.append("templateId", templateId);

                    fetch("../controllers/deleteSmsTemplate_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Handle the response from the server
                                document.getElementById('overlay').style.display = 'none';
                                document.getElementById('deleteModal').style.display = 'none';
                                location.reload();
                            } else {
                                displayMessage("deleteModal", "something went wrong!!", false);
                                document.getElementById('overlay').style.display = 'none';
                                document.getElementById('changeModal').style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                })












                // Add click event listener to view links
                document.querySelectorAll('.view').forEach(function(link) {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();

                        var templateID = this.dataset.templateid;
                        // Set templateID in hidden input
                        templateid.value = templateID;

                        // Make an AJAX request to fetch the email template data
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    var emailTemplate = JSON.parse(xhr.responseText);
                                    // console.log(emailTemplate.Subject)
                                    // Populate data into modal inputs
                                    title.innerText = emailTemplate.Category;
                                    // Assuming you have a textarea with id 'edit-PlanBody'
                                    bodyInput.value = emailTemplate.Body;
                                    initialBodyValue = bodyInput.value;

                                    // Show modal
                                    document.getElementById('changeModal').style.display = 'block';
                                    document.getElementById('overlay').style.display = 'block';
                                } else {
                                    console.error('Failed to fetch email template data');
                                }
                            }
                        };
                        xhr.open('GET', '../controllers/getSmsTemplate_contr.php?t=' + templateID, true);
                        xhr.send();
                    });
                });

                // Close modal when cancel button is clicked
                document.getElementById('cancel').addEventListener('click', function() {
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('changeModal').style.display = 'none';
                });

                // Close modal when cancel button is clicked
                document.getElementById('cancelBtn').addEventListener('click', function() {
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('newModal').style.display = 'none';
                });

                // Close modal when cancel button is clicked
                document.getElementById('delcancelBtn').addEventListener('click', function() {
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('deleteModal').style.display = 'none';
                });




                updateSmsBtn.addEventListener("click", function() {
                    // Check if the values of the input fields have changed
                    if (bodyInput.value === initialBodyValue) {
                        // Content has not been changed
                        displayMessage("errorMsg", "No changes Made!!", true);
                        return;
                    } else {


                        var formData = new FormData();
                        formData.append("message", bodyInput.value);
                        formData.append("templateid", templateid.value);

                        fetch("../controllers/updateSmsTemplate_contr.php", {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Handle the response from the server
                                    displayMessage("errorMsg", "Updated Successfully!!", false);
                                    // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    displayMessage("errorMsg", "something went wrong!!", false);
                                    document.getElementById('overlay').style.display = 'none';
                                    document.getElementById('changeModal').style.display = 'none';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });





                        // No changes made
                        displayMessage("modalerror", "Updated Successfully!!", false);
                        return; // Exit the function
                    }
                });







                function displayMessage(messageElement, message, isError, ) {
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
                    }, 1000);
                }
            </script>