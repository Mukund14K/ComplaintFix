<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit(); }
include 'db.php';

$complaint_id = mysqli_real_escape_string($conn, $_GET['id']);

// Logic 1: Handle Status Update & Final Conclusion Logic
if (isset($_POST['update_status'])) {
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    // Capture conclusion only if status is resolved
    $conclusion = isset($_POST['conclusion']) ? mysqli_real_escape_string($conn, $_POST['conclusion']) : '';
    
    $update_sql = "UPDATE complaints SET status = '$new_status', conclusion = '$conclusion' WHERE complaint_id = '$complaint_id'";
    if ($conn->query($update_sql)) {
        header("Location: admin_update_success.php?id=" . $complaint_id);
        exit();
    }
}

// Logic 2: Handle Posting a New Progress Update
if (isset($_POST['post_update'])) {
    $update_text = mysqli_real_escape_string($conn, $_POST['update_text']);
    $log_sql = "INSERT INTO complaint_updates (complaint_id, update_text) VALUES ('$complaint_id', '$update_text')";
    $conn->query($log_sql);
}

// Fetch Complaint with Student Name, Conclusion, and Attachment fields
$query = "SELECT c.*, u.name as student_name, u.email as student_email FROM complaints c 
          JOIN users u ON c.user_id = u.user_id 
          WHERE c.complaint_id = '$complaint_id'";
