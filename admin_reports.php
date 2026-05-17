<?php
// Start session and check administrative privileges
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Capture Filter Inputs from the GET request
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$priority = isset($_GET['priority']) ? mysqli_real_escape_string($conn, $_GET['priority']) : '';

// Construct Dynamic SQL Query with JOIN
$query = "SELECT c.*, u.name as student_name FROM complaints c 
          JOIN users u ON c.user_id = u.user_id WHERE 1=1";

if ($category) { $query .= " AND c.category = '$category'"; }
if ($status) { $query .= " AND c.status = '$status'"; }
if ($priority) { $query .= " AND c.priority = '$priority'"; }

$query .= " ORDER BY c.created_at DESC";
$report_result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institutional Reports | ComplaintFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .sidebar { background-color: #3E2723; border-right: 2px solid #C5A059; }
        .nav-active { background-color: #4A0E0E; color: #C5A059; }
        
        /* Professional Print Formatting */
        @media print {
            .sidebar, .filter-section, .print-btn { display: none !important; }
            main { margin-left: 0 !important; padding: 0 !important; }
            .report-card { border: none !important; shadow: none !important; }
            .print-header { display: block !important; }
            .action-link { display: none !important; }
        }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-64 sidebar text-[#F5F5F0] fixed h-full flex flex-col print:hidden">
        <div class="p-8 border-b border-[#C5A059]/20">
            <h1 class="text-2xl font-serif text-[#C5A059] font-bold uppercase tracking-tight">ComplaintFix</h1>
            <p class="text-[10px] uppercase tracking-widest opacity-60 mt-1">Admin Analytics</p>
        </div>
        <nav class="flex-1 py-10 px-4 space-y-2">
            <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded text-[#F5F5F0] opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Overview</span>
            </a>
            <a href="admin_reports.php" class="flex items-center gap-3 px-4 py-3 rounded nav-active">
                <div class="w-2 h-2 rounded-full bg-[#C5A059]"></div>
                <span class="text-sm font-medium tracking-wide">Generate Reports</span>
            </a>
        </nav>
        <div class="p-8 border-t border-[#C5A059]/20">
            <a href="logout.php" class="block w-full text-center py-3 border border-[#C5A059] text-[#C5A059] text-[10px] font-bold uppercase tracking-widest hover:bg-[#C5A059] hover:text-[#3E2723] transition-all rounded">
                Sign Out Admin
            </a>
        </div>
    </aside>

    <main class="ml-64 flex-1 p-12">
        <div class="hidden print-header mb-10 text-center">
            <h1 class="text-3xl font-serif text-black uppercase">Ashford University Official Records</h1>
            <p class="text-sm italic">Grievance & Registration Audit Report - <?php echo date('F d, Y'); ?></p>
            <hr class="mt-4 border-black">
        </div>

        <header class="flex justify-between items-end mb-12 border-b pb-6 border-[#3E2723]/10 print:hidden">
            <div>
                <h2 class="text-4xl font-serif text-[#4A0E0E] leading-tight">Institutional Reports</h2>
                <p class="text-[#3E2723] opacity-60 text-sm italic mt-2">Analytical insights into campus registrations and student welfare.</p>
            </div>
            <button onclick="window.print()" class="print-btn px-6 py-3 bg-[#C5A059] text-[#3E2723] text-[10px] font-bold uppercase tracking-widest rounded shadow-lg hover:bg-opacity-90 transition-all">
                Export to PDF / Print
            </button>
        </header>

        <section class="filter-section bg-white p-8 rounded shadow-sm border border-[#C5A059]/10 mb-8 print:hidden">
            <form action="admin_reports.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-2 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-sm outline-none">
                        <option value="">All Categories</option>
                        <option value="Academic Affairs" <?php if($category == 'Academic Affairs') echo 'selected'; ?>>Academic Affairs</option>
                        <option value="Hostel & Infrastructure" <?php if($category == 'Hostel & Infrastructure') echo 'selected'; ?>>Hostel & Infrastructure</option>
                        <option value="Administrative Services" <?php if($category == 'Administrative Services') echo 'selected'; ?>>Administrative Services</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-sm outline-none">
                        <option value="">All Statuses</option>
                        <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="In Progress" <?php if($status == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Resolved" <?php if($status == 'Resolved') echo 'selected'; ?>>Resolved</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Priority</label>
                    <select name="priority" class="w-full px-4 py-2 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-sm outline-none">
                        <option value="">All Priorities</option>
                        <option value="Low" <?php if($priority == 'Low') echo 'selected'; ?>>Low</option>
                        <option value="Medium" <?php if($priority == 'Medium') echo 'selected'; ?>>Medium</option>
                        <option value="High" <?php if($priority == 'High') echo 'selected'; ?>>High</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#4A0E0E] text-[#FCF9F2] py-2 rounded text-[10px] font-bold uppercase tracking-widest hover:bg-opacity-95 transition-all">Filter</button>
                    <a href="admin_reports.php" class="flex-1 text-center border border-gray-200 text-gray-400 py-2 rounded text-[10px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-all">Reset</a>
                </div>
            </form>
        </section>

        <section class="report-card bg-white border border-[#C5A059]/30 rounded overflow-hidden shadow-xl">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#FCF9F2] border-b border-[#C5A059]/20">
                    <tr class="text-[10px] uppercase tracking-widest text-[#3E2723] opacity-60">
                        <th class="px-8 py-4 font-semibold">Ref ID</th>
                        <th class="px-8 py-4 font-semibold">Student Name</th>
                        <th class="px-8 py-4 font-semibold">Category</th>
                        <th class="px-8 py-4 font-semibold text-right">Status & Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#FCF9F2] text-sm text-[#3E2723]">
                    <?php if ($report_result->num_rows > 0): 
                        while($row = $report_result->fetch_assoc()): ?>
                            <tr class="hover:bg-[#C5A059]/5 transition-colors">
                                <td class="px-8 py-6 font-bold text-[#C5A059]">#<?php echo $row['complaint_id']; ?></td>
                                <td class="px-8 py-6"><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td class="px-8 py-6 text-xs italic">
                                    <span class="block"><?php echo $row['category']; ?></span>
                                    <span class="text-[9px] uppercase tracking-widest opacity-50"><?php echo $row['priority']; ?> Priority</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-6">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest 
                                            <?php 
                                                if($row['status'] === 'Pending') echo 'bg-[#4A0E0E] text-white';
                                                elseif($row['status'] === 'In Progress') echo 'bg-[#C5A059] text-white';
                                                elseif($row['status'] === 'Resolved') echo 'border border-[#3E2723] text-[#3E2723]';
                                            ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                        <a href="admin_view_complaint.php?id=<?php echo $row['complaint_id']; ?>" class="action-link text-[10px] font-bold uppercase tracking-widest text-[#C5A059] hover:underline">
                                            Review Case
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; 
                    else: ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-300 italic font-serif">No records found matching these institutional filters.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        
        <footer class="mt-8 text-center hidden print:block">
            <p class="text-[10px] uppercase tracking-[0.4em] text-gray-400">Generated via ComplaintFix Management System</p>
        </footer>
    </main>
</body>
</html>