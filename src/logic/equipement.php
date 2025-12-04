<?php
require_once 'equipement_db.php';

$equipements = getEquipements();

// ===== Add equipment =====
// if (isset($_POST['action']) && $_POST['action'] === 'add') {
//     $nom = trim($_POST['nom']);
//     $type = trim($_POST['type']);
//     $quantite = intval($_POST['quantite']);
//     $etat = $_POST['etat'];

//     // Simple validation
//     $errors = [];
//     if (empty($nom)) $errors[] = "Le nom est requis.";
//     if (empty($type)) $errors[] = "Le type est requis.";
//     if ($quantite < 0) $errors[] = "La quantité doit être positive.";
//     if (!in_array($etat, ['bon','moyen','a remplacer'])) $errors[] = "État invalide.";

//     if (empty($errors)) {
//         $success = addEquipement($nom, $type, $quantite, $etat);
//         if ($success) {
//             echo "Équipement ajouté avec succès.";
//         } else {
//             echo "Erreur lors de l'ajout.";
//         }
//     } else {
//         foreach($errors as $error) {
//             echo $error . "<br>";
//         }
//     }
// }

// // ===== Update equipment =====
// if (isset($_POST['action']) && $_POST['action'] === 'update') {
//     $id = intval($_POST['id']);
//     $nom = trim($_POST['nom']);
//     $type = trim($_POST['type']);
//     $quantite = intval($_POST['quantite']);
//     $etat = $_POST['etat'];

//     // Validation
//     $errors = [];
//     if (empty($nom)) $errors[] = "Le nom est requis.";
//     if (empty($type)) $errors[] = "Le type est requis.";
//     if ($quantite < 0) $errors[] = "La quantité doit être positive.";
//     if (!in_array($etat, ['bon','moyen','a remplacer'])) $errors[] = "État invalide.";

//     if (empty($errors)) {
//         $success = updateEquipement($id, $nom, $type, $quantite, $etat);
//         if ($success) {
//             echo "Équipement mis à jour avec succès.";
//         } else {
//             echo "Erreur lors de la mise à jour.";
//         }
//     } else {
//         foreach($errors as $error) {
//             echo $error . "<br>";
//         }
//     }
// }

// // ===== Delete equipment =====
// if (isset($_GET['action']) && $_GET['action'] === 'delete') {
//     $id = intval($_GET['id']);
//     $success = deleteEquipement($id);
//     if ($success) {
//         echo "Équipement supprimé avec succès.";
//     } else {
//         echo "Erreur lors de la suppression.";
//     }
// }

// ===== Get all equipments (for listing) =====
?>
