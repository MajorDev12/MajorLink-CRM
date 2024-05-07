<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addProduct_contr.php';
require_once  '../modals/addProduct_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$products = getProductData($connect);
?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>

<style>
    p {
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
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Add Product</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">

            <div class="content">


                <div id="overlay"></div>
                <!-- Add this to your HTML for the modal -->
                <div class="modal-container" id="productModal">
                    <div id="modalBackground"></div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Plan</h5>
                            <button type="button" id="closeModal" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit-ProductId" value="">
                            <label for="editPlanName">Name:</label>
                            <input type="text" id="edit-ProductName" class="form-control modalInput">
                            <label for="editPlanVolume">Price:</label>
                            <input type="number" id="edit-ProductPrice" class="form-control modalInput">
                            <label for="editPlanPrice">Notes:</label>
                            <textarea name="" id="edit-ProductNotes" class="form-control modalInput" cols="40" rows="5"></textarea>
                        </div>
                        <div class="modal-footer">
                            <p id="modalerror"></p>
                            <button type="button" class="btn btn-info" data-plan-id="<?= $product['PlanID'] ?>" onclick="updateProductData(this)">Save Changes</button>
                        </div>
                    </div>
                </div>





                <!-- Add this to your HTML for the modal -->
                <div class="modal-container" id="deleteModal">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="mt-3">Are you sure you want to delete this Product?</p>
                            <input type="hidden" id="deleteProductId" value="">
                        </div>
                        <div class="modal-footer">
                            <p id="errordelmodal"></p>
                            <button type="button" id="deleteButton" class="btn btn-danger ml-3" onclick="deleteProductConfirmed()">Delete</button>
                        </div>
                    </div>
                </div>









                <!-- enctype="multipart/form-data" -->

                <form id="addProductForm" class="row g-3 form">

                    <div class="col-md-6">
                        <label for="ProductName">Product Name:</label>
                        <div class="input-group mb-3">
                            <input type="text" name="ProductName" id="ProductName" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="ProductName">Product Price:</label>
                        <div class="input-group mb-3">
                            <input type="number" name="ProductPrice" id="ProductPrice" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label for="">Notes</label>
                        <textarea name="notes" id="notes" cols="10" rows="10"></textarea>
                    </div>
                    <!-- <div class="col-md-6">
                        <label for="ProductName">Product Image:</label>
                        <div class="input-group mb-3">
                            <input type="file" name="ProductImage" id="ProductName" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div> -->

                    <p id="error"></p>
                    <div class="form-group">
                        <button type="button" id="addbtn" name="submitProduct" onclick="addProduct();" class="btn btn-primary">Save</button>
                    </div>
                </form>














                <div class="col-md-12 p-4">
                    <table class="">
                        <thead class="">
                            <tr class="">
                                <th scope="col">No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Notes</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            <?php $products = getProductData($connect);
                            //var_dump($plans['Name']); 
                            ?>
                            <?php foreach ($products as $key => $product) : ?>
                                <tr>
                                    <th class="p-3" scope="row"><?= $key + 1 ?></th>
                                    <td><?= $product['ProductName'] ?></td>
                                    <td><?= $product['Price'] ?></td>
                                    <td><?= $product['Description'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-plan-id="<?= $product['ProductID'] ?>" onclick="editProduct('<?= $product['ProductID'] ?>', '<?= $product['ProductName'] ?>', '<?= $product['Price'] ?>', '<?= $product['Description'] ?>')">Update</button>
                                        <button type="button" id="deleteProductId" class="btn btn-danger" data-plan-id="<?= $product['ProductID'] ?>" onclick="confirmDelete('<?= $product['ProductID'] ?>')">Del</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php require_once "footer.php"; ?>


            <script>
                var loader = document.getElementById("loader");

                function addProduct() {
                    // e.preventDefault();
                    console.log("it runs")
                    document.getElementById("addbtn").disabled = false;
                    var productName = document.getElementById("ProductName").value.trim();
                    var ProductPrice = document.getElementById("ProductPrice").value.trim();
                    var notes = document.getElementById("notes").value.trim();
                    var isValid = true;

                    // displayMessage("error", "Cannot be empty", true);
                    if (!productName || !ProductPrice) {
                        displayMessage("error", "Name and Price Cannot be empty", true);
                        isValid = false;
                        document.getElementById("addbtn").disabled = false;
                        return;
                    }
                    if (!/^[a-zA-Z0-9\s]+$/.test(productName)) {
                        displayMessage("error", "Invalid characters in Name", true);
                        isValid = false;
                        document.getElementById("addbtn").disabled = false;
                        return;
                    }

                    if (!/^\d+$/.test(ProductPrice)) {
                        displayMessage("error", "Only numbers expected!", true);
                        isValid = false;
                        document.getElementById("addbtn").disabled = false;
                        return;
                    }


                    if (!/^[a-zA-Z0-9\s]*$/.test(notes)) {
                        displayMessage("error", "Invalid characters in Notes", true);
                        isValid = false;
                        document.getElementById("addbtn").disabled = false;
                        return;
                    }

                    if (isValid) {
                        // e.stopImmediatePropagation();
                        loader.style.display = "flex";
                        // Create a FormData object to send data via Fetch API
                        var formData = new FormData();
                        formData.append("productName", productName);
                        formData.append("ProductPrice", ProductPrice);
                        formData.append("notes", notes);

                        console.log(productName)
                        console.log(ProductPrice)
                        console.log(notes)

                        // Use Fetch API to send the data
                        fetch('../controllers/addProduct_contr.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById("addbtn").disabled = false;
                                    loader.style.display = "none";
                                    displayMessage("error", "saved Successfuly", false);
                                    document.getElementById("addProductForm").reset();
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    displayMessage("error", "Error fetching Data", true);
                                }
                            })
                            .catch(error => {
                                displayMessage("error", "Network Error", true);
                                document.getElementById("addbtn").disabled = false;
                                loader.style.display = "none";
                            })
                    } else {
                        displayMessage("error", "Null", true);
                    }

                }












                var initialProductName = "";
                var initialProductPrice = "";
                var initialProductNotes = "";


                function editProduct(ProductId, ProductName, ProductPrice, ProductNotes) {
                    document.getElementById('edit-ProductId').value = ProductId;
                    document.getElementById('edit-ProductName').value = initialProductName = ProductName;
                    document.getElementById('edit-ProductPrice').value = initialProductPrice = ProductPrice;
                    document.getElementById('edit-ProductNotes').value = initialProductNotes = ProductNotes;
                    // Show the modal (you need to implement your own showModal function)
                    showModal();
                }




                // Function to check for changes and send data for update
                function updateProductData(button) {
                    console.log("it works");
                    var ProductId = document.getElementById('edit-ProductId').value;
                    var updatedProductName = document.getElementById('edit-ProductName').value;
                    var updatedProductPrice = document.getElementById('edit-ProductPrice').value;
                    var updatedProductNotes = document.getElementById('edit-ProductNotes').value;
                    var modalInputs = document.querySelectorAll('.modalInput');
                    var isValid = true;

                    modalInputs.forEach(dataInput => {
                        if (!dataInput.value.trim()) {
                            displayMessage("modalerror", `Cannot be empty ${dataInput.id}`, true);
                            isValid = false;
                            return;
                        } else if (!/^[a-zA-Z0-9\s]+$/.test(dataInput.value.trim())) {
                            displayMessage("modalerror", `Only letters and numbers allowed in: ${dataInput.id}`, true);
                            isValid = false;
                            return;
                        }
                    });
                    if (!isValid) {
                        return; // Exit the function if validation fails
                    }

                    // Check if changes are made
                    if (
                        updatedProductName !== initialProductName ||
                        updatedProductPrice !== initialProductPrice ||
                        updatedProductNotes !== initialProductNotes
                    ) {
                        loader.style.display = "flex";
                        // Changes detected, send data for update
                        // Perform your AJAX request here
                        // You can use the Fetch API for this purpose
                        var sendData = new FormData();
                        sendData.append('ProductId', ProductId);
                        sendData.append('updatedProductName', updatedProductName);
                        sendData.append('updatedProductPrice', updatedProductPrice);
                        sendData.append('updatedProductNotes', updatedProductNotes);

                        fetch('../controllers/updateProduct_contr.php', {
                                method: 'POST',
                                body: sendData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                    hideModal();
                                    loader.style.display = "none";
                                    // displayMessage("modalerror", "Updated Successfuly", false);
                                } else {
                                    // Handle failure (e.g., display an error message)
                                    console.error("Update failed: " + data.error);
                                    displayMessage("modalerror", "Update failed: " + data.error, true);
                                }
                            })
                            .catch(error => {
                                // Handle network or request errors
                                console.error("An error occurred: " + error);
                                displayMessage("modalerror", "An error occurred: " + error, true);
                                loader.style.display = "none";
                                // setTimeout(() => {
                                //     hideModal();
                                // }, 2000);
                            });
                    } else {
                        // No changes made
                        displayMessage("modalerror", "No changes made", true);
                    }
                }








                function confirmDelete(ProductId) {
                    // Set the planId to a hidden input in the delete confirmation modal
                    document.getElementById('deleteProductId').value = ProductId;
                    // Show the delete confirmation modal
                    showDeleteModal();
                }






                function deleteProductConfirmed() {
                    var ProductId = document.getElementById('deleteProductId').value;
                    button = document.querySelector("#deleteButton");

                    if (ProductId !== null) {
                        loader.style.display = "flex";

                        var sendData = new FormData();
                        sendData.append('ProductId', ProductId);

                        fetch('../controllers/deleteProduct_contr.php', {
                                method: 'POST',
                                body: sendData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    hideDeleteModal();
                                    planId = null;
                                    loader.style.display = "none";
                                    displayMessage("errordelmodal", "Deleted Successfuly", false);
                                    location.reload();
                                } else {
                                    // Handle failure (e.g., display an error message)
                                    console.error("Update failed: " + data.error);
                                    displayMessage("errordelmodal", "Delete failed: " + data.error, true);
                                }
                            })
                            .catch(error => {
                                // Handle network or request errors
                                document.getElementById("errordelmodal").innerText = "error";
                                console.error("An error occurred: " + error);
                                displayMessage("errordelmodal", "An error occurred: " + error, true);
                                loader.style.display = "none";
                                setTimeout(() => {
                                    hideModal();
                                }, 2000);
                            });
                    } else {
                        displayMessage("errordelmodal", "NULL", false);
                    }
                }










                closeModal.addEventListener('click', function() {
                    hideModal();
                })

                closeDelModal.addEventListener('click', function() {
                    hideDeleteModal();
                })

                function showDeleteModal() {
                    document.getElementById('deleteModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                }


                function hideDeleteModal() {
                    document.getElementById('deleteModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }

                // Show modal and overlay
                function showModal() {
                    document.getElementById('productModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                    document.getElementById('overlay').style.transition = '.3s';
                }

                // Hide modal and overlay
                function hideModal() {
                    document.getElementById('productModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }
            </script>