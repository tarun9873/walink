<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Link Expired – Reactivate WhatsApp Link</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f0fdf4, #ecfeff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: #ffffff;
            padding: 45px 35px;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 420px;
            width: 100%;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .icon {
            font-size: 60px;
            margin-bottom: 10px;
        }

        h1 {
            color: #dc2626;
            font-size: 26px;
            margin-bottom: 12px;
        }

        p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: 0.3s ease;
            box-shadow: 0 8px 20px rgba(37,211,102,0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(37,211,102,0.4);
        }

        .note {
            margin-top: 18px;
            font-size: 13px;
            color: #888;
        }

        .brand {
            margin-top: 25px;
            font-size: 14px;
            color: #25D366;
            font-weight: 600;
        }

        @media(max-width:480px){
            .card {
                padding: 35px 25px;
            }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="icon">🔒</div>

    <h1>Link Expired</h1>

    <p>
        This WhatsApp link is temporarily disabled because the owner's subscription has expired.
        <br><br>
        Once the plan is renewed, the same link will start working automatically.
    </p>

    <a href="{{ route('pricing') }}" class="btn">
        Upgrade Plan & Reactivate
    </a>

    <div class="note">
        No need to create a new link after upgrade.
    </div>

    <div class="brand">
        Powered by Walive.link
    </div>
</div>

</body>
</html>
