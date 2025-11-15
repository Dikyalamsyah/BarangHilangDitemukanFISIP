<?php
require_once __DIR__ . '/../inc/auth.php';
require_admin();
require_once __DIR__ . '/../inc/db.php';

$q = trim($_GET['q'] ?? '');
$cat = trim($_GET['cat'] ?? '');
$st  = trim($_GET['status'] ?? '');

$where = " WHERE 1=1 ";
$params = []; $types = "";
if($q !== ''){ $where .= " AND (title LIKE CONCAT('%',?,'%') OR location LIKE CONCAT('%',?,'%')) "; $params[]=$q; $params[]=$q; $types.="ss"; }
if($cat !== ''){ $where .= " AND category = ? "; $params[]=$cat; $types.="s"; }
if($st  !== ''){ $where .= " AND status = ? "; $params[]=$st; $types.="s"; }

$sql = "SELECT id,type,title,category,location,reporter_name,reporter_contact,status,created_at FROM items " . $where . " ORDER BY id DESC";
$stmt = $mysqli->prepare($sql);
if($types!==""){ $stmt->bind_param($types, ...$params); }
$stmt->execute();
$res = $stmt->get_result();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="items_export.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['id','type','title','category','location','reporter_name','reporter_contact','status','created_at']);
while($row=$res->fetch_assoc()){ fputcsv($out, $row); }
fclose($out); exit;
