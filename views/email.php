<?php require_once "../controllers/session_Config.php"; ?>

<?php
require_once  '../database/pdo.php';
require_once  '../modals/addArea_mod.php';
require_once  '../modals/getSubarea_mod.php';
require_once  '../modals/getEmail_mod.php';
require_once  '../modals/getClientsNames_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

?>
<?php require_once "header.php"; ?>




<style>
    /* Styling for the dropdown container */
    .dropdown {
        display: inline-block;
    }

    /* #customer {
        margin-top: 20%;
        padding: 70px;
    } */

    .dropdown .dropbtn {
        background-color: #f1f1f1;
        padding: 10px;
        border: 1px solid #ccc;
        cursor: pointer;
    }


    .dropdown-content {
        display: none;
        background-color: #fff;
        min-width: 160px;
        border: 1px solid #ccc;
    }


    .dropdown-content label {
        display: block;
        padding: 8px 12px;
    }


    .dropdown:hover .dropdown-content {
        display: block;
    }


    /* textarea */

    .textContainer {
        background-color: #ffffff;
        padding: 50px 30px;
        border-radius: 10px;
    }

    .options {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 15px;
    }

    .options button {
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


    .options select {
        padding: 7px;
        border: 1px solid #020929;
        border-radius: 3px;
    }

    .options label,
    .options select {
        font-family: "Poppins", sans-serif;
    }

    .options .input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .options input[type="color"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: transparent;
        width: 40px;
        height: 28px;
        border: none;
        cursor: pointer;
    }

    .options input[type="color"]::-webkit-color-swatch {
        border-radius: 15px;
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 3px #020929;
    }

    .options input[type="color"]::-moz-color-swatch {
        border-radius: 15px;
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 3px #020929;
    }

    #text-input {
        margin-top: 10px;
        border: 1px solid #dddddd;
        padding: 20px;
        min-height: 50vh;
    }

    #text-input1 {
        margin-top: 10px;
        border: 1px solid #dddddd;
        padding: 20px;
        min-height: 50vh;
    }

    .options .active {
        background-color: #e0e9ff;
    }

    #areacheckbox {
        display: none;

    }

    #subareacheckbox {
        display: none;
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
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Email</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">

                <h3 class="mb-5">Send Mass Mail</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="dropdown">
                            <label for="">Choose recipient</label>
                            <select name="" id="recipientSelect" class="form-select">
                                <option value="" selected disabled>filter</option>
                                <option value="All">All Customers</option>
                                <option value="Area">Area</option>
                                <option value="SubArea">Sub Area</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>





                    <?php $emailTemplates = getEmailTemplate($connect); ?>
                    <?php if ($emailTemplates) : ?>
                        <div class="col-md-6">
                            <div class="dropdown">
                                <label for="">Choose Message</label>
                                <select id="templateSelect" class="form-select">
                                    <option value="" selected disabled>Template Message</option>
                                    <?php foreach ($emailTemplates as $template) : ?>
                                        <option value="<?= $template["TemplateID"]; ?>"><?= $template["Name"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <?php $areas = getData($connect); ?>
                <?php if ($areas) : ?>

                    <div id="areacheckbox" class="row mt-5">
                        <?php foreach ($areas as $area) : ?>
                            <div class="col-md-3 mt-2">
                                <input type="checkbox" value="<?= $area["AreaID"]; ?>" id="">
                                <span><?= $area["AreaName"]; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else : "no data available"; ?>
                    <?php endif; ?>
                    </div>



                    <?php $subareas = getAllSubareas($connect); ?>
                    <?php if ($subareas) : ?>

                        <div id="subareacheckbox" class="row mt-5">
                            <?php foreach ($subareas as $area) : ?>
                                <div class="col-md-3 mt-2">
                                    <input type="checkbox" value="<?= $area["SubAreaID"]; ?>" id="">
                                    <span><?= $area["SubAreaName"]; ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : "no data available"; ?>
                        <?php endif; ?>
                        </div>











                        <!-- Textarea -->
                        <div class="textContainer mt-5">
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
                            <label class="mt-5" for="backColor">Subject</label>
                            <input id="subjectInput" type="text" class="p-2 masssubject" style="width: 100%;">
                            <label class="mt-5" for="backColor">Message</label>
                            <div id="text-input" class="massmessage" contenteditable="true"></div>
                        </div>
                        <!-- Textarea -->
                        <p id="errorMsg"></p>
                        <button id="massSend" class="btn btn-primary">Send</button>
            </div>








            <div class="content">
                <h3 class="mb-5">Single User</h3>
                <div class="row">

                    <div class="col-md-6">
                        <label for="customer">Customer</label>
                        <select id="customer" class="form-select">
                            <option value="" selected hidden>--Search--</option>
                        </select>
                    </div>



                    <?php if ($emailTemplates) : ?>
                        <div class="col-md-6">
                            <div class="dropdown">
                                <label for="">Choose Message</label>
                                <select id="templateSelect1" class="form-select">
                                    <option value="" selected disabled>Template Message</option>
                                    <?php foreach ($emailTemplates as $template) : ?>
                                        <option value="<?= $template["TemplateID"]; ?>"><?= $template["Name"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <!-- Textarea -->
                <div class="textContainer mt-5">
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
                        <select id="fontName2" class="adv-option-button"></select>
                        <select id="fontSize2" class="adv-option-button"></select>

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

                    <label class="mt-5" for="backColor">Subject</label>
                    <input id="subjectInput1" type="text" class="p-2" style="width: 100%;">
                    <label class="mt-5" for="backColor">Message</label>
                    <div id="text-input1" contenteditable="true"></div>
                </div>
                <!-- Textarea -->
                <p id="errorMsg1"></p>
                <button id="singleSend" class="btn btn-primary">Send</button>
            </div>
            <?php require_once "footer.php"; ?>

            <script>
                var recipientSelect = document.querySelector("#recipientSelect");
                var customerSelect = document.querySelector("#customer");
                var areacheckbox = document.querySelector("#areacheckbox");
                var subareacheckbox = document.querySelector("#subareacheckbox");
                var massSend = document.querySelector("#massSend");
                var singleSend = document.querySelector("#singleSend");
                var singlesubject = document.querySelector("#subjectInput1");
                var masssubject = document.querySelector(".masssubject");
                var singlemessage = document.querySelector("#text-input1");
                var massmessage = document.querySelector(".massmessage");


                massSend.addEventListener("click", function() {
                    const selectedValue = recipientSelect.value;

                    if (!selectedValue) {
                        displayMessage("errorMsg", "Choose a recipient", true);
                        return;
                    }
                    if (!masssubject.value || !massmessage.innerText) {
                        displayMessage("errorMsg", "Fill in the Subject and message first", true);
                        return;
                    }



                    if (selectedValue === "Area") {
                        // Get all checkboxes inside the areacheckbox div
                        const checkboxes = areacheckbox.querySelectorAll('input[type="checkbox"]:checked');

                        // If no checkboxes are checked, display an alert
                        if (checkboxes.length === 0) {
                            displayMessage("errorMsg", "Choose atleast one Area", true);
                            return; // Exit the function
                        }

                        // Extract the values of the checked checkboxes and store them in an array
                        var checkedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
                        // console.log(checkedValues);
                    }


                    if (selectedValue === "SubArea") {
                        // Get all checkboxes inside the areacheckbox div
                        const checkboxes = subareacheckbox.querySelectorAll('input[type="checkbox"]:checked');
                        // If no checkboxes are checked, display an alert
                        if (checkboxes.length === 0) {
                            displayMessage("errorMsg", "Choose atleast one Sub Area", true);
                            return; // Exit the function
                        }

                        // Extract the values of the checked checkboxes and store them in an array
                        var checkedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
                        // console.log(checkedValues);
                    }




                    var formData = new FormData();
                    formData.append("selectedValue", selectedValue);
                    formData.append("checkedValues", checkedValues);
                    formData.append("masssubject", masssubject.value);
                    formData.append("massmessage", massmessage.innerText);

                    fetch("../controllers/massEmail_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Handle the response from the server
                                displayMessage("errorMsg", data.message, false);
                                // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                setTimeout(() => {
                                    window.location.href = "email.php";
                                }, 2000);
                            } else {
                                displayMessage("errorMsg", data.message, true);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                });





                singleSend.addEventListener("click", function() {
                    const selectedCustomer = customerSelect.value;
                    const subject = singlesubject.value;
                    const message = singlemessage.innerText;

                    if (!selectedCustomer) {
                        displayMessage("errorMsg1", "Choose a recipient", true);
                        return;
                    }
                    if (!subject || !message) {
                        displayMessage("errorMsg1", "Fill in the Subject and message first", true);
                        return;
                    }



                    var formData = new FormData();
                    formData.append("selectedCustomer", selectedCustomer);
                    formData.append("subject", subject);
                    formData.append("message", message);

                    fetch("../controllers/singleEmail_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Handle the response from the server
                                displayMessage("errorMsg1", data.message, false);
                                // localStorage.setItem('AddNewClientPaymentToast', 'true');
                                setTimeout(() => {
                                    window.location.href = "email.php";
                                }, 2000);
                            } else {
                                displayMessage("errorMsg1", data.message, true);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                });









                $(document).ready(function() {
                    var customerList = [];

                    <?php $clientData = getClientsNames($connect); ?>
                    <?php foreach ($clientData as $client) : ?>
                        customerList.push({
                            id: "<?php echo $client['ClientID']; ?>",
                            text: "<?php echo $client['FirstName'] . ' ' . $client['LastName']; ?>"
                        });
                    <?php endforeach; ?>

                    $("#customer").select2({
                        data: customerList
                    });



                    // Attach change event listener
                    $("#customer").on("change", function() {
                        selectedClientId = $(this).val();


                        // Make an AJAX request to get client data
                        $.ajax({
                            url: '../controllers/getClientInfo_contr.php',
                            type: 'GET',
                            data: {
                                clientId: selectedClientId
                            },
                            success: function(response) {
                                // Update the client information based on the response
                                $('.clientNames').text(response.FirstName + ' ' + response.LastName);
                            },
                            error: function(error) {
                                console.error('Error fetching client data:', error);
                            }
                        });
                    });


                });








                // Add a change event listener
                recipientSelect.addEventListener("change", function() {
                    // Get the selected value
                    var selectedValue = recipientSelect.value;
                    // Check the selected value and show/hide checkboxes accordingly
                    if (selectedValue === "Area") {
                        areacheckbox.style.display = 'flex';
                        subareacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "SubArea") {
                        subareacheckbox.style.display = 'flex';
                        areacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "All") {
                        subareacheckbox.style.display = 'none';
                        areacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "Active") {
                        subareacheckbox.style.display = 'none';
                        areacheckbox.style.display = 'none';
                    }
                    if (selectedValue === "Inactive") {
                        subareacheckbox.style.display = 'none';
                        areacheckbox.style.display = 'none';
                    }
                });







                document.getElementById('templateSelect').addEventListener('change', function() {
                    var templateId = this.value;
                    if (!templateId) return;

                    // Fetch the email template data
                    fetchEmailTemplate(templateId)
                        .then(data => {
                            // Populate the subject and body inputs with template data
                            document.getElementById('subjectInput').value = data.Subject;
                            document.getElementById('text-input').innerText = data.Body;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });




                document.getElementById('templateSelect1').addEventListener('change', function() {
                    var templateId = this.value;
                    if (!templateId) return;

                    // Fetch the email template data
                    fetchEmailTemplate(templateId)
                        .then(data => {
                            // Populate the subject and body inputs with template data
                            document.getElementById('subjectInput1').value = data.Subject;
                            document.getElementById('text-input1').innerText = data.Body;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });






                function fetchEmailTemplate(templateId) {
                    return fetch('../controllers/getEmailTemplate_contr.php?t=' + templateId)
                        .then(response => response.json());
                }





                let optionsButtons = document.querySelectorAll(".option-button");
                let advancedOptionButton = document.querySelectorAll(".adv-option-button");
                let fontName = document.getElementById("fontName");
                let fontName2 = document.getElementById("fontName2");
                let fontSizeRef = document.getElementById("fontSize");
                let fontSizeRef2 = document.getElementById("fontSize2");
                let writingArea = document.getElementById("text-input");
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