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

// Fetch all complaints for the logged-in student
$query = "SELECT * FROM complaints WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint History | ComplaintFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .sidebar { background-color: #3E2723; border-right: 2px solid #C5A059; }
        .nav-active { background-color: #4A0E0E; color: #C5A059; }
    </style>
</head>
<body class="flex min-h-screen">
    <aside class="w-64 sidebar text-[#F5F5F0] fixed h-full flex flex-col">
        <div class="p-8 border-b border-[#C5A059]/20">
            <h1 class="text-2xl font-serif text-[#C5A059] font-bold tracking-tight uppercase">ComplaintFix</h1>
            <p class="text-[10px] uppercase tracking-widest opacity-60 mt-1">Ashford University</p>
        </div>
        <nav class="flex-1 py-10 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded text-[#F5F5F0] opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Dashboard</span>
            </a>
            <a href="submit_complaint.php" class="flex items-center gap-3 px-4 py-3 rounded text-[#F5F5F0] opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Submit Complaint</span>
            </a>
            <a href="track_complaints.php" class="flex items-center gap-3 px-4 py-3 rounded nav-active">
                <div class="w-2 h-2 rounded-full bg-[#C5A059]"></div>
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
        <header class="mb-12 border-b pb-6 border-[#3E2723]/10 flex justify-between items-end">
            <div>
                <h2 class="text-4xl font-serif text-[#4A0E0E] leading-tight">Track History</h2>
                <p class="text-[#3E2723] opacity-60 text-sm italic mt-2">Comprehensive log of all administrative registrations.</p>
            </div>
            <p class="text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-40">
                Total Records: <?php echo $result->num_rows; ?>
            </p>
        </header>

        <section class="space-y-6">
            <?php if ($result->num_rows > 0): ?>
                <?php while($complaint = $result->fetch_assoc()): ?>
                    <div class="bg-white border border-[#C5A059]/20 rounded-lg p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 hover:shadow-md transition-shadow">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#C5A059]">
                                    #<?php echo $complaint['complaint_id']; ?>
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-[0.1em] text-gray-300">|</span>
                                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#3E2723] opacity-40">
                                    <?php echo date("M d, Y", strtotime($complaint['created_at'])); ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-serif text-[#4A0E0E] mb-1">
                                <?php echo htmlspecialchars($complaint['category']); ?>
                            </h3>
                            <p class="text-sm text-[#3E2723] opacity-70 line-clamp-1 italic mb-3">
                                "<?php echo htmlspecialchars(substr($complaint['description'], 0, 100)); ?>..."
                            </p>
                            <div class="flex items-center gap-4">
                                <span class="text-[9px] uppercase tracking-widest px-2 py-1 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-[#3E2723]">
                                    Priority: <?php echo $complaint['priority']; ?>
                                </span>
                                <?php if(!empty($complaint['location'])): ?>
                                <span class="text-[9px] uppercase tracking-widest px-2 py-1 bg-gray-50 border border-gray-100 rounded text-gray-400">
                                    Loc: <?php echo htmlspecialchars($complaint['location']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-4">
                            <span class="px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest 
                                <?php 
                                    if($complaint['status'] === 'Pending') echo 'bg-[#4A0E0E] text-white';
                                    elseif($complaint['status'] === 'In Progress') echo 'bg-[#C5A059] text-white';
                                    elseif($complaint['status'] === 'Resolved') echo 'border-2 border-[#3E2723] text-[#3E2723]';
                                ?>">
                                <?php echo $complaint['status']; ?>
                            </span>
                            <a href="view_complaint.php?id=<?php echo $complaint['complaint_id']; ?>" class="text-[10px] uppercase font-bold tracking-widest text-[#C5A059] hover:underline">
                                View Full Details →
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-white border border-dashed border-[#C5A059]/30 rounded-lg p-20 text-center">
                    <p class="text-[#3E2723] opacity-40 italic font-serif text-lg">No history found. Your filed registrations will appear here.</p>
                    <a href="submit_complaint.php" class="inline-block mt-6 text-xs font-bold uppercase tracking-widest text-[#4A0E0E] hover:underline">File your first complaint</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>