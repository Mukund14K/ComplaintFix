<?php
// Start session
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'db.php'; // Data Layer connection
$error = "";

// Handle Registration Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 1. Check if email already exists
    $check_email = "SELECT email FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        $error = "This email is already registered with an account.";
    } else {
        // 2. Hash the password for security as per SDD Security Design
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insert new user into database
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to login with success message
            header("Location: login.php?registered=true");
            exit();
        } else {
            $error = "Registration failed. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComplaintFix | Register Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .bg-burgundy { background-color: #4A0E0E; }
        .text-burgundy { color: #4A0E0E; }
        .bg-walnut { background-color: #3E2723; }
        .text-gold { color: #C5A059; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded shadow-2xl p-10 border border-[#C5A059]/20">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-serif text-[#4A0E0E] mb-2 uppercase tracking-tight">Join ComplaintFix</h1>
            <p class="text-[#3E2723] opacity-60 text-sm italic">Create your official student portal account</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded text-xs mb-6 border border-red-100 italic">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-6">
            <div>
                <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded focus:ring-1 focus:ring-burgundy outline-none transition-all placeholder:text-gray-300 text-sm" placeholder="Johnathan Doe">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded focus:ring-1 focus:ring-burgundy outline-none transition-all placeholder:text-gray-300 text-sm" placeholder="student@university.edu">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Secure Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded focus:ring-1 focus:ring-burgundy outline-none transition-all placeholder:text-gray-300 text-sm" placeholder="••••••••">
            </div>
            
            <button type="submit" class="w-full bg-[#4A0E0E] text-[#FCF9F2] py-4 rounded font-bold uppercase tracking-widest text-xs hover:bg-opacity-95 transition-all shadow-lg active:scale-95">CREATE ACCOUNT</button>
        </form>
        
        <div class="mt-10 pt-6 border-t border-[#3E2723] border-opacity-10 text-center">
            <p class="text-sm text-[#3E2723] opacity-70">Already registered? <a href="login.php" class="text-[#C5A059] font-bold hover:underline">Log In</a></p>
            <p class="text-sm text-[#3E2723] opacity-70">Have an admin account? <a href="admin_login.php" class="text-gold font-bold hover:underline">Admin Login</a></p>
        </div>
    </div>
</body>
</html>