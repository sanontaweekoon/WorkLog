<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worklog - เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            font-family: 'Kanit', sans-serif;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #F7F8F9; /* สีเขียวมินิมอล */
            z-index: -1;
        }
        .login-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 330px;
            text-align: center;
        }
        .login-card h3 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .login-card p {
            font-size: 14px;
            color: #7f8c8d;
        }
        .logo {
            font-size: 50px;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 20px;
            padding: 10px;
        }
        .btn-login {
            background: #28a745;
            border: none;
            border-radius: 25px;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo"><i class="fa-solid fa-clipboard-list"></i></div>
        <h3>Welcome to Worklog</h3>
        <p>เข้าสู่ระบบเพื่อบันทึกงานของคุณ</p>
        <form action="chklogin.php" method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" required placeholder="ชื่อผู้ใช้">
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" required placeholder="รหัสผ่าน">
            </div>
            <button type="submit" class="btn btn-login w-100">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>
</html>
