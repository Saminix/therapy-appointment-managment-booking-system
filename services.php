<?php
session_start();
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name ="viewport" content="with=device-width, inital-scale-1.0">
    <link href="html" rel="stylesheet" type="text/css" />
    <title> About us </title>
    <link rel="stylesheet" href="stylesheets/navigation.css">
    <link rel="stylesheet" href="stylesheets/services.css">
    <?php include("includes/navigation.php"); ?>
    <script src="https://kit.fontawesome.com/d713efa799.js" crossorigin="anonymous"></script>

</head>
<body>
    <div class="content">
        <div class = "container">
            <h1>Our Services</h1>
            <div class="row">
                <div class="service">
                  <i class="fa-solid fa-ear-listen"></i>
                  <h2> Type A £35 Counselling</h2>
                  <p> Package Includes 30 Minute Talking & Listening Session </p>
              </div>
              <div class="service">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
                <h2> Type B £55 Counselling</h2>
                <p> Package Includes 60 Minute Talking & Analytical Sessions</p>
            </div>
            <div class="service">
              <i class="fa-regular fa-clipboard"></i>
              <h2> Type C £65 Counselling</h2>
              <p> Package Includes 75 Minute Talking Session & Communication Stage</p>
          </div>
          <div class="service">
             <i class="fa-solid fa-users"></i>
             <h2> Type D £85 Counselling</h2>
             <p> Package Includes 90 Minute Talking Sessions & Evalulation Stage</p>
         </div>
         <div class="service">
             <i class="fa-solid fa-hand-holding-heart"></i>
             <h2> Type E £105 Counselling</h2>
             <p> Package Includes 2 Hour Talking Sessions & Response Therapy</p>
         </div>
     </div>
 </div>

 <div class="meet">
    <h1> Meet Our Therapists!</h1>
</div>


<div class="column">
    <div class="card">
       <img src="./images/Profiles/P5.jpg" alt="sarah"> 
       <div class="therapist">
        <h2>Sarah Nelly</h2>
        <p class="title"> Manager and Director of Practice</p>
        <p>"Hi there, I am the director of this establishment and we specialize in providing counseling services. Our organization is dedicated to serving individuals who are struggling with various mental health issues, and we strive to create a safe and non-judgmental space where everyone can share their thoughts and feelings without fear.</p>
        <p> We welcome anyone to our therapy practice, and we are honored to support those who are seeking help".</p>
        
        <button class="button">Book a Consultation</button>
    </div>
</div>
</div>


<div class="column">
    <div class="card">
       <img src="./images/Profiles/P3.jpg" alt="sarah"> 
       <div class="therap3st">
        <h2>Halima Akhtar</h2>
        <p class="title"> Therapist </p>
        <p>"Hi, I am Halima, one of the qualified therapists, who specialises in talking therapy. As we hold many sessions, it's important to recognize that every individual who seeks therapy has their own unique concerns and struggles. It takes a lot of courage to reach out for help, and it's essential that out therapists provide a safe and welcoming environment where clients feel comfortable sharing their thoughts and feelings". </p>
        <button class="button">Book a Consultation</button>
    </div>
</div>
</div>


<div class="column">
    <div class="card">
       <img src="./images/Profiles/P4.jpg" alt="sarah"> 
       <div class="therapist">
        <h2>Albert Fonso</h2>
        <p class="title"> Therapist</p>
        <p>"Hey there, I'm Albert, and I understand that talking therapy can seem intimidating at first. But trust me, there's nothing to be scared of. As a therapist, I strive to create a safe and supportive environment where my clients feel comfortable.I work with clients from all kinds of backgrounds and religions, and I make it a point to take into consideration their unique situations. For me, positivity is key. I believe that a positive mindset can make all the difference in our mental and emotional well-being". </p>
        <button class="button">Book a Consultation</button>
    </div>
</div>
</div>


</div>



</body>

</html>