$result = $conn->query($query);
$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Case #<?php echo $complaint_id; ?> | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .sidebar { background-color: #3E2723; border-right: 2px solid #C5A059; }

        @media print {
            .sidebar, .action-sidebar, .back-btn, .print-btn, .update-form-area { display: none !important; }
            main { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
            .bg-white { border: 1px solid #eee !important; box-shadow: none !important; }
            .print-header { display: block !important; }
        }
    </style>
</head>
<body class="flex min-h-screen">
    <aside class="w-64 sidebar text-[#F5F5F0] fixed h-full flex flex-col print:hidden">
        <div class="p-8 border-b border-[#C5A059]/20">
            <h1 class="text-2xl font-serif text-[#C5A059] font-bold uppercase tracking-tight">ComplaintFix</h1>
            <p class="text-[10px] uppercase tracking-widest opacity-60 mt-1">Admin Review Mode</p>
        </div>
        <nav class="flex-1 py-10 px-4">
            <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded bg-[#4A0E0E] text-[#C5A059]">
                <span class="text-sm font-medium tracking-wide">← Back to Overview</span>
            </a>
        </nav>
    </aside>

    <main class="ml-64 flex-1 p-12">
        <div class="hidden print-header mb-10 text-center border-b-2 border-[#3E2723] pb-6">
            <h1 class="text-3xl font-serif text-[#4A0E0E] uppercase">Ashford University</h1>
            <p class="text-sm font-bold tracking-widest text-gray-500">OFFICIAL GRIEVANCE RESOLUTION RECORD</p>
            <p class="text-[10px] text-gray-400 mt-2">Generated on: <?php echo date('F d, Y'); ?></p>
        </div>

        <header class="mb-12 border-b pb-6 border-[#3E2723]/10 flex justify-between items-end">
            <div>
                <h2 class="text-4xl font-serif text-[#4A0E0E] leading-tight">Case Review: #<?php echo $data['complaint_id']; ?></h2>
                <p class="text-[#3E2723] opacity-60 text-sm italic mt-2">Submitted by <?php echo htmlspecialchars($data['student_name']); ?> (<?php echo $data['student_email']; ?>)</p>
            </div>
            <button onclick="window.print()" class="print-btn px-6 py-2 bg-[#C5A059] text-[#3E2723] text-[10px] font-bold uppercase tracking-widest rounded shadow hover:bg-opacity-90 transition-all">
                Export Case PDF
            </button>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-12 rounded shadow-xl border border-[#C5A059]/10">
                    <?php if($data['status'] == 'Resolved' && !empty($data['conclusion'])): ?>
                    <div class="mb-10 p-6 bg-[#4A0E0E] text-[#FCF9F2] rounded border-l-4 border-[#C5A059]">
                        <label class="text-[10px] uppercase tracking-widest font-bold opacity-60">Final Resolution Conclusion</label>
                        <p class="mt-2 font-serif italic text-lg"><?php echo htmlspecialchars($data['conclusion']); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <label class="text-[10px] uppercase tracking-widest font-bold opacity-60">Category</label>
                            <p class="text-xl font-serif text-[#3E2723]"><?php echo $data['category']; ?></p>
                        </div>
                        <div class="text-right">
                            <label class="text-[10px] uppercase tracking-widest font-bold opacity-60">Priority</label>
                            <p class="text-sm font-bold text-[#C5A059] uppercase tracking-widest"><?php echo $data['priority']; ?></p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="text-[10px] uppercase tracking-widest font-bold opacity-60">Detailed Statement</label>
                        <div class="mt-4 p-6 bg-[#FCF9F2] border border-[#C5A059]/10 rounded italic text-[#3E2723] leading-relaxed">
                            "<?php echo htmlspecialchars($data['description']); ?>"
                        </div>
                    </div>

                    <div class="mb-8 pt-6 border-t border-gray-100">
                        <label class="text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60">Submitted Evidence / Proof</label>
                        <div class="mt-4">
                            <?php if (!empty($data['attachment']) && file_exists($data['attachment'])): ?>
                                <?php 
                                $file_ext = strtolower(pathinfo($data['attachment'], PATHINFO_EXTENSION));
                                $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                ?>
                                
                                <?php if (in_array($file_ext, $image_extensions)): ?>
                                    <div class="max-w-md rounded border border-gray-200 shadow-sm bg-gray-50 p-2">
                                        <a href="<?php echo htmlspecialchars($data['attachment']); ?>" target="_blank" title="Click to view full size">
                                            <img src="<?php echo htmlspecialchars($data['attachment']); ?>" alt="Evidence File" class="w-full h-auto object-contain max-h-80 rounded hover:opacity-95 transition-all">
                                        </a>
                                        <div class="mt-2 p-2 flex justify-between items-center bg-white rounded border border-gray-100">
                                            <span class="text-xs text-gray-500 font-mono truncate max-w-[220px]"><?php echo basename($data['attachment']); ?></span>
                                            <a href="<?php echo htmlspecialchars($data['attachment']); ?>" download class="text-xs font-bold text-[#4A0E0E] hover:underline uppercase tracking-wider">Download</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded border border-gray-200 max-w-md">
                                        <div class="p-3 bg-[#4A0E0E]/10 rounded text-[#4A0E0E] font-bold font-mono text-xs uppercase">
                                            <?php echo $file_ext; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-[#3E2723] truncate"><?php echo basename($data['attachment']); ?></p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Verification Document</p>
                                        </div>
                                        <a href="<?php echo htmlspecialchars($data['attachment']); ?>" download class="px-4 py-2 bg-[#4A0E0E] text-[#FCF9F2] text-xs font-bold rounded hover:opacity-90 transition-all uppercase tracking-wider">
                                            Download
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                            <?php else: ?>
                                <p class="text-sm text-gray-400 italic">No media documentation or evidence files attached to this case.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-12 pt-10 border-t border-gray-100">
                        <h3 class="text-lg font-serif text-[#4A0E0E] mb-6 italic">Administrative Progress Log</h3>
                        <div class="update-form-area print:hidden">
                            <form action="" method="POST" class="mb-10">
                                <textarea name="update_text" required class="w-full p-4 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-sm outline-none h-24 mb-4" placeholder="Post a progress update for the student..."></textarea>
                                <button type="submit" name="post_update" class="bg-[#C5A059] text-[#3E2723] px-6 py-2 text-[10px] font-bold uppercase tracking-widest rounded shadow hover:bg-opacity-90 transition-all">Post Update</button>
                            </form>
                        </div>

                        <div class="space-y-6 relative">
                            <div class="absolute left-[5px] top-2 bottom-2 w-px bg-[#C5A059]/20"></div>
                            <?php
                            $updates = $conn->query("SELECT * FROM complaint_updates WHERE complaint_id = '$complaint_id' ORDER BY created_at DESC");
                            while($u = $updates->fetch_assoc()): ?>
                                <div class="relative pl-8">
                                    <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-[#C5A059] border-2 border-white"></div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?php echo date('M d, Y | H:i', strtotime($u['created_at'])); ?></p>
                                    <p class="text-sm text-[#3E2723] mt-1"><?php echo htmlspecialchars($u['update_text']); ?></p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 action-sidebar print:hidden">
                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <label class="block text-[10px] uppercase tracking-widest font-bold opacity-60 mb-6">Update Case Status</label>
                    <form action="" method="POST" class="space-y-4">
                        <select id="statusSelect" name="status" onchange="toggleConclusionBox()" class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-sm outline-none">
                            <option value="Pending" <?php if($data['status'] == 'Pending') echo 'selected'; ?>>Pending Review</option>
                            <option value="In Progress" <?php if($data['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Resolved" <?php if($data['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                        </select>

                        <div id="conclusionBox" class="<?php echo ($data['status'] == 'Resolved') ? '' : 'hidden'; ?> space-y-2 mt-4">
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-[#4A0E0E]">Final Conclusion</label>
                            <textarea name="conclusion" class="w-full p-3 bg-[#FCF9F2] border border-[#4A0E0E]/20 rounded text-xs h-28" placeholder="Summarize the final resolution..."><?php echo htmlspecialchars($data['conclusion'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" name="update_status" class="w-full bg-[#4A0E0E] text-[#FCF9F2] py-4 rounded font-bold uppercase tracking-widest text-[10px] shadow-lg hover:bg-opacity-90 transition-all">
                            Save Status Update
                        </button>
                    </form>
                </div>

                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <label class="block text-[10px] uppercase tracking-widest font-bold opacity-60 mb-2">Submission Date</label>
                    <p class="text-sm text-[#3E2723]"><?php echo date("F d, Y", strtotime($data['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </main>

    <script>
    function toggleConclusionBox() {
        const status = document.getElementById('statusSelect').value;
        const box = document.getElementById('conclusionBox');
        if(status === 'Resolved') {
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    }
    </script>
</body>
</html>