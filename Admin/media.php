<?php
/**
 * Media library: upload images from the local computer and manage them.
 */
$pageTitle = 'Media Library';
$pageSubtitle = 'Upload and manage image assets used across the storefront.';
$activeNav = 'media';
require_once __DIR__ . '/Includes/header.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    verify_csrf();
    $filename = basename(sanitize($_POST['delete_file']));
    $path = UPLOAD_DIR . $filename;
    if ($filename !== '' && is_file($path)) {
        unlink($path);
        $successMessage = 'Image deleted.';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_files'])) {
    verify_csrf();
    $names = $_FILES['media_files']['name'];
    $uploaded = 0;
    $errors = [];

    foreach ($names as $index => $name) {
        if ($_FILES['media_files']['error'][$index] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        $file = [
            'name' => $name,
            'type' => $_FILES['media_files']['type'][$index],
            'tmp_name' => $_FILES['media_files']['tmp_name'][$index],
            'error' => $_FILES['media_files']['error'][$index],
            'size' => $_FILES['media_files']['size'][$index],
        ];
        try {
            save_uploaded_image($file);
            $uploaded++;
        } catch (RuntimeException $exception) {
            $errors[] = $exception->getMessage();
        }
    }

    if ($uploaded > 0) {
        $successMessage = $uploaded . ' image' . ($uploaded === 1 ? '' : 's') . ' uploaded.';
    }
    if (!empty($errors)) {
        $errorMessage = implode(' ', $errors);
    }
}

$mediaFiles = get_media_library();
?>
<?php if ($successMessage !== ''): ?><div class="admin-alert"><i class="bi bi-check-circle-fill me-1"></i><?php echo e($successMessage); ?></div><?php endif; ?>
<?php if ($errorMessage !== ''): ?><div class="admin-alert admin-alert-error"><i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo e($errorMessage); ?></div><?php endif; ?>

<div class="admin-card mb-4">
    <div class="admin-card-header">
        <div>
            <h5>Upload Images</h5>
            <p>Add images from your computer to use on any product</p>
        </div>
    </div>
    <div class="admin-card-body">
        <form method="post" enctype="multipart/form-data" class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
            <?php echo csrf_field(); ?>
            <input class="form-control" type="file" name="media_files[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple required>
            <button class="btn-admin-accent flex-shrink-0" type="submit"><i class="bi bi-upload me-1"></i>Upload</button>
        </form>
        <div class="form-text mt-2">JPG, PNG, WEBP or GIF, up to 5MB each. You can select multiple files at once.</div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h5>Library</h5>
            <p><?php echo count($mediaFiles); ?> uploaded image<?php echo count($mediaFiles) === 1 ? '' : 's'; ?></p>
        </div>
    </div>
    <div class="admin-card-body">
        <?php if (!empty($mediaFiles)): ?>
            <div class="media-grid">
                <?php foreach ($mediaFiles as $file): ?>
                    <div class="media-item">
                        <img src="<?php echo e($file['url']); ?>" alt="<?php echo e($file['name']); ?>" loading="lazy">
                        <div class="media-item-overlay">
                            <button type="button" class="media-action" data-copy="<?php echo e($file['url']); ?>" title="Copy URL"><i class="bi bi-link-45deg"></i></button>
                            <form method="post" data-confirm="Delete this image? This cannot be undone.">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="delete_file" value="<?php echo e($file['name']); ?>">
                                <button type="submit" class="media-action media-action-danger" title="Delete"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                        <div class="media-item-meta">
                            <span class="text-truncate"><?php echo e($file['name']); ?></span>
                            <span class="row-subtle"><?php echo format_file_size($file['size']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="admin-empty">
                <i class="bi bi-images"></i>
                <h6>No images yet</h6>
                <p class="mb-0">Upload your first image using the form above.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
