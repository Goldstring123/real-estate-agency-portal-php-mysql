<?php
require_once 'classes/RealEstateDatabase.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new RealEstateDatabase();

    $userName = trim($_POST['userName'] ?? '');
    $contactInfo = trim($_POST['contactInfo'] ?? '');
    $password = $_POST['password'] ?? '';
    $userType = $_POST['userType'] ?? '';

    if ($userName && $contactInfo && $password && $userType) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $db->addUser($userName, $contactInfo, $passwordHash, $userType);
            $message = '<div class="success">Registration successful! <a href="login.php">Click here to login</a></div>';
        } catch (Throwable $e) {
            $message = '<div class="error">Error: ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="error">Please fill in all fields.</div>';
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Register</h2>
<?php if ($message): ?>
    <?= $message ?>
<?php endif; ?>

<form method="POST">
    <label>Username</label>
    <input type="text" name="userName" required>

    <label>Contact Info</label>
    <input type="text" name="contactInfo" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>User Type</label>
    <select name="userType" required>
        <option value="">Select role</option>
        <option value="agent">Agent</option>
        <option value="buyer">Buyer</option>
        <option value="renter">Renter</option>
    </select>

    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>
<?php include 'includes/footer.php'; ?>