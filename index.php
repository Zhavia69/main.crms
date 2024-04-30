
<!DOCTYPE html>

<html lang="en">
<head>
  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>County Revenue Management System</title> 
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }
    .header {
      background: url('rtyui.jpg') center/cover no-repeat; /* Header background image */
      padding: 50px 0;
      text-align: center;
      color: #fff;
      
    }
    .header h1 {
      font-size: 3rem;
      margin-bottom: 20px;
      color: #ffffff;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .header h2 {
      font-size: 2rem;
      margin-bottom: 40px;
      color: #ffffff;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .jumbotron {
      background-color: #fff;
      margin-top: 30px;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .jumbotron p {
      font-size: 18px;
      line-height: 1.6;
    }
    .jumbotron p small {
      color: #6c757d;
    }
    .jumbotron p small a {
      color: #007bff;
      text-decoration: underline;
    }
    .jumbotron p small a:hover {
      color: #0056b3;
    }
    .jumbotron hr {
      border-color: #dee2e6;
    }
    .jumbotron img {
      max-width: 100%;
      height: auto;
      margin-bottom: 20px;
      border-radius: 5px;
    }
    .contact-info p {
      margin: 5px 0;
    }
    .contact-form input[type="text"],
    .contact-form input[type="email"],
    .contact-form textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .contact-form input[type="submit"] {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
    }
    .contact-form input[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>



  <style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,700;1,200;1,400;1,500&display=swap');
*{
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}
.animated-icon{
    width: 100%;
    height: 100vh;
    background: #f1f9ff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.social-links{
    display: flex;
    justify-content: center;

}
.social-links a{
    width: 80px;
    height: 80px;
    text-align: center;
    text-decoration: none;
    color: #000;
    box-shadow: 0 0 20px 10px rgba(0, 0, 0, 0.05);
    margin: 0 30px;
    border-radius:50% ;
    position: relative;
    overflow: hidden;
    transition: transform 0.5s;
}
.social-links a .fab{
    font-size: 30px;
    line-height: 80px;
    position: relative;
    z-index: 2;
    transition: color 0.5s;
}
.social-links a::after{
    content: "";
    width: 100%;
    height: 100%;
    top: -90px;
    left: 0;
    background: linear-gradient(-45deg,#ed1c94,#ffec17);
    position: absolute;
    transition: 0.5s;
}
.social-links a:hover::after{
    top: 0;

}
.social-links a:hover .fab{
    color: #fff;
}
.social-links a:hover{
    transform: translateY(-4Jpx);
}

  </style>
</head>
<body>

<!-- Header Section -->
<header class="header">
  <div class="container">
    <h1>County Revenue Management System</h1>
    <h2>Efficiently Managing County Finances</h2>
  </div>
</header>
<?php include_once("hhead.php"); ?>


<!-- Main Content Section -->
<div class="container">
  <div class="jumbotron">
    <h1>Welcome</h1>
    <h2>Dear Citizen, Welcome to the County Revenue Collection System</h2>
    <p>
      <small>
        Have you ever wondered how governments manage to fund all those schools, roads, and social programs? It's not magic, but it is a fascinating system! Governments raise money in two main ways:
        <br><br>
        Direct Revenue from Residents: This is like your paycheck – the government collects taxes from citizens and businesses. This is a crucial source of income for everyday operations.
        <br><br>
        Grants and Loans: Think of this as borrowing money for a big purchase. Governments sometimes take out loans or receive grants (gifts with strings attached) to fund large, expensive projects like building a new bridge.
        <br><br>
        Now, let's zoom in on Kenya's unique system. Here, county governments have two revenue streams:
        <br><br>
        Own Source Revenue: This is the money they collect directly from residents in their county, similar to your local city or state taxes.
        <br><br>
        Equitable Share: This is the big chunk of money sent from the national government. It's like a family allowance – the national government collects most of the taxes (income tax, import duties, etc.) because it's more efficient, but then shares a portion with the counties based on their needs.
        <br><br>
        Here's the interesting part: even though the national government collects most of the money, it doesn't get to keep it all! The Kenyan Constitution ensures each level of government receives enough to fulfill its responsibilities. Every year, a special commission advises Parliament on how to fairly distribute this "equitable share" between the national and county governments. This ensures everyone gets a slice of the pie!
        <br><br>
        But wait, there's more! The graph on our website shows a surprising trend. While county revenue has been steadily increasing, the national government's share has grown much faster. This raises some important questions: is the current system truly fair? Are counties getting the resources they need to thrive?
        <br><br>
        Explore our website to learn more and join the conversation! We believe in an informed citizenry, and understanding how government finances work is the first step to building a stronger future for all.
      </small>
    </p>
  </div>

  <!-- Contact Information and Form Section -->
<div class="jumbotron" id="contact-section">
  <div class="jumbotron">
    <div class="row">
      <div class="col-md-6">
        <div class="contact-info">
          <h2>Contact Information</h2>
          <p>Nairobi County</p>
          <p>Times Tower Building</p>
          <p>Haile Selassie Avenue</p>
          <p>P. O. Box 48240 - 00100</p>
        </div>
      </div>

    
      <div class="col-md-6">
        <div class="contact-form">
          <h2>Contact Form</h2>
          <form action="contact.php" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
            <input type="submit" value="Send Message">
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="social-icons">
        <div class="social-links">
            <a target="_blank" href="https://www.facebook.com/mdimranul.haque.ph"><i class="fab fa-facebook-f"></i></a>
            <a target="_blank" href="https://www.youtube.com/mdimranul.haque.ph"><i class="fab fa-youtube"></i></a>
            <a target="_blank" href="https://www.twitter.com/mdimranul.haque.ph"><i class="fab fa-twitter"></i></a>
            <a target="_blank" href="https://www.instagram.com/mdimranul.haque.ph"><i class="fab fa-instagram"></i></a>
            <a target="_blank" href="https://www.linkedin.com/mdimranul.haque.ph"><i class="fab fa-linkedin-in"></i></a>
        </div>
</div>
</div>



<!-- Footer Section -->
<footer>
  <div class="container">
  <?php include_once("ffoot.php"); ?>
  </div>
</footer>

</body>
</html>
