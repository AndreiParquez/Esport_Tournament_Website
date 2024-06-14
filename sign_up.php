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

    // Check if image file is an actual image or fake image
    $check = getimagesize($profileImage["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $error = "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        $error = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error = "Sorry, please choose another image.";
    } else {
        // Check for duplicate username
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // If everything is ok, try to upload file
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
            color: #6b7280; /* Gray-500 from Tailwind */
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
    </style>
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')]  bg-contain bg-repeat bg-center">
    <div class="flex justify-center items-center h-screen">
        <div class="w-[350px] flex flex-col p-2 relative items-center justify-center opacity-75 bg-zinc-800 border border-gray-500 shadow-lg rounded-2xl">
            <div id="cover" class="cover"></div>
            <div class="profile-container">
                <div class="m-4">
                    <div class="flex justify-center items-center">
                        <img src="src/img/video.png" class="h-14 drop-shadow-lg">
                        <div>
                            <h2 class="text-sci text-2xl drop-shadow-lg">Torneo</h2>
                            <p class="text-xs text-center character-spacing text-violet-700 font-bold ">No Pain No Game</p>
                        </div>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="flex flex-col w-full static justify-center z-10">
                    <h1 class="text-center text-sm">Account Creation</h1>
                    <div class="flex justify-center mt-4">
                        <div id="profileImageContainer" class="profile-image-preview">
                            <img src="src/img/prof.png" class="border-4 border-white rounded-full shadow-lg">
                        </div>
                        
                        <img id="profileImagePreview" src="#" alt="Profile Image Preview" class="profile-image-preview ring-2 ring-black ring-opacity-50 border-4 border-zinc-400 shadow-2xl hidden">

                        
                        <label for="profile_image" class="editprof flex justify-center items-center"><i class="fa-solid fa-camera"></i></label>

                        
                    </div>

                    
                    <div class="flex justify-center items-center space-x-8 m-3 text-zinc-200">
                        <i class="fa-brands fa-google text-xl drop-shadow-lg"></i>
                        <i class="fa-brands fa-twitter text-xl drop-shadow-lg"></i>
                        <i class="fa-brands fa-github text-xl drop-shadow-lg"></i>
                    </div>

                    
                        <div class="input flex flex-col w-full px-4 py-2 static">
                            <label for="username" class="text-violet-700 text-sm bg-transparent font-semibold relative -top-1 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Username:</label>
                            <input id="username" type="text" placeholder="Username" name="username" class="border-zinc-400 placeholder:text-white input px-[10px] py-[14px] text-xs bg-opacity-25 border-2 rounded-[5px]  bg-zinc-800 focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
                        </div>
                        <div class="input flex flex-col w-full px-4 static py-2 justify-center mt-1">
                            <label for="password" class="text-violet-700 text-sm bg-transparent font-semibold relative -top-1 ml-[7px] px-[3px] bg-[#e8e8e8] w-fit">Password:</label>
                            <input id="password" type="password" placeholder="Your password" name="password" class="border-zinc-400 placeholder:text-white input px-[10px] py-[14px] text-xs bg-[#e8e8e8] border-2 rounded-[5px] bg-opacity-25  bg-zinc-800  focus:outline-none placeholder:text-black/25 placeholder:text-xs" required />
                        </div>
                    <div class="input flex flex-col w-fit static">
                        <input id="profile_image" type="file" name="profile_image" required onchange="previewImage(event)" />
                    </div>
                    <div class="p-2 mt-2 text-center md:block">
                        <button type="submit" class="mb-2 w-[280px] md:mb-0 bg-violet-700 px-5 py-2 text-sm shadow-sm font-medium tracking-wider  hover:border-gray-700 text-gray-300 rounded-md hover:shadow-lg hover:bg-gray-800 transition ease-in duration-300">Sign Up</button>
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
        <div class="flex justify-center space-x-4">
                <i class="fa-regular fa-circle-check text-[40px]"></i>
                <div>
                <h3 class="text-sm leading-6 ">Success</h3>
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
            }, 300); // Match this duration with the transition duration
        }

        // Show the toast if there is an error or success
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
