<?php
// admin_login.php
session_start();
require 'db_connect.php';

$errorMessage = '';

// If the user is already logged in, redirect them to the dashboard.
if (isset($_SESSION['user_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errorMessage = "Please enter both username and password.";
    } else {
        try {
            // Prepare a statement to select the user from the database.
            $stmt = $db->prepare("SELECT id, username, password_hash, must_change_password FROM admins WHERE username = ?");
            if ($stmt === false) {
                throw new Exception("Database prepare statement failed: " . $db->error);
            }
            
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verify the password against the stored hash.
                if (password_verify($password, $user['password_hash'])) {
                    // Password is correct, so start a new session.
                    
                    // Regenerate session ID to prevent session fixation.
                    session_regenerate_id(true);

                    // Store data in session variables.
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['must_change_password'] = (bool)$user['must_change_password'];

                    // Redirect to the admin dashboard.
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    // Password is not valid.
                    $errorMessage = "Invalid username or password.";
                }
            } else {
                // No user found with that username.
                $errorMessage = "Invalid username or password.";
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $errorMessage = "An error occurred. Please try again later.";
        }
    }
}

$pageTitle = "Admin Login - Super Brothers LLC";
// We don't want the full public header/footer for the login page.
// It should be a standalone page for security and simplicity.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                'primary-black': 'var(--color-primary-black, #1a1a1a)',
                'accent-orange': 'var(--color-accent-orange, #ff6600)',
              }
            }
          }
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary-black">Admin Portal</h1>
            <p class="text-gray-500">Super Brothers LLC</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="mb-6 p-4 rounded-md bg-red-100 text-red-700 text-center" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange sm:text-sm"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange sm:text-sm">
            </div>
            <div>
                <button type="submit" class="w-full btn bg-accent-orange hover:bg-orange-600 text-primary-black font-bold py-3 px-4 rounded-md shadow-md transition duration-300">
                    Sign In
                </button>
            </div>
        </form>
         <div class="text-center mt-6">
            <a href="index.php" class="text-sm text-gray-500 hover:text-accent-orange">&larr; Back to Main Site</a>
        </div>
    </div>
</body>
</html>

