<?php
session_start();
$pass = 'admin123'; // change as needed
if(isset($_POST['login'])){
  if($_POST['password'] === $pass){ $_SESSION['ok']=1; } else { $err='كلمة المرور خاطئة'; }
}
if(isset($_GET['logout'])){ session_destroy(); header('Location: admin.php'); exit; }
if(empty($_SESSION['ok'])){
?>
<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>دخول الإدارة</title><link rel="stylesheet" href="assets/styles.css"><body><div class="container"><h2>تسجيل الدخول لإدارة الأخبار</h2><?php if(!empty($err)) echo '<p class="error">'.htmlspecialchars($err).'</p>'; ?><form method="post"><input type="password" name="password" placeholder="كلمة المرور"><button>دخول</button></form></div></body></html><?php exit; } 

$posts = json_decode(file_get_contents("data/news.json"), true) ?: [];

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='save'){
  // handle upload
  $img = '';
  if(!empty($_FILES['image']['name'])){
    $img = time().'_'.basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$img);
  }
  $new = ['title'=>$_POST['title'],'content'=>$_POST['content'],'image'=> $img?:$_POST['image_url'],'date'=>$_POST['date']];
  if(isset($_POST['idx']) && $_POST['idx'] !== ''){ $posts[intval($_POST['idx'])] = $new; } else { array_unshift($posts,$new); }
  file_put_contents('data/news.json', json_encode(array_values($posts), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
  header('Location: admin.php'); exit;
}
if(isset($_GET['del'])){ $idx = intval($_GET['del']); if(isset($posts[$idx])){ // delete image
  if(!empty($posts[$idx]['image']) && file_exists('uploads/'.$posts[$idx]['image'])) unlink('uploads/'.$posts[$idx]['image']);
  array_splice($posts,$idx,1);
  file_put_contents('data/news.json', json_encode(array_values($posts), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
  header('Location: admin.php'); exit;
}}
?>
<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>لوحة إدارة الأخبار</title><link rel="stylesheet" href="assets/styles.css"><body>
<div class="container admin-page">
  <h2>لوحة التحكم — إدارة الأخبار</h2>
  <p><a href="admin.php?logout=1">تسجيل خروج</a></p>

  <section class="admin-form">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="idx" id="idx" value="">
      <label>العنوان</label>
      <input name="title" id="title" required>
      <label>التاريخ</label>
      <input name="date" id="date" type="date" required value="<?php echo date('Y-m-d'); ?>">
      <label>النص</label>
      <textarea name="content" id="content" rows="6" required></textarea>
      <label>رفع صورة (أو ضع رابط)</label>
      <input type="file" name="image">
      <input type="text" name="image_url" id="image_url" placeholder="https://...">
      <button type="submit">حفظ / نشر</button>
    </form>
  </section>

  <section class="posts-list">
    <h3>الأخبار الحالية</h3>
    <?php foreach($posts as $i=>$p): ?>
      <div class="post-item">
        <img src="<?php echo htmlspecialchars($p['image']?:'assets/placeholder.png') ?>" class="thumb">
        <div class="meta">
          <strong><?php echo htmlspecialchars($p['title']) ?></strong><br>
          <small><?php echo htmlspecialchars($p['date']) ?></small>
        </div>
        <div class="actions">
          <a href="?del=<?php echo $i ?>" onclick="return confirm('حذف؟')">حذف</a>
          <a href="#" onclick="editPost(<?php echo $i ?>, '<?php echo htmlentities(addslashes($p['title']))?>','<?php echo htmlentities(addslashes($p['date']))?>','<?php echo htmlentities(addslashes($p['content']))?>','<?php echo htmlentities(addslashes($p['image']))?>')">تعديل</a>
        </div>
      </div>
    <?php endforeach; ?>
  </section>
</div>

<script>
function editPost(i,title,date,content,image){
  document.getElementById('idx').value=i;
  document.getElementById('title').value=title;
  document.getElementById('date').value=date;
  document.getElementById('content').value=content;
  document.getElementById('image_url').value=image;
  window.scrollTo(0,0);
}
</script>

</body></html>
