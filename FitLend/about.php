<?php
// about.php
include 'userheader.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/about.css">
</head>
<body>

<div class="about-section">
  <h1>About Us</h1>
  <p>Welcome to FitLend, your trusted partner for fitness equipment lending.</p>
  <p>Our mission is to make fitness accessible to everyone by offering a wide range of equipment, from cardio machines to strength training tools, all available for rent at affordable rates.</p>
</div>

<h2 style="text-align:center">Our Team</h2>
<div class="row">
  <div class="column">
    <div class="card">
      <img src="images/team1.jpg" alt="Sheila" style="width:100%">
      <div class="container">
        <h2>Sheila Chepkorir</h2>
        <p class="title">CEO & Founder</p>
        <p>Sheila is the visionary behind FitLend, with a passion for making fitness equipment accessible to everyone. With over 10 years of experience in the fitness industry, she has built a company that focuses on customer satisfaction and high-quality service.</p>
        <p>sheilac@fitlend.com</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>

  <div class="column">
    <div class="card">
      <img src="images/team2.jpg" alt="Michelle" style="width:100%">
      <div class="container">
        <h2>Michelle Akinyi</h2>
        <p class="title">Fitness Instructor</p>
        <p>Michelle is our lead fitness instructor, with a deep understanding of how to use our equipment to its fullest potential. She provides personalized training tips and guidance to our clients, ensuring they make the most of their rental experience.</p>
        <p>michellea@fitlend.com</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <img src="images/team3.jpg" alt="Suleiman" style="width:100%">
      <div class="container">
        <h2>Suleiman Kadir</h2>
        <p class="title">Marketer</p>
        <p>Suleiman is the marketing genius who ensures that everyone knows about FitLend. With his expertise in digital marketing and customer outreach, he drives our mission to make fitness accessible to all.</p>
        <p>suleimank@fitlend.com</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>
</div>

<?php include 'userfooter.php'; ?>
</body>
</html>
