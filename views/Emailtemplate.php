<?php
require_once "../controllers/session_Config.php";
require_once  '../database/pdo.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getEmail_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$emails = getEmailTemplate($connect);
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
                        <a class="active" href="#">Email Templates</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">


            <!-- modal -->
            <div id="overlay"></div>
            <div class="modal-container" id="deleteModal">
                <input type="hidden" id="deltemplateID">
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
                        <h5 id="title" class="modal-title">Custom</h5>
                        <button type="button" id="cancel" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mt-3">
                        <input type="hidden" id="edit-PlanId" value="">
                        <label for="subjectInput">Subject:</label>
                        <input type="text" id="subjectInput" class="form-control modalInput mb-3">
                        <label for="bodyInput" class="mt-5">Message Body:</label>
                        <div class="textContainer">
                            <div class="options">
                                <!-- Text Format -->
                                <button id="bold" class="option-button format">
                                    <i class="fa-solid fa-bold"></i>
                                </button>
                                <button id="italic" class="option-button format">
                                    <i class="fa-solid fa-italic"></i>
                                </button>
                                <button id="underline" class="option-button format">
                                    <i class="fa-solid fa-underline"></i>
                                </button>
                                <button id="strikethrough" class="option-button format">
                                    <i class="fa-solid fa-strikethrough"></i>
                                </button>
                                <button id="superscript" class="option-button script">
                                    <i class="fa-solid fa-superscript"></i>
                                </button>
                                <button id="subscript" class="option-button script">
                                    <i class="fa-solid fa-subscript"></i>
                                </button>

                                <!-- List -->
                                <button id="insertOrderedList" class="option-button">
                                    <div class="fa-solid fa-list-ol"></div>
                                </button>
                                <button id="insertUnorderedList" class="option-button">
                                    <i class="fa-solid fa-list"></i>
                                </button>

                                <!-- Undo/Redo -->
                                <button id="undo" class="option-button">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>
                                <button id="redo" class="option-button">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>

                                <!-- Link -->
                                <button id="createLink" class="adv-option-button">
                                    <i class="fa fa-link"></i>
                                </button>
                                <button id="unlink" class="option-button">
                                    <i class="fa fa-unlink"></i>
                                </button>

                                <!-- Alignment -->
                                <button id="justifyLeft" class="option-button align">
                                    <i class="fa-solid fa-align-left"></i>
                                </button>
                                <button id="justifyCenter" class="option-button align">
                                    <i class="fa-solid fa-align-center"></i>
                                </button>
                                <button id="justifyRight" class="option-button align">
                                    <i class="fa-solid fa-align-right"></i>
                                </button>
                                <button id="justifyFull" class="option-button align">
                                    <i class="fa-solid fa-align-justify"></i>
                                </button>
                                <button id="indent" class="option-button spacing">
                                    <i class="fa-solid fa-indent"></i>
                                </button>
                                <button id="outdent" class="option-button spacing">
                                    <i class="fa-solid fa-outdent"></i>
                                </button>

                                <!-- Headings -->
                                <select id="formatBlock" class="adv-option-button">
                                    <option value="H1">H1</option>
                                    <option value="H2">H2</option>
                                    <option value="H3">H3</option>
                                    <option value="H4">H4</option>
                                    <option value="H5">H5</option>
                                    <option value="H6">H6</option>
                                </select>

                                <!-- Font -->
                                <select id="fontName" class="adv-option-button"></select>
                                <select id="fontSize" class="adv-option-button"></select>

                                <!-- Color -->
                                <div class="input-wrapper">
                                    <input type="color" id="foreColor" class="adv-option-button" />
                                    <label for="foreColor">Font Color</label>
                                </div>
                                <div class="input-wrapper">
                                    <input type="color" id="backColor" class="adv-option-button" />
                                    <label for="backColor">Highlight Color</label>
                                </div>
                            </div>
                        </div>
                        <!-- <div id="bodyInput" contenteditable="true"></div> -->
                        <div class="form-floating">
                            <textarea class="form-control" style="height: 400px;" id="bodyInput"></textarea>

                        </div>
                        <!-- <textarea contenteditable="true" style="outline: none;" id="bodyInput" class="p-3 b-none" cols="64" rows="15"></textarea> -->
                    </div>
                    <div class="modal-footer">
                        <p id="modalerror"></p>
                        <button type="button" id="updateEmailBtn" class="btn btn-info">Save Changes</button>

                    </div>
                </div>

            </div>


            <div class="modal-container" id="newModal">
                <div id="modalBackground"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="title" class="modal-title">Custom</h5>
                        <button type="button" id="cancelBtn" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mt-3">
                        <label for="Category">Category:</label>
                        <input type="text" id="newcategory" class="form-control modalInput mb-3">
                        <label for="nameInput">Name:</label>
                        <input type="text" id="newnameInput" class="form-control modalInput mb-3">
                        <label for="subjectInput">Subject:</label>
                        <input type="text" id="newsubjectInput" class="form-control modalInput mb-3">
                        <label for="bodyInput" class="mt-5">Message Body:</label>
                        <div class="textContainer">
                            <div class="options">
                                <!-- Text Format -->
                                <button id="bold" class="option-button format">
                                    <i class="fa-solid fa-bold"></i>
                                </button>
                                <button id="italic" class="option-button format">
                                    <i class="fa-solid fa-italic"></i>
                                </button>
                                <button id="underline" class="option-button format">
                                    <i class="fa-solid fa-underline"></i>
                                </button>
                                <button id="strikethrough" class="option-button format">
                                    <i class="fa-solid fa-strikethrough"></i>
                                </button>
                                <button id="superscript" class="option-button script">
                                    <i class="fa-solid fa-superscript"></i>
                                </button>
                                <button id="subscript" class="option-button script">
                                    <i class="fa-solid fa-subscript"></i>
                                </button>

                                <!-- List -->
                                <button id="insertOrderedList" class="option-button">
                                    <div class="fa-solid fa-list-ol"></div>
                                </button>
                                <button id="insertUnorderedList" class="option-button">
                                    <i class="fa-solid fa-list"></i>
                                </button>

                                <!-- Undo/Redo -->
                                <button id="undo" class="option-button">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>
                                <button id="redo" class="option-button">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>

                                <!-- Link -->
                                <button id="createLink" class="adv-option-button">
                                    <i class="fa fa-link"></i>
                                </button>
                                <button id="unlink" class="option-button">
                                    <i class="fa fa-unlink"></i>
                                </button>

                                <!-- Alignment -->
                                <button id="justifyLeft" class="option-button align">
                                    <i class="fa-solid fa-align-left"></i>
                                </button>
                                <button id="justifyCenter" class="option-button align">
                                    <i class="fa-solid fa-align-center"></i>
                                </button>
                                <button id="justifyRight" class="option-button align">
                                    <i class="fa-solid fa-align-right"></i>
                                </button>
                                <button id="justifyFull" class="option-button align">
                                    <i class="fa-solid fa-align-justify"></i>
                                </button>
                                <button id="indent" class="option-button spacing">
                                    <i class="fa-solid fa-indent"></i>
                                </button>
                                <button id="outdent" class="option-button spacing">
                                    <i class="fa-solid fa-outdent"></i>
                                </button>

                                <!-- Headings -->
                                <select id="formatBlock" class="adv-option-button">
                                    <option value="H1">H1</option>
                                    <option value="H2">H2</option>
                                    <option value="H3">H3</option>
                                    <option value="H4">H4</option>
                                    <option value="H5">H5</option>
                                    <option value="H6">H6</option>
                                </select>

                                <!-- Font -->
                                <select id="fontName" class="adv-option-button"></select>
                                <select id="fontSize" class="adv-option-button"></select>

                                <!-- Color -->
                                <div class="input-wrapper">
                                    <input type="color" id="foreColor" class="adv-option-button" />
                                    <label for="foreColor">Font Color</label>
                                </div>
                                <div class="input-wrapper">
                                    <input type="color" id="backColor" class="adv-option-button" />
                                    <label for="backColor">Highlight Color</label>
                                </div>
                            </div>
                        </div>
                        <!-- <div id="bodyInput" contenteditable="true"></div> -->
                        <div class="form-floating">
                            <textarea class="form-control newMessageInput" style="height: 200px;" id="bodyInput"></textarea>

                        </div>
                        <!-- <textarea contenteditable="true" style="outline: none;" id="bodyInput" class="p-3 b-none" cols="64" rows="15"></textarea> -->
                    </div>
                    <div class="modal-footer">
                        <p id="newmodalerror"></p>
                        <button type="button" id="addNewBtn" class="btn btn-info">Save Email</button>

                    </div>
                </div>

            </div>













            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom header">
                    <h3>Create Email Messages</h3>
                    <button id="NewBtn" class="btn btn-primary">New Email</button>
                </div>

                <table class="mt-5">
                    <thead id="thead">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th style="text-align:center">Name</th>
                            <th style="text-align:center">Subject</th>
                            <th>Actions</th>

                    </thead>
                    <tbody id="tableBody" class="tableBody">

                        <?php $counter = 1; ?>
                        <?php if ($emails) : ?>
                            <?php foreach ($emails as $index => $email) : ?>
                                <tr>
                                    <td class="index pe-3"><?= $index + 1; ?></td>
                                    <td class=""><?= $email['Category']; ?></td>
                                    <td style="text-align:center" class=""><?= $email['Name']; ?></td>
                                    <td style="text-align:center" class=""><?= $email['Subject']; ?></td>
                                    <td style="text-align:center">
                                        <a href="#" class="icon view" data-templateid="<?= $email["TemplateID"]; ?>"><img src="../img/eyeIcon.png" alt=""></a>
                                        <?php if ($email['Status'] === 'custom') : ?>
                                            <abbr title="delete"><a href="#" data-deltemplateid="<?= $email["TemplateID"]; ?>" class="icon print"><img src="../img/deleteIcon.png" style="width: 18px; height: 20px;" alt=""></a></abbr>
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
                var updateEmailBtn = document.querySelector("#updateEmailBtn");
                var bodyInput = document.querySelector("#bodyInput");
                var newMessage = document.querySelector(".newMessageInput");
                var newname = document.querySelector("#newnameInput");
                var category = document.querySelector("#newcategory");
                var subjectInput = document.querySelector("#subjectInput");
                var newsubject = document.querySelector("#newsubjectInput");
                var templateid = document.querySelector("#templateID");
                var deltemplateid = document.querySelector("#deltemplateID");
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
                    if (!newname.value || !newsubject.value || !newMessage.value || !category.value) {
                        displayMessage("newmodalerror", "all Inputs must be filled", true);
                        return;
                    }


                    var formData = new FormData();
                    formData.append("newname", newname.value);
                    formData.append("newsubject", newsubject.value);
                    formData.append("newMessage", newMessage.value);
                    formData.append("category", category.value);

                    fetch("../controllers/addEmailTemplate_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Handle the response from the server
                                displayMessage("newmodalerror", "Added Successfully!!", false);
                                // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                document.getElementById('overlay').style.display = 'none';
                                document.getElementById('newModal').style.display = 'none';
                                location.href = "Emailtemplate.php";
                            } else {
                                displayMessage("newmodalerror", "something went wrong!!", false);
                                document.getElementById('overlay').style.display = 'none';
                                document.getElementById('newModal').style.display = 'none';
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




                var deleteEmailBtn = document.querySelector("#deleteEmailBtn");
                deleteEmailBtn.addEventListener("click", function() {

                    var templateId = document.querySelector("#deltemplateID").value;

                    // Check if templateId is not empty
                    if (!templateId) {
                        displayMessage("deleteModal", "something went wrong!!", false);
                        return;
                    }


                    var formData = new FormData();
                    formData.append("templateId", templateId);

                    fetch("../controllers/deleteEmailTemplate_contr.php", {
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
                                    subjectInput.value = emailTemplate.Subject;
                                    initialSubjectValue = subjectInput.value;
                                    // Assuming you have a textarea with id 'edit-PlanBody'
                                    bodyInput.value = emailTemplate.Body;
                                    initialBodyValue = bodyInput.value;
                                    document.getElementById('title').innerText = emailTemplate.Category;

                                    // Show modal
                                    document.getElementById('changeModal').style.display = 'block';
                                    document.getElementById('overlay').style.display = 'block';
                                } else {
                                    console.error('Failed to fetch email template data');
                                }
                            }
                        };
                        xhr.open('GET', '../controllers/getEmailTemplate_contr.php?t=' + templateID, true);
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




                updateEmailBtn.addEventListener("click", function() {
                    // Check if the values of the input fields have changed
                    if (bodyInput.value === initialBodyValue) {
                        // Content has not been changed
                        displayMessage("modalerror", "No changes Made!!", true);
                        return;
                    } else {


                        var formData = new FormData();
                        formData.append("bodyInput", bodyInput.value);
                        formData.append("subjectInput", subjectInput.value);
                        formData.append("templateid", templateid.value);

                        fetch("../controllers/updateEmailTemplate_contr.php", {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Handle the response from the server
                                    displayMessage("modalerror", "Updated Successfully!!", false);
                                    // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                    document.getElementById('overlay').style.display = 'none';
                                    document.getElementById('changeModal').style.display = 'none';
                                } else {
                                    displayMessage("modalerror", "something went wrong!!", false);
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




















                let optionsButtons = document.querySelectorAll(".option-button");
                let advancedOptionButton = document.querySelectorAll(".adv-option-button");
                let fontName = document.getElementById("fontName");
                let fontName2 = document.getElementById("fontName2");
                let fontSizeRef = document.getElementById("fontSize");
                let fontSizeRef2 = document.getElementById("fontSize2");
                let writingArea = document.getElementById("bodyInput");
                let linkButton = document.getElementById("createLink");
                let alignButtons = document.querySelectorAll(".align");
                let spacingButtons = document.querySelectorAll(".spacing");
                let formatButtons = document.querySelectorAll(".format");
                let scriptButtons = document.querySelectorAll(".script");

                //List of fontlist
                let fontList = [
                    "Arial",
                    "Verdana",
                    "Times New Roman",
                    "Garamond",
                    "Georgia",
                    "Courier New",
                    "cursive",
                ];

                //Initial Settings
                const initializer = () => {
                    //function calls for highlighting buttons
                    //No highlights for link, unlink,lists, undo,redo since they are one time operations
                    highlighter(alignButtons, true);
                    highlighter(spacingButtons, true);
                    highlighter(formatButtons, false);
                    highlighter(scriptButtons, true);

                    //create options for font names
                    fontList.map((value) => {
                        let option = document.createElement("option");
                        option.value = value;
                        option.innerHTML = value;
                        fontName.appendChild(option);
                    });

                    //create options for font names
                    fontList.map((value) => {
                        let option = document.createElement("option");
                        option.value = value;
                        option.innerHTML = value;
                        fontName2.appendChild(option);
                    });

                    //fontSize allows only till 7
                    for (let i = 1; i <= 7; i++) {
                        let option = document.createElement("option");
                        option.value = i;
                        option.innerHTML = i;
                        fontSizeRef.appendChild(option);
                    }

                    //default size
                    fontSizeRef.value = 3;

                    //fontSize allows only till 7
                    for (let i = 1; i <= 7; i++) {
                        let option = document.createElement("option");
                        option.value = i;
                        option.innerHTML = i;
                        fontSizeRef2.appendChild(option);
                    }

                    //default size
                    fontSizeRef2.value = 3;
                };

                //main logic
                const modifyText = (command, defaultUi, value) => {
                    //execCommand executes command on selected text
                    document.execCommand(command, defaultUi, value);
                };

                //For basic operations which don't need value parameter
                optionsButtons.forEach((button) => {
                    button.addEventListener("click", () => {
                        modifyText(button.id, false, null);
                    });
                });

                //options that require value parameter (e.g colors, fonts)
                advancedOptionButton.forEach((button) => {
                    button.addEventListener("change", () => {
                        modifyText(button.id, false, button.value);
                    });
                });

                //link
                linkButton.addEventListener("click", () => {
                    let userLink = prompt("Enter a URL");
                    //if link has http then pass directly else add https
                    if (/http/i.test(userLink)) {
                        modifyText(linkButton.id, false, userLink);
                    } else {
                        userLink = "http://" + userLink;
                        modifyText(linkButton.id, false, userLink);
                    }
                });

                //Highlight clicked button
                const highlighter = (className, needsRemoval) => {
                    className.forEach((button) => {
                        button.addEventListener("click", () => {
                            //needsRemoval = true means only one button should be highlight and other would be normal
                            if (needsRemoval) {
                                let alreadyActive = false;

                                //If currently clicked button is already active
                                if (button.classList.contains("active")) {
                                    alreadyActive = true;
                                }

                                //Remove highlight from other buttons
                                highlighterRemover(className);
                                if (!alreadyActive) {
                                    //highlight clicked button
                                    button.classList.add("active");
                                }
                            } else {
                                //if other buttons can be highlighted
                                button.classList.toggle("active");
                            }
                        });
                    });
                };

                const highlighterRemover = (className) => {
                    className.forEach((button) => {
                        button.classList.remove("active");
                    });
                };

                window.onload = initializer();










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