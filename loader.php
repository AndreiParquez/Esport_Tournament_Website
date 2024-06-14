<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            margin: 0;
            padding: 0;
        }
.loader{
    background-color: gray;
    height: 100vh;
    width: 100vw;
}
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
    </style>
</head>
<body>
    <div class="loader">
    <div class="progress-loader">
    <div class="progress"></div>
    </div>
</div>
    
</body>
</html>


