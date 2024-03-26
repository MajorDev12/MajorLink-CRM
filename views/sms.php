<?php require_once "../controllers/session_Config.php"; ?>

<?php
require_once  '../database/pdo.php';
require_once  '../modals/getClientsNames_mod.php';
// require_once  '../modals/viewSingleUser_mod.php';
// require_once  '../modals/addInvoice_mod.php';
// require_once  '../modals/addSale_mod.php';
// require_once  '../modals/addPlan_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
?>
<?php require_once "header.php"; ?>

<style>
    ::selection {
        color: #fff;
        background: #664AFF;
    }

    .wrapper {
        max-width: 300px;
        margin: 10px auto;
    }

    .wrapper .search-input {
        background: #fff;
        border-radius: 5px;
        position: relative;
        box-shadow: 0px 1px 5px 3px rgba(0, 0, 0, 0.12);
    }

    .search-input input {
        height: 35px;
        width: 100%;
        outline: none;
        border: none;
        border-radius: 5px;
        padding: 0 60px 0 20px;
        font-size: 18px;
        box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .search-input.active input {
        border-radius: 5px 5px 0 0;
    }

    .search-input .autocom-box {
        padding: 0;
        opacity: 0;
        pointer-events: none;
        max-height: 280px;
        overflow-y: auto;
    }

    .search-input.active .autocom-box {
        padding: 10px 8px;
        opacity: 1;
        pointer-events: auto;
    }

    .autocom-box li {
        list-style: none;
        padding: 8px 12px;
        display: none;
        width: 100%;
        cursor: default;
        border-radius: 3px;
    }

    .search-input.active .autocom-box li {
        display: block;
    }

    .autocom-box li:hover {
        background: #efefef;
    }

    .search-input .icon {
        position: absolute;
        right: 0px;
        top: 0px;
        height: 55px;
        width: 55px;
        text-align: center;
        line-height: 55px;
        font-size: 20px;
        color: #644bff;
        cursor: pointer;
    }

    /* Styling for the dropdown container */
    .dropdown {
        display: inline-block;
    }


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
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
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
        height: 50vh;
    }

    .options .active {
        background-color: #e0e9ff;
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
                        <a class="active" href="#">Invoices</a>
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

                <h3 class="mb-5">Send Mass Sms</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="dropdown">
                            <button class="dropbtn">Select Area</button>
                            <div class="dropdown-content">
                                <label>
                                    <input type="radio" name="options" value="option1"> Pipeline
                                </label>
                                <label>
                                    <input type="radio" name="options" value="option2"> Lanet
                                </label>
                                <label>
                                    <input type="radio" name="options" value="option3"> Mzee Wanyama
                                </label>
                                <!-- Add more options as needed -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="dropdown">
                            <button class="dropbtn">Select Sub Area</button>
                            <div class="dropdown-content">
                                <label>
                                    <input type="checkbox" name="options" value="option1"> Pakawa
                                </label>
                                <label>
                                    <input type="checkbox" name="options" value="option2"> Jb
                                </label>
                                <label>
                                    <input type="radio" name="options" value="option3"> Trizer
                                </label>
                                <!-- Add more options as needed -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="">Send To All</label>
                        <input type="radio" name="" id="">
                    </div>
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
                    <div id="text-input" contenteditable="true"></div>
                </div>
                <!-- Textarea -->
                <button class="btn btn-primary">Send</button>
            </div>

            <div class="content">
                <h3 class="mb-5">Single User</h3>
                <label for="">Search Client</label>
                <input type="search" name="" id="">
                <button class="btn btn-primary">Search</button>
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
                    <div id="text-input" contenteditable="true"></div>
                </div>
                <!-- Textarea -->
                <button class="btn btn-primary">Send</button>
            </div>

            <script>
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
            </script>