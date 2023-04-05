<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
        }

        input.form-control,
        button.btn {
            font-size: 16px;
            font-weight: 500;
        }

        button.btn {
            position: relative;
            padding-left: 2.5rem;
        }

        button.btn i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .strength-bar {
            width: 100%;
            height: 10px;
            background-color: #ddd;
            position: relative;
        }

        .strength-fill {
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            transition: width 0.5s ease-in-out;
        }

        .strength-weak {
            background-color: #f44336;
        }

        .strength-medium {
            background-color: #ff9800;
        }

        .strength-strong {
            background-color: #4caf50;
        }
    </style>

</head>

<body class="d-flex justify-content-center align-items-center vh-100">