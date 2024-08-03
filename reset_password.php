<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
  body {
    background-color: #212121;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .reset-box {
    width: 400px;
    padding: 40px;
    background: rgba(24, 20, 20, 0.987);
    box-sizing: border-box;
    box-shadow: 0 15px 25px rgba(0,0,0,.6);
    border-radius: 10px;
  }

  .reset-box .user-box {
    position: relative;
  }

  .reset-box .user-box input {
    width: 100%;
    padding: 10px 0;
    font-size: 16px;
    color: #fff;
    margin-bottom: 30px;
    border: none;
    border-bottom: 1px solid #fff;
    outline: none;
    background: transparent;
  }

  .reset-box .user-box label {
    position: absolute;
    top: 0;
    left: 0;
    padding: 10px 0;
    font-size: 16px;
    color: #fff;
    pointer-events: none;
    transition: .5s;
  }

  .reset-box .user-box input:focus ~ label,
  .reset-box .user-box input:valid ~ label {
    top: -20px;
    left: 0;
    color: #bdb8b8;
    font-size: 12px;
  }

  .reset-box form button {
    position: relative;
    display: inline-block;
    padding: 10px 20px;
    color: #ffffff;
    font-size: 16px;
    text-decoration: none;
    text-transform: uppercase;
    overflow: hidden;
    transition: .5s;
    margin-top: 40px;
    letter-spacing: 4px;
    background: transparent;
    border: none;
    cursor: pointer;
    text-align: center;
  }

  .reset-box form button:hover {
    background: #03f40f;
    color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 5px #03f40f,
                0 0 25px #03f40f,
                0 0 50px #03f40f,
                0 0 100px #03f40f;
  }

  .reset-box form button span {
    position: absolute;
    display: block;
  }

  @keyframes btn-anim1 {
    0% {
      left: -100%;
    }

    50%,100% {
      left: 100%;
    }
  }

  .reset-box form button span:nth-child(1) {
    bottom: 2px;
    left: -100%;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #03f40f);
    animation: btn-anim1 2s linear infinite;
  }

  .reset-box .form-center {
    text-align: center;
  }
</style>
</head>
<body>
<div class="reset-box">
  <form action="update_password.php" method="post">
    <div class="user-box">
      <input type="password" name="new_password" required="">
      <label>New Password</label>
    </div>
    <div class="form-center">
      <button type="submit">UPDATE PASSWORD<span></span></button>
    </div>
  </form>
</div>
</body>
</html>
