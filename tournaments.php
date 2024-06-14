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

$conn->close();

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
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
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
    </style>
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
        <!-- Sidebar -->
        <aside class="fixed-sidebar w-64 p-4 z-10">
            <nav>
                <ul class="space-y-2 text-sm text-poppins">
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-800 bg-violet-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-violet-900 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-trophy"></i></div><span class="ml-3 font-bold">Tournaments</span></a></li>
                    <li class="px-4"><a href="social.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-earth-americas"></i></div><span class="ml-3 font-bold">Social</span></a></li>
                    <li class="px-4"><a href="calendar.php" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-regular fa-calendar"></i></div><span class="ml-3 font-bold">Calendar</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-list-check"></i></i></div><span class="ml-3 font-bold">Leaderboards</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Games</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-violet-950 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Players</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content content flex-1 p-6 mt-20 text-poppins">


        <div class="w-full max-w-4xl mx-auto slider-container relative mb-5">
        <div class="slider">
            <div class="slider-item flex items-center justify-center  p-6">
                <div class="text-center">
                    <img src="src/img/ling.png" alt="Image 1" class=" h-96 absolute bottom-0 left-0 z-20">
                    <div class="h-[340px] bg-blue-800 rounded-lg w-[800px] text-right p-7 pl-[450px] pt-10 bg-opacity-75 relative ">
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
        </aside>
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
            <input type="hidden" id="eventId" name="eventId"> <!-- Hidden field to store event ID -->
            <div class="flex justify-end">
                <button type="submit" class="bg-violet-700 hover:bg-violet-800 text-white font-bold py-2 text-xs px-4 rounded">Book</button>
                <button type="button" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold text-xs py-2 px-4 rounded" onclick="closeBookingModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Open the booking modal when Join button is clicked
    function openBookingModal(eventId) {
        // Set the eventId in the hidden input field
        document.getElementById('eventId').value = eventId;
        // Show the booking modal
        document.getElementById('bookingModal').classList.remove('hidden');
    }

    // Close the booking modal
    function closeBookingModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }

    // Handle form submission
    document.getElementById('bookingForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch('book_event.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.text();
            alert(data); // Show response from server
            closeBookingModal(); // Close modal on success
        } catch (error) {
            console.error('Error booking event:', error);
        }
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
        card.className = 'event-card border border-zinc-800 bg-zinc-800 rounded-lg p-1 shadow-lg w-52';
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
        joinButton.className = 'bg-violet-700 hover:bg-gray-700 text-white text-xs p-3 rounded-full font-meduim';
        joinButton.innerHTML = '<i class="fa-solid fa-arrow-right text-black "></i>'; // Add any additional text you want next to the icon
        joinButton.addEventListener('click', () => openBookingModal(event.id));
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