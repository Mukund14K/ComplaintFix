<?php
// Start session and check authentication
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// 1. Fetch real-time statistics for the specific student
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved
    FROM complaints WHERE user_id = '$user_id'";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// 2. Fetch recent complaints for the activity table (including ID for linking)
$complaints_query = "SELECT complaint_id, category, description, status FROM complaints 
                    WHERE user_id = '$user_id' 
                    ORDER BY complaint_id DESC LIMIT 5";
$complaints_result = $conn->query($complaints_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComplaintFix | Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .sidebar { background-color: #3E2723; border-right: 2px solid #C5A059; }
        .nav-active { background-color: #4A0E0E; color: #C5A059; }
        .stat-card { background: white; border: 1px solid rgba(197, 160, 89, 0.3); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="flex min-h-screen">
    <aside class="w-64 sidebar text-[#F5F5F0] fixed h-full flex flex-col">
        <div class="p-8 border-b border-[#C5A059]/20">
            <h1 class="text-2xl font-serif text-[#C5A059] font-bold tracking-tight uppercase">ComplaintFix</h1>
            <p class="text-[10px] uppercase tracking-widest opacity-60 mt-1">Ashford University</p>
        </div>
        <nav class="flex-1 py-10 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded nav-active">
                <div class="w-2 h-2 rounded-full bg-[#C5A059]"></div>
                <span class="text-sm font-medium tracking-wide">Dashboard</span>
            </a>
            <a href="submit_complaint.php" class="flex items-center gap-3 px-4 py-3 rounded text-[#F5F5F0] opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Submit Complaint</span>
            </a>
            <a href="track_complaints.php" class="flex items-center gap-3 px-4 py-3 rounded text-[#F5F5F0] opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Track History</span>
            </a>
        </nav>
        
        <div class="p-8 border-t border-[#C5A059]/20">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-[#C5A059] flex items-center justify-center text-[#3E2723] font-bold">
                    <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                </div>
                <div>
                    <p class="text-[#F5F5F0] text-sm font-medium"><?php echo htmlspecialchars($user_name); ?></p>
                    <p class="text-[#F5F5F0] text-xs opacity-50 uppercase tracking-tighter">Student</p>
                </div>
            </div>
            <a href="logout.php" class="text-[10px] uppercase tracking-widest font-bold text-[#C5A059] hover:underline transition-all">Sign Out</a>
        </div>
    </aside>

    <main class="ml-64 flex-1 p-12">
        <header class="flex justify-between items-end mb-12 border-b pb-6 border-[#3E2723]/10">
            <div>
                <h2 class="text-4xl font-serif text-[#4A0E0E] leading-tight">Student Overview</h2>
                <p class="text-[#3E2723] opacity-60 text-sm italic mt-2">Welcome back to the Ashford Administrative Portal.</p>
            </div>
            <a href="submit_complaint.php" class="px-6 py-3 bg-[#4A0E0E] text-[#FCF9F2] text-xs font-bold uppercase tracking-widest rounded shadow-lg hover:bg-opacity-90 transition-all active:scale-95">
                File New Complaint
            </a>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="stat-card p-8 rounded shadow-sm">
                <p class="text-[10px] uppercase font-bold text-[#3E2723] opacity-60 tracking-widest mb-4">Total Filed</p>
                <p class="text-4xl font-serif text-[#4A0E0E]"><?php echo $stats['total']; ?></p>
                <div class="mt-4 h-1 w-full bg-[#FCF9F2]"><div class="h-1 bg-[#4A0E0E] w-full"></div></div>
            </div>
            <div class="stat-card p-8 rounded shadow-sm">
                <p class="text-[10px] uppercase font-bold text-[#3E2723] opacity-60 tracking-widest mb-4">Pending Approval</p>
                <p class="text-4xl font-serif text-[#C5A059]"><?php echo $stats['pending'] ?? 0; ?></p>
                <div class="mt-4 h-1 w-full bg-[#FCF9F2]"><div class="h-1 bg-[#C5A059] w-1/2"></div></div>
            </div>
            <div class="stat-card p-8 rounded shadow-sm">
                <p class="text-[10px] uppercase font-bold text-[#3E2723] opacity-60 tracking-widest mb-4">Resolved Cases</p>
                <p class="text-4xl font-serif text-[#3E2723]"><?php echo $stats['resolved'] ?? 0; ?></p>
                <div class="mt-4 h-1 w-full bg-[#FCF9F2]"><div class="h-1 bg-[#3E2723] w-full"></div></div>
            </div>
        </section>

        <section class="bg-white border border-[#C5A059]/30 rounded overflow-hidden shadow-xl flex flex-col">
            <div class="bg-[#4A0E0E] px-8 py-4 flex justify-between items-center">
                <h3 class="text-[#FCF9F2] font-serif italic text-lg">Detailed Registration Flow</h3>
                <span class="text-[#C5A059] text-[10px] uppercase tracking-widest">Real-time Activity</span>
            </div>
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#FCF9F2] border-b border-[#C5A059]/20">
                    <tr class="text-[10px] uppercase tracking-widest text-[#3E2723] opacity-60">
                        <th class="px-8 py-4 font-semibold">Category</th>
                        <th class="px-8 py-4 font-semibold">Description Snippet</th>
                        <th class="px-8 py-4 font-semibold text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#FCF9F2] text-sm text-[#3E2723]">
                    <?php if ($complaints_result->num_rows > 0): 
                        $index = 0;
                        while($complaint = $complaints_result->fetch_assoc()): ?>
                            <tr class="<?php echo $index % 2 !== 0 ? 'bg-[#FCF9F2]/30' : ''; ?> hover:bg-[#C5A059]/5 transition-colors">
                                <td class="px-8 py-6 font-medium"><?php echo htmlspecialchars($complaint['category']); ?></td>
                                <td class="px-8 py-6 opacity-80 truncate max-w-md">
                                    <a href="view_complaint.php?id=<?php echo $complaint['complaint_id']; ?>" class="hover:text-[#4A0E0E] hover:underline">
                                        <?php echo htmlspecialchars($complaint['description']); ?>
                                    </a>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest 
                                        <?php 
                                            if($complaint['status'] === 'Pending') echo 'bg-[#4A0E0E] text-white';
                                            elseif($complaint['status'] === 'In Progress') echo 'bg-[#C5A059] text-white';
                                            elseif($complaint['status'] === 'Resolved') echo 'border border-[#3E2723] text-[#3E2723]';
                                        ?>">
                                        <?php echo $complaint['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php $index++; endwhile; 
                    else: ?>
                        <tr>
                            <td colspan="3" class="px-8 py-16 text-center text-gray-400 italic font-serif">No recent registrations found in your account.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>