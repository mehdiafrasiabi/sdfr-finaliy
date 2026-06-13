<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>پنل موقتاً بسته است</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            font-family: Tahoma, "Segoe UI", sans-serif;
            color: #e2e8f0;
            padding: 24px;
        }
        .card {
            background: rgba(30, 41, 59, .85);
            border: 1px solid rgba(148, 163, 184, .2);
            border-radius: 20px;
            padding: 40px 32px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.4);
        }
        .icon {
            width: 72px; height: 72px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: rgba(245, 158, 11, .15);
            display: flex; align-items: center; justify-content: center;
            font-size: 34px;
        }
        h1 { font-size: 22px; margin: 0 0 12px; color: #f8fafc; }
        p { font-size: 15px; line-height: 1.9; color: #cbd5e1; margin: 0 0 24px; }
        .actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        a.btn {
            text-decoration: none;
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 14px;
            display: inline-block;
        }
        a.primary { background: #f59e0b; color: #1e293b; font-weight: bold; }
        a.ghost { border: 1px solid rgba(148,163,184,.35); color: #e2e8f0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🔒</div>
        <h1>پنل به‌طور موقت بسته است</h1>
        <p>{{ $message }}</p>
        <div class="actions">
            <a class="btn primary" href="{{ url('/') }}">صفحهٔ اصلی</a>
            <a class="btn ghost" href="{{ route('client.logout') }}">خروج از حساب</a>
        </div>
    </div>
</body>
</html>
