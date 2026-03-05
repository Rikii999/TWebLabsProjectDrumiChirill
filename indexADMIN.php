<?php
session_start();
include 'config.php';

// обработка админки
if(isset($_POST['admin_login'])){
    if($_POST['username']=='admin' && $_POST['password']=='1234'){
        $_SESSION['admin']=true;
    } else {
        $login_error="Неверный логин/пароль";
    }
}

// обработка формы
if(isset($_POST['submit_property'])){
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $title = $_POST['title'] ?? '';
    $price = $_POST['price'] ?? '';
    $type = $_POST['type'] ?? '';

    if(!$name || !$email || !$phone || !$title || !$price || !$type){
        $msg="Заполните все поля";
    } elseif(!str_ends_with($email,"@gmail.ru")){
        $msg="Email должен быть @gmail.ru";
    } elseif(!preg_match("/^[0-9]+$/",$phone)){
        $msg="Телефон только цифры";
    } else {
        $stmt = $conn->prepare("INSERT INTO properties (name,email,phone,title,price,type) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssds",$name,$email,$phone,$title,$price,$type);
        $stmt->execute();
        $msg="Заявка добавлена";
    }
}

// выбор страницы
$page = $_GET['page'] ?? 'menu';
$type_prefill = $_GET['type'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Real Estate</title>
<style>
body{font-family:Arial;background:#222;color:#fff;padding:20px;}
input,select,button{padding:10px;margin:5px 0;display:block;width:300px;border-radius:5px;border:none;}
button{background:#555;color:#fff;cursor:pointer;}
button:hover{background:linear-gradient(45deg,#444,#888);}
.nav{margin-bottom:20px;}
.nav a{margin-right:10px;color:#fff;text-decoration:none;padding:5px 10px;background:#444;border-radius:5px;}
.nav a:hover{background:#666;}
.message{padding:10px;margin-top:10px;background:#333;border-radius:5px;}
.menu-icons img{width:100px;height:100px;margin:10px;cursor:pointer;}
table{border-collapse:collapse;width:100%;margin-top:20px;}
td,th{border:1px solid #555;padding:8px;text-align:left;}
th{background:#444;}
</style>
</head>
<body>

<?php if($page=='menu'): ?>
    <h2>Выберите действие</h2>
    <div class="menu-icons">
        <a href="?page=form&type=sale"><img src="assets/selling.png" alt="Продажа"><br>Продажа</a>
        <a href="?page=form&type=rent"><img src="assets/renting.png" alt="Аренда"><br>Аренда</a>
        <a href="?page=form&type=buy_request"><img src="assets/buying.png" alt="Покупка"><br>Покупка</a>
        <a href="?page=admin">Админка</a>
    </div>

<?php elseif($page=='form'): ?>
    <h2>Форма заявки</h2>
    <?php if(isset($msg)) echo "<div class='message'>$msg</div>"; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Имя" required>
        <input type="email" name="email" placeholder="Email (@gmail.ru)" required>
        <input type="text" name="phone" placeholder="Телефон" required>
        <input type="text" name="title" placeholder="Название жилья" required>
        <input type="number" name="price" placeholder="Цена" required>
        <select name="type">
            <option value="sale" <?= $type_prefill=='sale'?'selected':'' ?>>Продажа</option>
            <option value="rent" <?= $type_prefill=='rent'?'selected':'' ?>>Аренда</option>
            <option value="buy_request" <?= $type_prefill=='buy_request'?'selected':'' ?>>Покупка</option>
        </select>
        <button type="submit" name="submit_property">Отправить</button>
    </form>
    <div class="nav"><a href="?page=menu">Вернуться в меню</a></div>

<?php elseif($page=='admin'): ?>
    <?php if(!isset($_SESSION['admin'])): ?>
        <h2>Вход для администратора</h2>
        <?php if(isset($login_error)) echo "<div class='message'>$login_error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" name="admin_login">Войти</button>
        </form>
    <?php else: ?>
        <h2>Админка — Все заявки</h2>
        <?php 
            $result = $conn->query("SELECT * FROM properties ORDER BY created_at DESC");
        ?>
        <table>
            <tr>
                <th>ID</th><th>Имя</th><th>Email</th><th>Телефон</th><th>Название</th><th>Цена</th><th>Тип</th><th>Дата</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['type'] ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <div class="nav"><a href="?page=menu">Вернуться в меню</a></div>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>