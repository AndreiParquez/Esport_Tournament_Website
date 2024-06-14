<?php
session_start();

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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/1dbbe6d297.js" crossorigin="anonymous"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');

        .tajawal-regular {
            font-family: "Tajawal", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        :root {
            --fc-border-color: rgba(255, 255, 255, 0);
            --fc-daygrid-event-dot-width: 5px;
        }
        /* Target the specific date elements */
        .fc-day {
            border: 1px solid #ccc; /* Example border styling */
            border-radius: 5px; /* Example border radius */
        }

        /* Apply additional styling for today's date */
        .fc-today {
            background-color: #f0f0f0; /* Example background color */
        }

        /* Apply styling for hovered dates */
        .fc-day:hover {
            background-color: #323232; /* Example background color on hover */
            cursor: pointer; /* Change cursor to pointer on hover */
            color: #ffffff;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgb(38, 39, 41);
        }

        .fc .fc-button-primary {
            background-color:rgba(0, 0, 0, 0.229);
            border-color: rgba(0, 0, 0, 0);
            color: var(--fc-button-text-color);
        }
        .fc .fc-button-group {
            display: inline-flex;
            position: relative;
            vertical-align: middle;
            font-size: 12px;
        }
        .fc .fc-button-primary {
            font-size: 12px;
        }

        .fc {
            direction: ltr;
            border-radius: 10px;
        }
        .fc .fc-col-header-cell-cushion {
            display: inline-block;
            padding: 2px 4px;
            font-size: 13px;
        }
        .fc .fc-toolbar-title {
            font-size: 13px;
            font-weight: 500;
            color: #fff;
        }

        .fc-button-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .fc-button-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
     

        .fc-daygrid-day-frame {
            border-radius: 10px; /* Adjust the value as needed */
            font-size: 13px;
            font-weight: bold;
        }
        #calendar {
            height: 600px;
        }
        .fc-event {
            background-color: #6510b0; /* Set the background color of the event */
            color: #ffffff; /* Set the text color of the event */
            border: 1px solid #5e687200; /* Set the border color and thickness of the event */
            border-radius: 20px; /* Set the border radius to make the edges rounded */
            padding: 10px; /* Add padding to the event to create space around the text */
        }
         /* Hide the default date picker icon */
         input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            position: absolute;
            right: 0;
            z-index: 1;
            width: 100%;
        }
        input[type="date"]::-webkit-inner-spin-button,
        input[type="date"]::-webkit-clear-button,
        input[type="time"]::-webkit-inner-spin-button,
        input[type="time"]::-webkit-clear-button {
            display: none;
            -webkit-appearance: none;
        }
        input[type="date"]::-moz-clear,
        input[type="time"]::-moz-clear {
            display: none;
        }
        /* Custom styles for the input fields */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #9ca3af; /* Tailwind gray-400 */
        }
    </style>
    
