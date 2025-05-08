<!-- sidebar.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div id="sidebar">
    <div class="sidebar-header">📚 Admin Panel</div>
    <ul class="sidebar-menu">
        <!-- Books -->
        <li>
            <button onclick="toggleDropdown('bookDropdown')" class="dropdown-btn">
                <i class="fas fa-book"></i> Books <i class="fas fa-chevron-down arrow-icon"></i>
            </button>
            <div id="bookDropdown" class="dropdown">
                <a href="addbook.php"><i class="fas fa-plus-circle"></i> Add Book</a>
                <a href="allbook.php"><i class="fas fa-list"></i> All Books</a>
            </div>
        </li>

        <!-- Authors -->
        <li>
            <button onclick="toggleDropdown('authorDropdown')" class="dropdown-btn">
                <i class="fas fa-user-edit"></i> Authors <i class="fas fa-chevron-down arrow-icon"></i>
            </button>
            <div id="authorDropdown" class="dropdown">
                <a href="addauthors.php"><i class="fas fa-plus-circle"></i> Add Author</a>
                <a href="allauthors.php"><i class="fas fa-list"></i> All Authors</a>
            </div>
        </li>

        <!-- Categories -->
        <li>
            <button onclick="toggleDropdown('categoryDropdown')" class="dropdown-btn">
                <i class="fas fa-layer-group"></i> Categories <i class="fas fa-chevron-down arrow-icon"></i>
            </button>
            <div id="categoryDropdown" class="dropdown">
                <a href="addcategories.php"><i class="fas fa-plus-circle"></i> Add Category</a>
                <a href="allcategories.php"><i class="fas fa-list"></i> All Categories</a>
            </div>
        </li>

        
        <!-- Logout -->
        <li>
            <form action="../logout.php" method="POST">
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>

<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('show');
        dropdown.previousElementSibling.querySelector('.arrow-icon').classList.toggle('rotate');
    }
</script>

<style>
    #sidebar {
        background-color: #2c3e50;
        color: #ecf0f1;
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }

    .sidebar-header {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
        color: #fff;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
    }

    .sidebar-menu li {
        margin-bottom: 15px;
    }

    .dropdown-btn {
        background: none;
        border: none;
        color: #ecf0f1;
        padding: 12px 15px;
        width: 100%;
        text-align: left;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .dropdown-btn:hover {
        background-color: #34495e;
    }

    .dropdown {
        display: none;
        margin-top: 5px;
    }

    .dropdown.show {
        display: block;
    }

    .dropdown a {
        display: block;
        padding: 10px 20px;
        color: #ecf0f1;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.3s;
    }

    .dropdown a:hover {
        background-color: #3d566e;
    }

    .arrow-icon {
        transition: transform 0.3s;
    }

    .arrow-icon.rotate {
        transform: rotate(180deg);
    }

    .logout-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 12px 15px;
        width: 100%;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color: #c0392b;
    }

    @media (max-width: 768px) {
        #sidebar {
            position: relative;
            width: 100%;
            height: auto;
            padding: 15px;
        }
    }
</style>
