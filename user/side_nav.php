<section id="sidebar">
    <a href="#" class="brand">
        <img src="../img/<?= $companyData[0]["LogoURL"] ?>" class="logoImg" alt="" style="width: 50px; height: 50px;">
        <span class="text"><?= $companyData[0]["CompanyName"] ?></span>
    </a>



    <ul class="nav-links">
        <li>
            <div class="iocn-link">
                <a href="index.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Home</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="index.php">Home</a></li>
            </ul>
        </li>


        <li>
            <div class="iocn-link">
                <a href="../controllers/choosepaymentPage_contr.php">
                    <i class='bx bx-money'></i>
                    <span class="link_name">Make Payment</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="../controllers/choosepaymentPage_contr.php">Make Payment</a></li>
            </ul>
        </li>



        <li>
            <div class="iocn-link">
                <a href="transaction.php">
                    <i class='bx bx-wallet'></i>
                    <span class="link_name">Transactions</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="transaction.php">Transactions</a></li>
            </ul>
        </li>



        <li>
            <div class="iocn-link">
                <a href="notification.php">
                    <i class='bx bxs-bell'></i>
                    <span class="link_name">Notifications</span>
                </a>
            </div>
        </li>



        <li>
            <div class="iocn-link">
                <a href="invoices.php">
                    <i class='bx bx-bar-chart-alt-2'></i>
                    <span class="link_name">Invoices</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="transaction.php">Invoices</a></li>
            </ul>
        </li>


        <li>
            <div class="iocn-link">
                <a href="settings.php">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Settings</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="settings.php">Settings</a></li>
            </ul>
        </li>





        <script>
            // let arrow = document.querySelectorAll(".arrow");
            // for (var i = 0; i < arrow.length; i++) {
            //     arrow[i].addEventListener("click", (e) => {
            //         let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
            //         arrowParent.classList.toggle("showMenu");
            //     });
            // }
        </script>
</section>