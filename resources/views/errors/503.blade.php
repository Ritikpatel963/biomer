<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - Bharat Biomer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --bb-green-dark: #1a4f2a;
            --bb-green-mid: #2e7d45;
            --bb-green-btn: #194E20;
            --bb-green-hover: #1e6131;
            --bb-text-body: #3d3d3d;
            --bb-text-light: #5a5a5a;
            --bb-bg: #ffffff;
            --bb-font-body: 'Poppins', sans-serif;
        }
        body {
            font-family: var(--bb-font-body);
            background-color: var(--bb-bg);
            color: var(--bb-text-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .coming-soon-container {
            text-align: center;
            max-width: 600px;
            background: var(--bb-bg);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(30,80,40,.10);
        }
        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--bb-green-dark);
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2rem;
            color: var(--bb-text-light);
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .highlight {
            color: var(--bb-green-mid);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="coming-soon-container">
        <img src="{{ asset('assets/images/home-img/bb logo.png') }}" alt="Bharat Biomer Logo" class="logo">
        <h1>Coming Soon</h1>
        <p>We're working hard to bring you something amazing at <span class="highlight">Bharat Biomer</span>. Stay tuned for our launch!</p>
        <p class="text-muted">Expected launch: Soon</p>
    </div>
</body>
</html>