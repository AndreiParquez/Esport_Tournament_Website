<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if userId is set in the session
if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $username = $_SESSION['username'];
    $imagePath = $_SESSION['imagePath'];
} else {
    header("Location: login.php");
    exit();
}

// Fetch posts from the database
$sql = "SELECT posts.*, likes.userId AS liked_by_user FROM posts 
        LEFT JOIN likes ON posts.id = likes.postId AND likes.userId = $userId 
        ORDER BY time DESC";
$result = $conn->query($sql);

$posts = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        .liked {
            color: red;
        }
        #image {
            display: none;
        }
    </style>
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')]  bg-contain bg-repeat bg-center">
    <header class="fixed-header flex items-center justify-between p-3 px-10 bg-zinc-950">
        <div class="flex items-center">
            <div class="flex justify-center items-center space-x-2">
                <img src="src/img/video.png" class="h-7">
                <div>
                    <h2 class="text-sci text-xs">Torneo</h2>
                    <p class="text-[9px] text-center character-spacing">No Pain No Game</p>
                </div>
            </div>
        </div>
        <nav class="flex space-x-6">
            <a href="#" class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                <i class="fa-regular fa-bell"></i>
            </a>
            <a href="#" class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                <i class="fa-regular fa-comments"></i>
            </a>
            <div class="text-gray-300 flex items-center">
                <img src="<?php echo $imagePath; ?>" class="h-8 w-8 rounded-full border-2 object-cover border-gray-700 mr-2">
                <span><?php echo $username; ?></span>
            </div>
        </nav>
    </header>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="fixed-sidebar w-64 bg-zinc-900 p-4">
            <nav>
                <ul class="space-y-2 text-sm text-poppins">
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"><div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-trophy"></i></div><span class="ml-3 font-bold">Tournaments</span></a></li>
                    <li class="px-4"><a href="calendar.php" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"><div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-regular fa-calendar"></i></div><span class="ml-3 font-bold">Calendar</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"><div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-list-check"></i></i></div><span class="ml-3 font-bold">Leaderboards</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"><div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Games</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"><div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Players</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content content flex-1 p-6 mt-20 text-poppins">
            <div class="max-w-xl mx-auto">
                <div>
                    <p class="text-xs  -mb-2">Post</p>
                    <div class="flex items-center">
                        <h1 class="rounded-full text-md font-bold text-center flex justify-center items-center h-10 ">Latest update</h1>
                    </div>
                </div>
                <!-- Post Form -->
                <div class="shadow-md rounded-lg pb-1 p-3 mb-3 text-xs bg-zinc-800 bg-opacity-75 z-10 sticky -top-6">
                    <form action="create_post.php" method="POST" enctype="multipart/form-data">
                        <div class="flex items-center mb-4">
                            <img src="<?php echo $imagePath; ?>" alt="Avatar" class="rounded-full h-10 w-10 object-cover mr-4">
                            <textarea name="content" class="w-full bg-gray-700 p-2 rounded-md text-white" placeholder="What's happening?" required></textarea>
                            <label for="image" id="postimg"><i class="fa-regular fa-image text-2xl p-2 text-lime-500"></i></label>
                        </div>
                        <div class="flex items-center justify-between">
                            <div id="chosen-image-container" class="mt-4">
                                <img id="chosen-image" src="#" alt="Chosen Image" class="hidden rounded-sm h-20 block">
                            </div>
                            <input type="file" id="image" name="image" accept="image/*" class="text-gray-300">
                            <button type="submit" class="bg-violet-700 hover:bg-violet-800 text-white font-bold py-2 px-4 rounded">Post</button>
                        </div>
                    </form>
                </div>

                <h1 class="rounded-full text-md font-bold text-left h-10 ">Timeline</h1>

             
              <!-- Display Posts -->
            <?php foreach ($posts as $post): ?>
                <div class="shadow-md rounded-lg bg-zinc-800 bg-opacity-75 p-4 mb-4 text-white">
                    <div class="flex items-start mb-4">
                        <img src="<?php echo $post['avatar']; ?>" alt="Avatar" class="rounded-full h-10 w-10 object-cover mr-4">
                        <div>
                            <div class="flex items-center space-x-2">
                                <h4 class="font-bold text-sm"><?php echo $post['username']; ?></h4>
                                <p class="text-gray-500 text-xs" data-time="<?php echo $post['time']; ?>" id="post-time-<?php echo $post['id']; ?>"></p>
                            </div>
                            <p class="text-gray-100 text-xs mt-1"><?php echo $post['content']; ?></p>
                        </div>
                    </div>
                    <?php if ($post['image']): ?>
                        <div class="relative mt-2  -mx-4">
                            <img src="<?php echo $post['image']; ?>" alt="Post Image" class="w-full  object-cover">
                        
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between items-center mt-2 p-2 text-xs text-gray-500">
                        <div class="flex items-center space-x-1">
                            <button class="like-btn flex items-center space-x-1 <?php echo $post['liked_by_user'] ? 'text-red-500' : ''; ?>" data-post-id="<?php echo $post['id']; ?>">
                                <i class="fa-<?php echo $post['liked_by_user'] ? 'solid' : 'regular'; ?> fa-heart"></i>
                                <span><?php echo $post['likes_count']; ?> likes</span>
                            </button>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button class="flex items-center space-x-1">
                                <i class="far fa-comment"></i>
                                <span><?php echo $post['comments']; ?> comments</span>
                            </button>
                            <button class="flex items-center space-x-1">
                                <i class="fas fa-share"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            $('.like-btn').on('click', function() {
                var postId = $(this).data('post-id');
                var button = $(this);
                var likeCount = parseInt(button.find('span').text());
                if (button.hasClass('liked')) {
                    likeCount -= 1;
                    button.find('i').removeClass('fa-solid').addClass('fa-regular');
                    button.removeClass('liked');
                } else {
                    likeCount += 1;
                    button.find('i').removeClass('fa-regular').addClass('fa-solid');
                    button.addClass('liked');
                    button.removeClass('text-gray-500');
                }
                button.find('span').text(likeCount + ' likes');

                $.ajax({
                    url: 'like_post.php',
                    method: 'POST',
                    data: { postId: postId },
                });
            });
        });

        document.getElementById('image').addEventListener('change', function(event) {
            var chosenImage = document.getElementById('chosen-image');
            var chosenImageContainer = document.getElementById('chosen-image-container');
            var file = event.target.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    chosenImage.src = e.target.result;
                    chosenImage.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                chosenImage.src = '#';
                chosenImage.classList.add('hidden');
            }
        });

        function timeAgo(time) {
            const now = new Date();
            const postTime = new Date(time);
            const seconds = Math.floor((now - postTime) / 1000);

            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) {
                return interval + " year" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) {
                return interval + " month" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) {
                return interval + " day" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) {
                return interval + " hour" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 60);
            if (interval >= 1) {
                return interval + " minute" + (interval > 1 ? "s" : "") + " ago";
            }
            return Math.floor(seconds) + " second" + (seconds > 1 ? "s" : "") + " ago";
        }

        function updatePostTimes() {
            document.querySelectorAll('[data-time]').forEach(function(element) {
                const time = element.getAttribute('data-time');
                element.innerText = timeAgo(time);
            });
        }

        setInterval(updatePostTimes, 60000); // Update every minute
        updatePostTimes(); // Initial update
    </script>
</body>
</html>
