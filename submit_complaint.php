<?php
// Start session and check authentication as per SDD Security Design
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle form submission logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_SESSION['user_id'];
    $cat = mysqli_real_escape_string($conn, $_POST['category']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']); 
    $location = mysqli_real_escape_string($conn, $_POST['location']); 
    $is_anonymous = isset($_POST['anonymous']) ? 1 : 0;
    $date = date('Y-m-d'); 
    $status = "Pending"; 

    $attachment_path = ""; // Default empty state if no validation proof is provided

    // NEW: Server-Side File Upload Processor Logic Pipeline
    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == 0) {
        $target_dir = "uploads/";
        
        // Dynamic directory safe guard: build folder automatically if missing
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Sanitize file names by prepping with a unique timestamp signature to avoid namespace collision
        $file_name = time() . "_" . basename($_FILES["evidence"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Enforce maximum file limit boundary constraint of 5MB
        if ($_FILES["evidence"]["size"] <= 5242880) {
            // Move binary stream out of volatile temp memory into persistent disk allocation
            if (move_uploaded_file($_FILES["evidence"]["tmp_name"], $target_file)) {
                $attachment_path = mysqli_real_escape_string($conn, $target_file);
            }
        } else {
            echo "<script>alert('Error: Attachment exceeds maximum allowed file payload parameter of 5MB.');</script>";
        }
    }

    // FIXED: Query structured parameters updated to store both location string AND attachment file path string fields natively
    $sql = "INSERT INTO complaints (user_id, category, description, priority, status, created_at, location, attachment) 
            VALUES ('$uid', '$cat', '$desc', '$priority', '$status', '$date', '$location', '$attachment_path')";

    if ($conn->query($sql) === TRUE) {
        header("Location: submission_success.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComplaintFix | Submit Complaint</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #FCF9F2; }
        .font-serif { font-family: 'Georgia', serif; }
        .sidebar { background-color: #3E2723; border-right: 2px solid #C5A059; }
        .nav-active { background-color: #4A0E0E; color: #C5A059; }
        
        .priority-btn:checked + label { 
            border-color: #C5A059; 
            background-color: #FCF9F2; 
            border-width: 2px;
        }

        .upload-zone { border: 2px dashed rgba(197, 160, 89, 0.2); transition: all 0.3s ease; }
        .upload-zone:hover { border-color: #C5A059; background-color: #FCF9F2; }
        
        .toggle-dot { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .toggle-bg { transition: background-color 0.3s ease; }
        input:checked ~ .toggle-bg { background-color: #4A0E0E; }
        input:checked ~ .toggle-dot { transform: translateX(1.25rem); background-color: #C5A059; }
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
            <a href="submit_complaint.php" class="flex items-center gap-3 px-4 py-3 rounded nav-active">
                <div class="w-2 h-2 rounded-full bg-[#C5A059]"></div>
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
        <header class="mb-12 border-b pb-6 border-[#3E2723]/10">
            <h2 class="text-4xl font-serif text-[#4A0E0E] leading-tight">Official Complaint</h2>
            <p class="text-[#3E2723] opacity-60 text-sm italic mt-2">All complaints are treated with utmost confidentiality.</p>
        </header>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-12 rounded shadow-xl border border-[#C5A059]/10 space-y-8">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-4">Registration Category</label>
                        <div class="relative">
                            <select name="category" required class="w-full px-6 py-4 bg-[#FCF9F2] border border-[#C5A059]/20 rounded appearance-none text-sm cursor-pointer text-[#3E2723] outline-none">
                                <option value="" disabled selected>Select a category</option>
                                <option>Academic Affairs</option>
                                <option>Hostel & Infrastructure</option>
                                <option>Examination & Results</option>
                                <option>Library Services</option>
                            </select>
                            <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-[#C5A059]">▼</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-4">Detailed Statement</label>
                        <textarea name="description" required rows="8" class="w-full px-6 py-4 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-sm text-[#3E2723] outline-none" placeholder="Provide a detailed account of your complaint..."></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-4">Supporting Evidence</label>
                        <div class="upload-zone rounded-lg p-10 flex flex-col items-center justify-center text-center cursor-pointer relative">
                            <input type="file" name="evidence" id="evidenceInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileInfo()">
                            <div class="text-[#C5A059] opacity-40 mb-4" id="uploadIcon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                            </div>
                            <p class="text-sm text-[#3E2723] font-medium" id="uploadStatusText">Drag & drop files or <span class="text-[#C5A059] underline">browse</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-6">Priority Level</label>
                    <div class="space-y-3">
                        <?php foreach(['Low', 'Medium', 'High'] as $p): ?>
                        <div class="flex items-center">
                            <input type="radio" id="<?=strtolower($p)?>" name="priority" value="<?=$p?>" class="hidden priority-btn" <?= $p == 'Medium' ? 'checked' : '' ?>>
                            <label for="<?=strtolower($p)?>" class="w-full px-4 py-3 border border-gray-100 rounded text-sm cursor-pointer hover:bg-gray-50 transition-all block text-center font-medium text-[#3E2723] opacity-80">
                                <?= $p ?> Priority
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60 mb-4">Incident Location</label>
                    <input type="text" name="location" placeholder="e.g., Block B, Room 304" class="w-full px-4 py-3 bg-[#FCF9F2] border border-[#C5A059]/20 rounded text-sm text-[#3E2723] outline-none placeholder:text-gray-300">
                </div>

                <div class="bg-white p-8 rounded shadow-lg border border-[#C5A059]/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-[#3E2723] opacity-60">Anonymous Submission</label>
                            <p class="text-[10px] text-[#3E2723] opacity-40 mt-1 uppercase tracking-tighter">Your identity will be hidden.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="anonymous" class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer toggle-bg"></div>
                            <div class="absolute left-[2px] top-[2px] bg-white w-4 h-4 rounded-full toggle-dot"></div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#4A0E0E] text-[#FCF9F2] py-5 rounded font-bold uppercase tracking-widest text-xs shadow-2xl hover:bg-opacity-95 active:scale-95 transition-all">
                    Submit Complaint
                </button>
            </div>
        </form>
    </main>

    <script>
    // UX feedback helper: displays the uploaded filename dynamically inside the upload box container boundaries
    function updateFileInfo() {
        const fileInput = document.getElementById('evidenceInput');
        const statusText = document.getElementById('uploadStatusText');
        const iconContainer = document.getElementById('uploadIcon');
        
        if (fileInput.files.length > 0) {
            const fileName = fileInput.files[0].name;
            statusText.innerHTML = "Selected file: <span class='text-[#C5A059] font-mono font-bold'>" + fileName + "</span>";
            iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
        }
    }
    </script>
</body>
</html>