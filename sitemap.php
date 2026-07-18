<?php
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc><?php echo BASE_URL; ?></loc></url>
  <url><loc><?php echo BASE_URL; ?>shop.php</loc></url>
  <url><loc><?php echo BASE_URL; ?>cart.php</loc></url>
  <url><loc><?php echo BASE_URL; ?>checkout.php</loc></url>
  <url><loc><?php echo BASE_URL; ?>blog.php</loc></url>
</urlset>
