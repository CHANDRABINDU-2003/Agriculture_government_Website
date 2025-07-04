<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$stmt = $pdo->prepare("SELECT * FROM research_projects WHERE id = ?");
$stmt->execute([$_GET['id']]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<h2><?= htmlspecialchars($project['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
<a href="research_projects.php">← Back</a>
