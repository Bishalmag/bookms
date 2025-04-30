<div id="sidebar" style="background-color: white; color: #333; padding: 20px; width: 20%;">
    <ol style="list-style-type: none; padding: 0; margin: 0;">

        <!-- Add Book Button -->
        <li style="margin-bottom: 10px;">
            <a href="addbook.php" style="display: block; padding: 10px; background-color: #218838; text-align: center; text-decoration: none; color: #fff; border-radius: 5px;">Add Books</a>
        </li>

        <!-- Author Dropdown -->
        <li style="margin-bottom: 10px;">
            <button onclick="toggleDropdown('authorDropdown')" style="width: 100%; padding: 10px; background-color: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Authors ▾</button>
            <div id="authorDropdown" style="display: none; margin-top: 5px;">
                <a href="addauthors.php" style="display: block; padding: 10px; background-color: #218838; text-align: center; text-decoration: none; color: #fff; border-radius: 5px; margin-bottom: 5px;">Add Author</a>
                <a href="allauthors.php" style="display: block; padding: 10px; background-color: #218838; text-align: center; text-decoration: none; color: #fff; border-radius: 5px;">All Authors</a>
            </div>
        </li>

        <!-- Categories Dropdown -->
        <li style="margin-bottom: 10px;">
            <button onclick="toggleDropdown('categoryDropdown')" style="width: 100%; padding: 10px; background-color: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Categories ▾</button>
            <div id="categoryDropdown" style="display: none; margin-top: 5px;">
                <a href="addcategories.php" style="display: block; padding: 10px; background-color: #218838; text-align: center; text-decoration: none; color: #fff; border-radius: 5px; margin-bottom: 5px;">Add Category</a>
                <a href="allcategories.php" style="display: block; padding: 10px; background-color: #218838; text-align: center; text-decoration: none; color: #fff; border-radius: 5px;">All Categories</a>
            </div>
        </li>

        <!-- Logout Button -->
        <li>
            <form action="logout.php" method="post" style="margin: 0;">
                <button type="submit" style="display: block; width: 100%; padding: 10px; background-color: #28a745; text-align: center; text-decoration: none; color: #fff; border-radius: 5px; border: none; cursor: pointer;">Logout</button>
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

<style>
    #authorDropdown, #categoryDropdown {
        transition: all 0.3s ease;
    }
</style>
