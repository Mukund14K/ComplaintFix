<?php
session_start();
include 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // In a real system, you would check an 'is_admin' column in the users table
    $query = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['user_id'];
            $_SESSION['admin_name'] = $user['name'];
            header("Location: admin_dashboard.php");
            exit();
        } else { $error = "Invalid Administrative Credentials."; }
    } else { $error = "Unauthorized Access Denied."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ComplaintFix | Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FCF9F2; }
        .bg-walnut { background-color: #3E2723; }
        .text-gold { color: #C5A059; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded shadow-2xl overflow-hidden flex flex-col md:flex-row border border-[#C5A059]/20">
        <div class="md:w-1/2 bg-walnut p-12 text-white flex flex-col justify-between border-r-2 border-[#C5A059]">
            <div>
                <h1 class="text-4xl font-serif text-gold mb-4 uppercase tracking-tight">ComplaintFix</h1>
                <p class="text-[#F5F5F0] text-opacity-80 leading-relaxed font-light italic">Administrative Management Portal</p>
                <div class="w-12 h-1 bg-gold mt-6"></div>
            </div>
            <p class="text-[10px] uppercase tracking-widest opacity-60">Ashford University Staff Only</p>
        </div>
        <div class="md:w-1/2 p-12 bg-white">
            <h2 class="text-3xl font-serif text-[#4A0E0E] mb-2">Staff Portal</h2>
            <p class="text-[#3E2723] opacity-60 text-sm mb-8 italic">Authenticate to manage registrations</p>
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded text-xs mb-6 border border-red-100 italic"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="admin_login.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Staff Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded outline-none text-sm">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded outline-none text-sm">
                </div>
                <button type="submit" class="w-full bg-[#4A0E0E] text-[#FCF9F2] py-4 rounded font-bold uppercase tracking-widest text-xs hover:bg-opacity-90 shadow-lg transition-all">ACCESS CONTROL PANEL</button>
            </form>
            <div class="mt-10 pt-6 border-t border-gray-100 text-center">
                <a href="login.php" class="text-xs uppercase tracking-widest text-gray-400 hover:text-[#C5A059]">Return to Student Access</a>
            </div>
        </div>
    </div>
</body>
</html>