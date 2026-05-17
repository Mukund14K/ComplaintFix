<?php
// Start the session for authentication as per SDD Behavioral Design [cite: 510]
session_start();

// Redirect if already logged in to prevent double-login [cite: 511]
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'db.php'; // Data Layer connection [cite: 38, 108, 109]
$error = "";

// Handle the Login Logic [cite: 504-513]
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Search user in database [cite: 508]
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify encrypted password as per Security Design 
        if (password_verify($password, $user['password'])) {
            // Create user session [cite: 510]
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            
            // Redirect to dashboard [cite: 511, 543]
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Credentials. Please try again.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComplaintFix | Student Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .bg-burgundy { background-color: #4A0E0E; }
        .text-burgundy { color: #4A0E0E; }
        .bg-walnut { background-color: #3E2723; }
        .text-gold { color: #C5A059; }
        .border-gold { border-color: #C5A059; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded shadow-2xl overflow-hidden flex flex-col md:flex-row border border-[#C5A059]/20">
        
        <div class="md:w-1/2 bg-walnut p-12 text-white flex flex-col justify-between border-r-2 border-gold">
            <div>
                <h1 class="text-4xl font-serif text-gold mb-4 uppercase tracking-tight">ComplaintFix</h1>
                <p class="text-[#F5F5F0] text-opacity-80 leading-relaxed font-light">"Excellence in Student Welfare & Governance"</p>
                <div class="w-12 h-1 bg-gold mt-6"></div>
            </div>
            <div class="hidden md:block">
                <p class="text-[10px] uppercase tracking-widest opacity-60">Ashford University Official Portal</p>
            </div>
        </div>
        
        <div class="md:w-1/2 p-12 bg-white">
            <h2 class="text-3xl font-serif text-burgundy mb-2">Student Portal</h2>
            <p class="text-[#3E2723] opacity-60 text-sm mb-8 italic">Sign in to your academic account</p>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded text-xs mb-6 border border-red-100 italic">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
                <div class="bg-green-50 text-green-600 p-3 rounded text-[10px] mb-6 border border-green-100 uppercase tracking-widest font-bold">
                    Registration Successful. Please authenticate below.
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded focus:ring-1 focus:ring-burgundy outline-none transition-all placeholder:text-gray-300 text-sm" placeholder="student@example.com">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded focus:ring-1 focus:ring-burgundy outline-none transition-all placeholder:text-gray-300 text-sm" placeholder="••••••••">
                </div>
                
                <button type="submit" class="w-full bg-burgundy text-[#FCF9F2] py-4 rounded font-bold uppercase tracking-widest text-xs hover:bg-opacity-90 transition-all shadow-lg active:scale-95">LOG IN</button>
            </form>
            
            <div class="mt-10 pt-6 border-t border-[#3E2723] border-opacity-10 text-center">
                <p class="text-sm text-[#3E2723] opacity-70">New around here? <a href="register.php" class="text-gold font-bold hover:underline">Register Account</a></p>
                <a href="admin_login.php" class="inline-block mt-4 text-[9px] uppercase tracking-[0.3em] text-gray-400 hover:text-burgundy transition-colors">Admin Access</a>
            </div>
        </div>
    </div>
</body>
</html>