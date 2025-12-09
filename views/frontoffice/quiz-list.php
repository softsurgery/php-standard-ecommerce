<?php
require_once __DIR__ . '/../../controllers/QuizController.php';

$controller = new QuizController();
$quizzes = $controller->getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Available Quizzes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">

    <h1 class="text-3xl font-bold mb-6">Available Quizzes</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($quizzes as $quiz): ?>
            <a href="./quiz-submission.php?id=<?= $quiz['id'] ?>"
                class="block p-4 bg-white rounded-lg shadow hover:shadow-lg transition">

                <h2 class="text-xl font-semibold"><?= htmlspecialchars($quiz['name']); ?></h2>

                <?php if (!empty($quiz['description'])): ?>
                    <p class="text-gray-600 mt-2"><?= htmlspecialchars($quiz['description']); ?></p>
                <?php endif; ?>

                <p class="mt-3 text-blue-600 font-medium">
                    Start Quiz →
                </p>
            </a>
        <?php endforeach; ?>
    </div>

</body>

</html>