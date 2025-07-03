<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System - Header</title>
    <style>
    /* Header Styles */
       #header {
        color: white !important;
    }
    
    #header h1,
    #header .header-btn {
        color: white !important;
    }
    
    #header .header-btn:hover {
        color: white !important;
    }
    #header {
        background-color: #2c3e50;
        color: #fff;
        padding: 10px 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 170px; /* Matches sidebar width */
        width: calc(100% - 150px); /* Full width minus sidebar */
        height: 70px;
        z-index: 1000;
        box-sizing: border-box;
    }

    .header-brand {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Original Logo Styling */
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

    .header-title {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: #fff;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .header-btn {
        padding: 10px 16px;
        background-color: #4a6fa5;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .header-btn i {
        font-size: 0.9rem;
    }

    .header-btn:hover {
        background-color: #3a5a80;
        transform: translateY(-1px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #header {
            left: 0;
            width: 100%;
            padding: 10px 15px;
            height: 60px;
        }
        
        #logo img {
            width: 45px;
            height: 45px;
        }
        
        .header-title {
            font-size: 1.1rem;
        }
        
        .header-btn {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
    }
</style>
</head>
<body>
    <!-- Header part start -->
    <div id="header">
        <div style="display: flex; align-items: center;">
            <div id="logo">
                <img src="uploads/logo.png" alt="Logo">
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
