<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// OpenLinker is a web based library system designed to manage 
// journals, ILL, document delivery and OpenURL links
// 
// Copyright (C) 2012, Pablo Iriarte
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// Units table : creation and update of records
// 
require ("config.php");
require ("authcookie.php");
if (!empty($_COOKIE[illinkid]))
{
$action2="";
$action="";
$id=addslashes($_POST['id']);
$ip = $_SERVER['REMOTE_ADDR'];
$action=addslashes($_POST['action']);
$action2=addslashes($_GET['action']);
if ($action2!="")
$action = $action2;
if (($monaut == "admin")||($monaut == "sadmin"))
{
$mes="";
$date=date("Y-m-d H:i:s");
$code = addslashes(trim($_POST['code']));
$name1 = addslashes(trim($_POST['name1']));
$name2 = addslashes(trim($_POST['name2']));
$name3 = addslashes(trim($_POST['name3']));
$name4 = addslashes(trim($_POST['name4']));
$name5 = addslashes(trim($_POST['name5']));
$library = addslashes(trim($_POST['library']));
$unitdepartment = addslashes(trim($_POST['department']));
$unitdepartmentnew = addslashes(trim($_POST['departmentnew']));
if ($unitdepartment == "new")
$unitdepartment = $unitdepartmentnew;
$unitfaculty = addslashes(trim($_POST['faculty']));
$unitfacultynew = addslashes(trim($_POST['facultynew']));
if ($unitfaculty == "new")
$unitfaculty = $unitfacultynew;
$unitip1 = addslashes(trim($_POST['ip1']));
$unitip2 = addslashes(trim($_POST['ip2']));
$unitipext = addslashes(trim($_POST['ipext']));
if ($unitip1 != "1")
$unitip1 = 0;
if ($unitip2 != "1")
$unitip2 = 0;
if ($unitipext != "1")
$unitipext = 0;
$validation = $_POST['validation'];
if ($validation != "1")
$validation = 0;
if (($action == "update")||($action == "new"))
{
// Tester si le code est unique
require ("connect.php");
$reqcode = "SELECT * FROM units WHERE units.code = '$code'";
$resultcode = mysql_query($reqcode,$link);
$nbcode = mysql_num_rows($resultcode);
$enregcode = mysql_fetch_array($resultcode);
$idcode = $enregcode['id'];
if (($nbcode == 1)&&($action == "new"))
$mes = $mes . "<br/>le code '" . $code . "' existe déjà dans la base, veuillez choisir un autre";
if (($nbcode == 1)&&($action != "new")&&($idcode != $id))
$mes = $mes . "<br/>le code '" . $code . "' est déjà attribué à une autre unité , veuillez choisir un autre";
if ($name1 == "")
$mes = $mes . "<br/>le nom1 est obligatoire";
if ($code == "")
$mes = $mes . "<br/>le code est obligatoire";

if ($mes != "")
{
require ("headeradmin.php");
echo "<center><br/><b><font color=\"red\">\n";
echo $mes."</b></font>\n";
echo "<br /><br /><a href=\"javascript:history.back();\"><b>retour au formulaire</a></b></center><br /><br /><br /><br />\n";
require ("footer.php");
}
else
{
// 
// Début de l'édition
//
if ($action == "update")
{
if ($id != "")
{
require ("connect.php");
require ("headeradmin.php");
$reqid = "SELECT * FROM units WHERE id = '$id'";
$myhtmltitle = $configname[$lang] . " : édition de la fiche unité " . $id;
$resultid = mysql_query($reqid,$link);
$nb = mysql_num_rows($resultid);
if ($nb == 1)
{
$enregid = mysql_fetch_array($resultid);
$query = "UPDATE units SET units.name1='$name1', units.name2='$name2', units.name3='$name3', units.name4='$name4', units.name5='$name5', units.library='$library', units.code='$code', units.department='$unitdepartment', units.faculty='$unitfaculty', units.internalip1display=$unitip1, units.internalip2display=$unitip2, units.externalipdisplay=$unitipext, units.validation=$validation WHERE units.id=$id";
$resultupdate = mysql_query($query) or die("Error : ".mysql_error());
echo "<center><br/><b><font color=\"green\">\n";
echo "La modification de la fiche " . $id . " a été enregistrée avec succès</b></font>\n";
echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste de unités</a></center>\n";
require ("footer.php");
}
else
{
echo "<center><br/><b><font color=\"red\">\n";
echo "La modification n'a pas été enregistrée car l'identifiant de la fiche " . $id . " n'a pas été trouvée dans la base.</b></font>\n";
echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
require ("footer.php");
}
}
else
{
require ("headeradmin.php");
require ("menurech.php");
echo "<center><br/><b><font color=\"red\">\n";
echo "La modification n'a pas été enregistrée car il manque l'identifiant de la fiche</b></font>\n";
echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche</b></center><br /><br /><br /><br />\n";
require ("footer.php");
}
}
}
// 
// Fin de l'édition
//
// Début de la création
//
if ($action == "new")
{
require ("connect.php");
require ("headeradmin.php");
$myhtmltitle = $configname[$lang] . " : nouvelle unité ";
$query = "INSERT INTO `units` (`id`, `name1`, `name2`, `name3`, `name4`, `name5`, `code`, `library`, `department`, `faculty`, `internalip1display`, `internalip2display`, `externalipdisplay`, `validation`) ";
$query .= "VALUES ('', '$name1', '$name2', '$name3', '$name4', '$name5', '$code', '$library', '$unitdepartment', '$unitfaculty', $unitip1, $unitip2, $unitipext, $validation)";
$result = mysql_query($query) or die("Error : ".mysql_error());
$id = mysql_insert_id();
echo "<center><br/><b><font color=\"green\">\n";
echo "La nouvelle fiche " . $id . " a été enregistrée avec succès</b></font>\n";
echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste de unités</a></center>\n";
echo "</center>\n";
echo "\n";
require ("footer.php");
}
}
// 
// Fin de la création
//
// Début de la suppresion
//
if ($action == "delete")
{
$id=addslashes($_GET['id']);
$myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une unité ";
require ("headeradmin.php");
echo "<center><br/><br/><br/><b><font color=\"red\">\n";
echo "Voulez-vous vraiement supprimer la fiche " . $id . "?</b></font>\n";
echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
echo "<input name=\"table\" type=\"hidden\" value=\"units\">\n";
echo "<input name=\"id\" type=\"hidden\" value=\"".$id."\">\n";
echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
echo "<br /><br />\n";
echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . $id . " en cliquant ici\">\n";
echo "</form>\n";
echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste des unités</a></center>\n";
echo "</center>\n";
echo "\n";
require ("footer.php");
}
if ($action == "deleteok")
{
$myhtmltitle = $configname[$lang] . " : supprimer une unité ";
require ("connect.php");
require ("headeradmin.php");
$query = "DELETE FROM units WHERE units.id = '$id'";
$result = mysql_query($query) or die("Error : ".mysql_error());
echo "<center><br/><b><font color=\"green\">\n";
echo "La fiche " . $id . " a été supprimée avec succès</b></font>\n";
echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste des unités</a></center>\n";
echo "</center>\n";
echo "\n";
require ("footer.php");
}
// 
// Fin de la saisie
//
}
else
{
require ("header.php");
echo "<center><br/><b><font color=\"red\">\n";
echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
require ("footer.php");
}
}
else
{
require ("header.php");
require ("codefail.php");
require ("footer.php");
}
?>
