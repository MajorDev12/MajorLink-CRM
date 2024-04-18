<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/getClient_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>
<?php require_once "header.php"; ?>
<style>
    .main-content .clienttable-view .head .tableActions {
        display: flex;
        flex-direction: row;
        justify-content: end;
    }

    .main-content .clienttable-view .head .tableActions .bx {
        padding: 5px;
        cursor: pointer;
    }

    .main-content .clienttable-view .head .tableActions .searchBtn,
    .main-content .clienttable-view .head .tableActions .searchInput {
        border: 1px solid var(--dark-grey);
        outline: none;
        display: none;
        transition: all 0.5s ease-in-out;
    }

    .show {
        display: inline-block !important;
    }

    .main-content .clienttable-view .head .tableActions .searchInput {
        width: 60%;
        padding-left: 10px;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        font-size: 14px;
    }

    .main-content .clienttable-view .head .tableActions .searchBtn {
        background-color: var(--light);
        color: var(--dark);
        padding: 2px;
        margin-right: 2px;
        font-size: 14px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    .main-content .clienttable-view .head .tableActions .searchBtn:hover {
        color: var(--light-green);
        background-color: var(--blue);
    }

    .main-content .clienttable-view .head .tableActions .filterdiv {
        position: absolute;
        top: 100%;
        display: none;
        flex-direction: column;
        width: 15%;
        background-color: var(--light);
        box-shadow: 2px 2px 5px var(--light-green);
    }

    .main-content .clienttable-view .head .tableActions .filterdiv p {
        padding-bottom: 5px;
        border-bottom: 1px solid var(--grey);
        font-weight: 500;
    }

    .main-content .clienttable-view .head .tableActions .filterdiv span {
        font-size: 12px;
    }

    .viewBtn {
        width: 15px;
    }

    .icon {
        width: 40px;
    }

    .modal-body p {
        color: var(--light-dark);
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
                        <a href="main.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">List Customers</a>
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

            <div id="overlay"></div>
            <div class="modal-container" id="deleteModal">
                <div id="modalBackground"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p class="mt-3">Are you sure you want to proceed? This action will result in the permanent deletion of the client and all associated data.</p>
                        <input type="hidden" id="deleteClientId" value="">
                    </div>
                    <div class="modal-footer">
                        <p id="errordelmodal"></p>
                        <button type="button" id="deleteButton" class="btn btn-danger ml-3">Delete</button>
                        <button type="button" id="cancel" class="btn btn-primary ml-3">Cancel</button>
                    </div>
                </div>
            </div>




            <div class="clienttable-view">
                <div class="order">

                    <div class="head">
                        <h3>Customers List</h3>
                        <!-- <i class='bx bx-search'></i>
                        <i class='bx bxs-printer'></i>
                        <i class='bx bxs-spreadsheet'></i>
                        <i class='bx bx-filter'></i> -->


                        <div class="tableActions">
                            <input type="submit" value="Search" class="searchBtn" id="searchBtn1">
                            <input type="search" class="searchInput" id="searchInput1">
                            <i class='bx bx-search' id="searchIcon1" onclick=""></i>
                            <i class='bx bxs-printer' id="printIcon1"></i>
                            <i class='bx bxs-spreadsheet' id="spreadsheetIcon1"></i>
                            <!-- <i class='bx bx-filter'></i> -->
                            <div class="filterdiv shadow-sm p-3 mb-5 bg-white rounded row">
                                <p>Filter</p>
                                <div class="col">
                                    <input type="radio" name="" id="">
                                    <span>None</span>
                                </div>
                                <div class="col">
                                    <input type="radio" name="" id="">
                                    <span>Start Date</span>
                                </div>
                                <div class="col">
                                    <input type="radio" name="" id="">
                                    <span>Due Date</span>
                                </div>
                                <div class="col">
                                    <input type="radio" name="" id="">
                                    <span>Status</span>
                                </div>
                            </div>
                        </div>


                    </div>


                    <table>
                        <thead id="thead">
                            <tr>
                                <th class="pe-3">#</th>
                                <th>Username</th>
                                <th>Area</th>
                                <th>SubArea</th>
                                <th>Expire</th>
                                <th>Current Plan</th>
                                <th>Status</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody class="searchData">
                            <?php
                            $clientData = getClientData($connect);
                            $counter = 1;
                            ?>
                            <?php foreach ($clientData as $client) : ?>
                                <tr>
                                    <td><?php echo $counter; ?></td>
                                    <td class=""><?php echo $client['FirstName'] . ' ' . $client['LastName']; ?></td>
                                    <td><span class=""><?php echo $client['Area']; ?></span></td>
                                    <td><span class=""><?php echo $client['SubArea']; ?></span></td>
                                    <td><span class=""><?php echo $client['ExpireDate']; ?></span></td>
                                    <td><span class=""><?php echo $client['Plan']; ?></span></td>
                                    <?php echo "<td><span class=''>" . ($client['ActiveStatus'] == 1 ? 'Active' : 'Inactive') . "</span></td>"; ?>
                                    <td>
                                        <!-- Update the onclick event to trigger the form submission -->
                                        <a href="viewSingleUser.php?id=<?= $client['ClientID'] ?>" class="btn btn-primary me-2 icon view"><img src="../img/eyeIcon.png" class="viewBtn" alt=""></a>
                                        <a href="#" data-client-id="<?= $client['ClientID'] ?>" class="btn btn-secondary delete-btn"><img src="../img/deleteIcon.png" class="viewBtn" alt=""></a>
                                    </td>
                                </tr>
                                <?php
                                // Increment the counter after each iteration
                                $counter++;
                                ?>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <?php require_once "footer.php"; ?>



            <script>
                document.addEventListener("DOMContentLoaded", function() {

                    const searchIcon1 = document.getElementById('searchIcon1');
                    const searchInput1 = document.getElementById('searchInput1');
                    const searchBtn1 = document.getElementById('searchBtn1');
                    const printIcon1 = document.getElementById('printIcon1');
                    const spreadsheetIcon1 = document.getElementById('spreadsheetIcon1');
                    const thead = document.getElementById('thead');
                    const searchData = document.getElementsByClassName('searchData');
                    const pageUrl1 = '../controllers/searchClientData_contr.php';
                    addSearchEventListener(searchIcon1, searchInput1, searchBtn1);
                    Search(searchBtn1, searchInput1, pageUrl1, 'searchData');



                    printIcon1.addEventListener('click', function() {
                        // Access the first element with the class 'searchSales'
                        printTable(printIcon1, searchData[0], 'thead');
                    });


                    spreadsheetIcon1.addEventListener("click", function() {
                        exportToCSV(thead, searchData[0]);
                    })








                    // Get the delete button elements
                    const deleteButtons = document.querySelectorAll(".delete-btn");

                    // Get the delete modal and its components
                    const deleteModal = document.getElementById("deleteModal");
                    const closeModalBtn = document.getElementById("closeDelModal");
                    const cancel = document.getElementById("cancel");
                    const deleteButton = document.getElementById("deleteButton");
                    const deleteClientIdInput = document.getElementById("deleteClientId");
                    const errorDelModal = document.getElementById("errordelmodal");
                    const overlay = document.getElementById("overlay");

                    // Add event listener to each delete button
                    deleteButtons.forEach(function(button) {
                        button.addEventListener("click", function() {
                            // Get the client ID from the data-client-id attribute
                            const clientId = button.getAttribute("data-client-id");
                            // Set the client ID in the hidden input field
                            deleteClientIdInput.value = clientId;
                            // Show the delete modal
                            deleteModal.style.display = "block";
                            overlay.style.display = "block";
                        });
                    });

                    // Add event listener to close the modal when close button is clicked
                    closeModalBtn.addEventListener("click", function() {
                        deleteModal.style.display = "none";
                        overlay.style.display = "none";

                    });

                    cancel.addEventListener("click", function() {
                        deleteModal.style.display = "none";
                        overlay.style.display = "none";

                    });

                    // Add event listener to delete button in the modal
                    deleteButton.addEventListener("click", function() {
                        // Get the client ID from the hidden input field
                        const clientId = deleteClientIdInput.value;
                        // Call the function to delete the client
                        // console.log(ClientId)
                        deleteClient(clientId);
                    });

                    // Function to delete the client using Fetch API
                    function deleteClient(clientId) {
                        var formData = new FormData();
                        formData.append("clientId", clientId);


                        fetch("../controllers/deleteClient_contr.php", {
                                method: "POST",
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Check if deletion was successful
                                if (data.success) {
                                    // Close the modal
                                    // displayMessage("errorDelModal", "Successfully deleted", false);
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    // Display error message
                                    displayMessage("errorDelModal", "Error deleting client", true);
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                displayMessage("errorDelModal", "Error deleting client", true);
                            });
                    }




                    function addSearchEventListener(searchIcon, searchInput, searchBtn) {
                        searchIcon.addEventListener('click', function() {
                            // Toggle the 'show' class on searchInput and searchBtn
                            searchInput.classList.toggle('show');
                            searchBtn.classList.toggle('show');

                            // Focus on the searchInput when it becomes visible
                            if (searchInput.classList.contains('show')) {
                                searchInput.focus();
                            }
                        });
                    }
                });
            </script>