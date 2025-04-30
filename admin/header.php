<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        /* Global styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Header styles */
        #header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            display: flex;
            align-items: center;
        }

     
  
    #logo img {
        width: 60px; 
        height: 60px;
        border-radius: 100%; 
        overflow: hidden; 
    }





        #banner h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            #header {
                padding: 10px;
            }

            #logo img {
                width: 80px; /* Adjust size as needed */
            }

            #banner h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!--header part start -->
    <div id="header">
        <div id="logo">
        <img src="image.png" alt="image">
        </div>
        <div id="banner">
            <h1>Book Management System</h1>
        </div>
        
    </div>
    <!--Header part End-->
</body>
</html>
