<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
            overflow: hidden;
            /* Prevent vertical scrolling */
        }

        .container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            width: 100%;
            max-width: 1000px;
            /* Ensure a max-width for better appearance */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: auto;
            /* Allow scrolling within the container if needed */
            max-height: 90vh;
            /* Restrict the max height */
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }

        .logo span {
            color: #00afab;
        }

        h1 {
            font-size: 20px;
            color: #333333;
            margin-bottom: 15px;
        }

        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: rgb(0, 0, 0);
        }

        .username {
            color: #00afab;
        }

        .description {
            color: #555555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .otp-box {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            background-color: #00afab;
            display: inline-block;
            padding: 10px 20px;
            letter-spacing: 10px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .note {
            color: #777777;
            font-size: 14px;
            margin: 15px 0;
        }

        .security {
            color: #006666;
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="https://res.cloudinary.com/dnrhne5fh/image/upload/v1742349937/iltxkj2q5hmaowbwo3yf.png" alt="logo">
        </div>
        <h1>Confirm Your Registration with OTP</h1>
        <p class="greeting">Hello <span class="username">{{ $name }}</span> 👋</p>
        <p class="description">
            Thank you for registering with TechBus!
            To complete your registration and secure your account,
            please enter the One-Time Password (OTP) below:
        </p>
        <div class="otp-box">
            @foreach (str_split($otp) as $digit)
                {{ $digit }}&nbsp;
            @endforeach
        </div>
        <p class="note">
            If you didn’t request a password reset, you can safely ignore this email.
            Rest assured, your account remains secure.
        </p>
        <p class="security">
            <strong>For additional security, please do not share this OTP with anyone.</strong>
        </p>
    </div>
</body>

</html>
