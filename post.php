<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$posts = json_decode(file_get_contents("data/news.json"), true) ?: [];
$post = $posts[$id] ?? null;
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $post?htmlspecialchars($post['title']):'الخبر' ?></title>
<link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header class="site-header"><div class="container header-grid"><div class="brand"><img src="assets/logo.png" class="logo"><div><h1>مستشفى الأمراض الجلدية بدمياط</h1></div></div></div></header>
<main class="container">
<?php if(!$post): ?>
  <p>الخبر غير موجود</p>
<?php else: ?>
  <article class="article-full">
    <h2><?php echo htmlspecialchars($post['title']) ?></h2>
    <time><?php echo htmlspecialchars($post['date'] ?? '') ?></time>
    <img src="<?php echo htmlspecialchars($post['image']?:'assets/placeholder.png') ?>" class="full-image" alt="">
    <div class="article-content"><?php echo nl2br(htmlspecialchars($post['content'])) ?></div>
  </article>
<?php endif; ?>
  <a href="index.php" class="back-link">العودة للأخبار</a>
</main>
<footer class="site-footer"><div class="container footer-grid"><div>© مستشفى الأمراض الجلدية بدمياط</div><div>إعداد وتنفيذ: فني تسجيل طبي وإحصاء — أحمد الديب</div></div></footer>
</body>
</html>
