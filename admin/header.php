<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System - Header</title>
    <style>
        /* Global styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header styles */
        #header {
            background-color: #2c3e50;
        color: #fff;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1001;
        margin-left: 22%;
        width: 78%;
        }

        #logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #4a6fa5;
            transition: transform 0.3s ease;
        }

        #logo img:hover {
            transform: scale(1.05);
        }

        #banner {
            margin-left: 20px;
        }

        #banner h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            margin-left: auto;
        }

        .header-btn {
            padding: 10px 20px;
            background-color: #4a6fa5;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .header-btn:hover {
            background-color: #3a5a80;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsive styles */
        @media (max-width: 1200px) {
        #header {
            margin-left: 0;
            width: 100%;
        }

            #logo img {
                width: 45px;
                height: 45px;
            }

            #banner {
                margin-left: 0;
                margin-top: 10px;
            }

            #banner h1 {
                font-size: 18px;
            }

            .header-actions {
                margin-left: 0;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header part start -->
    <div id="header">
        <div id="logo">
            <img src="admin/uploads/image.png" alt="Logo">
        </div>
        <div id="banner">
            <h1>Book Management System</h1>
        </div>
        <div class="header-actions">
            <a href="addbook.php" class="header-btn">Add Book</a>
        </div>
    </div>
    <!-- Header part End -->
</body>
</html>
