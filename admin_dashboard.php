<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit(); }
include 'db.php';

// Global Stats for all students
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved
    FROM complaints";
$stats = $conn->query($stats_query)->fetch_assoc();

// Fetch ALL complaints with User Names
$all_query = "SELECT c.*, u.name as student_name FROM complaints c 
              JOIN users u ON c.user_id = u.user_id 
              ORDER BY c.complaint_id DESC";
$complaints = $conn->query($all_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | ComplaintFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .sidebar { background-color: #3E2723; border-right: 2px solid #C5A059; }
        .nav-active { background-color: #4A0E0E; color: #C5A059; }
    </style>
</head>
<body class="flex min-h-screen">
    <aside class="w-64 sidebar text-[#F5F5F0] fixed h-full flex flex-col">
        <div class="p-8 border-b border-[#C5A059]/20">
            <h1 class="text-2xl font-serif text-[#C5A059] font-bold tracking-tight uppercase">ComplaintFix</h1>
            <p class="text-[10px] uppercase tracking-widest opacity-60 mt-1">Administrator Portal</p>
        </div>
        <nav class="flex-1 py-10 px-4 space-y-2">
            <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded nav-active">
                <div class="w-2 h-2 rounded-full bg-[#C5A059]"></div>
                <span class="text-sm font-medium tracking-wide">Overview</span>
            </a>
            <a href="admin_reports.php" class="flex items-center gap-3 px-4 py-3 rounded opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Generate Reports</span>
            </a>
        </nav>
        <div class="p-8 border-t border-[#C5A059]/20 text-center">
            <a href="logout.php" class="text-[10px] uppercase tracking-widest font-bold text-[#C5A059] hover:underline">Sign Out Admin</a>
        </div>
    </aside>

    <main class="ml-64 flex-1 p-12">
        <header class="mb-12 border-b pb-6 border-[#3E2723]/10 flex justify-between items-end">
            <div>
                <h2 class="text-4xl font-serif text-[#4A0E0E] leading-tight">University Overview</h2>
                <p class="text-[#3E2723] opacity-60 text-sm italic mt-2">Managing total institutional registrations.</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-[#3E2723] uppercase tracking-widest"><?php echo $_SESSION['admin_name']; ?></p>
                <p class="text-[10px] text-[#C5A059] uppercase tracking-tighter italic">Administrative Lead</p>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-8 rounded shadow-sm border border-[#C5A059]/30">
                <p class="text-[10px] uppercase font-bold opacity-60 tracking-widest mb-4">Total University Complaints</p>
                <p class="text-4xl font-serif text-[#4A0E0E]"><?php echo $stats['total']; ?></p>
            </div>
            <div class="bg-white p-8 rounded shadow-sm border border-[#C5A059]/30">
                <p class="text-[10px] uppercase font-bold opacity-60 tracking-widest mb-4">Pending Action</p>
                <p class="text-4xl font-serif text-[#C5A059]"><?php echo $stats['pending'] ?? 0; ?></p>
            </div>
            <div class="bg-white p-8 rounded shadow-sm border border-[#C5A059]/30">
                <p class="text-[10px] uppercase font-bold opacity-60 tracking-widest mb-4">Successfully Resolved</p>
                <p class="text-4xl font-serif text-[#3E2723]"><?php echo $stats['resolved'] ?? 0; ?></p>
            </div>
        </section>

        <section class="bg-white border border-[#C5A059]/30 rounded overflow-hidden shadow-xl">
            <div class="bg-[#4A0E0E] px-8 py-4"><h3 class="text-[#FCF9F2] font-serif italic text-lg">Student Registration Flow</h3></div>
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#FCF9F2] border-b border-[#C5A059]/20 text-[10px] uppercase tracking-widest opacity-60">
                    <tr>
                        <th class="px-8 py-4">Student</th>
                        <th class="px-8 py-4">Category</th>
                        <th class="px-8 py-4">Priority</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#3E2723]">
                    <?php while($row = $complaints->fetch_assoc()): ?>
                        <tr class="border-b border-[#FCF9F2] hover:bg-[#FCF9F2]/50 transition-colors">
                            <td class="px-8 py-6 font-bold"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td class="px-8 py-6"><?php echo $row['category']; ?></td>
                            <td class="px-8 py-6">
                                <span class="text-[9px] font-bold px-2 py-1 rounded border <?php echo ($row['priority'] == 'High' ? 'border-red-600 text-red-600' : 'border-[#C5A059] text-[#C5A059]'); ?>">
                                    <?php echo strtoupper($row['priority']); ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="admin_view_complaint.php?id=<?php echo $row['complaint_id']; ?>" class="bg-[#4A0E0E] text-[#FCF9F2] text-[10px] font-bold px-4 py-2 rounded uppercase tracking-widest hover:opacity-90">Review Case</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>