<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginSuccess = false; // Initialize login success flag
$loginFailed = false; // Initialize login failed flag

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['userId'] = $user['id']; // Assuming 'id' is the primary key column of the users table
        $_SESSION['username'] = $user['username']; // Store username in session
        $_SESSION['imagePath'] = $user['profile_image']; // Store image path in session (assuming the column name is 'image_path')
        $loginSuccess = true; // Set login success flag
    } else {
        $loginFailed = true; // Set login failed flag
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
    <style>
.progress-loader {
    top: 50%;
    left: 50%;
    position: absolute;
    transform: translate(-50%, -50%);
  }
  
  .progress-loader {
    width: 150px;
    background: rgba(236, 236, 238, 0.253);
    height: 3px;
    border-radius: 7px;
  }
  
  .progress {
    content: '';
    width: 1px;
    height: 3px;
    border-radius: 7px;
    background: rgb(255, 255, 255);
    transition: 0.5s;
    animation: loading1274 2s ease infinite;
  }
  
  @keyframes loading1274 {
    0% {
      width: 0%;
    }
  
    10% {
      width: 10%;
    }
  
    50% {
      width: 40%;
    }
  
    60% {
      width: 60%;
    }
  
    100% {
      width: 100%;
    }
    
  }
  
  
  </style>
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')]  bg-contain bg-repeat bg-center">
    <div class="flex justify-center items-center h-screen">
        <div class="w-[350px] flex flex-col p-4 relative items-center justify-center bg-zinc-800 bg-opacity-75 border border-gray-500 shadow-lg rounded-2xl">
            <div class="m-4">
                <div class="flex justify-center items-center">
                    <img src="src/img/video.png" class="h-14">
                    <div>
                        <h2 class="text-sci text-2xl">Torneo</h2>
                        <p class="text-xs text-violet-700 font-bold text-center character-spacing">No Pain No Game</p>
                    </div>
                </div>
            </div>
            <form action="login.php" method="post" class="flex flex-col w-fit static">
                <div class="input flex flex-col w-full px-4 py-2 static">
                    <label for="username" class="text-violet-700 text-sm bg-zinc-800 font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Username:</label>
                    <input id="username" type="text" placeholder="email, username" name="username" class="border-zinc-400 placeholder:text-gray-400 input px-[10px] py-[14px] text-xs bg-[#e8e8e8] border-2 rounded-[5px] w-[280px] bg-zinc-800 focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
                </div>
                <div class="input flex flex-col w-full px-4 py-2 static">
                    <label for="password" class="text-violet-700 text-sm bg-zinc-800 font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Password:</label>
                    <input id="password" type="password" placeholder="Your password" name="password" class="border-zinc-400 placeholder:text-gray-400 input px-[10px] py-[14px] text-xs bg-[#e8e8e8] border-2 rounded-[5px] w-[280px] bg-zinc-800 focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
                </div>
                <div class="p-2 mt-2 text-center md:block">
                    <button type="submit" class="mb-2 w-[280px] md:mb-0 bg-zinc-900 px-5 py-2 text-sm shadow-sm font-medium tracking-wider hover:border-gray-700 text-gray-300 rounded-md hover:shadow-lg hover:bg-violet-700 transition ease-in duration-300">Sign in</button>
                    <p class="text-gray-400 text-xs m-2">Login with social accounts</p>
                    <div class="flex justify-center items-center space-x-8 text-zinc-500">
                        <i class="fa-brands fa-google text-xl"></i>
                        <i class="fa-brands fa-twitter text-xl"></i>
                        <i class="fa-brands fa-github text-xl"></i>
                    </div>
                    <p class="text-gray-400 text-xs m-2">Don't have an account?<a href="sign_up.php" class="font-bold text-gray-300">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="fixed inset-0 bg-zinc-900   hidden">
    <div class="progress-loader">
    <div class="progress"></div>
    </div>
    </div>
       
    

    <div id="errorModal" class="fixed top-0 inset-x-1/3 left- p-4 w-1/3 bg-white text-zinc-900 rounded-b-lg shadow-lg transform transition-transform -translate-y-full hidden">
    <div class="flex justify-between px-4 items-center">
        <div class="flex justify-center space-x-4">
            <i class="fa-regular fa-circle-xmark text-[40px]"></i>
            <div>
                <h3 class="text-sm leading-6 ">Invalid Username or Password</h3>
                <p class="text-xs text-gray-500">Please check your credentials and try again.</p>
            </div>
        </div>
        <button type="button" onclick="closeModal()" class="ml-4 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-1 bg-zinc-700 text-xs  text-gray-300 hover:bg-gray-900 focus:outline-none sm:text-sm">Ok</button>
    </div>
    </div>

    <script>
        function showLoader() {
            document.getElementById('loader').classList.remove('hidden');
        }

        function hideLoader() {
            document.getElementById('loader').classList.add('hidden');
        }

        function showModal() {
            const errModal = document.getElementById('errorModal');
            document.getElementById('errorModal').classList.remove('hidden');
            setTimeout(() => {
            errModal.classList.remove('-translate-y-full');
            errModal.classList.add('translate-y-0');
        }, 10);
        }

        function closeModal() {
            const errModal = document.getElementById('errorModal');
            document.getElementById('errorModal').classList.add('hidden');
            errModal.classList.add('-translate-y-full');
            errModal.classList.remove('translate-y-0');
            setTimeout(() => {
                errModal.classList.add('hidden');
            }, 3000);
        }

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if ($loginSuccess): ?>
                showLoader();
                setTimeout(function() {
                    window.location.href = "tournaments.php";
                }, 1500); // 2 second delay
            <?php elseif ($loginFailed): ?>
                showModal();
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>
