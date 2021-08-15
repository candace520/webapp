<style>
nav {
    background-color: black;
}

#storeLogo img {
    width: 90px;
    height: 80px;
}

.navContent {
    background-color: grey;
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <div id="storeLogo">
            <img src="picture/logo/candace.png">
        </div>
        <button class="navbar-toggler ml-auto custom-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navContent rounded-pill col-10">
            <div class="collapse navbar-collapse justify-content-around" id="navbarToggle">
                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <a class="nav-link word" href="index.php">Home </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="create.php" id="navbarDarkDropdownMenuLink"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Product
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item word" href="create.php">Create Product <img
                                        src='picture/img/create.png' style='width: 15%;'></a></li>
                            <li><a class="dropdown-item" href="product_read.php">Product List <img
                                        src='picture/img/read.png' style='width: 15%;'></a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="customer.php" id="navbarDarkDropdownMenuLink"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Customer
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="customer.php">Create Customer <img
                                        src='picture/img/create.png' style='width: 15%;'></a></li>
                            <li><a class="dropdown-item" href="customer_read.php">Customer List <img
                                        src='picture/img/read.png' style='width: 15%;'></a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="orders.php" id="navbarDarkDropdownMenuLink"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Order
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="orders.php">Create Order <img
                                        src='picture/img/create.png' style='width: 15%;'></a></li>
                            <li><a class="dropdown-item" href="order_read.php">Order List <img
                                        src='picture/img/read.png' style='width: 15%;'></a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php" >Contact Us</a>
                    </li>
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</nav>