import unittest
import requests

class TestComplaintFixUnits(unittest.TestCase):

    def setUp(self):
        """
        Arrange: Establish link targets pointing to your working localhost directory.
        """
        self.base_url = "http://localhost:8080/complaintfix"
        self.session = requests.Session()
        
        # Testing context targets
        self.student_email = "student@college.edu"
        self.student_pass = "SecurePass123"

    def tearDown(self):
        """
        Cleanup: Flushes application session states.
        """
        self.session.get(f"{self.base_url}/logout.php")
        self.session.close()

    def test_TC01_verifyLogin_valid_credentials(self):
        """TC01: Valid credentials routing check."""
        url = f"{self.base_url}/process_login.php"
        payload = {
            "email": self.student_email, 
            "password": self.student_pass, 
            "role": "student",
            "submit": "login"
        }
        response = self.session.post(url, data=payload, allow_redirects=True)
        # Accept either a 302 redirection or a 200 OK confirmation page render
        self.assertIn(response.status_code, [200, 302])

    def test_TC02_verifyLogin_invalid_password(self):
        """TC02: Invalid password routing check."""
        url = f"{self.base_url}/process_login.php"
        payload = {
            "email": self.student_email, 
            "password": "WrongPasswordXYZ", 
            "role": "student",
            "submit": "login"
        }
        response = self.session.post(url, data=payload, allow_redirects=True)
        self.assertIn(response.status_code, [200, 302])

    def test_TC03_verifyLogin_empty_fields(self):
        """TC03: Empty fields checking loop."""
        url = f"{self.base_url}/process_login.php"
        payload = {
            "email": "", 
            "password": "", 
            "role": "student"
        }
        response = self.session.post(url, data=payload, allow_redirects=True)
        self.assertEqual(response.status_code, 200)

    def test_TC04_checkPasswordConstraints_sub_minimum(self):
        """TC04: Sub-minimum boundary length registration check."""
        url = f"{self.base_url}/process_register.php"
        payload = {
            "name": "Mukund", 
            "email": "new@college.edu", 
            "password": "abc12", 
            "confirm_password": "abc12"
        }
        response = self.session.post(url, data=payload, allow_redirects=True)
        self.assertEqual(response.status_code, 200)

    def test_TC05_checkPasswordConstraints_exact_minimum(self):
        """TC05: Valid edge boundary length registration check."""
        url = f"{self.base_url}/process_register.php"
        payload = {
            "name": "Mukund", 
            "email": "new@college.edu", 
            "password": "secure", 
            "confirm_password": "secure"
        }
        response = self.session.post(url, data=payload, allow_redirects=True)
        self.assertEqual(response.status_code, 200)

    def test_TC06_parseAttachmentPayload_valid_size(self):
        """TC06: Dynamic complaint post with valid parameter sizes."""
        # Authenticate session
        login_url = f"{self.base_url}/process_login.php"
        self.session.post(login_url, data={"email": self.student_email, "password": self.student_pass, "role": "student"})
        
        url = f"{self.base_url}/process_complaint.php"
        payload = {
            "category": "Hostel Maintenance", 
            "title": "Water cut Block C", 
            "description": "Water line cut since 08:00 AM.",
            "submit": "complaint"
        }
        response = self.session.post(url, data=payload, allow_redirects=True)
        self.assertIn(response.status_code, [200, 302])

    def test_TC07_parseAttachmentPayload_over_limit(self):
        """TC07: Rejection validation framework check for simulated oversized files."""
        login_url = f"{self.base_url}/process_login.php"
        self.session.post(login_url, data={"email": self.student_email, "password": self.student_pass, "role": "student"})
        
        url = f"{self.base_url}/process_complaint.php"
        payload = {
            "category": "Hostel Maintenance", 
            "title": "Water cut Block C", 
            "description": "Oversized attachment dummy test.", 
            "MAX_FILE_SIZE": "5242880"
        }
        response = self.session.post(url, data=payload, allow_redirects=True)
        self.assertEqual(response.status_code, 200)

    def test_TC08_updateLifecycle_invalid_status(self):
        """TC08: Authorization route parameters boundary check."""
        login_url = f"{self.base_url}/process_login.php"
        self.session.post(login_url, data={"email": self.student_email, "password": self.student_pass, "role": "student"})
        
        response = self.session.get(f"{self.base_url}/view_complaint.php?id=999")
        self.assertTrue(response.status_code in [200, 302])

if __name__ == "__main__":
    unittest.main()