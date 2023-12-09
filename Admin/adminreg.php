<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        body {
            background-color: #88c8f7;
            color: #000;
            padding: 20px;
        }

        h2 {
            color: #fff;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            margin-bottom: 0.5rem;
            display: block;
        }

        input {
            margin-bottom: 1rem;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        input[type="file"] {
            padding: 0;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2 class="mb-4">Admin Registration Form</h2>
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="instname">Institution Name:</label>
            <input type="text" class="form-control" name="instname" required>
        </div>

        <div class="mb-3">
            <label for="instlogo">Institution Logo:</label>
            <input type="file" class="form-control" name="instlogo" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="instadd">Institution Address:</label>
            <input type="text" class="form-control" name="instadd" required>
        </div>

        <div class="mb-3">
            <label for="instemail">Institution Email:</label>
            <input type="email" class="form-control" name="instemail" required>
        </div>

        <div class="mb-3">
            <label for="instphno">Institution Phone Number:</label>
            <input type="text" class="form-control" name="instphno" required>
        </div>

        <div class="mb-3">
            <label for="name">Admin Name:</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label for="adminemail">Admin Email:</label>
            <input type="email" class="form-control" name="adminemail" required>
        </div>

        <div class="mb-3">
            <label for="adminphno">Admin Phone Number:</label>
            <input type="text" class="form-control" name="adminphno" required>
        </div>

        <div class="mb-3">
            <label for="pass">Password (min 6 characters):</label>
            <input type="password" class="form-control" name="pass" minlength="6" required>
        </div>


        <div class="mb-3">
            <label for="adminimage">Admin Image:</label>
            <input type="file" class="form-control" name="adminimage" accept="image/*" required>
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Register">
        </div>
    </form>

    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>