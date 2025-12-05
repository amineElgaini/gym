<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include './../logic/equipement_db.php';
include './../logic/dashboard.php';
$select = getEquipementsTypeAndStatus();

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
    <div class="bg-white p-6 rounded shadow mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Derniers Cours</h2>
            <button onclick="openAddCourModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Course
            </button>
        </div>
        <div class="overflow-x-auto">
            <table id="courTable" class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Id</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Category</th>
                        <th class="border px-4 py-2">Max Capacity</th>
                        <th class="border px-4 py-2">Sessions</th>
                        <th class="border px-4 py-2">Equipements</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Equipment Table -->
    <div class="bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Équipements Disponibles</h2>
            <button onclick="openAddEquipmentModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Equipment
            </button>
        </div>
        <div class="overflow-x-auto">
            <table id="equipmentTable" class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Type</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Course Modals -->
    <!-- View Course Modal -->
    <div id="viewCourModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Course Details</h3>
            <div id="viewCourContent" class="space-y-2"></div>
            <button onclick="closeModal('viewCourModal')" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editCourModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Edit Course</h3>
            <form id="editCourForm" class="space-y-3">
                <input type="hidden" id="editCourId" name="id">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" id="editCourName" name="name" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <input type="text" id="editCourCategory" name="category" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Max Capacity</label>
                    <input type="number" id="editCourMax" name="max" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    <button type="button" onclick="closeModal('editCourModal')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div id="addCourModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Add New Course</h3>
            <form id="addCourForm" class="space-y-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <input type="text" name="category" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Max Capacity</label>
                    <input type="number" name="max" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add</button>
                    <button type="button" onclick="closeModal('addCourModal')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Equipment Modals -->
    <!-- View Equipment Modal -->
    <div id="viewEquipmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Equipment Details</h3>
            <div id="viewEquipmentContent" class="space-y-2"></div>
            <button onclick="closeModal('viewEquipmentModal')" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
        </div>
    </div>

    <!-- Edit Equipment Modal -->
    <div id="editEquipmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Edit Equipment</h3>
            <form id="editEquipmentForm" class="space-y-3">
                <input type="hidden" id="editEquipId" name="id">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" id="editEquipName" name="name" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select id="editEquipType" name="type_id" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select Type</option>
                        <?php foreach ($select['data']['types'] as  $type): ?>
                            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select id="editEquipStatus" name="status_id" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select Status</option>
                        <?php foreach ($select['data']['statuses']  as $status): ?>
                            <option value="<?= $status['id'] ?>"><?= $status['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div>
                    <label class="block text-sm font-medium mb-1">Quantity</label>
                    <input type="number" id="editEquipQuantity" name="quantity" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    <button type="button" onclick="closeModal('editEquipmentModal')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Equipment Modal -->
    <div id="addEquipmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Add New Equipment</h3>
            <form id="addEquipmentForm" class="space-y-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="type_id" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select Type</option>
                        <?php foreach ($select['data']['types'] as  $type): ?>
                            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status_id" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select Status</option>
                        <?php foreach ($select['data']['statuses']  as $status): ?>
                            <option value="<?= $status['id'] ?>"><?= $status['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Quantity</label>
                    <input type="number" name="quantity" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add</button>
                    <button type="button" onclick="closeModal('addEquipmentModal')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Course Functions
        function viewCour(cour) {
            const content = document.getElementById('viewCourContent');

            // Format equipment list
            let equipmentList = 'None';
            if (Array.isArray(cour.equipment) && cour.equipment.length > 0) {
                equipmentList = '<ul>' + cour.equipment.map(eq =>
                    `<li>
                <strong>${eq.name}</strong> | Type: ${eq.type} | Status: ${eq.status}
            </li>`
                ).join('') + '</ul>';
            }

            // Format time slots
            let timeList = 'Not specified';
            if (Array.isArray(cour.time) && cour.time.length > 0) {
                timeList = '<ul>' + cour.time.map(slot =>
                    `<li>${slot.day} | ${slot.start_time} | ${slot.time_in_minutes} min</li>`
                ).join('') + '</ul>';
            }

            content.innerHTML = `
                <p><strong>ID:</strong> ${cour.id}</p>
                <p><strong>Name:</strong> ${cour.name}</p>
                <p><strong>Category:</strong> ${cour.category}</p>
                <p><strong>Max Capacity:</strong> ${cour.max}</p>
                <p><strong>Sessions:</strong> ${cour.session_count}</p>
                <p><strong>Equipment Count:</strong> ${cour.equipment_count}</p>
                <hr>
                <p><strong>Time Slots:</strong></p>
                ${timeList}
                <hr>
                <p><strong>Equipment:</strong></p>
                ${equipmentList}
            `;

            document.getElementById('viewCourModal').classList.remove('hidden');
        }


        function editCour(cour) {
            document.getElementById('editCourId').value = cour.id;
            document.getElementById('editCourName').value = cour.name;
            document.getElementById('editCourCategory').value = cour.category;
            document.getElementById('editCourMax').value = cour.max;
            document.getElementById('editCourModal').classList.remove('hidden');
        }

        function openAddCourModal() {
            document.getElementById('addCourModal').classList.remove('hidden');
        }

        function deleteCour(id) {
            if (!confirm('Are you sure you want to delete this course?')) return;

            fetch('./../logic/cour_api.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${encodeURIComponent(id)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        const row = document.getElementById(`course-row-${id}`);
                        if (row) row.remove();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Delete course error:', error);
                    alert('An error occurred while deleting the course.');
                });
        }


        // Equipment Functions
        function viewEquipment(equip) {
            const content = document.getElementById('viewEquipmentContent');
            content.innerHTML = `
                <p><strong>ID:</strong> ${equip.id}</p>
                <p><strong>Name:</strong> ${equip.name}</p>
                <p><strong>Type:</strong> ${equip.type}</p>
                <p><strong>Status:</strong> ${equip.status}</p>
                <p><strong>Quantity:</strong> ${equip.quantity}</p>
            `;
            document.getElementById('viewEquipmentModal').classList.remove('hidden');
        }

        function editEquipment(equip) {
            document.getElementById('editEquipId').value = equip.id;
            document.getElementById('editEquipName').value = equip.name;
            document.getElementById('editEquipType').value = equip.type_id;
            document.getElementById('editEquipStatus').value = equip.status_id;
            document.getElementById('editEquipQuantity').value = equip.quantity;
            document.getElementById('editEquipmentModal').classList.remove('hidden');
        }

        function openAddEquipmentModal() {
            document.getElementById('addEquipmentModal').classList.remove('hidden');
        }

        function deleteEquipment(id) {
            if (confirm('Are you sure you want to delete this equipment?')) {
                fetch('./../logic/equipement_api.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + encodeURIComponent(id)
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.status === 'success') {
                            alert('Equipment deleted successfully!');
                            const row = document.getElementById(`equipment-row-${id}`);
                            if (row) row.remove();

                        } else {
                            alert(data.message || 'Failed to delete equipment');
                        }
                    })
                    .catch(err => console.error('Error deleting equipment:', err));
            }
        }

        // Form Submissions
        document.getElementById('editCourForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your update logic here
            console.log('Update course:', new FormData(this));
            closeModal('editCourModal');
        });

        document.getElementById('addCourForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your create logic here
            console.log('Add course:', new FormData(this));
            closeModal('addCourModal');
        });

        document.getElementById('editEquipmentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            fetch('/logic/equipement_api.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        alert(res.message);
                        loadEquipments();
                    } else {
                        alert(res.message);
                    }
                    closeModal('editEquipmentModal');
                })
                .catch(err => {
                    console.error('Update error:', err);
                    alert('An error occurred while updating.');
                    closeModal('editEquipmentModal');
                });
        });


        document.getElementById('addEquipmentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch('./../logic/equipement_api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);

                    if (data.status === "success") {
                        alert("Equipment added successfully!");

                        form.reset();

                        loadEquipments();

                        closeModal('addEquipmentModal');
                    } else {
                        alert(data.message || "Error adding equipment");
                    }
                })
                .catch(err => console.error('Error:', err));
        });

        function loadEquipments() {
            fetch('./../logic/equipement_api.php')
                .then(res => res.json())
                .then(data => {
                    if (data.status !== "success") return;

                    const tbody = document.querySelector('#equipmentTable tbody');
                    tbody.innerHTML = "";

                    data.data.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.className = "bg-gray-50";
                        tr.id = "equipment-row-" + item.id;

                        tr.innerHTML = `
                            <td class="border px-4 py-2">${item.id}</td>
                            <td class="border px-4 py-2">${item.name}</td>
                            <td class="border px-4 py-2">${item.type}</td>
                            <td class="border px-4 py-2">${item.status}</td>
                            <td class="border px-4 py-2">${item.quantity}</td>
                            <td class="border px-4 py-2">
                            <button class="edit bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">Edit</button>
                            <button onclick='deleteEquipment(${item.id})' class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                            </td>
                            `;
                            // <button onclick='viewEquipment(${item.id})' class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">View</button>

                        tr.querySelector('button.edit').addEventListener('click', () => editEquipment(item));


                        tbody.appendChild(tr);
                    });
                })
                .catch(err => console.error("Error loading equipments:", err));
        }

        function loadCours() {
            fetch('./../logic/cour_api.php')
                .then(res => res.json())
                .then(data => {
                    if (data.status !== "success") return;

                    const tbody = document.querySelector('#courTable tbody');
                    tbody.innerHTML = "";

                    data.data.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.className = "bg-gray-50";
                        tr.id = "course-row-" + item.id;

                        tr.innerHTML = `
                            <td class="border px-4 py-2">${item.id}</td>
                            <td class="border px-4 py-2">${item.name}</td>
                            <td class="border px-4 py-2">${item.category}</td>
                            <td class="border px-4 py-2">${item.max}</td>
                            <td class="border px-4 py-2">${item.session_count}</td>
                            <td class="border px-4 py-2">${item.equipment_count}</td>
                            <td class="border px-4 py-2">
                                <button onclick='viewCour(${item.id})' class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">View</button>
                                <button onclick='editCour(${item.id})' class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">Edit</button>
                                <button onclick='deleteCour(${item.id})' class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                            </td>
                        `;

                        tbody.appendChild(tr);
                    });
                })
                .catch(err => console.error("Error loading equipments:", err));
        }
        loadEquipments();
        loadCours();
    </script>

</body>

</html>