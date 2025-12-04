<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include './../logic/equipement.php';
include './../logic/cour.php';
include './../logic/dashboard.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Salle de Sport</title>
    <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="./../styles/output.css">
</head>

<body class="bg-gray-100 p-8">

    <h1 class="text-3xl font-bold mb-6">Dashboard Salle de Sport</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold mb-2">Total Cours</h2>
            <p class="text-3xl font-bold"><?= $dashboardTotals['total_cours'] ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold mb-2">Total Equipments</h2>
            <p class="text-3xl font-bold"><?= $dashboardTotals['total_equipments'] ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold mb-2">Total Sessions</h2>
            <p class="text-3xl font-bold"><?= $dashboardTotals['total_sessions'] ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold mb-2">Total Users</h2>
            <p class="text-3xl font-bold"><?= $dashboardTotals['total_users'] ?></p>
        </div>
    </div>

    <!-- Latest Courses Table -->
    <?php if (!empty($cours)): ?>
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Derniers Cours</h2>
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Id</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Category</th>
                        <th class="border px-4 py-2">Max Capacity</th>
                        <th class="border px-4 py-2">Sessions</th>
                        <th class="border px-4 py-2">Equipements</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cours as $cour): ?>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2"><?= htmlspecialchars($cour['id']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($cour['name']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($cour['category']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($cour['max']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($cour['session_count']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($cour['equipment_count']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No cours found.</p>
    <?php endif; ?>






    <?php if (!empty($equipements)): ?>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Équipements Disponibles</h2>
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Type</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equipements as $equip): ?>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2"><?= htmlspecialchars($equip['id']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($equip['name']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($equip['type']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($equip['status']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($equip['quantity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No equipments found.</p>
    <?php endif; ?>

</body>

</html>