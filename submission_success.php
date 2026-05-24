<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Confirmed | ComplaintFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Newsreader', serif; }
        .ashford-burgundy { color: #4A0E0E; }
        .ashford-gold { color: #C5A059; }
        .success-card { border: 1px solid rgba(197, 160, 89, 0.3); background-color: white; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="max-w-2xl w-full success-card p-12 rounded-lg shadow-2xl text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-[#4A0E0E]"></div>
        <div class="absolute top-2 left-0 w-full h-1 bg-[#C5A059]"></div>

        <div class="w-20 h-20 bg-[#FCF9F2] border-2 border-[#C5A059] rounded-full flex items-center justify-center mx-auto mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#4A0E0E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-4xl font-serif ashford-burgundy mb-4">Submission Confirmed</h1>
        <p class="text-[#3E2723] opacity-70 mb-10 max-w-md mx-auto">
            Your grievance has been successfully logged into the Ashford University Administrative System. A representative will review the details shortly.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
            <div class="p-6 bg-[#FCF9F2] rounded border border-[#C5A059]/10">
                <p class="text-[10px] uppercase tracking-widest font-bold opacity-40 mb-1">Status Assigned</p>
                <p class="text-sm font-bold ashford-burgundy uppercase tracking-wider">Pending Review</p>
            </div>
            <div class="p-6 bg-[#FCF9F2] rounded border border-[#C5A059]/10">
                <p class="text-[10px] uppercase tracking-widest font-bold opacity-40 mb-1">Filing Date</p>
                <p class="text-sm font-bold ashford-burgundy uppercase tracking-wider"><?php echo date('d M, Y'); ?></p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="dashboard.php" class="w-full sm:w-auto px-8 py-3 bg-[#4A0E0E] text-[#FCF9F2] text-[10px] font-bold uppercase tracking-widest rounded shadow-lg hover:bg-opacity-95 transition-all">
                Return to Dashboard
            </a>
            <a href="track_complaints.php" class="w-full sm:w-auto px-8 py-3 border border-[#C5A059] text-[#3E2723] text-[10px] font-bold uppercase tracking-widest rounded hover:bg-[#FCF9F2] transition-all">
                Track History
            </a>
        </div>

        <footer class="mt-12 pt-8 border-t border-gray-100">
            <p class="text-[9px] uppercase tracking-[0.3em] text-gray-400">Institutional Grievance Protocol • Ashford University</p>
        </footer>
    </div>

</body>
</html>