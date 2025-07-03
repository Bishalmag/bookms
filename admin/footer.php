<style>
    /* Footer Styles */
    #footer {
        background-color: #2c3e50;
        color: #fff;
        text-align: center;
        padding: 20px;
        position: fixed;
        bottom: 0;
        left: 150px; /* Matches sidebar width */
        width: calc(100% - 150px); /* Full width minus sidebar */
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 800;
        box-sizing: border-box;
    }

    #footer p {
        margin: 0;
        font-size: 14px;
        font-weight: 400;
        letter-spacing: 0.5px;
    }

    #footer a {
        color: #4a6fa5;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    #footer a:hover {
        color: #3a5a80;
        text-decoration: underline;
    }

    .footer-links {
        margin-top: 10px;
    }

    .footer-links a {
        margin: 0 10px;
        font-size: 13px;
    }

    /* Responsive Footer */
    @media (max-width: 768px) {
        #footer {
            left: 0;
            width: 100%;
            padding: 15px 10px;
        }
        
        #footer p {
            font-size: 13px;
        }
        
        .footer-links a {
            display: block;
            margin: 5px 0;
        }
    }
</style>
<!--Footer part start -->
<div id="footer">
    <p>&copy; 2025 Book Management System. All rights reserved.</p>
</div>
<!--Footer part End -->