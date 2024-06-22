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

$loginSuccess = false;
$loginFailed = false; 

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
        $_SESSION['userId'] = $user['id']; 
        $_SESSION['username'] = $user['username'];
        $_SESSION['imagePath'] = $user['profile_image']; 
        $loginSuccess = true; 
    } else {
        $loginFailed = true; 
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
    z-index: 100;
  }
  
  .progress-loader {
    width: 150px;
    background: rgba(236, 236, 238, 0.253);
    height: 3px;
    border-radius: 7px;
    z-index: 100;
  }
  
  .progress {
    content: '';
    width: 1px;
    height: 3px;
    border-radius: 7px;
    background: rgb(255, 255, 255);
    transition: 0.5s;
    animation: loading1274 2s ease infinite;
    z-index: 100;
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
  .logo {
    margin: auto;
    position: relative;

    }

    .logo b {
            color: #ffff; 
            text-shadow: 0 -40px 900px , 0 0 2px, 0 0 1em #7C3AED, 0 0 0.5em #7C3AED, 0 0 0.1em #7C3AED, 0 10px 3px #000;
        }
        .logo b span {
            animation: blink linear infinite 2s;
        }
        .logo b span:nth-of-type(2) {
            animation: blink linear infinite 3s;
        }
        @keyframes blink {
            78% {
                color: inherit;
                text-shadow: inherit;
            }
            79% {
                color: #333;
            }
            80% {
                text-shadow: none;
            }
            81% {
                color: inherit;
                text-shadow: inherit;
            }
            82% {
                color: #333;
                text-shadow: none;
            }
            83% {
                color: inherit;
                text-shadow: inherit;
            }
            92% {
                color: #333;
                text-shadow: none;
            }
            92.5% {
                color: inherit;
                text-shadow: inherit;
            }
        }
        .shadow-violet {

        text-shadow: 0px 0px 5px rgba(124, 58, 237, 0.5);
}


  
  
  </style>
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')]  bg-contain bg-repeat bg-center">
    <div class="flex justify-center items-center h-screen">
        <div class="w-[350px] flex flex-col p-4 relative items-center justify-center bg-zinc-800 shadow-[0_4px_10px_rgba(124,58,237,0.5)] bg-opacity-75 border border-gray-500  rounded-2xl">
            <div class="m-4">
                <div class="flex justify-center items-center">
                    
                    <div>
                    <div class="logo text-sci text-[27px]"><b>T<span>o</span>r<span>n</span>eo</b></div>
                        <p class="text-[10px] text-violet-300 font-bold text-center character-spacing">No Pain No Game</p>
                    </div>
                </div>
            </div>
            <form action="login.php" method="post" class="flex flex-col w-fit static text-poppins">
        <div class="input flex flex-col w-full px-4 py-2 static">
            <label for="username" class="text-violet-700 text-sm bg-zinc-800 z-10 font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Username:</label>
            <div class="relative w-full">
                <i class="fa fa-user absolute left-3 top-1/2 drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] transform -translate-y-1/2 text-violet-500"></i>
                <input id="username" type="text" placeholder="email, username" name="username" class="border-zinc-400 placeholder:text-gray-400 input pl-10 pr-[10px] py-[14px] text-xs bg-[#e8e8e8] border-2 rounded-[5px] w-[280px] bg-zinc-800 focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
            </div>
        </div>
        <div class="input flex flex-col w-full px-4 py-2 static">
            <label for="password" class="text-violet-700 text-sm bg-zinc-800 z-10 font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Password:</label>
            <div class="relative w-full">
                <i class="fa fa-lock absolute left-3 top-1/2 drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] transform -translate-y-1/2 text-violet-500"></i>
                <input id="password" type="password" placeholder="your password" name="password" class="border-zinc-400 placeholder:text-gray-400 input pl-10 pr-[10px] py-[14px] text-xs bg-[#e8e8e8] border-2 rounded-[5px] w-[280px] bg-zinc-800 focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
            </div>
        </div>
        <div class="p-2 mt-2 text-center md:block">
            <button type="submit" class="mb-2 w-[280px] md:mb-0 bg-zinc-900 px-5 py-2 text-sm  font-medium tracking-wider hover:border-gray-700 text-gray-300 rounded-md  hover:bg-violet-700 transition ease-in duration-300 hover:shadow-[0_4px_10px_rgba(124,58,237,0.5)]">Sign in</button>
            <p class="text-gray-400 text-xs m-2">Login with social accounts</p>
            <div class="flex justify-center items-center space-x-8 text-violet-300">
                <i class="fa-brands drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] fa-google text-xl"></i>
                <i class="fa-brands drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] fa-twitter text-xl"></i>
                <i class="fa-brands drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] fa-github text-xl"></i>
            </div>
            <p class="text-gray-400 text-xs m-2">Don't have an account?<a href="sign_up.php" class="font-bold text-gray-300 drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)]">Sign up</a></p>
        </div>
    </form>
        </div>
    </div>
   



    <div id="loader" class="fixed inset-0 bg-zinc-900  z-20 hidden">
    <div class="progress-loader">
    <div class="progress shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></div>
    </div>
    </div>
       
    

    <div id="errorModal" class="fixed top-0 inset-x-1/3 left- p-4 w-1/3 bg-white text-zinc-900 rounded-b-lg shadow-lg transform transition-transform -translate-y-full hidden">
    <div class="flex justify-between px-4 items-center">
        <div class="flex justify-center space-x-4 text-poppins">
            <i class="fa-regular text-red-500 fa-circle-xmark text-[40px]"></i>
            <div>
                <h3 class="text-sm leading-6 font-bold ">Invalid Username or Password</h3>
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
                }, 1500); 
            <?php elseif ($loginFailed): ?>
                showModal();
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>
