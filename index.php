<?php
$posts = json_decode(file_get_contents("data/news.json"), true) ?: [];
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>مستشفى الأمراض الجلدية بدمياط - الأخبار</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header class="site-header">
  <div class="container header-grid">
    <div class="brand">
      <img src="assets/logo.png" alt="لوجو" class="logo">
      <div>
        <h1>مستشفى الأمراض الجلدية بدمياط</h1>
        <p class="tagline">أحدث الأخبار والإعلانات — صحة أفضل للجميع</p>
      </div>
    </div>
    <nav class="top-nav">
      <a href="index.php" class="active">الأخبار</a>
      <a href="org.php">الهيكل التنظيمي</a>
      <a href="admin.php">لوحة التحكم</a>
    </nav>
  </div>
</header>

<main class="container">
  <section class="hero">
    <h2>آخر الأخبار</h2>
    <p class="hero-sub">تابع أحدث أنشطة المستشفى، الفعاليات، والإعلانات الرسمية</p>
  </section>

  <section class="news-grid">
<?php if(empty($posts)): ?>
    <div class="empty">لا توجد أخبار حالياً.</div>
<?php else: 
  // sort by date desc if date present
  usort($posts, function($a,$b){
    return strcmp($b['date'] ?? '', $a['date'] ?? '');
  });
  foreach($posts as $i=>$p): ?>
    <article class="card">
      <a href="post.php?id=<?php echo $i ?>">
        <div class="card-image" style="background-image:url('<?php echo htmlspecialchars($p['image']?:'assets/placeholder.png') ?>')"></div>
        <div class="card-body">
          <h3><?php echo htmlspecialchars($p['title']) ?></h3>
          <time><?php echo htmlspecialchars($p['date'] ?? '') ?></time>
          <p><?php echo nl2br(htmlspecialchars(mb_substr($p['content'],0,180))) ?>...</p>
        </div>
      </a>
    </article>
<?php endforeach; endif; ?>
  </section>
</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div>© جميع الحقوق محفوظة — مستشفى الأمراض الجلدية بدمياط</div>
    <div>إعداد وتنفيذ: فني تسجيل طبي وإحصاء — أحمد الديب</div>
  </div>
</footer>
</body>
</html>
