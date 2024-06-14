<!-- game.php -->

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$today = date('Y-m-d');
$sql = "SELECT * FROM events WHERE start_date <= '$today' AND end_date >= '$today'";
$result = $conn->query($sql);

$tournamentsToday = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tournamentsToday[] = $row;
    }
}



$sql = "SELECT * FROM games"; // Query to fetch games
$result = $conn->query($sql);

$games = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }
}


// Check if the game ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $gameId = mysqli_real_escape_string($conn, $_GET['id']);

    // Query to fetch game information based on the provided ID
    $sql = "SELECT * FROM games WHERE id = $gameId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Game found, fetch and display its information
        $game = $result->fetch_assoc();
        $gameName = $game['name'];
        $gameLogo = $game['logo'];
        $gameDescription = $game['description'];
        $releaseDate = $game['release_date'];
        $genre = $game['genre'];
        $platform = $game['platform'];
    } else {
        // Game not found, handle accordingly (e.g., show error message)
        $errorMessage = "Game not found!";
    }
} else {
    // Redirect if game ID is not provided
    header("Location: index.php"); // Assuming your main page is index.php
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $gameName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
    <style>
        .bg-blur {
            background-size: cover;
            background-position: center;
        }
    </style>
    <!-- Add your CSS and JavaScript files here -->
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')]  bg-contain bg-repeat bg-center">
<header class="fixed-header flex items-center justify-between p-3 px-10 bg-zinc-950">
        <div class="flex items-center">
            <div class="flex justify-center items-center space-x-2">
                <img src="src/img/video.png" class="h-7">
                <div>
                    <h2 class="text-sci text-xs">Torneo</h2>
                    <p class="text-[9px] text-center text-violet-700 character-spacing">No Pain No Game</p>
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
                <img src="<?php echo $imagePath; ?>" class="h-8 w-8 rounded-full border-2 border-gray-700 mr-2">
                <span><?php echo $username; ?></span>
            </div>
        </nav>
    </header>
    <div class="flex">
    <aside class="fixed-sidebar w-64 p-4 z-10">
            <nav>
                <ul class="space-y-2 text-sm text-poppins">
                    <li class="px-4"><a href="tournaments.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-800  rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-trophy"></i></div><span class="ml-3 font-bold">Tournaments</span></a></li>
                    <li class="px-4"><a href="social.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-earth-americas"></i></div><span class="ml-3 font-bold">Social</span></a></li>
                    <li class="px-4"><a href="calendar.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-regular fa-calendar"></i></div><span class="ml-3 font-bold">Calendar</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-list-check"></i></i></div><span class="ml-3 font-bold">Leaderboards</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 bg-violet-700 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Games</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Players</span></a></li>
                </ul>
            </nav>
        </aside>



      
        
        <!-- Main content -->
        <main class="main-content content flex-1 p-6 mt-20 text-poppins relative  rounded-lg shadow-lg mx-4 md:mx-auto max-w-4xl z-10">
            <div class="w-full slider-container relative mb-5">
                <!-- Blurred cover image -->
                <div class="w-full h-48 bg-blur arounded-lg mb-5 rounded-lg opacity-25" style="background-image: url('<?php echo $gameLogo; ?>');"></div>
                <div class="absolute top-40">
                    <div class="flex px-10 items-center space-x-1">
                        <img src="<?php echo $gameLogo; ?>" alt="<?php echo $gameName; ?> Logo" class="h-24 rounded-full border-4 border-violet-950 mb-4 z-10">
                        <div>
                            <h1 class="text-md font-bold mb-1"><?php echo $gameName; ?></h1>
                            <div class="text-xs ml-3">
                            
                            <p >Release Date: <span class="text-violet-600 ml-1" ><?php echo $releaseDate; ?></span></p>
                            <p class="">Genre:<span class="text-violet-600 ml-1" > <?php echo $genre; ?></span></p>
                            <p class="">Platform:<span class="text-violet-600 ml-1" > <?php echo $platform; ?></span></p>
                        </div>
                    </div>

                </div>
                <p class="text-xs indent-8"><?php echo $gameDescription; ?></p>

                <!-- Add more game information as needed -->

                <?php if(isset($errorMessage)) : ?>
                    <p class="text-red-500"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
                </div>
            </div>
        </main>
 



                <aside class="fixed-sidebar right-sidebar w-64 p-4 text-poppins z-10">
            <h1 class="text-sm font-bold mb-4">Tournaments Today</h1>
            <div class="h-48 rounded-lg bg-zinc-800 p-2 overflow-auto">
                <?php if (!empty($tournamentsToday)) : ?>
                    <ul class="space-y-2">
                        <?php foreach ($tournamentsToday as $tournament) : ?>
                            <li class="bg-violet-700 p-3 rounded-lg shadow">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xs font-bold"><?php echo htmlspecialchars($tournament['name']); ?></h2>
                                    <p class="text-gray-300 text-[8px]"><?php echo htmlspecialchars($tournament['start_date']); ?> to <?php echo htmlspecialchars($tournament['end_date']); ?></p>
                                </div>
                                <p class="text-gray-300 text-[10px]"><?php echo htmlspecialchars($tournament['description']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="text-gray-400">No tournaments today.</p>
                <?php endif; ?>
            </div>

            <h1 class="text-sm font-bold mb-4 mt-4">Games</h1>
            
            <!-- Games Logo Div with 3 Columns -->
            <div class="grid grid-cols-3 gap-4">
            <?php foreach ($games as $game) : ?>
                <a href="game.php?id=<?php echo $game['id']; ?>">
                    <img src="<?php echo $game['logo']; ?>" alt="<?php echo $game['name']; ?>" class="h-12 rounded-md transform scale-100 transition-transform duration-300 hover:scale-110">
                </a>
            <?php endforeach; ?>
        </div>
        </aside>












</body>
</html>