</head>
<body class="bg-zinc-900 text-white font-sans">
    <header class="fixed-header flex items-center justify-between p-3 px-10 bg-zinc-950">
        <div class="flex items-center space-x-4">
            <span class="text-sci text-sm ">T o r n e o</span>
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
        <aside class="fixed-sidebar w-64 bg-zinc-900 p-4">
            <nav>
                <ul class="space-y-2 text-sm text-poppins">
                    <li class="px-4"><a href="tournaments.php" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-trophy"></i></div><span class="ml-3 font-bold">Tournaments</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-regular fa-calendar"></i></div><span class="ml-3 font-bold">Calendar</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-list-check"></i></i></div><span class="ml-3 font-bold">Leaderboards</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Games</span></a></li>
                    <li class="px-4"><a href="#" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded"> <div class="text-gray-300 hover:text-white border-2 border-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fa-solid fa-gamepad"></i></div><span class="ml-3 font-bold">Players</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div id="calendar"></div>
        </main>

        <aside class="fixed-sidebar right-sidebar w-64 bg-zinc-900 p-4 text-poppins">
            <h1>Filter by</h1>
            <div class="flex justify-between mb-4">
                <div class="relative inline-block text-left">
                    <div>
                        <button onclick="toggleDropdown()" type="button" class="inline-flex justify-center w-full border border-gray-300 shadow-sm px-4 py-2 text-xs rounded-full font-medium text-white mt-4  focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true">
                            Options
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l7 7a1 1 0 01-1.414 1.414L10 5.414l-6.293 6.293a1 1 0 01-1.414-1.414l7-7A1 1 0 0110 3z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <div id="dropdown-menu" class="hidden  absolute right-10 mt-2 w-56 rounded-md shadow-lg  ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="">
                        <div class="py-1" role="none z-50">
                            <button class="text-white block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="2">This weekdndjkenfjk</button>
                            <button class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">This month</button>
                            <button class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">This year</button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>


    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-25 flex items-center hidden justify-center z-50">
        <div class="relative backdrop-blur-sm bg-zinc-800 bg-opacity-60 rounded-lg p-6 w-full max-w-md">
            <button type="button" onclick="closeModal()" class="absolute top-4 right-4 text-gray-300 hover:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="flex justify-center space-x-4 text-poppins">
                <img src="src/img/event.png" class="h-6">
                <h2 class="text-lg mb-6 text-white">Create Event</h2>
            </div>
            <form id="eventForm">
    <div class="mb-4">
        <label for="eventName" class="block text-xs font-medium text-white">Event Name</label>
        <input type="text" id="eventName" name="eventName" class="mt-1 block w-full shadow-sm sm:text-sm border border-zinc-700 bg-zinc-800 bg-opacity-50 text-white py-2 rounded-md focus:ring focus:ring-blue-500" required>
    </div>
    <div class="mb-4">
        <label for="eventDescription" class="block text-xs font-medium text-white">Description</label>
        <textarea id="eventDescription" name="eventDescription" class="mt-1 block w-full shadow-sm sm:text-sm border border-zinc-700 bg-zinc-800 bg-opacity-50 text-white py-2 rounded-md focus:ring focus:ring-blue-500" required></textarea>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-2">
        <div class="input-wrapper my-4">
            <label for="startDate" class="block text-xs font-medium text-white absolute -top-4">Start Date</label>
            <input type="date" id="startDate" name="startDate" class="mt-1 block w-full shadow-sm sm:text-xs border border-zinc-700 text-poppins text-xs bg-zinc-800 bg-opacity-50 text-white py-2 rounded-md pl-10 focus:ring focus:ring-blue-500" required>
            <i class="fa fa-calendar-alt input-icon text-zinc-700"></i>
        </div>
        <div class="input-wrapper my-4">
            <label for="endDate" class="block text-xs font-medium text-white absolute -top-4">End Date</label>
            <input type="date" id="endDate" name="endDate" class="mt-1 block w-full shadow-sm sm:text-xs border border-zinc-700 bg-zinc-800 bg-opacity-50 text-white py-2 rounded-md pl-10 focus:ring focus:ring-blue-500" required>
            <i class="fa fa-calendar-alt input-icon text-zinc-700"></i>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="input-wrapper my-2">
            <label for="startTime" class="block text-xs font-medium text-white absolute -top-4">Start Time</label>
            <input type="time" id="startTime" name="startTime" class="mt-1 block w-full shadow-sm sm:text-xs border border-zinc-700 bg-zinc-800 bg-opacity-50 text-white py-2 rounded-md pl-10 focus:ring focus:ring-blue-500" required>
            <i class="fa fa-clock input-icon text-zinc-700"></i>
        </div>
        <div class="input-wrapper my-2">
            <label for="endTime" class="block text-xs font-medium text-white absolute -top-4">End Time</label>
            <input type="time" id="endTime" name="endTime" class="mt-1 block w-full shadow-sm sm:text-xs border border-zinc-700 bg-zinc-800 bg-opacity-50 text-white py-2 rounded-md pl-10 focus:ring focus:ring-blue-500" required>
            <i class="fa fa-clock input-icon text-zinc-700"></i>
        </div>
    </div>
    <div class="mb-6">
        <label for="thumbnail" class="block text-xs font-medium text-white">Thumbnail</label>
        <input type="file" id="thumbnail" name="thumbnail" class="mt-1 block w-full shadow-sm sm:text-xs border border-zinc-700 bg-zinc-800 bg-opacity-50 text-white py-2 rounded-md focus:ring focus:ring-blue-500" accept="image/*" required>
    </div>
    <div class="flex justify-center">
        <button type="submit" class="flex items-center justify-center bg-zinc-700 text-white px-4 py-2 rounded-full hover:bg-blue-600 focus:outline-none w-1/2 :ring focus:ring-blue-700">
            <span>Save Event</span>
            <i class="fa-solid fa-right-to-bracket ml-2"></i>
        </button>
    </div>
</form>

        </div>
    </div>
    
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: async function(fetchInfo, successCallback, failureCallback) {
            try {
                const response = await fetch('fetch_events.php');
                const events = await response.json();
                successCallback(events);
            } catch (error) {
                console.error('Error fetching events:', error);
                failureCallback(error);
            }
        },
        dateClick: function(info) {
            document.getElementById('startDate').value = info.dateStr;
            document.getElementById('endDate').value = info.dateStr;
            openModal();
        }
    });

    calendar.render();

    document.getElementById('eventForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('save_event.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Event created successfully!');
                calendar.refetchEvents();
                closeModal();
            } else {
                alert('Error creating event');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating event');
        });
    });
});

function openModal() {
    document.getElementById('eventModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('eventModal').classList.add('hidden');
}

    </script>
</body>
</html>
