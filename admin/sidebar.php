<!-- sidebar.php -->
<div id="sidebar">
    <ol>
        <!-- Author Dropdown -->
        <li>
            <button onclick="toggleDropdown('authorDropdown')">Authors ▾</button>
            <div id="authorDropdown" class="dropdown" style="display: none;">
                <a href="addauthors.php">Add Author</a>
                <a href="allauthors.php">All Authors</a>
            </div>
        </li>

        <!-- Categories Dropdown -->
        <li>
            <button onclick="toggleDropdown('categoryDropdown')">Categories ▾</button>
            <div id="categoryDropdown" class="dropdown" style="display: none;">
                <a href="addcategories.php">Add Category</a>
                <a href="allcategories.php">All Categories</a>
            </div>
        </li>

        <!-- Logout Button -->
        <li>
            <form action="/logout.php" method="POST" style="margin: 0;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </li>
    </ol>
</div>

<!-- JavaScript for Dropdowns -->
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }
</script>

<!-- CSS for Sidebar and Dropdowns -->
<style>
    /* Sidebar Styles */
    #sidebar {
        background-color: #f4f4f4; /* Light background color */
        color: #333;
        padding: 20px;
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    #sidebar ol {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    #sidebar li {
        margin-bottom: 15px;
    }

    #sidebar button {
        background-color: #4a6fa5;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 12px;
        width: 100%;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #sidebar button:hover {
        background-color: #3a5a80;
        transform: translateY(-2px);
    }

    .dropdown a {
        display: block;
        background-color: #3a5a80;
        padding: 10px;
        margin-top: 5px;
        text-decoration: none;
        color: white;
        border-radius: 5px;
        font-size: 14px;
        text-align: left;
    }

    .dropdown a:hover {
        background-color: #2c487a;
    }

    /* Logout Button */
    .logout-btn {
        background-color: #e74c3c;
        color: white;
        padding: 12px;
        width: 100%;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-align: left;
    }

    .logout-btn:hover {
        background-color: #c0392b;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        #sidebar {
            width: 100%;
            height: auto;
            position: relative;
            padding: 15px;
        }

        #sidebar button,
        .dropdown a {
            font-size: 14px;
        }
    }
</style>
