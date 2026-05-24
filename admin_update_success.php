<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$complaint_id = isset($_GET['id']) ? $_GET['id'] : 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Confirmed | Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Newsreader', serif; }
        .success-border { border-top: 4px solid #3E2723; border-bottom: 4px solid #C5A059; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 text-[#3E2723]">

    <div class="max-w-xl w-full bg-white rounded shadow-2xl overflow-hidden p-10 success-border text-center">
        
        <div class="w-16 h-16 bg-[#3E2723] rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C5A059]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>

        <h1 class="text-3xl font-serif text-[#4A0E0E] mb-2 uppercase tracking-tight">Record Updated</h1>
        <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#C5A059] mb-8">Official Administrative Entry</p>

        <div class="bg-[#FCF9F2] p-6 rounded border border-[#C5A059]/20 mb-10 text-left">
            <div class="flex justify-between items-center border-b border-[#C5A059]/10 pb-3 mb-3">
                <span class="text-[10px] uppercase font-bold opacity-50">Reference ID</span>
                <span class="text-sm font-serif font-bold text-[#4A0E0E]">#<?php echo htmlspecialchars($complaint_id); ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[10px] uppercase font-bold opacity-50">Log Status</span>
                <span class="text-[10px] font-bold py-1 px-3 bg-[#3E2723] text-white rounded-full">COMMITTED</span>
            </div>
        </div>

        <p class="text-sm italic opacity-70 mb-10">
            "The modifications to this case have been saved to the central registry and are now visible to the student."
        </p>

        <div class="flex flex-col gap-3">
            <a href="admin_dashboard.php" class="w-full py-3 bg-[#3E2723] text-[#F5F5F0] text-[10px] font-bold uppercase tracking-widest rounded hover:bg-[#4A0E0E] transition-all shadow-md">
                Return to Admin Overview
            </a>
            <a href="admin_reports.php" class="w-full py-3 border border-[#C5A059] text-[#3E2723] text-[10px] font-bold uppercase tracking-widest rounded hover:bg-[#FCF9F2] transition-all">
                View Institutional Reports
            </a>
        </div>

        <div class="mt-10 opacity-30 text-[9px] uppercase tracking-widest">
            Ashford University • Administrative Governance
        </div>
    </div>

</body>
</html>