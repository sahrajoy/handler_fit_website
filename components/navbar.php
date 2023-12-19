<?php

echo "
<nav class='navbar navbar-expand-lg bg-body-tertiary'>
  <div class='container-fluid'>
    <a class='navbar-brand' href='{$loc}index.php'>
      <img src='{$loc}assets/images/handler.png' id='logo'>
    </a>
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
      <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='{$loc}onlinecoaching.php' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
            Online Coaching
            </a>
            <ul class='dropdown-menu'>
            <li><a class='dropdown-item' href='{$loc}online_coaching/whats_online.php'>what's online-coaching?</a></li>
            <li><a class='dropdown-item' href='{$loc}online_coaching/weight_loss_coaching.php'>weight loss coaching</a></li>
            <li><a class='dropdown-item' href='{$loc}online_coaching/fitness_nutrition_tips.php'>fitness & nutrition tips</a></li>
            <li><a class='dropdown-item' href='{$loc}online_coaching/training_plans.php'>training plans</a></li>
            </ul>
        </li>
        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='{$loc}personal_training.php' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
            Personal Training
            </a>
            <ul class='dropdown-menu'>
                <li><a class='dropdown-item' href='{$loc}personal_training/whats_personal.php'>what's personal training?</a></li>
                <li><a class='dropdown-item' href='{$loc}personal_training/hypertrophy.php'>Hypertrophy (muscle building)</a></li>
                <li><a class='dropdown-item' href='{$loc}personal_training/strength_training.php'>Strength training</a></li>
                <li><a class='dropdown-item' href='{$loc}personal_training/belly-butt-thigh.php'>Belly-butt-thigh Workout</a></li>
            </ul>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='{$loc}about.php'>About</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='{$loc}contact.php'>Contact</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' id='green' href='#'>Get in touch</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='https://www.instagram.com/handler_fit/'>&#xf16d;</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
";