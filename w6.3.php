<?php
//Create Session
if (!isset($_SESSION)) {
    session_start();
}

//Create variables to hold form data and errors
$nameErr = $emailErr = $contBackErr = "";
$name = $email = $contBack = $comment = "";
$formErr = false;

//Validate form when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["name"]))) {
        $nameErr = "Name is required.";
        $formErr = true;
    } else {
        $name = cleanInput($_POST["name"]);
        //Use REGEX to accept only letters and white spaces
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and standard spaces allowed.";
            $formErr = true;
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required.";
        $formErr = true;
    } else {
        $email = cleanInput($_POST["email"]);
        // Check if e-mail address is formatted correctly
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Please enter a valid email address.";
            $formErr = true;
        }
    }

    if (empty($_POST["contact-back"])) {
        $contBackErr = "Please let us know if we can contact you back.";
        $formErr = true;
    } else {
        $contBack = cleanInput($_POST["contact-back"]);
    }

    $comment = cleanInput($_POST["comments"]);
}

//Clean and sanitize form inputs
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * If no form errors occur, 
 * send the data to the database
 */
if (($_SERVER["REQUEST_METHOD"] == "POST") && (!($formErr))){
    //Create Connection Variables
    $hostname = "php-mysql-exercisedb.slccwebdev.com";
    $username = "phpmysqlexercise";
    $password = "mysqlexercise";
    $databasename = "php_mysql_exercisedb";

    try {
        //Create new PDO Object with connection parameters
        $conn = new PDO("mysql:host=$hostname;dbname=$databasename",$username, $password);

        //Set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

        $sql = "INSERT INTO jd_sp21_Contacts (name, email, contactBack, comments) VALUES (:name, :email, :contactBack, :comment);";

        //Variable containing SQL command
        $stmt = $conn->prepare($sql);

        //Bind parameters
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':contactBack', $contBack, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        //Execute SQL statement on server
        $stmt->execute();

        //Build success message to display
        $_SESSION['message'] = '<p class="font-weight-bold">Thank you for your submission!</p><p class="font-weight-light" >Your request has been sent.</p>';

        $_SESSION['complete'] = true;

        //Redirect
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;

    } catch (PDOException $error) {

        //Build error message to display
        $_SESSION['message'] =  "<p>We apologize, the form was not submitted successfully. Please try again later.</p>";
        // Uncomment code below to troubleshoot issues
        echo '<script>console.log("DB Error: ' . addslashes($error->getMessage()) . '")</script>';
        $_SESSION['complete'] = true;
        //Redirect
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    $conn = null;
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Theme Simply Me</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
 <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    
 <style>
      body {
  font: 20px "Montserrat", sans-serif;
  line-height: 1.8;
  color: #f5f6f7;
}

p {font-size: 16px;}

    .bg-1 {
      background-color: #088076; /* Green */
      color: #0a0707;
  }
  .bg-8 {
      background-color: #800844; /* Green */
      color: #0a0707;
  }
  .nav ul{list-style: none;}

  .bg-2 {
    background-image: url(images/myself.jpg);
    width: 100%;
    height: 100%; /* image */
    color: #fcdada;
  }
  .bg-3 {
    background-color: #ffffff; /* White */
    color: #555555;
  }
  .bg-4 {
    background-color: #40528a; /* light blue */
    color: #f8f1f1;
  }
  .bg-5 {
    background-color: #f801ae; /* lilac */
    color: #f5e6e6;
  }
  .bg-6 {
    background-color: #f2f5eb; /* white */
    color: #382f2f;
  }
  .bg-7 {
    background-color: #02f854; /* green */
    color: #555555;
  }
  .container-fluid {
  padding-top: 70px;
  padding-bottom: 70px;
}
.comments{width: 200px; height: 200px;}


<!--navBar styling-->

#navbar {
  background-color: #333; /* Black background color */
  position: fixed; /* Make it stick/fixed */
  top: -50px; /* Hide the navbar 50 px outside of the top view */
  width: 100%; /* Full width */
  transition: top 0.3s; /* Transition effect when sliding down (and up) */
}

/* Style the navbar links */
#navbar a {
  float: left;
  display: block;
  color: white;
  text-align: center;
  padding: 15px;
  text-decoration: none;
}

#navbar a:hover {
  background-color: #ddd;
  color: black;
}

nav #header {background-image: url("images/myself.jpg");}                  <!--ask hot to resize the img of thee background-->



    </style>
  
</head>
<body >                                            


