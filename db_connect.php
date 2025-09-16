<?php
// db_connect.php - LOCAL DEVELOPMENT VERSION

// Turn on error reporting for mysqli to throw exceptions on errors
// This allows us to use try-catch blocks for cleaner error handling.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database credentials for YOUR specific LOCALHOST development
$db_host = "localhost";         // Standard for local MySQL. You can also use "localhost:3306"
$db_user = "root";              // Your local MySQL username
$db_pass = "mysql";             // Your local MySQL password for the 'root' user
$db_name = "superduperbrosllc";     // Your specific local database name

try {
    // Attempt to create a new mysqli connection
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Set the character set for the connection - crucial for handling special characters properly!
    $db->set_charset("utf8mb4");

    // You can uncomment this for quick verification during development
    // echo "Successfully connected to LOCAL database: " . $db->host_info . "<br>";

} catch (mysqli_sql_exception $e) {
    // In a live application, you'd log this error to a secure file or error tracking system.
    // For local development, echoing can be helpful, but still be mindful of sensitive info.
    error_log("LOCAL Database Connection Error: " . $e->getMessage() . " (User: " . htmlspecialchars($db_user) . ", Host: " . htmlspecialchars($db_host) . ", DB: " . htmlspecialchars($db_name) . ")");
    
    // For the user/developer, display a more specific error for local dev.
    die("<h2>Local Database Connection Failed!</h2>
         <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         <p><strong>Details:</strong></p>
         <ul>
             <li>Host: " . htmlspecialchars($db_host) . "</li>
             <li>User: " . htmlspecialchars($db_user) . "</li>
             <li>Password: " . (empty($db_pass) ? "<i>Not Set (Blank)</i>" : "<i>Set (Hidden for security, but it was '" . htmlspecialchars($db_pass) . "')</i>") . "</li>
             <li>Database Name: " . htmlspecialchars($db_name) . "</li>
         </ul>
         <p><strong>Troubleshooting Tips:</strong></p>
         <ol>
             <li>Ensure your local MySQL server (e.g., XAMPP, MAMP, WAMP) is running.</li>
             <li>Verify the database credentials (host, username, password) in `db_connect.php` match your local MySQL setup.
                 <ul>
                    <li>Current Host: <strong>" . htmlspecialchars($db_host) . "</strong></li>
                    <li>Current User: <strong>" . htmlspecialchars($db_user) . "</strong></li>
                    <li>Current Password: <strong>" . htmlspecialchars($db_pass) . "</strong></li>
                    <li>Current DB Name: <strong>" . htmlspecialchars($db_name) . "</strong></li>
                 </ul>
             </li>
             <li>Check if the database '<strong>" . htmlspecialchars($db_name) . "</strong>' exists in your local phpMyAdmin or MySQL client. If not, create it or ensure it's correctly named.</li>
             <li>If you're using a specific port for MySQL locally (other than the default 3306) and didn't include it in the hostname, you might need to specify it: e.g., `localhost:YOUR_PORT_NUMBER`. (Default is 3306, which is usually fine if just 'localhost' is used).</li>
         </ol>");
}

// If the script reaches this point, the database connection was successful.
// The $db object is now available for use in any script that includes this file.
?>
