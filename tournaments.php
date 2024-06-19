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
$conn->close();
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>

    <style>
        
         .slider-container {
            overflow: hidden;
        }
        .slider {
            display: flex;
            transition: transform 0.3s ease-in-out;
        }
        .slider-item {
            min-width: 100%;
        }
        .logo {

  margin: auto;
  position: relative;

}

.logo b {
            color: #ffff; /* Tailwind CSS violet-700 */
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
        
.msg{
  position:absolute;
  top:10px;
  left:10px;
  color:#555;
  text-transform:uppercase;
  z-index:4;
}

.m-ticket{
  width:350px;
  background:#fff;
  border-radius:12px;
  overflow:hidden;

  display:flex;
  flex-direction:column;
  align-items:center;
  position:relative;

}


.m-ticket:before{
  content:"";
  position:absolute;
  left:-10px;
  top:41%;
  background:rgb(39 39 42);

 
  width:17px;
  height:17px;
  border-radius:50%;
}


.m-ticket:after{
  content:"";
  position:absolute;
  right:-10px;
  top:41%;
  background:rgb(39 39 42);

  
  width:17px;
  height:17px;
  border-radius:50%;
}


.m{
  position:absolute;
  right:-5px;
  top:15%;
  transform:rotate(270.5deg);
  font-size:.80em;
  color:#888;
}


.m-ticket > .movie-details{
  display:flex;
  gap:20px;
  padding:20px 20px;
}


.m-ticket > .movie-details > .poster{
  width:100px;
  height:120px;
  object-fit:cover;
  border-radius:8px;
  box-shadow:0 0 10px #888;
}

.m-ticket > .movie-details > .movie > h4{
  margin: 0;
  font-size:1.3em;
  width:200px;
}


.m-ticket > .movie-details > .movie > p{
  font-size:.80em;
  line-height:19px;
  color:#777;
}


.m-ticket > .info{
  width:93%;
  border-radius:20px;
  background:#eee;
  padding:10px 0px;
  text-align:center;
  font-size:.72em;
  color:#777
}



.m-ticket > .ticket-details{
  display:flex;
  gap:20px;
  padding:20px 20px;
}


.m-ticket > .ticket-details > .scan{
  width:100px;
  height:100px;
}

.m-ticket > .ticket-details > .ticket{
  text-align:center;
  width:200px;
}


.m-ticket > .ticket-details > .ticket > p{
  font-size:.80em;
  line-height:19px;
  color:#777;
}

.m-ticket > .ticket-details > .ticket > b{
  margin-top:10px;
  display:inline-block;
  font-size:1.2em;
  font-weight:400;
}


.m-ticket > .ticket-details > .ticket > h6{
  text-transform:uppercase;
  font-weight:100;
  font-size:.95em;
  margin-top:10px;
}


.m-ticket > .info-cancel{
  width:100%;
  background:#eee;
  color:#777;
  padding:10px 0px;
  text-align:center;
  font-size:.80em;
}


.m-ticket > .total-amount{
  display:flex;
  justify-content:space-between;
  padding:12px 20px;
  font-weight:700;
  font-size:.90em;
  width:100%;
  color: #000;
}

    </style>
</head>
<body class="bg-zinc-900 text-white font-sans bg-[url('src/img/bg.jpg')]  bg-contain bg-repeat bg-center">

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
            <img src="<?php echo $imagePath; ?>" id="userImage" class="h-8 w-8 object-cover rounded-full shadow-[0_4px_10px_rgba(124,58,237,0.5)] border-2 border-gray-700 mr-2 cursor-pointer">
            <span><?php echo $username; ?></span>
        </div>
    </nav>
    </header>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="fixed-sidebar w-64 p-4 z-10">
            <nav>
                <ul class="space-y-2 text-sm text-poppins">
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-800 bg-violet-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-violet-900 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-trophy shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Tournaments</span></a></li>
                    <li class="px-4"><a href="social.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-earth-americas shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Social</span></a></li>
                    <li class="px-4"><a href="calendar.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-regular fa-calendar shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Calendar</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-list-check shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></i></div><span class="ml-3 font-bold">Leaderboards</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Games</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad shadow-[0_4px_10px_rgba(124,58,237,0.5)]"></i></div><span class="ml-3 font-bold">Players</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content content flex-1 p-6 mt-10  text-poppins">

        <div class="flex justify-center items-center">
            
        <div class="flex flex-col justify-center items-center space-y-2 my-24">
            <div class="text-center">
                <div class="logo text-sci text-[60px]"><b>T<span>o</span>r<span>n</span>eo</b></div>
                <p class="text-[11px] font-bold text-center text-violet-200 shadow-lg character-spacing">No Pain No Game</p>
                <div class="w-full md:w-1/2 ml-[245px] mt-3">
                    <p class="text-sm indent-2"><span class="text-sci text-[10px]">Torneo </span>provides news and exclusive content for competitive gaming. Start your journey into the world of <span class="text-violet-700 font-bold"> esports</span>!</p>
                </div>
            </div>
        </div>



            </div>




        <div class="w-full max-w-4xl mx-auto slider-container relative mb-5">
      
            
            
            <div class="slider">
            <div class="slider-item flex items-center justify-center  p-6">
                <div class="text-center">
                    <img src="src/img/ling.png" alt="Image 1" class=" h-96 absolute bottom-0 left-0 z-20">
                    <div class="h-[340px] bg-violet-800 rounded-lg w-[800px] shadow-[0_4px_10px_rgba(124,58,237,0.5)] text-right p-7 pl-[450px] pt-10 bg-opacity-75 relative ">
                    <h2 class="text-2xl font-bold mb-2">Unlock a World of Possibilities</h2>
                    <p class="text-xs z-30">From epic showdowns in popular titles to niche competitions, our platform hosts a diverse range of tournaments to suit every gamer's taste.</p>
                    <img src="src/img/mpl.jpg" alt="New Image Cover" class="absolute inset-0 object-cover w-full h-full opacity-25 z-15">
                </div>
                </div>
            </div>
            <div class="slider-item flex items-center justify-center  p-6">

                <div class="text-center">
                    <img src="src/img/cod.png" alt="Image 1" class=" h-96 absolute  left-[1420px] z-20">
                    <div class="h-[340px] bg-yellow-300 rounded-lg w-[800px] text-left p-7 pr-[450px] pt-10 bg-opacity-75 relative ">
                    <h2 class="text-2xl font-bold mb-2">Unlock a World of Possibilities</h2>
                    <p class="text-xs z-30">From epic showdowns in popular titles to niche competitions, our platform hosts a diverse range of tournaments to suit every gamer's taste.</p>
                    <img src="src/img/covercod.jpg" alt="New Image Cover" class="absolute inset-0 object-cover w-full h-full opacity-25 z-15">
                    </div>
                </div>
            </div>
            <div class="slider-item flex items-center justify-center  p-6">
            

                <div class="text-center ">
                    <img src="src/img/jett.png" alt="Image 1" class=" h-96 absolute  left-[1800px] z-20 ">
                    <div class="h-[340px] bg-red-800 rounded-lg w-[800px] text-right p-7 pl-[450px] pt-10 bg-opacity-75 relative ">
                    
                        <h2 class="text-xl font-bold mb-2 z-20">Unlock a World of Possibilities</h2>
                        <p class="text-xs">From epic showdowns in popular titles to niche competitions, our platform hosts a diverse range of tournaments to suit every gamer's taste.</p>

                        <!-- Image Cover -->
                        <img src="src/img/covervalo.jpg" alt="New Image Cover" class="absolute inset-0 object-cover w-full h-full opacity-25 z-15">
                        
                    </div>
                </div>
            </div>
            
        </div>
        <button id="prevButton" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-700 bg-opacity-25 rounded-full text-sm text-white px-4 py-2 z-20 hover:bg-opacity-75">Prev</button>
        <button id="nextButton" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-700 bg-opacity-25 rounded-full text-sm text-white px-4 py-2 z-20 hover:bg-opacity-75">Next</button>
    </div>


        <div class="p-8">
        <div class="flex">
                <div>
                <p class="text-xs ml-5 -mb-2">Raging</p>
                <div class="flex items-center">
                <h1 class="rounded-full text-md font-bold text-center flex justify-center items-center h-10 px-5">Live on Youtube</h1>
                <span class="text-[10px] bg-red-500 text-black px-3 py-1  rounded-full">Live</span>

                </div>
                </div>

                </div>
        <div class="flex  space-x-2  justify-center ">
            <div class="space-y-3">
            <iframe width="320" height="180" class="rounded-lg shadow-[0_4px_10px_rgba(124,58,237,0.5)]" src="https://www.youtube.com/embed/ScPScTYWN0U?si=D7OcgYEZ9mW-BmTn&amp;start=4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <iframe width="320" height="180" class="rounded-lg shadow-[0_4px_10px_rgba(124,58,237,0.5)]" src="https://www.youtube.com/embed/S_5vlPXLCRc?si=xSjByQpY_WpMZWN6&amp;start=4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                
            </div>
        <iframe width="510" height="372" class="rounded-lg shadow-[0_4px_10px_rgba(124,58,237,0.5)]" src="https://www.youtube.com/embed/0utNNmt2Mmk?si=eR2erIGssHfRz-Ex" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
        </div>
        
        




            <div class="flex w-[1000px] justify-between px-10">
                <div class="flex">
                <div>
                <p class="text-xs ml-5 -mb-2">Trending</p>
                <div class="flex items-center">
                <h1 class="rounded-full text-md font-bold text-center flex justify-center items-center h-10 px-5">Latest Tournaments</h1>
                <span class="text-[10px] bg-lime-500 text-black px-3 py-1  rounded-full">Coming Soon</span>

                </div>
                </div>

                </div>
            <div class="mb-4 relative mr-10">
                <input type="text" id="searchInput" class=" bg-zinc-800 text-xs p-2 pl-10 rounded-full  w-full placeholder:text-xs" placeholder="Search events..." >
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-violet-600"></i>
            </div>
            </div>
            
            <div class="grid px-14 grid-cols-4 gap-3" id="eventContainer">
                <!-- Event cards will be inserted here -->
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
                    <img src="<?php echo $game['logo']; ?>" alt="<?php echo $game['name']; ?>" class="h-12 rounded-md transform hover:shadow-[0_4px_10px_rgba(124,58,237,0.5)] scale-100 transition-transform duration-300 hover:scale-110">
                </a>
            <?php endforeach; ?>
        </div>
        </aside>

    </div>


    <div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 ">
        <div class="bg-violet-700 rounded-lg p-2 w-56 absolute right-4 top-16">
            <div class="flex justify-between text-xs items-center">
                <h2 class="text-sm font-semibold">User Information</h2>
                <button id="closeModal" class="text-gray-100 hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4 flex justify-center items-center">
                <div>
                <img src="<?php echo $imagePath; ?>" id="userImage" class="h-24 w-24 object-cover rounded-full shadow-[0_4px_10px_rgba(124,58,237,0.5)] border-4 border-gray-700 mr-2 cursor-pointer">


                <!-- Add more user information here -->
                </div>
                
                
                <a href="login.php" class="overflow-hidden relative w-28 p-3 h-10 bg-zinc-100 text-poppins text-zinc-700 rounded border border-violet-600 text-xs font-bold cursor-pointer relative z-10 group shadow-[0_4px_10px_rgba(124,58,237,0.5)] text-center no-underline">
                    Logout <?php echo $username; ?>
                    <span class="absolute w-36 h-32 -top-5 -left-2 bg-violet-200 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-500 duration-1000 origin-right shadow-violet"></span>
                    <span class="absolute w-36 h-32 -top-5 -left-2 bg-violet-400 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-700 duration-700 origin-right shadow-violet"></span>
                    <span class="absolute w-36 h-32 -top-5 -left-2 bg-violet-600 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-1000 duration-500 origin-right shadow-violet"></span>
                    <span class="group-hover:opacity-100 group-hover:duration-1000 duration-100 opacity-0 absolute text-white top-2.5 left-2 z-10">Logout?<i class="fa-solid fa-play ml-4"></i></span>
                </a>
            </div>
        </div>
    </div>

  <!-- Form Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="backdrop-blur-sm bg-zinc-800 bg-opacity-60 rounded-lg p-6 w-[400px]">
    <div class="mb-3">
                <p class="text-xs text-violet-700 -mb-2">Book</p>
                <div class="flex items-center">
                <h1 class="rounded-full text-md font-bold text-center flex justify-center items-center h-10 ">Enter your credentials</h1>
                

                </div>
                </div>
        <form id="bookingForm">
            <h1 id="Title"></h1>
            
            <div class="mb-4">
                <label for="name" class="block text-xs font-medium text-gray-200">Name</label>
                <input type="text" id="name" name="name" class="mt-1 p-2 border border-zinc-700 bg-zinc-800 text-xs bg-opacity-50 text-white rounded-md w-full" placeholder="John Doe" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-xs font-medium text-gray-200">Email</label>
                <input type="email" id="email" name="email" class="mt-1 p-2 border border-zinc-700 bg-zinc-800 text-xs bg-opacity-50 text-white rounded-md w-full" placeholder="example@gmail.com" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-xs font-medium text-gray-200">Phone</label>
                <input type="tel" id="phone" name="phone" class="mt-1 p-2 border border-zinc-700 bg-zinc-800 bg-opacity-50 text-xs text-white rounded-md w-full" placeholder="+63..." required>
            </div>
            <input type="hidden" id="eventId" name="eventId">
            <input type="hidden" id="eventimg" name="eventimg">
            <input type="hidden" id="eventTitle" name="eventTitle">
            <input type="hidden" id="eventStart" name="eventStart">
            <input type="hidden" id="eventEnd" name="eventEnd">
           <!-- Hidden field to store event ID -->
            <div class="flex justify-end">
                <button type="submit" class="bg-violet-700 hover:bg-violet-800 text-white font-bold py-2 text-xs px-4 rounded">Book</button>
                <button type="button" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold text-xs py-2 px-4 rounded" onclick="closeBookingModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-zinc-800  rounded-lg p-6 w-[800px]">
        <div class="mb-3">
        <button type="button" class="bg-violet-700 hover:bg-violet-800 text-white font-bold py-2 text-xs px-4 rounded" onclick="closeSuccessModal()">Close</button>

            <p class="text-xs text-700 -mb-2">Booking Successful</p>
            <div class="flex items-center">
                <h1 class="rounded-full text-md font-bold text-center flex justify-center items-center h-10 ">Booking Information</h1>
            </div>
        </div>
      

        <div class="flex justify-center">
            <div class="m-ticket" id="m-ticket">
                <p class="m">Torneo - Ticket</p>
                <div class="movie-details">
                    <img src="" id="eventImg" class="poster">
                    <div class="movie text-black">
                        <h4 id="t-title"></h4>
                        <p id="t-title-eventid" class="-mtop-10"></p>
                        <p><span id="t-start"></span></p>
                        <div id="bookingInfo" class="movie text-black"></div>
                    </div>
                </div>
                <div class="info">
                    Tap for support, details & more actions
                </div>
                <div class="ticket-details">
                    <img src="src/img/qr.png" class="scan">
                    <div class="ticket">
                        <p>1Ticket(s)</p>
                        <p>Thank You!</p>
                    </div>
                </div>
                <div class="info-cancel">
                    Cancellation not available for this venue
                </div>
            </div><!---m-ticket end---->
            
        </div>
        <div class="flex justify-center space-x-10 mt-4">
            <button type="button" class="bg-violet-700 hover:bg-violet-800 text-white font-bold py-2 text-xs px-4 rounded" onclick="printTicket()"><i class="fa-solid fa-ticket mr-2"></i>Download</button>
        </div>
    </div>
</div>

<script>
    function printTicket() {
        var ticketElement = document.getElementById("m-ticket");
        
        html2canvas(ticketElement).then(function(canvas) {
            // Convert the canvas to an image
            var imgData = canvas.toDataURL('image/png');
            
            // Create a link element
            var link = document.createElement('a');
            link.href = imgData;
            link.download = 'ticket.png';
            
            // Trigger the download
            link.click();
        });
    }
</script>




<script>
        document.getElementById('userImage').addEventListener('click', function() {
            document.getElementById('userModal').classList.remove('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('userModal').classList.add('hidden');
        });

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('userModal')) {
                document.getElementById('userModal').classList.add('hidden');
            }
        });
    </script>

