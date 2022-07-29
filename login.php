<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login-page.css">
</head>

<body>
  <main id="main-holder">
    <h1 id="login-header">Login</h1>
    
    <div id="login-error-msg-holder">
      <p id="login-error-msg">Invalid username <span id="error-msg-second-line">and/or password</span></p>
    </div>
    
    <form action='checklogin.php' method="POST">
      <input type="text" name="username" placeholder="Username" class="login-form-field"/>
      <input type="password" name="password" placeholder="Password" class="login-form-field"/>
      <input type="submit" name="login" value="Login" class="login-form-submit"/>
    </form>
  </main>
</body>
</html>