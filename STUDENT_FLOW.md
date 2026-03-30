# Student Management System - Data & File Flow Documentation

This document outlines the complete workflow of the Student Management System, from initial data entry and file upload to database storage and display within the user interface.

## 1. File Upload & Processing Flow

When a user submits the "Add Student" or "Edit Student" form, the following process occurs:

### A. Client-Side (Browser)
1. **Validation**: The `FormValidator` class (`public/js/validator.js`) performs real-time and on-submit checks (e.g., stopping numbers in names, enforcing character limits, and ensuring required files are selected).
2. **Form Submission**: The user clicks "Save Student Record". Data is sent via `POST` to the server as `multipart/form-data`.

### B. Server-Side (Controller)
1. **Route Handling**: `app/Config/Routes.php` directs the request to the `save()` or `update()` methods in `StudentController.php`.
2. **File Extraction**: The controller uses CodeIgniter's `$this->request->getFile()` or `$this->request->getFiles()` to access uploaded objects.
3. **Randomized Renaming**: To prevent name collisions (e.g., two students uploading `my_photo.jpg`), the system uses `$file->getRandomName()`.
4. **File Storage**: The files are moved to dedicated directories in `public/uploads/`:
   - `/profile/` -> Student photos.
   - `/resume/` -> PDF resumes.
   - `/id_proof/` -> Identity documents.
   - `/certificates/` -> Multiple educational certificates.

## 2. Database Storage Architecture

The system uses two related tables to store student data and their associated multiple files.

### A. `students` Table
Stores primary student details and core file names.
- `id` (Primary Key)
- `name`, `email`, `phone`, `department` (Text data)
- `profile_photo` (Stores string like `1711786523_abcdef.jpg`)
- `resume` (Stores PDF filename)
- `id_proof` (Stores identification filename)

### B. `certificates` Table
Stores additional files related to a specific student (One-to-Many relationship).
- `id` (Primary Key)
- `student_id` (Foreign Key linking to `students.id`)
- `file_name` (Stores certificate filename)

## 3. Retrieval & Display Flow

### A. List View (`/students`)
1. `StudentController::index()` fetches all records from the `StudentModel`.
2. Data is passed to `student_list.php`. 
3. **Rendering**: The system uses the filenames stored in the database to construct image paths: `<img src="/uploads/profile/<?= $s['profile_photo'] ?>">`.
4. **DataTable**: `DataTables` is initialized to provide searching and sorting on the client side.

### B. Individual Profile View (`/students/view/{id}`)
1. `StudentController::view($id)` fetches the single student record AND all matching certificates from the `CertificateModel`.
2. **Structured Display**: `student_view.php` renders the profile image, text details in a grid, and lists all documents as clickable boxes.
3. **Separation**: Documents like Resumes and Certificates use `target="_blank"`, allowing them to open in a new browser tab for easy viewing.

### C. Inline Editing (`/students/edit/{id}`)
1. The system loads the existing student data into the form.
2. When updating, if a new file is uploaded, the controller **automatically unlinks (deletes)** the old file from the server to save space before saving the new reference to the database.

---
**Core Technology Stack**: CodeIgniter 4 (PHP), jQuery (Validation), DataTables (UI Layer), CSS3 (Premium Aesthetics).