<div id="header" >
                    <nav class="navbar navbar-inverse" id="navbar">                                 <!-- navbar -->
                        <div class="container-fluid">
                          <div class="navbar-header">
                            <a class="navbar-brand" href="#header">My Portfolio</a>
                          </div>
                          <ul class="nav navbar-nav">
                            <li class="active"><a href="#main">Profile</a></li>
                            <li><a href="#education">Education</a></li>
                            <li><a href="#experiences">Experiences</a></li>
                            <li><a href="#abilities">Abilities</a></li>
                            <li><a href="#contact">Contact</a></li>
                          </ul>
                        </div>
                      </nav>   
                </div>
</div>
<div class="profile">
                <h1>PROFILE</h1>
                <div class="container-fluid bg-2 text-center" id="main"> 
                    <h1>ABOUT ME</h1>
                    <p> Im an action oriented person who enjoys challenges and look for the positive aspects in any adversity.
                    <p> NASM certified personal trainer comitted to always get you to be <i>the best version of yourself</i>, not only phisichally but mentally.</p>
                    <p>Strategic thinker with exceptional health and wellness expertise.<p>
                    <img src="images/myself.jpg" alt="" class="img-circle" width="300px" height="300px">
                    <div id="details">
                        <h1><i>DETAILS</i></h1>
                        <br>
                        <h2>Name</h2>
                        <p>Arianna Hernandez</p>
                        <h2>Age</h2>
                        <p>24 years old.</p>
                        <h2>Location</h2>
                        <p>Salt Lake City, Utah</p>
                        <h2>Follow</h2>
                        <img src="images/FB.jpg" alt="" height="20px" width="20px"> 
                        <img src="images/IG.png" alt="" height="20px" width="20px">
                    </div>
