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

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $profileImage = $_FILES['profile_image'];

    $targetDir = "uploads/avatars/";
    $targetFile = $targetDir . basename($profileImage["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $uploadOk = 1;

    $check = getimagesize($profileImage["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $error = "File is not an image.";
        $uploadOk = 0;
    }

    
    if (file_exists($targetFile)) {
        $error = "Sorry, file already exists.";
        $uploadOk = 0;
    }

  
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    
    if ($uploadOk == 0) {
        $error = "Sorry, please choose another image.";
    } else {
        
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            
            if (move_uploaded_file($profileImage["tmp_name"], $targetFile)) {
                $sql = "INSERT INTO users (username, password, profile_image) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $password, $targetFile);
                if ($stmt->execute()) {
                    $success = "Sign up successful!";
                } else {
                    $error = "Error occurred during sign up. Please try again.";
                }
                $stmt->close();
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
    <style>
        .profile-image-preview {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e8e8e8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280; 
        }
        .placeholder-text {
            font-size: 14px;
        }
        .editprof {
            position: absolute;
            margin-top: 99px;
            margin-left: 75px;
            background-color: rgba(0, 0, 0, 0.579);
            color: #fff;
            text-align: center;
            height: 30px;
            width: 30px;
            border-radius: 50%;
            z-index: 10;
        }
        #profile_image {
            display: none;
        }
        .cover {
            width: 340px;
            height: 130px;
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            position: absolute;
            top: 90px;
            z-index: 0;
            border-radius: 8px 8px 0 0;
        }
        .profile-container {
            position: relative;
            z-index: 10;
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
        <div class="w-[350px] flex flex-col p-2 shadow-[0_4px_10px_rgba(124,58,237,0.5)] relative items-center justify-center bg-opacity-75 bg-zinc-800 border border-gray-500 rounded-2xl">
            <div id="cover" class="cover"></div>
            <div class="profile-container">
                <div class="m-4">
                    <div class="flex justify-center items-center">
                     
                        <div>
                        <div class="logo text-sci text-[27px]"><b>T<span>o</span>r<span>n</span>eo</b></div>
                        <p class="text-[10px] text-violet-300 font-bold text-center character-spacing">No Pain No Game</p>
                        </div>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="flex flex-col w-full static text-poppins justify-center z-10">
                    <h1 class="text-center text-sm ">Account Creation</h1>
                    <div class="flex justify-center mt-4">
                        <div id="profileImageContainer" class="profile-image-preview">
                            <img src="src/img/prof.png" class="border-4 border-white rounded-full shadow-[0_4px_10px_rgba(124,58,237,0.5)]">
                        </div>
                        
                        <img id="profileImagePreview" src="#" alt="Profile Image Preview" class="profile-image-preview shadow-[0_4px_10px_rgba(124,58,237,0.5)] ring-2 ring-black ring-opacity-50 border-4 border-zinc-400 shadow-2xl hidden">

                        
                        <label for="profile_image" class="editprof flex justify-center items-center"><i class="fa-solid fa-camera"></i></label>

                        
                    </div>

                    
                    <div class="flex justify-center items-center space-x-8 m-3 text-zinc-200">
                    <i class="fa-brands drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] fa-google text-xl"></i>
                        <i class="fa-brands drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] fa-twitter text-xl"></i>
                        <i class="fa-brands drop-shadow-[0_4px_10px_rgba(124,58,237,0.5)] fa-github text-xl"></i>
                    </div>

                    
                        <div class="input flex flex-col w-full px-4 py-2 static">
                            <label for="username" class="text-violet-700 text-sm bg-transparent font-semibold relative -top-1 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Username:</label>
                            <input id="username" type="text" placeholder="Username" name="username" class="border-zinc-400 placeholder:text-gray-400 input px-[10px] py-[14px] text-xs bg-opacity-25 border-2 rounded-[5px]  bg-zinc-800 focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
                        </div>
                        <div class="input flex flex-col w-full px-4 static py-2 justify-center mt-1">
                            <label for="password" class="text-violet-700 text-sm bg-transparent font-semibold relative -top-1 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Password:</label>
                            <input id="password" type="password" placeholder="Your password" name="password" class="border-zinc-400 placeholder:text-gray-400 input px-[10px] py-[14px] text-xs bg-[#e8e8e8] border-2 rounded-[5px] bg-opacity-25  bg-zinc-800  focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
                        </div>
                    <div class="input flex flex-col w-fit static">
                        <input id="profile_image" type="file" name="profile_image" required onchange="previewImage(event)" />
                    </div>
                    <div class="p-2 mt-2 text-center md:block">
                        <button type="submit" class="mb-2 w-[280px] md:mb-0 bg-violet-700 px-5 py-2 text-xs shadow-sm font-medium tracking-wider  hover:border-gray-700 text-gray-300 rounded-md hover:shadow-lg hover:bg-gray-800 transition ease-in duration-300">Sign Up</button>
                        <p class="text-gray-400 text-xs m-2">Already have an account?<a href="login.php" class="font-bold text-gray-300"> Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="errorToast" class="fixed top-0 inset-x-1/3 left- p-4 w-1/3 bg-white text-zinc-900 rounded-b-lg shadow-lg transform transition-transform -translate-y-full hidden">
        <div class="flex justify-between items-center">
        <div class="flex justify-center space-x-4">
                <i class="fa-regular fa-circle-xmark text-[40px]"></i>
                <div>
                <h3 class="text-sm leading-6 ">Error</h3>
                <p class="text-xs text-gray-500" id="errorMessage"></p>
                </div>
        </div>
            <button type="button" onclick="closeToast('errorToast')" class="ml-4 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-1 bg-zinc-700 text-xs  text-gray-300 hover:bg-gray-900 focus:outline-none sm:text-sm">Ok</button>
        </div>
    </div>

    <div id="successToast" class="fixed top-0 inset-x-1/3 left- p-4 w-1/3 bg-white text-zinc-900 rounded-b-lg shadow-lg transform transition-transform -translate-y-full hidden">
        <div class="flex justify-between items-center">
        <div class="flex justify-center space-x-4 text-poppins">
                <i class="fa-regular fa-circle-check text-lime-500 text-[40px]"></i>
                <div>
                <h3 class="text-sm leading-6 font-bold">Success</h3>
                <p class="text-xs text-gray-500" id="successMessage"></p>
                </div>
        </div>
            <button type="button" onclick="closeToast('successToast')" class="ml-4 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-1 bg-zinc-700 text-xs  text-gray-300 hover:bg-gray-900 focus:outline-none sm:text-sm">Ok</button>
        </div>
    </div>

  

    <script>
        function showToast(type, message) {
            const toast = document.getElementById(type);
            const messageElement = document.getElementById(type === 'errorToast' ? 'errorMessage' : 'successMessage');
            messageElement.innerText = message;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.remove('-translate-y-full');
                toast.classList.add('translate-y-0');
            }, 10);
        }

        function closeToast(type) {
            const toast = document.getElementById(type);
            toast.classList.add('-translate-y-full');
            toast.classList.remove('translate-y-0');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300); 
        }

       
        <?php if (!empty($error)) : ?>
            showToast('errorToast', '<?php echo $error; ?>');
        <?php elseif (!empty($success)) : ?>
            showToast('successToast', '<?php echo $success; ?>');
        <?php endif; ?>
    </script>

<script>
        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var dataURL = reader.result;
                var output = document.getElementById('profileImagePreview');
                var cover = document.getElementById('cover');
                var container = document.getElementById('profileImageContainer');
                output.src = dataURL;
                cover.style.backgroundImage = `url(${dataURL})`;
                output.classList.remove('hidden');
                container.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</body>
</html>
