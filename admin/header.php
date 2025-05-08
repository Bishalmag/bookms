<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System - Header</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #header {
            background-color: #2c3e50;
            color: #fff;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0px;
            z-index: 1001;
            margin-left: 18.5%;
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
            padding: 10px 15px;
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

        @media (max-width: 1200px) {
            #header {
                margin-left: 0;
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            #logo img {
                width: 45px;
                height: 45px;
            }

            #banner {
                margin: 10px 0;
            }

            .header-actions {
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header part start -->
    <div id="header">
        <div style="display: flex; align-items: center;">
            <div id="logo">
                <img src="uploads/image.png" alt="Logo">
            </div>
            <div id="banner">
                <h1>Book Management System</h1>
            </div>
        </div>

        <div class="header-actions">
            <a href="dashboard.php" class="header-btn"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
            <a href="addbook.php" class="header-btn"><i class="fas fa-plus-circle"></i> Add Book</a>
            
        </div>
    </div>
    <!-- Header part End -->

    <!-- Font Awesome CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
</body>
</html>
