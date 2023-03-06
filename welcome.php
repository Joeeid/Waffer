<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" type="image/png" href="/project/image/logo.png">

</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600&display=swap');


body {
  font-family: 'Poppins', sans-serif;
  margin:0;
}

.logo{
    font-size: 2.5rem;
    font-weight: bolder;
}

h1 {
  font-weight:bold;
  letter-spacing: 2px;
  font-size:48px;
}
p {
  font-family: 'Lato', sans-serif;
  letter-spacing: 1px;
  font-size:14px;
  color: #333333;
}

.header {
  position:relative;
  text-align:center;
  background: linear-gradient(60deg, rgba(37,30,126,1) 0%, rgba(255,120,0,1) 100%);
  color:white;
}
.logo {
  width:50px;
  fill:white;
  padding-right:15px;
  display:inline-block;
  vertical-align: middle;
}

.flex { /*Flexbox for containers*/
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.inner{
  height:65vh;
  width:100%;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.transparent{
  background:rgba(255, 255, 255, 0.1);
}

.location{
  width: 30%;
  height: 3rem;
  background:#eee;
  border-radius: .5rem;
  padding:0.5rem;
  font-size: 1rem;
  color:#130f40;
  text-transform: none;
}

form{
  width: 100%;
}

.btn{
    margin-top: 1rem;
    display: inline-block;
    padding:.8rem 3rem;
    font-size: 1.7rem;
    border-radius: .5rem;
    border:.2rem solid #130f40;
    color:#130f40;
    cursor: pointer;
    background: #eee;
    transition: all .2s linear;
}

.btn:hover{
    background: #ff7800;
    color:#fff;
}

.waves {
  position:relative;
  width: 100%;
  height:15vh;
  margin-bottom:-7px; /*Fix for safari gap*/
  min-height:100px;
  max-height:150px;
}

.content {
  position:relative;
  height:20vh;
  text-align:center;
  background-color: white;
}

/* Animation */

.parallax > use {
  animation: move-forever 25s cubic-bezier(.55,.5,.45,.5)     infinite;
}
.parallax > use:nth-child(1) {
  animation-delay: -2s;
  animation-duration: 7s;
}
.parallax > use:nth-child(2) {
  animation-delay: -3s;
  animation-duration: 10s;
}
.parallax > use:nth-child(3) {
  animation-delay: -4s;
  animation-duration: 13s;
}
.parallax > use:nth-child(4) {
  animation-delay: -5s;
  animation-duration: 20s;
}
@keyframes move-forever {
  0% {
   transform: translate3d(-90px,0,0);
  }
  100% { 
    transform: translate3d(85px,0,0);
  }
}
/*Shrinking for mobile*/
@media (max-width: 768px) {
  .waves {
    height:40px;
    min-height:40px;
  }
  .content {
    height:30vh;
  }
  h1 {
    font-size:24px;
  }
  .location{
    width:60%;
  }
}
</style>
<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
?>
<body>
    <!--Hey! This is the original version
of Simple CSS Waves-->

<div class="header">
<div class="transparent">
<!--Content before waves-->
<div class="inner">
<div class="inner-header flex">
<!--Just the logo.. Don't mind this-->
<i class="fas fa-shopping-basket logo"></i>
<h1>Waffer</h1>
</div>
<form action="<?=$_COOKIE['last_page']?>" method="POST">
<label for="location">Select the nearest to your delivery location:</label><br/>
<select name="location" class="location" id="location">
  <option value="beirut">Beirut</option>
  <option value="mount lebanon">Mount Lebanon</option>
  <option value="north lebanon">North Lebanon</option>
  <option value="akkar">Akkar</option>
  <option value="baalbek">Baalbek</option>
  <option value="beqaa">Beqaa</option>
  <option value="south lebanon">South Lebanon</option>
  <option value="Nabatieh">Nabatieh</option>
</select><br/>
  <input type="submit" value="Continue" class="btn">
</form>
</div>

<!--Waves Container-->
<div>
<svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
<defs>
<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
</defs>
<g class="parallax">
<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
<use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
</g>
</svg>
</div>
<!--Waves end-->
</div>
</div>
<!--Header ends-->

<!--Content starts-->
<div class="content flex">
  <p>By Joe EID & Fatima El Kheshen</p>
</div>
<!--Content ends-->
</body>
<?php
}else{
  setcookie('why',"Please Login First.");
  header('Location: login.php');
}
?>
</html>