<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - UT Satui</title>
    <style>
        body{display:flex;justify-content:center;align-items:center;height:100vh;background:#f3f4f6;font-family:sans-serif}
        form{background:white;padding:2rem;border-radius:8px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);width:100%;max-width:320px}
        h2{text-align:center;color:#374151;margin-bottom:1.5rem}
        input{width:100%;padding:0.5rem;margin-bottom:1rem;border:1px solid #d1d5db;border-radius:4px;box-sizing:border-box}
        button{width:100%;background:#fbbf24;color:#1f2937;font-weight:bold;padding:0.75rem;border:none;border-radius:4px;cursor:pointer}
        button:hover{background:#f59e0b}
        .err{color:#ef4444;font-size:0.875rem;margin-bottom:1rem;text-align:center}
    </style>
</head>
<body>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <h2>Admin Portal</h2>
        
        {{-- General error message --}}
        @error('password') <div class="err">{{ $message }}</div> @enderror
        
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">LOGIN</button>
    </form>
</body>
</html>