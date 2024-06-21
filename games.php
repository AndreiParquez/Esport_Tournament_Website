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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
    <style>
        .bg-blur {
            background-size: cover;
            background-position: center;
        }
    </style>
    <script>
        function searchGames() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let cards = document.getElementsByClassName('game-card');
            for (let i = 0; i < cards.length; i++) {
                let card = cards[i];
                let name = card.getAttribute('data-name').toLowerCase();
                if (name.includes(input)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        }
    </script>
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')] bg-contain bg-repeat bg-center">
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
                <li class="px-4"><a href="tournaments.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-800 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
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
    <main class="main-content content flex-1 p-6 mt-20 text-poppins relative rounded-lg shadow-lg mx-4 md:mx-auto max-w-4xl z-10">
    <div class="flex w-full justify-between mt-5 mb-5">
                <div class="flex">
                <div>
                <p class="text-xs  -mb-2">Most played games</p>
                <div class="flex items-center space-x-5">
                <h1 class="rounded-full text-md font-bold text-center flex justify-center items-center h-10 ">High end Games</h1>
                <span class="text-[10px] bg-lime-500 text-white  px-3 py-1  rounded-full">Trending</span>

                </div>
                </div>

                </div>
            <div class="mb-4 relative ">
                <input type="text" id="searchInput" onkeyup="searchGames()" class="bg-zinc-800 text-xs p-2 pl-10 rounded-full w-full placeholder:text-xs" placeholder="Search events...">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-violet-600"></i>
            </div>
            </div>

        <!-- Cards of Games -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($games as $game) : ?>
        <div class="relative bg-gray-800 rounded-md shadow-lg transform hover:scale-105 transition-transform duration-300 overflow-hidden game-card" data-name="<?php echo htmlspecialchars($game['name']); ?>">
            <div class="relative">
                <img src="<?php echo $game['card']; ?>" alt="<?php echo htmlspecialchars($game['name']); ?>" class="w-full h-40 object-cover">
                <div class="h-10 w-full bg-gradient-to-t from-zinc-900 to-zinc-800 opacity-75"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-800 via-gray-700 via-gray-600 to-gray-800 opacity-80"></div>
            </div>
            <div class="absolute inset-0 flex flex-col justify-between py-3">
                <div>
                    <img src="<?php echo $game['cover']; ?>" alt="<?php echo htmlspecialchars($game['name']); ?>" class="w-full h-20 object-contain px-10 mt-10">
                </div>
                <div class="flex justify-between items-center px-2">
                    <p class="text-[11px]"><?php echo htmlspecialchars($game['name']); ?></p>
                    <a href="game.php?id=<?php echo $game['id']; ?>" class="inline-block bg-violet-700 text-white px-3 py-1 rounded-md text-xs hover:bg-violet-900">View</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
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
                    <img src="<?php echo $game['logo']; ?>" alt="<?php echo htmlspecialchars($game['name']); ?>" class="h-12 rounded-md transform scale-100 transition-transform duration-300 hover:scale-110">
                </a>
            <?php endforeach; ?>
        </div>
    </aside>
</div>
</body>
</html>