<script>
        function openBookingModal(eventId, imageThumbnail,evenTitle,eventStart,eventEnd) {
    document.getElementById('eventId').value = eventId;
    document.getElementById('eventimg').value = imageThumbnail;
    document.getElementById('Title').textContent = evenTitle;
    document.getElementById('eventTitle').value = evenTitle;
    document.getElementById('eventStart').value = eventStart;
    document.getElementById('eventEnd').value = eventEnd;
    document.getElementById('eventStart').value = eventStart;
    document.getElementById('eventEnd').value = eventEnd;

    
    document.getElementById('bookingModal').classList.remove('hidden');
}


        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
        }

        document.getElementById('bookingForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(document.getElementById('bookingForm'));

            fetch('book_event.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const name = formData.get('name');
                    const email = formData.get('email');
                    const phone = formData.get('phone');
                    const eventId = formData.get('eventId');
                    const eventTitle = formData.get('eventTitle');
                    const eventStart= formData.get('eventStart');
                    const eventEnd= formData.get('eventEnd');
                    console.log(eventTitle);
                    const eventimg = formData.get('eventimg');
                    document.getElementById('eventImg').src = eventimg;
                    document.getElementById('t-title').textContent = eventTitle;
                    document.getElementById('t-start').textContent = eventStart;
                    document.getElementById('t-title-eventid').textContent = 'Event Id: ' + eventId;

                    const bookingInfo = `
                        <p class="text-xs">Name: ${name}</p>
                        <p class="text-xs">${email}</p>
                        
 
                    `;
                    document.getElementById('bookingInfo').innerHTML = bookingInfo;
                    

                    document.getElementById('bookingModal').classList.add('hidden');
                    document.getElementById('successModal').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>




<script>
    function toggleDropdown() {
        var menu = document.getElementById('dropdown-menu');
        menu.classList.toggle('hidden');
    }

    window.onclick = function(event) {
        if (!event.target.matches('#menu-button')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (!openDropdown.classList.contains('hidden')) {
                    openDropdown.classList.add('hidden');
                }
            }
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchEvents();

        // Add event listener to the search input
        document.getElementById('searchInput').addEventListener('input', filterEvents);
    });

    async function fetchEvents() {
        try {
            const response = await fetch('fetch_events_tourna.php');
            const events = await response.json();
            renderEvents(events);
        } catch (error) {
            console.error('Error fetching events:', error);
        }
    }

    function renderEvents(events) {
        const eventContainer = document.getElementById('eventContainer');
        eventContainer.innerHTML = ''; // Clear existing events
        events.forEach(event => {
            const card = createEventCard(event);
            eventContainer.appendChild(card);
        });
    }

    function createEventCard(event) {
        const card = document.createElement('div');
        card.className = 'event-card border border-zinc-800 bg-zinc-800 rounded-lg p-1  w-52';
        card.dataset.title = event.title.toLowerCase(); // Store title for filtering
        
        const image = document.createElement('img');
        image.src = event.image;
        image.alt = event.title;
        image.className = 'rounded-md  h-28 w-full';
        card.appendChild(image);

        const content = document.createElement('div');
        content.className = 'm-1';

        const date = document.createElement('p');
        date.className = 'text-gray-400 text-[10px]';
        date.textContent = `${event.start} - ${event.end}`;
        content.appendChild(date);

       

        const title = document.createElement('h3');
        title.className = 'text-sm font-bold';
        title.textContent = event.title;
        content.appendChild(title);

        const des = document.createElement('p');
        des.className = 'text-zinc-400 text-[10px]';
        des.textContent = event.description_text;
        content.appendChild(des);

        

        const participants = document.createElement('div');
        participants.className = 'flex items-center justify-between mt-2 -m-2 p-3 rounded-b-md bg-zinc-950';

        const participantCount = document.createElement('div');
        participantCount.className = 'text-gray-400 text-xs';

        // Set the inner HTML with both the participant count and the icon
        participantCount.innerHTML = `

            <div class="flex space-x-5">
        
            <div>
            <p class="text-[9px]">Prizepool</p>
                <i class="fa-solid fa-award text-green-500"></i>
            <span class="font-bold text-white">₱ ${event.prize_pool}</span> 
            </div>
            <div>
            <p class="text-[9px]">Participants</p>
                <i class="fa-solid fa-users text-yellow-500"></i>
            <span class="font-bold text-white">${event.participants}</span> 
            </div>
            </div>

            
        
        `;

        participants.appendChild(participantCount);
       


        const joinButton = document.createElement('button');
        joinButton.className = 'bg-violet-700 hover:bg-gray-700 text-white text-xs p-3 shadow-[0_4px_10px_rgba(124,58,237,0.5)] rounded-full font-meduim';
        joinButton.innerHTML = '<i class="fa-solid fa-arrow-right text-white font-bold "></i>'; // Add any additional text you want next to the icon
        joinButton.addEventListener('click', () => openBookingModal(event.id, event.image, event.title,event.start,event.end,event.description_text));
        participants.appendChild(joinButton);


        content.appendChild(participants);
        card.appendChild(content);

        return card;
    }

    function filterEvents() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const eventCards = document.querySelectorAll('.event-card');

        eventCards.forEach(card => {
            const title = card.dataset.title;
            if (title.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

<script>
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slider-item');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        let currentIndex = 0;

        function showSlide(index) {
            const totalSlides = slides.length;
            if (index >= totalSlides) {
                currentIndex = 0;
            } else if (index < 0) {
                currentIndex = totalSlides - 1;
            } else {
                currentIndex = index;
            }
            const offset = -currentIndex * 100;
            slider.style.transform = `translateX(${offset}%)`;
        }

        prevButton.addEventListener('click', () => {
            showSlide(currentIndex - 1);
        });

        nextButton.addEventListener('click', () => {
            showSlide(currentIndex + 1);
        });
    </script>
</body>
</html>