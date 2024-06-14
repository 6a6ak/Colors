<?php
require_once 'relation.php';
session_start();

function validate_color_code($color_code) {
    if (strpos($color_code, '#') !== 0) {
        $color_code = '#' . $color_code;
    }
    return (preg_match('/^#[a-f0-9]{3,6}$/i', $color_code)) ? $color_code : false;
}

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $palette_id = intval($_POST['id']);
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
        $stmt = $mysqli->prepare('UPDATE user_palettes SET pack_name = ?, color1 = ?, color2 = ?, color3 = ?, color4 = ?, color5 = ?, color6 = ?, color7 = ?, color8 = ?, color9 = ?, color10 = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param('ssssssssssssi', $packName, $validColorCodes[0], $validColorCodes[1], $validColorCodes[2], $validColorCodes[3], $validColorCodes[4], $validColorCodes[5], $validColorCodes[6], $validColorCodes[7], $validColorCodes[8], $validColorCodes[9], $palette_id, $user_id);
        $stmt->execute();

        $stmt->close();

        echo "<script>alert('Color pack updated successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Please enter a valid pack name and at least one valid color code.');</script>";
    }
} else if (isset($_GET['id'])) {
    $palette_id = intval($_GET['id']);

    $stmt = $mysqli->prepare('SELECT * FROM user_palettes WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $palette_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $palette = $result->fetch_assoc();

    $stmt->close();
} else {
    echo 'No palette ID provided for editing.';
    exit;
}
?>

<?php include 'header.php'; ?>
    <div class="form-container">
        <h2>Edit Color Pack</h2>
        <form method="post" action="edit_colors.php">
            <input type="hidden" name="id" value="<?= $palette['id'] ?>">
            <div class="form-group">
                <label for="pack_name">Pack Name:</label>
                <input type="text" id="pack_name" name="pack_name" value="<?= htmlspecialchars($palette['pack_name']) ?>" required>
            </div>
            <?php for ($i = 1; $i <= 10; $i++): ?>
            <div class="form-group">
                <label for="color<?= $i ?>">Color <?= $i ?>:</label>
                <input type="text" id="color<?= $i ?>" name="color<?= $i ?>" value="<?= htmlspecialchars($palette["color$i"]) ?>">
            </div>
            <?php endfor; ?>
            <button type="submit">Update Pack</button>
        </form>
    </div>
</div>
</body>
</html>
