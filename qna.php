<?php
// Function to calculate similarity (Levenshtein distance)
function calculateSimilarity($input, $question) {
    return levenshtein(strtolower($input), strtolower($question));
}

// Load QA data from JSON file
$qa_file = __DIR__ . '/qa_data.json';
$qa_data = json_decode(file_get_contents($qa_file), true);

// Initialize variables
$user_question = $selected_category = $selected_question = $response = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['question'])) {
        $user_question = trim($_POST['question']);
        
        // Find the most similar question
        $closest_question = null;
        $shortest_distance = PHP_INT_MAX;

        foreach ($qa_data['categories'] as $category) {
            foreach ($category['questions'] as $question) {
                $distance = calculateSimilarity($user_question, $question['question']);
                if ($distance < $shortest_distance) {
                    $shortest_distance = $distance;
                    $closest_question = $question;
                    $selected_category = $category['name'];
                }
            }
        }

        // Set the response
        if ($closest_question && $shortest_distance <= 3) { // Threshold can be adjusted
            $response = "Did you mean: <strong>{$closest_question['question']}</strong>?<br>Answer: {$closest_question['answer']}";
        } else {
            $response = "Sorry, we couldn't find a similar question in our database.";
        }
    }
}

// Prepare QA data for JavaScript
$qa_data_json = json_encode($qa_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v5.3.0, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
  <meta name="description" content="">
  <title>Home</title>
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons2/mobirise2.css">
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons-bold/mobirise-icons-bold.css">
  <link rel="stylesheet" href="assets/tether/tether.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/dropdown/css/style.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="preload" as="style" href="assets/mobirise/css/mbr-additional.css"><link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">
  <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


  <style>
      body {
          background: linear-gradient(to right, #e0f7fa, #e0ffe0);
          font-family: Arial, sans-serif;
      }
      .card {
          border: none;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
      .btn-primary {
          background-color: #4CAF50;
          border-color: #4CAF50;
      }
      .navbar .nav-link {
          color: #4CAF50 !important;
      }
  </style>
</head>
<body>
<section class="menu cid-s48OLK6784" once="menu" id="menu1-h">
  <nav class="navbar navbar-dropdown navbar-fixed-top navbar-expand-lg">
      <div class="container">
          <div class="navbar-brand">
              <span class="navbar-logo">
                  <a href="./home.php">
                      <img src="assets/images/logo.png" alt="Mobirise" style="height: 3.8rem;">
                  </a>
              </span>
              <span class="navbar-caption-wrap">
                  <a class="navbar-caption text-dark display-7" href="#">
                      FarmEase<br>Intelligent Farm Management System
                  </a>
              </span>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <div class="hamburger">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
              </div>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav nav-dropdown" data-app-modern-menu="true">
                  <li class="nav-item"><a class="nav-link link text-black display-4" href="#">&nbsp;</a></li>
              </ul>
              <div class="navbar-buttons mbr-section-btn">
                  <a class="btn btn-success display-4" href="./home.php">Home</a>
                  <a class="btn btn-success display-4" href="./phone.php">Login</a>
                  <a class="btn btn-success display-4" href="#">E-market</a>
              </div>
          </div>
      </div>
  </nav>
</section>

<div class="container" style="margin-top: 120px;margin-bottom: 100px;">
  <h2 class="text-center mb-4" style="color: #4CAF50;">FarmEase Q&A</h2>
  
  <!-- Category selection form -->
  <form method="POST" action="" class="mb-4" id="qnaForm">
      <div class="form-group">
          <label for="category">Select a Category:</label>
          <select class="form-control" id="category" name="category" required>
              <option value="" selected disabled>Select Category</option>
              <?php foreach ($qa_data['categories'] as $category): ?>
                  <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="form-group" id="questionDropdown" style="display: none;">
          <label for="selected_question">Select a Question:</label>
          <select class="form-control" id="selected_question" name="selected_question">
              <option value="" selected disabled>Select Question</option>
          </select>
      </div>
  </form>

  <!-- Display answer -->
  <div class="card mb-3" id="answerDisplay" style="display: none;">
      <div class="card-body">
          <h5 class="card-title" style="color: #4CAF50;">Selected Question:</h5>
          <p class="card-text"><strong>A:</strong> <span id="answerText"></span></p>
      </div>
  </div>
  <p id="google_translate_element"></p>
  <!-- Form to submit a new question -->
  <form method="POST" action="" class="shadow p-4" style="background-color: #ffffff; border-radius: 10px;">
      <div class="form-group">
          <label for="question" class="font-weight-bold" style="color: #333;">Ask a Question:</label>
          <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
  </form>

  <!-- Display response to new question -->
  <?php if ($response): ?>
      <div class="alert alert-info mt-4" role="alert">
          <?php echo $response; ?>
      </div>
  <?php endif; ?>
</div>



<section class="footer4 cid-sqKDFx7MRz" once="footers" id="footer4-z">

    
    
    <div class="container">
        <div class="row mbr-white">
            <div class="col-6 col-lg-3">
                <div class="media-wrap col-md-8 col-12">
                    <a href="">
                        <img src="assets/images/logo.png" alt="Mobirise">
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="mbr-section-subtitle mbr-fonts-style mb-2 display-7">
                    <strong>About</strong>
                </h5>
                <p class="mbr-text mbr-fonts-style mb-4 display-4">
                    Farmease is a web application build for betterment of Farmer's Life.It provides Complete Ecosystem which include Intelligent Farmer E-marketplace with Analysis. </p>
                <h5 class="mbr-section-subtitle mbr-fonts-style mb-3 display-7">
                    <strong>Follow Us</strong>
                </h5>
                <div class="social-row display-7">
                    <div class="soc-item">
                        <a href="https://twitter.com" target="_blank">
                            <span class="mbr-iconfont socicon socicon-facebook"></span>
                        </a>
                    </div>
                    <div class="soc-item">
                        <a href="https://twitter.com" target="_blank">
                            <span class="mbr-iconfont socicon socicon-twitter"></span>
                        </a>
                    </div>
                    <div class="soc-item">
                        <a href="https://twitter.com" target="_blank">
                            <span class="mbr-iconfont socicon socicon-instagram"></span>
                        </a>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="mbr-section-subtitle mbr-fonts-style mb-2 display-7">
                    <strong>Contact</strong>
                </h5>
                <ul class="list mbr-fonts-style display-4">
                    <ul>
                            <li>Address: Windsor, Ontario</li>
                            <li>Email: Farmease@gmail.com</li>
                        </ul>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="mbr-section-subtitle mbr-fonts-style mb-2 display-7">
                    
                </h5>
            <h6>Contact us</h6>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#">
                            <input type="text" placeholder="Farmease@gmail.com
">
                            <button type="submit" class="site-btn">Subscribe</button>
                        </form>
                    </div>
            
        </div>
    </div>
</section>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Pass PHP data to JavaScript
  var qaData = <?php echo $qa_data_json; ?>;

  document.getElementById('category').addEventListener('change', function() {
      var selectedCategory = this.value;
      var questionDropdown = document.getElementById('selected_question');
      var questions = qaData.categories.find(category => category.name === selectedCategory).questions;

      // Clear previous options
      questionDropdown.innerHTML = '<option value="" selected disabled>Select Question</option>';

      // Populate new options
      questions.forEach(question => {
          var option = new Option(question.question, question.question);
          questionDropdown.add(option);
      });

      document.getElementById('questionDropdown').style.display = 'block';
  });

  document.getElementById('selected_question').addEventListener('change', function() {
      var selectedQuestion = this.value;
      var answerDisplay = document.getElementById('answerDisplay');
      var answerText = document.getElementById('answerText');
      var selectedCategory = document.getElementById('category').value;

      var questions = qaData.categories.find(category => category.name === selectedCategory).questions;
      var answer = questions.find(question => question.question === selectedQuestion).answer;

      answerText.textContent = answer;
      answerDisplay.style.display = 'block';
  });
</script>
</body>
</html>
