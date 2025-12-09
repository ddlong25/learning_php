<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Đăng nhập</title>
    <style>
        :root{
            --bg:#f3f6fb;
            --card:#ffffff;
            --accent:#2563eb;
            --error:#b00020;
            --success:#0a7a07;
            --muted:#6b7280;
        }
        *{box-sizing:border-box;font-family:system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;}
        body{
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(180deg,var(--bg),#e9eef8);
            padding:24px;
        }
        .login-wrap{
            width:100%;
            max-width:420px;
            background:var(--card);
            border-radius:12px;
            box-shadow:0 10px 30px rgba(15,23,42,0.08);
            padding:28px;
        }
        .brand{
            display:flex;
            gap:12px;
            align-items:center;
            margin-bottom:10px;
        }
        .logo{
            width:48px;
            height:48px;
            border-radius:8px;
            background:linear-gradient(135deg,var(--accent),#7c3aed);
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-weight:700;
            font-size:18px;
        }
        h1{font-size:18px;margin:0;color:#0f172a;}
        p.lead{margin:6px 0 18px;color:var(--muted);font-size:13px;}
        .message { padding:10px 12px; margin-bottom:16px; border-radius:8px; font-size:14px; }
        .error   { color:var(--error); background:#fff0f0; border:1px solid #ffd6d6; }
        .success { color:var(--success); background:#f0fff0; border:1px solid #d2f4d2; }
        label{display:block;font-size:13px;color:var(--muted);margin-bottom:6px}
        .input{
            width:100%;
            padding:10px 12px;
            border-radius:8px;
            border:1px solid #e6e9ef;
            font-size:14px;
            background:#fff;
            margin-bottom:12px;
            outline:none;
            transition:box-shadow .15s, border-color .15s;
        }
        .input:focus{box-shadow:0 6px 18px rgba(37,99,235,0.12);border-color:var(--accent);}
        .actions{display:flex;align-items:center;justify-content:space-between;margin-top:6px;}
        .btn{
            background:var(--accent);
            color:#fff;
            border:none;
            padding:10px 14px;
            border-radius:8px;
            cursor:pointer;
            font-weight:600;
            box-shadow:0 6px 18px rgba(37,99,235,0.12);
        }
        .btn:active{transform:translateY(1px)}
        .small{font-size:13px;color:var(--muted)}
        .footer{margin-top:18px;text-align:center;color:var(--muted);font-size:13px}
        @media (max-width:480px){
            .login-wrap{padding:20px;border-radius:10px}
        }
    </style>
</head>
<body>
<?php
$message = '';
$cls = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($username === '' || $password === '') {
        $message = 'Vui lòng nhập cả username và mật khẩu.';
        $cls = 'error';
    } else {
        $safeUser = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $message = "Đăng nhập thành công. Chào: {$safeUser}";
        $cls = 'success';
    }
}
?>

<div class="login-wrap" role="main" aria-labelledby="loginTitle">
    <div class="brand">
        <div class="logo">LONG</div>
        <div>
            <h1 id="loginTitle">Đăng nhập</h1>
            <p class="lead">Nhập username và mật khẩu để tiếp tục</p>
        </div>
    </div>

    <?php if ($message !== ''): ?>
        <div class="message <?php echo $cls; ?>">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="" novalidate>
        <label for="username">Username</label>
        <input id="username" name="username" class="input" type="text" autocomplete="username"
               value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">

        <label for="password">Mật khẩu</label>
        <input id="password" name="password" class="input" type="password" autocomplete="current-password">

        <div class="actions">
            <div class="small"><label><input type="checkbox" name="remember"> Ghi nhớ</label></div>
            <button class="btn" type="submit">Đăng nhập</button>
        </div>
    </form>

    <div class="footer">
        <a href="#" style="color:var(--accent);text-decoration:none">Quên mật khẩu?</a>
    </div>
</div>
</body>
</html>