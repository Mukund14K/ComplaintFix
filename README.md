# ComplaintFix — Smart Complaint Management System

ComplaintFix is a secure, lightweight web application engineered to streamline institutional grievance management, eliminate slow paperwork chains, and provide transparent complaint lifecycle tracking within a college environment. The platform features isolated user flows for students to voice concerns and an analytical workspace for administrators to resolve issues efficiently.

Developed completely using an open-source development stack, this project utilizes native modular design principles to separate user presentation interfaces from backend database processors.

---

## 🚀 Architectural Highlights

- **Role-Based Authentication:** Secure, session-managed logging layers protecting student profiles and administrative operational data.
- **Dynamic Track Ledger:** Interactive tracking interfaces that let users visually monitor administrative updates chronologically.
- **Administrative Control Panel:** A centralized workspace loaded with filtering, tracking, and prioritization metrics.
- **Fail-Safe Robustness:** Embedded database catch boundaries to isolate connection timeouts and display clean, controlled maintenance notifications instead of raw server errors.

---

## 🛠️ Built With

- **Frontend Presentation Layer:** HTML5, CSS3, JavaScript (ES6)
- **Business Logic Layer:** Structured Native PHP (Session-driven, sanitized)
- **Data Layer:** MySQL Relational Database
- **Development Stack:** XAMPP Local Server Environment

---

## 📂 Codebase File Structure

The project uses a clean, unified root structure to manage frontend forms alongside asynchronous backend execution scripts:

```text
/complaintfix/
│
├── assets/                  # CSS style sheets and UI themes
│   └── style.css            # Consolidated layout configuration styles
├─ db.php                    # Centralized database connection with exception handling
├── admin_dashboard.php      # Main administrative data view grid
├── admin_login.php          # Secure entry portal for college admins
├── admin_reports.php        # Metric filter module and analytics layout
├── admin_update_success.php # Visual confirmation screen for case resolutions
├── admin_view_complaint.php # Case review room for adding remarks and tracking status
├── dashboard.php            # Primary landing screen for authenticated students
├── login.php                # Unified portal entry screen for student access
├── logout.php               # Automated session destruction utility
├── process_complaint.php    # Server-side validator and insert tool for new complaints
├── process_login.php        # Authentication validator engine for security
├── process_register.php     # Password hashing and new profile initialization script
├── register.php             # Student registration and identity intake form
├── submission_success.php   # Feedback page showing successful database insertion
├── submit_complaint.php     # Complex intake form for complaint entry fields
├── track_complaints.php     # Student's custom operational data log ledger
├── view_complaint.php       # Dedicated read-only view for checking update threads
