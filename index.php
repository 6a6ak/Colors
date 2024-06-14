<?php include 'header.php'; ?>

<?php
require_once 'relation.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare('SELECT * FROM user_palettes WHERE user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$palettes = [];
while ($row = $result->fetch_assoc()) {
    $palettes[] = $row;
}

$stmt->close();
?>

<?php foreach ($palettes as $palette): ?>
<div class="color-card">
    <h3><?= htmlspecialchars($palette['pack_name']) ?></h3>
    <div class="color-row">
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <?php if (!empty($palette["color$i"])): ?>
            <div class="color-box" style="background-color: <?= htmlspecialchars($palette["color$i"]) ?>;">
                <div class="color-hex"><?= htmlspecialchars($palette["color$i"]) ?></div>
                <i class="fas fa-copy copy-icon" onclick="copyToClipboard('<?= htmlspecialchars($palette["color$i"]) ?>')"></i>
            </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <div class="button-row">
        <button class="edit-icon" onclick="editColorPack(<?= $palette['id'] ?>)">
            <i class="fas fa-edit"></i> Edit Pack
        </button>
        <button class="delete-icon" onclick="confirmDelete(<?= $palette['id'] ?>)">
            <i class="fas fa-trash"></i> Delete Pack
        </button>
    </div>
</div>
<?php endforeach; ?>

<script>
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('Copied: ' + text);
    }

    function confirmDelete(paletteId) {
        if (confirm('Are you sure you want to delete this color pack?')) {
            window.location.href = 'delete_palette.php?id=' + paletteId;
        }
    }

    function editColorPack(paletteId) {
        window.location.href = 'edit_colors.php?id=' + paletteId;
    }
</script>
</div>
</body>
</html>
