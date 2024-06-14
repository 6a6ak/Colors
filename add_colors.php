<?php
require_once 'relation.php';

function validate_color_code($color_code) {
    if (strpos($color_code, '#') !== 0) {
        $color_code = '#' . $color_code;
    }
    return (preg_match('/^#[a-f0-9]{3,6}$/i', $color_code)) ? $color_code : false;
}

session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $packName = $_POST['pack_name'];
    $colorCodes = array_filter([
        $_POST['color1'], $_POST['color2'], $_POST['color3'], $_POST['color4'], $_POST['color5'],
        $_POST['color6'], $_POST['color7'], $_POST['color8'], $_POST['color9'], $_POST['color10']
    ]);

    $validColorCodes = [];
    foreach ($colorCodes as $code) {
        $validatedCode = validate_color_code(trim($code));
        if ($validatedCode) {
            $validColorCodes[] = $validatedCode;
        } else {
            echo "<script>alert('Invalid color code: $code. Please enter valid hex color codes.');</script>";
            exit;
        }
    }

    if (count($validColorCodes) > 0 && !empty($packName)) {
        $stmt = $mysqli->prepare('INSERT INTO user_palettes (user_id, pack_name, color1, color2, color3, color4, color5, color6, color7, color8, color9, color10) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isssssssssss', $user_id, $packName, $validColorCodes[0], $validColorCodes[1], $validColorCodes[2], $validColorCodes[3], $validColorCodes[4], $validColorCodes[5], $validColorCodes[6], $validColorCodes[7], $validColorCodes[8], $validColorCodes[9]);
        $stmt->execute();

        $stmt->close();

        echo "<script>alert('Color pack added successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Please enter a valid pack name and at least one valid color code.');</script>";
    }
}
?>

<?php include 'header.php'; ?>
    <div class="form-container">
        <h2>Add Color Pack</h2>
        <form method="post" action="add_colors.php">
            <div class="form-group">
                <label for="pack_name">Pack Name:</label>
                <input type="text" id="pack_name" name="pack_name" required>
            </div>
            <?php for ($i = 1; $i <= 10; $i++): ?>
            <div class="form-group">
                <label for="color<?= $i ?>">Color <?= $i ?>:</label>
                <input type="text" id="color<?= $i ?>" name="color<?= $i ?>">
            </div>
            <?php endfor; ?>
            <button type="submit">Add Pack</button>
        </form>
    </div>
</div>
</body>
</html>
