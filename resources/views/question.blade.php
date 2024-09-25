<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zakat Question Page</title>
  <link rel="stylesheet" href="style.css">
  <style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f4f7f9;
  color: #333;
  padding: 20px;
}

.container {
  max-width: 900px;
  margin: 0 auto;
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile {
  text-align: center;
  margin-bottom: 20px;
}

.profile-image {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background-color: #e0e0e0;
}

.question-box {
  margin-bottom: 30px;
}

.question-box h3 {
  color: #333;
  margin-bottom: 10px;
}

.response-box {
  display: flex;
  justify-content: space-around;
  margin-bottom: 20px;
}

.response {
  text-align: center;
  padding: 20px;
  border-radius: 8px;
  width: 40%;
}

.response.true {
  background-color: #e7f8ef;
  color: #34a853;
}

.response.false {
  background-color: #fcebea;
  color: #ea4335;
}

.percentage {
  font-size: 36px;
  font-weight: bold;
}

.comments-section {
  margin-bottom: 30px;
}

.comment {
  display: flex;
  margin-bottom: 20px;
}

.comment-user-image {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: #e0e0e0;
  margin-right: 10px;
}

.comment-content p {
  margin-bottom: 5px;
}

.app-download {
  text-align: center;
  margin-top: 20px;
}

.app-download p {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
}

.store-buttons img {
  width: 150px;
  margin: 5px;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .response-box {
    flex-direction: column;
    gap: 10px;
  }

  .response {
    width: 100%;
  }

  .comment {
    flex-direction: column;
  }
}

  </style>
</head>
<body>
  <div class="container">
    <div class="profile">
      <img src="profile-placeholder.png" alt="Profile" class="profile-image">
      <h2>Tonny Johnson</h2>
    </div>
    
    <div class="question-box">
      <h3>Question:</h3>
      <p>I want to give my Zakat to an Islamic Center, what should I do? All Scholar on our platform are verified and background checked. Feel free to ask any type of question.</p>
    </div>

    <div class="response-box">
      <div class="response true">
        <div class="percentage">30%</div>
        <div class="label">True</div>
      </div>
      <div class="response false">
        <div class="percentage">70%</div>
        <div class="label">False</div>
      </div>
    </div>

    <div class="comments-section">
      <h4>Comments</h4>
      <div class="comment">
        <img src="comment-user1.png" alt="User" class="comment-user-image">
        <div class="comment-content">
          <p><strong>Amyrobson</strong> <span>1 month ago</span></p>
          <p>Impressive! Though it seems the drag feature could be improved. But overall it looks incredible. You've nailed the design and the responsiveness at various breakpoints works really well.</p>
        </div>
      </div>

      <div class="comment">
        <img src="comment-user2.png" alt="User" class="comment-user-image">
        <div class="comment-content">
          <p><strong>Hamza Naeem</strong> <span>1 month ago</span></p>
          <p>All Scholar on our platform are verified and background checked. Feel free to ask any type of question. The responsiveness at various breakpoints works really well.</p>
        </div>
      </div>
    </div>

    <div class="app-download">
      <p>Download The App</p>
      <div class="store-buttons">
        <img src="playstore.png" alt="Google Play">
        <img src="appstore.png" alt="App Store">
      </div>
    </div>
  </div>
</body>
</html>
