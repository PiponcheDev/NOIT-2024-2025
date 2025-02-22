<?php
session_start();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>БгБус</title>
    <link rel="icon" href="../media/bus-solid.svg" />
    <link rel="stylesheet" href="../css/index.css" />
    <script src="https://kit.fontawesome.com/b56d58b9c9.js" crossorigin="anonymous" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet" />
</head>
<body>
    <nav>
        <div>
            <i class="fa-solid fa-bus" id="logo"></i>
            <h1 id="name">БгБус</h1>
        </div>
        <ul>
            <li>
                <form method="GET" action="card_checker.php">
                    <button class="nav-button"><input type="submit" name="card" value="Мои карти" /></button>
                </form>
            </li>
            <li>
                <a href="logout.php"><button class="nav-button">Изход от профил</button></a>
            </li>
        </ul>
    </nav>
    <section>
        <div class="container">
            <div class="deco"></div>
            <div class="info">
                <h2>Най-екологичния начин да пътувате</h2>
                <p>
                    Пътувайте екологично с автобус! Един автобус заменя десетки коли,
                    намалявайки емисиите и задръстванията. Удобно, икономично и зелено –
                    автобусите са най-добрият избор за чисто бъдеще!
                </p>
                <form method="GET" action="card_checker.php">
                    <input type="submit" name="buy" value="Закупи карта" class="buy"/>
                </form>
            </div>
        </div>
    </section>
</body>
</html>