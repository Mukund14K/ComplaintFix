<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$complaint_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch specific complaint details
$query = "SELECT * FROM complaints WHERE complaint_id = '$complaint_id' AND user_id = '$user_id'";
$result = $conn->query($query);
$data = $result->fetch_assoc();

if (!$data) {
    echo "Complaint not found or unauthorized access.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details | ComplaintFix</title>
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded hover:opacity-100 opacity-70 transition-all">
                <span class="text-sm font-medium tracking-wide">Dashboard</span>
            </a>
            <a href="submit_complaint.php" class="flex items-center gap-3 px-4 py-3 rounded opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Submit Complaint</span>
            </a>
            <a href="track_complaints.php" class="flex items-center gap-3 px-4 py-3 rounded opacity-70 hover:opacity-100 transition-all">
                <span class="text-sm font-medium tracking-wide">Track History</span>
            </a>
        </nav>
    </aside>

    <main class="ml-64 flex-1 p-12">
        <header class="flex justify-between items-center mb-12 border-b pb-6 border-[#3E2723]/10">
            <div>
                <h2 class="text-4xl font-serif text-[#4A0E0E] leading-tight">Complaint Case #<?php echo $data['complaint_id']; ?></h2>
                <p class="text-[#3E2723] opacity-60 text-sm italic mt-2">Official record of administrative filing.</p>
            </div>
            <a href="dashboard.php" class="px-6 py-2 border border-[#4A0E0E] text-[#4A0E0E] text-xs font-bold uppercase tracking-widest rounded hover:bg-[#4A0E0E] hover:text-white transition-all">
                Back to Dashboard
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                
                <?php if($data['status'] == 'Resolved' && !empty($data['conclusion'])): ?>
                <div class="bg-[#4A0E0E] p-10 rounded-lg shadow-xl border-l-8 border-[#C5A059] mb-8">
                    <label class="text-[10px] uppercase tracking-widest font-bold text-[#C5A059] opacity-80">Official Institutional Conclusion</label>
                    <p class="text-xl font-serif text-[#FCF9F2] mt-4 leading-relaxed">
                        <?php echo htmlspecialchars($data['conclusion']); ?>
                    </p>
                    <div class="mt-6 flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#C5A059]"></div>
                        <p class="text-[10px] uppercase tracking-widest text-[#C5A059] font-bold">Case Officially Closed</p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white p-12 rounded shadow-xl border border-[#C5A059]/10">
                    <div class="mb-8">
                        <label class="text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60">Category</label>
                        <p class="text-xl font-medium text-[#3E2723] mt-1"><?php echo htmlspecialchars($data['category']); ?></p>
                    </div>

                    <div class="mb-8">
                        <label class="text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60">Detailed Statement</label>
                        <p class="text-md text-[#3E2723] leading-relaxed mt-4 whitespace-pre-wrap"><?php echo htmlspecialchars($data['description']); ?></p>
                    </div>

                    <div class="mt-12 pt-10 border-t border-gray-100">
                        <h3 class="text-lg font-serif text-[#4A0E0E] mb-8 italic">Case Progress Timeline</h3>
                        <div class="space-y-8 relative">
                            <div class="absolute left-[5px] top-2 bottom-2 w-px bg-[#C5A059]/20"></div>
                            
                            <?php
                            $updates_query = "SELECT * FROM complaint_updates WHERE complaint_id = '$complaint_id' ORDER BY created_at DESC";
                            $updates_result = $conn->query($updates_query);
                            
                            if ($updates_result && $updates_result->num_rows > 0):
                                while($update = $updates_result->fetch_assoc()): ?>
                                    <div class="relative pl-8">
                                        <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-[#C5A059] border-2 border-white shadow-sm"></div>
                                        <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400">
                                            <?php echo date("M d, Y | H:i", strtotime($update['created_at'])); ?>
                                        </p>
                                        <p class="text-sm text-[#3E2723] mt-2 leading-relaxed">
                                            <?php echo htmlspecialchars($update['update_text']); ?>
                                        </p>
                                    </div>
                                <?php endwhile;
                            else: ?>
                                <p class="text-sm text-gray-400 italic pl-2">Pending initial administrative review...</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="pt-8 mt-10 border-t border-gray-100">
                        <label class="text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60">Submitted On</label>
                        <p class="text-sm text-[#3E2723] mt-1"><?php echo date("F j, Y", strtotime($data['created_at'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-4">Current Status</label>
                    <div class="inline-block px-4 py-2 rounded bg-[#4A0E0E] text-[#FCF9F2] text-xs font-bold uppercase tracking-widest">
                        <?php echo $data['status']; ?>
                    </div>
                </div>

                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Priority Level</label>
                    <p class="text-[#C5A059] font-bold uppercase text-xs tracking-widest"><?php echo $data['priority']; ?> Priority</p>
                </div>

                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-2">Incident Location</label>
                    <p class="text-sm text-[#3E2723]"><?php echo !empty($data['location']) ? htmlspecialchars($data['location']) : 'Not Specified'; ?></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>