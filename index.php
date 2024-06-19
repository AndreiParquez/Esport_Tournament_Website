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
  }
  
  .progress-loader {
    width: 150px;
    background: rgba(236, 236, 238, 0.253);
    height: 3px;
    border-radius: 7px;
  }
  
  .progress {
    content: '';
    width: 1px;
    height: 3px;
    border-radius: 7px;
    background: rgb(255, 255, 255);
    transition: 0.5s;
    animation: loading1274 2s ease infinite;
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
        .shadow-violet {

        text-shadow: 0px 0px 5px rgba(124, 58, 237, 0.5);
}



  
  
  </style>
</head>
<body class="bg-zinc-900 text-white text-poppins bg-[url('src/img/bg.jpg')]  bg-contain bg-repeat bg-center ">
    <div class="flex justify-center items-center h-screen">
        <div class="flex flex-col justify-center items-center space-y-6 my-80">
            <div class="text-center">
                <div class="logo text-sci text-[60px]"><b>T<span>o</span>r<span>n</span>eo</b></div>
                <p class="text-[11px] font-bold text-center text-violet-200 shadow-lg character-spacing">No Pain No Game</p>
                <div class="w-full md:w-1/2 ml-[245px] mt-3">
                    <p class="text-xs indent-2 text-violet-200 italic"><span class="  shadow-violet">Torneo </span>provides news and exclusive content for competitive gaming. Start your journey into the world of <span class="text-violet-700 shadow-violet font-bold"> esports</span>!</p>
                </div>
            </div>
            <div class="flex space-x-8 pt-7">
                <a href="sign_up.php" class="overflow-hidden relative w-28 p-3 h-10 bg-zinc-900 text-poppins text-violet-300 rounded border border-violet-600 text-xs font-bold cursor-pointer relative z-10 group shadow-[0_4px_10px_rgba(124,58,237,0.5)] text-center no-underline">
                    Start
                    <span class="absolute w-36 h-32 -top-8 -left-2 bg-violet-200 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-500 duration-1000 origin-right shadow-violet"></span>
                    <span class="absolute w-36 h-32 -top-8 -left-2 bg-violet-400 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-700 duration-700 origin-right shadow-violet"></span>
                    <span class="absolute w-36 h-32 -top-8 -left-2 bg-violet-600 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-1000 duration-500 origin-right shadow-violet"></span>
                    <span class="group-hover:opacity-100 group-hover:duration-1000 duration-100 opacity-0 absolute top-2.5 left-2 z-10">Sign up <i class="fa-solid fa-play ml-4"></i></span>
                </a>
                <a href="login.php" class="overflow-hidden relative w-28 p-3 h-10 bg-zinc-100 text-poppins text-zinc-700 rounded border border-violet-600 text-xs font-bold cursor-pointer relative z-10 group shadow-[0_4px_10px_rgba(124,58,237,0.5)] text-center no-underline">
                    Continue
                    <span class="absolute w-36 h-32 -top-5 -left-2 bg-violet-200 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-500 duration-1000 origin-right shadow-violet"></span>
                    <span class="absolute w-36 h-32 -top-5 -left-2 bg-violet-400 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-700 duration-700 origin-right shadow-violet"></span>
                    <span class="absolute w-36 h-32 -top-5 -left-2 bg-violet-600 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-1000 duration-500 origin-right shadow-violet"></span>
                    <span class="group-hover:opacity-100 group-hover:duration-1000 duration-100 opacity-0 absolute text-white top-2.5 left-2 z-10">Sign in <i class="fa-solid fa-play ml-4"></i></span>
                </a>
            </div>
        </div>
        

    </div>

    <!-- Loader -->
    <div id="loader" class="fixed inset-0 bg-zinc-900   hidden">
    <div class="progress-loader">
    <div class="progress"></div>
    </div>
    </div>
       
    

    <div id="errorModal" class="fixed top-0 inset-x-1/3 left- p-4 w-1/3 bg-white text-zinc-900 rounded-b-lg shadow-lg transform transition-transform -translate-y-full hidden">
    <div class="flex justify-between px-4 items-center">
        <div class="flex justify-center space-x-4">
            <i class="fa-regular fa-circle-xmark text-[40px]"></i>
            <div>
                <h3 class="text-sm leading-6 ">Invalid Username or Password</h3>
                <p class="text-xs text-gray-500">Please check your credentials and try again.</p>
            </div>
        </div>
        <button type="button" onclick="closeModal()" class="ml-4 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-1 bg-zinc-700 text-xs  text-gray-300 hover:bg-gray-900 focus:outline-none sm:text-sm">Ok</button>
    </div>
    </div>

</body>
</html>
