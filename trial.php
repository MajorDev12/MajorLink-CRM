<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Drop Down Sidebar Menu | CodingLab </title>
    <link rel="stylesheet" href="style.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Google Fonts Import Link */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --poppins: 'Poppins', sans-serif;
            --lato: 'Lato', sans-serif;
            --light: #F9F9F9;
            --light-green: #ecfaf6;
            --green: #2cce89;
            --blue: #3C91E6;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --light-dark: #222831;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 280px;
            color: var(--dark);
            background: var(--light);
            font-family: var(--lato);
            z-index: 100;
            transition: all 0.5s ease;
            /* overflow-x: auto; */
            scrollbar-width: none;
        }

        #sidebar.hide {
            width: 60px;
        }

        #sidebar .brand {
            font-size: 24px;
            font-weight: 700;
            height: 56px;
            display: flex;
            align-items: center;
            color: var(--blue);
            position: sticky;
            top: 0;
            left: 0;
            background: var(--light);
            z-index: 500;
            padding-bottom: 20px;
            box-sizing: content-box;
        }

        #sidebar .brand .logoImg {
            margin-left: 10%;
            margin-right: 4%;
            display: flex;
            justify-content: center;
            border-radius: 50%;
        }

        #sidebar .brand .text {
            color: var(--blue);
            font-weight: 600;
            transition: 0.3s ease;
            transition-delay: 0.1s;
        }

        #sidebar.hide .brand .text {
            transition-delay: 0s;
            opacity: 0;
            pointer-events: none;
        }

        #sidebar .nav-links {
            width: 100%;
            height: 80%;
            padding: 80px 0 150px 0;
            overflow: auto;
        }

        #sidebar.hide .nav-links {
            overflow: visible;
        }

        #sidebar .nav-links::-webkit-scrollbar {
            display: none;
        }

        #sidebar .nav-links li {
            position: relative;
            list-style: none;
            transition: all 0.4s ease;
        }

        #sidebar .nav-links li:hover {
            background: var(--grey);
        }

        #sidebar .nav-links li .iocn-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #sidebar .nav-links li .iocn-link:hover {
            background-color: var(--grey);
        }

        #sidebar.hide .nav-links li .iocn-link {
            display: block
        }

        #sidebar .nav-links li .iocn-link a {
            cursor: pointer;
        }

        #sidebar .nav-links li i {
            height: 50px;
            min-width: 78px;
            text-align: center;
            line-height: 50px;
            color: var(--dark);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #sidebar .nav-links li.showMenu i.arrow {
            transform: rotate(-180deg);
        }

        #sidebar.hide .nav-links i.arrow {
            display: none;
        }

        #sidebar .nav-links li a {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        #sidebar .nav-links li a:hover {
            background-color: var(--grey);
        }

        #sidebar .nav-links li a .link_name {
            font-size: 18px;
            font-weight: 500;
            color: var(--dark);
            transition: all 0.4s ease;
        }

        #sidebar.hide .nav-links li a .link_name {
            opacity: 0;
            pointer-events: none;
        }

        #sidebar .nav-links li .sub-menu {
            padding: 6px 6px 14px 80px;
            margin-top: -10px;
            background: var(--grey);
            display: none;
        }

        #sidebar .nav-links li.showMenu .sub-menu {
            display: block;
        }

        #sidebar .nav-links li .sub-menu a {
            color: var(--dark);
            font-size: 15px;
            padding: 5px 0;
            white-space: nowrap;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        #sidebar .nav-links li .sub-menu a:hover {
            background-color: var(--grey);
            opacity: 0.2;
        }

        #sidebar.hide .nav-links li .sub-menu {
            position: absolute;
            left: 100%;
            top: -10px;
            margin-top: 0;
            padding: 10px 20px;
            border-radius: 0 6px 6px 0;
            opacity: 0;
            display: block;
            pointer-events: none;
            transition: 0s;
        }

        #sidebar.hide .nav-links li:hover .sub-menu {
            top: 0;
            opacity: 1;
            pointer-events: auto;
            transition: all 0.4s ease;
        }

        #sidebar .nav-links li .sub-menu .link_name {
            display: none;
        }

        #sidebar.hide .nav-links li .sub-menu .link_name {
            font-size: 18px;
            opacity: 1;
            display: block;
        }

        #sidebar .nav-links li .sub-menu.blank {
            opacity: 1;
            pointer-events: auto;
            padding: 3px 20px 6px 16px;
            opacity: 0;
            pointer-events: none;
        }

        #sidebar .nav-links li:hover .sub-menu.blank {
            top: 50%;
            transform: translateY(-50%);
        }


        @media (max-width: 768px) {
            #sidebar.hide .nav-links li .sub-menu {
                display: none;
            }

            #sidebar {
                width: 78px;
            }

            #sidebar.hide {
                width: 0;
            }

        }
    </style>
</head>

<body>
    <div id="sidebar" class="hide">
        <a href="#" class="brand ml-2">
            <img src="img/company_logo.png" class="logoImg" alt="" style="width: 50px; height: 50px;">
            <span class="text">MajorLink</span>
        </a>
        <ul class="nav-links">
            <li>
                <a href="#">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="#">Dashboard</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-user-plus'></i>
                        <span class="link_name">Customer</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name">Customer</a></li>
                    <li><a href="addClient.php">Add Client</a></li>
                    <li><a href="viewClient.php">View Client</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-edit'></i>
                        <span class="link_name">Setup</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name">Setup</a></li>
                    <li><a href="addClient.php">Add Client</a></li>
                    <li><a href="addArea.php">Add Area</a></li>
                    <li><a href="addSubarea.php">Add Sub-Area</a></li>
                    <li><a href="addplan.php">Add Plan</a></li>
                    <li><a href="addProduct.php">Add Product</a></li>
                    <li><a href="ExpenseType.php">Add Expense Type</a></li>
                    <li><a href="paymentMethod.php">Add Payment Method</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-credit-card-front'></i>
                        <span class="link_name">Billing</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="#">Billing</a></li>
                    <li><a href="makePayment.php">First Payment</a></li>
                    <li><a href="advancePayment.php">Advance Payment</a></li>
                    <li><a href="viewTransaction.php">View Transactions</a></li>
                    <li><a href="addExpense.php">Add Expense</a></li>
                    <li><a href="addSale.php">Add Sale</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bxs-report'></i>
                        <span class="link_name">Reports</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>

                <ul class="sub-menu blank">
                    <li><a class="link_name" href="#">Reports</a></li>
                    <li><a href="revenue.php">Revenue</a></li>
                    <li><a href="expense.php">Expense</a></li>
                    <li><a href="incomeVsExpense.php">Income Vs Expense</a></li>
                    <!-- <li><a href="summary.php">Business Summary</a></li> -->
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-bell'></i>
                        <span class="link_name">Notifications</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Notifications</a></li>
                    <li><a href="invoice.php" data-target="invoice">Invoice</a></li>
                    <li><a href="email.php" data-target="email">Email</a></li>
                    <li><a href="sms.php" data-target="sms">Sms</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-cog'></i>
                        <span class="link_name">Management</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="#">Management</a></li>
                    <li><a href="settings.php" data-target="settings">Settings</a></li>
                    <li><a href="profile.php" data-target="systemlogs">Profile</a></li>
                </ul>
            </li>


        </ul>
    </div>

    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
                arrowParent.classList.toggle("showMenu");
            });
        }
        let sidebar = document.querySelector("#sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("hide");
        });
    </script>
</body>

</html>