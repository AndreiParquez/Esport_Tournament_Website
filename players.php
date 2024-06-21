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
    die("Connection failed: " . $conn->connect_error);
}

// Check if userId is set in the session
if(isset($_SESSION['userId'])) {
    // Access user data from session
    $userId = $_SESSION['userId'];
    $username = $_SESSION['username'];
    $imagePath = $_SESSION['imagePath'];
} else {
    // If userId is not set, redirect the user to the login page or handle the scenario accordingly
    header("Location: login.php");
    exit(); // Make sure to exit after redirection to prevent further execution
}

// Fetch users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
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


$today = date('Y-m-d');
$sql = "SELECT * FROM events WHERE start_date <= '$today' AND end_date >= '$today'";
$result = $conn->query($sql);

$tournamentsToday = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tournamentsToday[] = $row;
    }
}


// Fetch bookings for each user
for ($i = 0; $i < count($users); $i++) {
    $userId = $users[$i]['id'];
    $sql = "SELECT COUNT(*) as count FROM bookings WHERE user_id = $userId";
    $bookingResult = $conn->query($sql);
    if ($bookingResult->num_rows > 0) {
        $bookingRow = $bookingResult->fetch_assoc();
        $users[$i]['bookings'] = $bookingRow['count'];
    } else {
        $users[$i]['bookings'] = 0;
    }
}


$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
    <style>
        .bg-blur {
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')] bg-contain bg-repeat bg-center">
<header class="fixed-header flex items-center justify-between p-3 px-10 bg-zinc-950">
    <div class="flex items-center">
        <div class="flex justify-center items-center space-x-2">
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
                        <i class="fa-solid fa-trophy shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Tournaments</span></a></li>
                    <li class="px-4"><a href="social.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-earth-americas shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Social</span></a></li>
                    <li class="px-4"><a href="calendar.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-regular fa-calendar shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Calendar</span></a></li>
                    <li class="px-4"><a href="games.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950  rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Games</span></a></li>
                    <li class="px-4"><a href="players.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 bg-violet-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Players</span></a></li>
                </ul>
            </nav>
        </aside>
    
    <!-- Main content -->
    <main class="main-content content flex-1 p-6 mt-20 text-poppins relative rounded-lg shadow-lg mx-4 md:mx-auto max-w-4xl z-10">
    <div class="flex">
                <div>
                <p class="text-xs  -mb-2">On the top</p>
                <div class="flex items-center space-x-5">
                <h1 class="rounded-full text-md font-bold text-center flex justify-center items-center h-10 ">Players</h1>
                <span class="text-[10px] bg-blue-500 text-white  px-3 py-1  rounded-full">Trending</span>

                </div>
                </div>
    </div>
        <div class="overflow-auto rounded-lg w-full shadow-lg">
            <table class="bg-zinc-900 w-full text-xs">
                <thead>
                    <tr class="w-full bg-zinc-800 text-gray-300">
                        <th class="border-b py-3 border-violet-900 text-start px-2">#</th>
                        <th class="border-b py-3 border-violet-900 text-start px-2">Username</th>
                        <th class="border-b py-3 border-violet-900 text-center px-2">Tournaments Joined</th>
                        <th class="border-b py-3 border-violet-900 text-start px-6">Winrate</th>
                        <th class="border-b py-3 border-violet-900 text-start px-6">Winrate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr class="text-gray-300 text-xs">
                            <td class="border-b border-violet-900 px-4 py-3"><?php echo $user['id']; ?></td>
                            <td class="border-b border-violet-900 px-4 py-3">
                                <div class="flex justify-start items-center">
                                    <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture" class="h-9 w-9 rounded-full mr-2 border-2 shadow-sm  bg-violet-600 border-violet-800 object-cover">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </div>
                            </td>
                            <td class="border-b border-violet-900  py-3 text-center"><?php echo $user['bookings']; ?></td>
                            <td class="border-b border-violet-900 px-4 py-3">
                                <div class="flex items-center space-x-2 justify-between">
                                <div class="text-right mt-1 text-gray-400 text-[10px]"><?php echo $user['winrate']; ?>%</div>
                                <div class="relative w-full h-2 bg-zinc-900 rounded">
                                    <div class="absolute top-0 left-0 h-2 bg-green-500 rounded" style="width: <?php echo $user['winrate']; ?>%;"></div>
                                </div>
                                </div>
                               
                            </td>
                            <td class="border-b border-violet-900 px-4 py-3">
                                <div class="flex items-center space-x-2 justify-between">
                                <div class="text-right mt-1 text-gray-400 text-[10px]"><?php echo $user['winrate']; ?>%</div>
                                <div class="relative w-full h-2 bg-zinc-600 rounded">
                                    <div class="absolute top-0 left-0 h-2 bg-yellow-600 rounded" style="width: <?php echo $user['winrate']; ?>%;"></div>
                                </div>
                                </div>
                               
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