</div>
                <div class="container-fluid bg-3 text-center" id="education"> 
                    <h2>Education</h2>
                    <p>Graduated from San Francisco Javier High School in Punto Fijo, Venezuela.
                    Attend Experimental University of Francisco de Miranda (UNEFM) for Chemical engineering and the University of Falcon and
                    Universidad of Falcon (UDEFA) for business management.
                    Obatined GED diploma from Granite Peaks High School.
                    </p>

          <hr>
                    <table>
                        <caption><strong>CERTIFICATIONS</strong></caption>
                        <tr>
                            <th>ORGANIZATION</th>
                            <th>CERTIFICATION TYPE</th>
                            <th>CERTIFICATE</th>
                        </tr>
                        <tr>
                            <td>National Academy of Sports and Medicine</td>
                            <td>Personal training </td>       
                            <td><a href="images/nasm.jpg" target="_blank">NASM</a>
                        </tr>
                        <tr>
                            <td>AED & CPR</td>
                            <td>First AID and CPR  </td>
                            <td><a href="images/CPRCERTIFICATE.JPG" target="_blank"> REDCROSS </a></td>
                        </tr>
                    </table> 

                <div class="container-fluid bg-4 text-center" id="experiences">
                    <h1> EXPERIENCES</h1>
                    <hr>
                <div id="jobList">
                    <h2><em>Field Operations Specialist</em> @ Peloton |  aug 2020- february 2022 <br></h2>
    
                    <button type="button" id="hideMeOne" style="background-color:#800844">hide description</button> 
                    <button type="button" id="showMeOne" style="background-color:#800844">show</button>
                    <div id="fieldOps">
                        <p> Extended remarkable support and information for multiple products (bike and tablet) to the Peloton members.</p>
                        <p>Functioned as the Frontline Ambassadors extensively supporting the organization’s mission and values (members first). </p>
                        <p>Provided detailed information and comprehensive orientation on the Peloton’s bike and tablet to the new members.</p>
                        <p>Extended exceptional support to the member throughout the entire delivery experience.
                        <p>Staged the bike and any additional accessories in the member’s home.
                        <p>Installed the Peloton hardware along with the troubleshooting of the post installations issues.</p>
                        <p>Successfully maintained designated fleet vehicle.</p>
                        <p>Managed delivery route with Peloton systems and maintained communication with the member before delivery.</p>
                        <p>Ensured compliance to and prioritized Peloton’s standards of safety for myself, my team member, and the member on and off the road at all times.</p> 
                    </div>
                    <br>
                    <hr>

                    <h2><em>Administrative Clerk </em>@ Highland Ridge Hospital |  oct 2019- aug 2020.</h2>
                    <button type="button" id="hideMeTwo" style="background-color:#800844">hide description</button> 
                    <button type="button" id="showMeTwo" style="background-color:#800844">show</button>
                    <div id="admClerk">
                        <p>Successfully resolved customer questions, provided enquired information, processed orders and addressed complaints.</p>
                        <p>Promptly answered phone calls and called customers and vendors to follow up on appointments and deliveries.</p>
                        <p>Displayed skills in compiling, maintaining and updating company records.</p>
                        <p>Managed office inventory and coordinated with vendors to ensure the regular supply of office materials.</p>
                        <p>Involved in setting up appointments, scheduled meetings, distributing reports and managing correspondence between the office and external bodies.</p>
                        <p>Gathered and maintained records of office business transactions.</p>
                        <p>Skillfully operated office equipment, involving printers, copiers, fax machines and multimedia instruments.</p>
                    </div>
                    <br>
                    <hr>
                    <h2><em>Interpreter</em> @ Linguistica International | 2019-onwards</h2>
                    <button type="button" id="hideMeThree" style="background-color:#800844">hide description</button> 
                    <button type="button" id="showMeThree" style="background-color:#800844">show</button>
                    <div id="interpreter">
                        <p>Interpreted communication between the staff and non-English speaking population. Translated written educational materials and documents as per readability guidelines.</p>
                    </div>

                </div>
                </div>

                          <div class="container-fluid bg-5 text-center" id="abilities">
                    <h1>ABILITIES</h1>
                    <ul>
                        <li> <h2>LANGUAGES  </h2>
                        <li>English.</li>  
                        <li>Spanish.</li>          
                        <li>Healthy living model.</li>
                        <li>Fitness instruction.  </li>
                        <li>Health and wellness</li>
                        <li>Coaching. </li>
                        <li>Excersice program design.</li>
                        <li>Knowledge of human anatomy. </li>
                        <li>Nutrition principles. </li>
                        <li>Great Microsoft office and computer understanding, including Power Point, Outlook, Word and Excel.</li>
                        <li>Organization and management skills.</li>
                        <li>Exceptional linguistic abilities Great communication and people Skill.</li>
                        <li>High decision-Making Values.</li>
                        <li>Time and stress management.</li>
                        <li>Quick thinker and fast learner Able to multitasking with multiple customers.</li>
                        <li>Actively looking for ways to help people.</li>
                        <li>Selfdriven, collaborative, and motivated to excel in achieving individual and team results.</li>
                        <li>Develop and maintain positive working relationships.</li>
                    </ul>
                </div>
                <!-- Contact Form Section -->
            <section id="contact">
        <div class="container py-5">
            <!-- Section Title -->
            <div class="row justify-content-center text-center">
                <div class="col-md-6">
                    <h2 class="display-4 font-weight-bold">Contact Me</h2>
                    <hr />
                </div>
            </div>
            <!-- Contact Form Row -->
            <div class="row justify-content-center">
                <div class="col-6">
                
                    <!-- Contact Form Start -->
                    <form id="contactForm" action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "#contact"); ?> method="POST" novalidate>
                        
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <span class="text-danger">*<?php echo $nameErr; ?></span>
                            <input type="text" class="form-control" id="name" placeholder="Full Name" name="name" value="<?php if(isset($name)) {echo $name;}?>"" />							
                        </div>
                        
                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <span class="text-danger">*<?php echo $emailErr; ?></span>
                            <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" value="<?php if(isset($email)) {echo $email;} ?>" />
                        </div>
                        
                        <!-- Radio Button Field -->
                        <div class="form-group">
                            <label class="control-label">Can we contact you back?</label>
                            <span class="text-danger">*<?php echo $contBackErr; ?></span>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="contact-back" id="yes" value="Yes"  <?php if ((isset($contBack)) && ($contBack == "Yes")) {echo "checked";}?>/>
                                <label class="form-check-label" for="yes" >Yes</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="contact-back" id="no" value="No" <?php if ((isset($contBack)) && ($contBack == "No")) {echo "checked";}?>/>
                                <label class="form-check-label" for="no" >No</label>
                            </div>
                        </div>
                        
                        <!-- Comments Field -->
                        <div class="form-group">
                            <label for="comments">Comments:</label>
                            <textarea id="comments" class="form-control" rows="3" name="comments"><?php if (isset($comment)) {echo $comment;} ?></textarea>
                        </div>

                        <!-- Required Fields Note-->
                        <div class="text-danger text-right">* Indicates required fields</div>
                        
                        <!-- Submit Button -->
                        <button class="btn btn-primary mb-2" type="submit" role="button" name="submit">Submit</button>
                    </form>						
                </div>
            </div>
        </div>

        <!-- Thank you Modal -->
        <div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="thankYouModalLabel">Thank you!</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Show thank you message -->
    <?php 
        if (isset($_SESSION['complete']) && $_SESSION['complete']) {
            echo "<script>$('#thankYouModal').modal('show');</script>";
            session_unset(); 
        };
    ?>
<script src="show-hide.js"></script>
</body>
</html>