<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
<!-- MDB -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css" rel="stylesheet" />
<style>
#storeLogo img {
    height: 100px;
}

nav {
    background-color: black;
}

.word {
    color: white;
}

/*when the word in navigation bar is hovered*/
.word:hover {
    color: grey;
    transition: 0.3s;
}
</style>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Navbar brand -->
        <div id="storeLogo">
            <div class="image"><img src="picture/logo/c.png"></div>
        </div>


        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
            data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <a class="nav-link word" href="home.php">Home</a>
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link word dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-mdb-toggle="dropdown" aria-expanded="false">
                        Product
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="create.php">Create Product <img src='picture/product/create.png'
                                    style='width: 10%;'></a></li>
                        <li><a class="dropdown-item" href="product_read.php">Product List <img
                                    src='picture/product/read.png' style='width: 10%;'></a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link word dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-mdb-toggle="dropdown" aria-expanded="false">
                        Customer
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="customer.php">Create Customer <img
                                    src='picture/product/create.png' style='width: 10%;'></a></li>
                        <li><a class="dropdown-item" href="customer_read.php">Customer List <img
                                    src='picture/product/read.png' style='width: 10%;'></a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link word dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-mdb-toggle="dropdown" aria-expanded="false">
                        Order
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="orders.php">Create Order <img src='picture/product/create.png'
                                    style='width: 10%;'></a></li>
                        <li><a class="dropdown-item" href="order_read.php">Order List <img src='picture/product/read.png'
                                    style='width: 10%;'></a></li>
                    </ul>
                </li>
            </ul>

            <!-- Icons -->
            <ul class="navbar-nav d-flex flex-row me-1">
                <li class="nav-item me-3 me-lg-0">
                    <a class="nav-link word" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>