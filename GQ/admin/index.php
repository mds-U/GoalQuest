<?php
    $page_title = "GoalQuest";
    include('includes_start/header.php');
    include('includes_start/navbar.php'); 
?>

<div class="content-wrapper">
    <div class="text-container py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="title">
                        <h2 class="h2-title">Welcome to</h2>
                        <h1 class="h1-title">Goal Quest</h1>
                        <p class="sub-title">Your Ultimate Task Management Companion</p>
                    </div>
                    
                    <div class="P-content">
                        <p>
                            Say goodbye to scattered tasks and hello to 
                            streamlined productivity. With <b>GoalQuest</b>, 
                            conquer your goals effortlessly with this powerful 
                            task organization and insightful tracking.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <img src="assets/dist/img/image-home.png" alt="image" class="img-fluid" >
                </div>
                
                <div class="col-md-12">
                    <div class="Reg-container">
                        <span class="span-Register" onclick="window.location.href='login.php';">Let's Get Started!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes_start/footer.php'); ?>
