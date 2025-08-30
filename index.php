<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>
        body{
            background: url(Images/backgroundUwall.jpg) no-repeat fixed;
            background-size: cover;
        }

        .row{
            margin: auto !important;
        }

        .hd{
            text-shadow: 0 0 6px blue;
            font-size: 32px;
        }

        .hd2{
            text-shadow: 0 0 6px green;
            font-size: 32px;
        }

        .home_link{
            position:absolute;
            margin:15px  0 0 4% !important;
            border: 4px solid;
            padding: 3px 20px;
            background-color: white;
            border-radius: 15px;
            font-weight: bolder;
            text-decoration: none;
        }

        /* Spinner styles */
        #spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(87, 101, 87, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            border: 20px groove #f3f3f3;
            border-top: 13px solid #3498db;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
    </style>
</head>
<body>
    <!-- Spinner overlay -->
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <a href="../index.php" class="home_link">Home</a>
    <a href="biology02.php" class="practice btn btn-primary mt-3 px-4 float-end mx-2">Practice</a>

    <div class="row container mt-4 pt-3">
        <div class="col-sm ">
            <div class="container pt-4 mt-2">
                <h1 class="text-center text-white hd">Signup</h1>
                <form action="signup.php" method="post">
                    <div class="mb-3 mt-3">
                        <label for="name" class="form-label h5">Name:</label>
                        <input type="name" class="form-control" id="name" placeholder="Enter your name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label h5">Email:</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                        <br>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div> 
        </div> 

        <div class="col-sm">
            <div class="container pt-4 mt-2 log_in">
                <h1 class="text-center text-white hd2">Login</h1>
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label h5">Email:</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                        <br>
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div> 
    </div>

    <script>
        // Hide spinner once page is fully loaded
        window.addEventListener('load', () => {
            document.getElementById('spinner-overlay').style.display = 'none';
        });
    </script>

    <!-- <script>
    // Simulate a 3-second load delay
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('spinner-overlay').style.display = 'none';
        }, 3000); // 3000ms = 3 seconds
    });
</script> -->

</body>
</html>
