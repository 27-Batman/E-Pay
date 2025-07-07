<?php
// Include the connection file at the top
include 'db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variables to hold error messages for each field
$fullname_error = "";
$email_error = "";
$contact_error = "";
$userid_error = "";
$password_error = "";
$gender_error = "";
$general_error = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $counter = trim($_POST['counter']);
    $userid = trim($_POST['userid']);
    $snno = trim($_POST['snno']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $gender = trim($_POST['gender']);

    // Validation for each field
    $is_valid = true;

    if (empty($fullname)) {
        $fullname_error = "Full name is required.";
        $is_valid = false;
    }
    elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $fullname)) {
        $fullname_error = "Name must contain only letters and spaces (2-50 characters).";
        $is_valid = false;
    }

    if (empty($email)) {
        $email_error = "Email is required.";
        $is_valid = false;
    }
    elseif(!preg_match("/^[a-z][a-z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-z]{2,}$/", $email)) {
            $email_errors = "Invalid email format. Email must start with a letter.";
            $is_valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
        $is_valid = false;
    }

    if (empty($contact)) {
        $contact_error = "Contact number is required.";
        $is_valid = false;
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $contact_error = "Invalid contact number. It must be 10 digits.";
        $is_valid = false;
    }

    if (empty($userid)||(!ctype_digit($userid) || (int)$userid < 1)){
        $userid_error = "User ID is required.";
    if (!ctype_digit($userid) || (int)$userid < 1){
        $userid_error = "User ID must be a positive integer.";
        $is_valid = false;
    }
        $is_valid = false;
    }

    if (empty($password)) {
        $password_error = "Password is required.";
        $is_valid = false;
    }
    elseif (!preg_match('/[!@#$%^&*()\-=+_\[\]{}|;:\'",.<>?\/\\\\]/', $password)) {
        $password_error = "Password must contain at least one special character, including \\.";
        $is_valid = false;
    }
    } elseif (strlen($password) < 6) {
        $password_error = "Password must be at least 6 characters long.";
        $is_valid = false;
    } elseif ($password !== $confirm_password){
        $password_error = "Passwords do not match.";
        $is_valid = false;
    }

    if (empty($gender)) {
        $gender_error = "Gender is required.";
        $is_valid = false;
    }

    // If all fields are valid, proceed with the database checks
    if ($is_valid) {
        // Check for duplicate contact, email, or UserID
        $check_query = "SELECT * FROM users WHERE contact = ? OR email = ? OR userid = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("sss", $contact, $email, $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $general_error = "The contact number, email, or UserID is already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user data
            $insert_query = "INSERT INTO users (fullname, email, contact, counter, userid, snno, password, gender) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param(
                "ssssssss",
                $fullname,
                $email,
                $contact,
                $counter,
                $userid,
                $snno,
                $hashed_password,
                $gender
            );

            if ($stmt->execute()) {
                $success_message = "Registration successful! Redirecting to login page...";
                header("Refresh: 2; url=../Login/login.php");
                exit();
            } else {
                $general_error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O-EBS | Registration</title>
    <link rel="icon" type="image/png" href="../Icon.png">
    <link rel="stylesheet" href="register.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            font-size: 1em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Welcome to<br><span>E-Pay</span></h1>
            <p>Pay Smart, Pay Easy</p>
            <div class="Login-prompt">
                <p>Already have an account ?</p>
                <a href="../Login/login.php" class="cta-button">Login</a>
            </div>
        </div>
        <div class="white-shape"></div>
        <div class="form-container">
            <h2>Register Your Account</h2>
              <!-- Display general error or success message -->
              <?php if (!empty($general_error)): ?>
                <div class="alert error">
                    <p class="error-message"><?php echo htmlspecialchars($general_error); ?></p>
                </div>
            <?php elseif (!empty($success_message)): ?>
                <div class="alert success">
                    <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>
            <form method="POST" action="register.php">
                <!-- Full Name -->
                <div class="form-group">
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname ?? ''); ?>" placeholder="Full Name *" required>
                    <?php if (!empty($fullname_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($fullname_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="email">
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="E-mail *" required>
                    <?php if (!empty($email_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($email_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Contact Number -->
                <div class="contact">
                    <input type="tel" name="contact" value="<?php echo htmlspecialchars($contact ?? ''); ?>" placeholder="Mobile No. *" required>
                    <?php if (!empty($contact_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($contact_error); ?></p>
                    <?php endif; ?>
                </div>

                <div class="search">
                    <select name="counter" id="select-state" required>
                    <option value="" disabled selected>---- NEA Counter ----</option>
                        <option value="243" <?php echo (isset($counter) && $counter == "243") ? 'selected' : ''; ?>>AANBU</option>
                        <option value="391" <?php echo (isset($counter) && $counter == "391") ? 'selected' : ''; ?>>ACHHAM</option>
                        <option value="273" <?php echo (isset($counter) && $counter == "273") ? 'selected' : ''; ?>>AMUWA</option>
                        <option value="268" <?php echo (isset($counter) && $counter == "268") ? 'selected' : ''; ?>>ANARMANI</option>
                        <option value="248" <?php echo (isset($counter) && $counter == "248") ? 'selected' : ''; ?>>ARGHAKHACHI</option>
                        <option value="384" <?php echo (isset($counter) && $counter == "384") ? 'selected' : ''; ?>>ARUGHAT</option>
                        <option value="345" <?php echo (isset($counter) && $counter == "345") ? 'selected' : ''; ?>>ATTARIYA</option>
                        <option value="237" <?php echo (isset($counter) && $counter == "237") ? 'selected' : ''; ?>>BADEGAUN SDC</option>
                        <option value="299" <?php echo (isset($counter) && $counter == "299") ? 'selected' : ''; ?>>BAGLUNG</option>
                        <option value="381" <?php echo (isset($counter) && $counter == "381") ? 'selected' : ''; ?>>BAITADI</option>
                        <option value="253" <?php echo (isset($counter) && $counter == "253") ? 'selected' : ''; ?>>BAJHANG</option>
                        <option value="254" <?php echo (isset($counter) && $counter == "254") ? 'selected' : ''; ?>>BAJURA</option>
                        <option value="215" <?php echo (isset($counter) && $counter == "215") ? 'selected' : ''; ?>>BALAJU</option>
                        <option value="219" <?php echo (isset($counter) && $counter == "219") ? 'selected' : ''; ?>>BANESHWOR</option>
                        <option value="373" <?php echo (isset($counter) && $counter == "373") ? 'selected' : ''; ?>>BANSGADHI</option>
                        <option value="399" <?php echo (isset($counter) && $counter == "399") ? 'selected' : ''; ?>>BARAHATHAWA</option>
                        <option value="267" <?php echo (isset($counter) && $counter == "267") ? 'selected' : ''; ?>>BARDAGHAT SDC</option>
                        <option value="378" <?php echo (isset($counter) && $counter == "378") ? 'selected' : ''; ?>>BARDIBAS</option>
                        <option value="277" <?php echo (isset($counter) && $counter == "277") ? 'selected' : ''; ?>>BARHABISE</option>
                        <option value="348" <?php echo (isset($counter) && $counter == "348") ? 'selected' : ''; ?>>BELAURI</option>
                        <option value="317" <?php echo (isset($counter) && $counter == "317") ? 'selected' : ''; ?>>BELBARI</option>
                        <option value="339" <?php echo (isset($counter) && $counter == "339") ? 'selected' : ''; ?>>BELTAR</option>
                        <option value="265" <?php echo (isset($counter) && $counter == "265") ? 'selected' : ''; ?>>BHADRAPUR</option>
                        <option value="272" <?php echo (isset($counter) && $counter == "272") ? 'selected' : ''; ?>>BHAIRAHAWA</option>
                        <option value="370" <?php echo (isset($counter) && $counter == "370") ? 'selected' : ''; ?>>BHAJANI</option>
                        <option value="245" <?php echo (isset($counter) && $counter == "245") ? 'selected' : ''; ?>>BHAKTAPUR DC</option>
                        <option value="211" <?php echo (isset($counter) && $counter == "211") ? 'selected' : ''; ?>>BHARATPUR DC</option>
                        <option value="270" <?php echo (isset($counter) && $counter == "270") ? 'selected' : ''; ?>>BHIMAN</option>
                        <option value="316" <?php echo (isset($counter) && $counter == "316") ? 'selected' : ''; ?>>BHOJPUR</option>
                        <option value="285" <?php echo (isset($counter) && $counter == "285") ? 'selected' : ''; ?>>BIRATNAGAR</option>
                        <option value="286" <?php echo (isset($counter) && $counter == "286") ? 'selected' : ''; ?>>BIRGUNJ DC</option>
                        <option value="301" <?php echo (isset($counter) && $counter == "301") ? 'selected' : ''; ?>>BODEBARSAIEN</option>
                        <option value="333" <?php echo (isset($counter) && $counter == "333") ? 'selected' : ''; ?>>BUDHABARE SDC</option>
                        <option value="223" <?php echo (isset($counter) && $counter == "223") ? 'selected' : ''; ?>>BUDHANILKANTHA</option>
                        <option value="229" <?php echo (isset($counter) && $counter == "229") ? 'selected' : ''; ?>>BUTWAL</option>
                        <option value="220" <?php echo (isset($counter) && $counter == "220") ? 'selected' : ''; ?>>CHABAHIL</option>
                        <option value="315" <?php echo (isset($counter) && $counter == "315") ? 'selected' : ''; ?>>CHAINPUR</option>
                        <option value="294" <?php echo (isset($counter) && $counter == "294") ? 'selected' : ''; ?>>CHANAULI</option>
                        <option value="356" <?php echo (isset($counter) && $counter == "356") ? 'selected' : ''; ?>>CHANDRANIGAPUR</option>
                        <option value="217" <?php echo (isset($counter) && $counter == "217") ? 'selected' : ''; ?>>CHAPAGAUN SDC</option>
                        <option value="326" <?php echo (isset($counter) && $counter == "326") ? 'selected' : ''; ?>>CHAUTARA</option>
                        <option value="385" <?php echo (isset($counter) && $counter == "385") ? 'selected' : ''; ?>>DADELDHURA</option>
                        <option value="350" <?php echo (isset($counter) && $counter == "350") ? 'selected' : ''; ?>>DAILEKH</option>
                        <option value="280" <?php echo (isset($counter) && $counter == "280") ? 'selected' : ''; ?>>DAMAK</option>
                        <option value="241" <?php echo (isset($counter) && $counter == "241") ? 'selected' : ''; ?>>DAMAULI</option>
                        <option value="383" <?php echo (isset($counter) && $counter == "383") ? 'selected' : ''; ?>>DARCHULA</option>
                        <option value="224" <?php echo (isset($counter) && $counter == "224") ? 'selected' : ''; ?>>DHADING</option>
                        <option value="344" <?php echo (isset($counter) && $counter == "344") ? 'selected' : ''; ?>>DHANGADI</option>
                        <option value="284" <?php echo (isset($counter) && $counter == "284") ? 'selected' : ''; ?>>DHANKUTA</option>
                        <option value="302" <?php echo (isset($counter) && $counter == "302") ? 'selected' : ''; ?>>DHANUSHADHAM</option>
                        <option value="212" <?php echo (isset($counter) && $counter == "212") ? 'selected' : ''; ?>>DHARAN</option>
                        <option value="269" <?php echo (isset($counter) && $counter == "269") ? 'selected' : ''; ?>>DHARKE</option>
                        <option value="320" <?php echo (isset($counter) && $counter == "320") ? 'selected' : ''; ?>>DHULABARI SDC</option>
                        <option value="375" <?php echo (isset($counter) && $counter == "375") ? 'selected' : ''; ?>>DHUNCHE DC</option>
                        <option value="397" <?php echo (isset($counter) && $counter == "397") ? 'selected' : ''; ?>>DIKTEL</option>
                        <!-- If you need more option or location then add here -->

                    </select>
                </div>
                 <!-- Customer ID -->
                 <div class="sn">
                    <input type="text" name="userid" value="<?php echo htmlspecialchars($userid ?? ''); ?>" placeholder="Customer ID *" required>
                    <?php if (!empty($userid_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($userid_error); ?></p>
                    <?php endif; ?>
                </div>
                <!-- SC Number -->
                <div class="sc_number">
                    <input type="text" name="snno" value="<?php echo htmlspecialchars($snno ?? ''); ?>" placeholder="SC No. *" required>
                </div>
                <!-- For the password -->
                <div class="passw">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <span class="toggle-password" onclick="togglePassword()">🙈</span>
                    <?php if (!empty($password_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($password_error); ?></p>
                    <?php endif; ?>
                </div>
                 <!-- Confirm Password -->
                <div class="confirm">
                    <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required>
                    <span class="toggle-password" onclick="toggleConfirmPassword()">🙈</span>
                </div>
                <!-- Gender -->
                <div class="gender-group">
                    <label>
                        <input type="radio" name="gender" value="Male" <?php echo (isset($gender) && $gender == "Male") ? 'checked' : ''; ?> required> Male
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Female" <?php echo (isset($gender) && $gender == "Female") ? 'checked' : ''; ?> required> Female
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Other" <?php echo (isset($gender) && $gender == "Other") ? 'checked' : ''; ?> required> Other
                    </label>
                    <?php if (!empty($gender_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($gender_error); ?></p>
                    <?php endif; ?>
                </div>

                  <!-- Submit Button -->
                <div class="submit">
                    <button type="submit"  name="register_btn" class="cta-button2">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="register.js"></script>
</body>
</html>
