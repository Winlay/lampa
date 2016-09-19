<?php

$nbElementPage = 10;
  /**/
function createEmbed($link) {
  $parts=  explode("watch?v=", $link);
  $newLien=$parts[0]."embed/".$parts[1];
  return $newLien; 
  } 
function connexion($login, $password) {
    $sql = "select u.* from user u where u.login= '$login' AND u.password= '$password'";
    $userConnected = array();
    $resultat = mysql_query($sql) or die(mysql_error());
    while ($result = mysql_fetch_assoc($resultat)) {
        $userConnected = $result;
    }
    if (count($userConnected) > 0) {

        return $userConnected;
    }
    return NULL;
}

function getList($table, $params = array(), $debut = -1, $limite = -1, $orderBy = "") {
    $sql = "select * from " . $table . " where 1=1 ";
    if (is_array($params) && count($params) > 0) {
        foreach ($params as $key => $value) {
            if ($value != "" || $value==0) {
                if ($key == 'id_entreprise' && ($table == 'tracking' || $table == 'envoi_message')) {
                    $sql.= " AND id_user IN (select id_user from user where id_entreprise = " . $value . " )";
                } elseif ($key == 'date_debut') {
                    $sql.= " AND date >='" . convertDate($value) . "' ";
                } elseif ($key == 'date_fin') {
                    $sql.= " AND  date <='" . convertDate($value) . "' ";
                } elseif ($key == 'telephone' || $key == 'message') {
                    $sql.= " AND  " . $key . " like '%" . $value . "%' ";
                } else {
                    $sql.= " AND " . $key . "='" . $value . "' ";
                }
            }
        }
    }

    $list = array();
// print_r($sql);
    if (isset($orderBy) && $orderBy != "")
        $sql.=" ORDER BY " . $orderBy." DESC";
    else
        $sql.=" ORDER BY created DESC";
    if ($debut != -1 || $limite != -1) {
        $sql .= makeLimite($debut, $limite);
    }

    $resultat = mysql_query($sql) or die(mysql_error());
    while ($result = mysql_fetch_assoc($resultat)) {
        $list[] = $result;
    }
    return $list;
}
function getListArticle($category) {
    $sql = "select a.* FROM articles a, categories c where a.etat=1 AND a.category_id=c.id AND c.slug='".$category."'";

    $list = array();
    $resultat = mysql_query($sql) or die(mysql_error());
    while ($result = mysql_fetch_assoc($resultat)) {
        $list[] = $result;
    }
    return $list;
}

function countElements($table, $params = array()) {
    $sql = "select * from " . $table . " where 1=1 ";
    if (is_array($params) && count($params) > 0) {
        foreach ($params as $key => $value) {
            if ($value != "") {
                if ($key == 'id_entreprise' && ($table == 'tracking' || $table == 'envoi_message')) {
                    $sql.= " AND id_user IN (select id_user from user where id_entreprise = " . $value . " )";
                } elseif ($key == 'date_debut') {
                    $sql.= " AND date >='" . convertDate($value) . "' ";
                } elseif ($key == 'date_fin') {
                    $sql.= " AND  date <='" . convertDate($value) . "' ";
                } else {
                    $sql.= " AND " . $key . "='" . $value . "' ";
                }
            }
        }
    }

    $list = array();

    $resultat = mysql_query($sql) or die(mysql_error());
    while ($result = mysql_fetch_assoc($resultat)) {
        $list[] = $result;
    }
    return count($list);
}

function delete($table, $params = array()) {
    $sql = "delete from " . $table . " where 1=1 ";
    if (is_array($params) && count($params) > 0) {
        foreach ($params as $key => $value) {
            $sql.= " AND " . $key . "='" . $value . "' ";
        }
    }

    mysql_query($sql) or die(mysql_error());
}


function enregistrer_user($params = array()) {
    $tab_keys = array();
    $tab_values = array();
    if (is_array($params) && count($params) > 0) {
        foreach ($params as $key => $value) {
            $tab_keys[] = $key;
            $tab_values[] = "'" . strip_tags($value) . "'";
        }
    }
    $sql = "insert into acheteur ";
    $sql.='(' . implode(',', $tab_keys) . ')';
    $sql.=' values (' . implode(',', $tab_values) . ')';

    $resultat = mysql_query($sql) or die(mysql_error());
    $id = mysql_insert_id();
    return $id;
}

function ajouter($table, $params = array()) {
    $tab_keys = array();
    $tab_values = array();
    // $date=false;
    if (is_array($params) && count($params) > 0) {
        foreach ($params as $key => $value) {
            $tab_keys[] = $key;
            $tab_values[] = "'" . mysql_real_escape_string($value) . "'";
        }
    }
    $sql = "insert into " . $table . " ";
    $sql.='(date,' . implode(',', $tab_keys) . ')';
    $sql.=' values (now(),' . implode(',', $tab_values) . ')';
//print_r($sql.'<br/>');
    $resultat = mysql_query($sql) or die(mysql_error());
    $id = mysql_insert_id();
    return $id;
}

function update($table, $id, $params = array()) {
    $set = " SET ";
    $i = 1;
    if (is_array($params) && count($params) > 0) {
        foreach ($params as $key => $value) {
            $set.=$key . "='" . str_replace("'", "\'", strip_tags($value)) . "'";
            if ($i != count($params))
                $set.=",";
            $i++;
        }
    }
    $sql = "update $table ";

    $sql.= $set . ' where id=' . $id;
    $resultat = mysql_query($sql) or die(mysql_error());
    return $resultat;
}

function getListUtilisateurs($params = array(), $groupeBy = "") {
    $sql = "select * from user where 1=1 ";
    if (is_array($params) && count($params) > 0) {
        foreach ($params as $key => $value) {
            $sql.= " AND " . $key . "=" . $value . " ";
        }
    }

    $list = array();
    $resultat = mysql_query($sql) or die(mysql_error());
    while ($result = mysql_fetch_assoc($resultat)) {
        $list[] = $result;
    }
    /*  if (count($list) > 0) {
      $listUsers = array();
      foreach ($list as $user) {

      $req = " select role from roles where id_user=" . $user['id'];
      $result = mysql_query($req) or die(mysql_error());
      while ($resultat = mysql_fetch_assoc($result)) {
      $user['roles'][] = $resultat['role'];
      }
      $listUsers[] = $user;
      }
      //        return $listUsers;
      } */
    return $list;
}

function nbJours($debut, $fin) {
    //60 secondes X 60 minutes X 24 heures dans une journée
    $nbSecondes = 60 * 60 * 24;

    $debut_ts = strtotime($debut);
    $fin_ts = strtotime($fin);
    $diff = $fin_ts - $debut_ts;
    return round($diff / $nbSecondes);
}

function truncate($string, $limit, $break = "", $pad = "...") {
    // return with no change if string is shorter than $limit 
    if (strlen($string) <= $limit)
        return $string; // is $break present between $limit and the end of the string? 
    else {
        $string = substr($string, 0, $limit) . $pad;
    }
    return $string;
}

$listeMois = array(1 => "Janvier", 2 => "Fevrier", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin", 7 => "Juillet", 8 => "Aout", 9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre");

function compareDate($date1, $date2) {
    $d1 = explode("-", $date1);
    $d2 = explode("-", $date2);
    $dateChif1 = $d1[0] . $d1[1] . $d1[2];
    $dateChif2 = $d2[0] . $d2[1] . $d2[2];
    $df1 = (float) $dateChif1;
    $df2 = (float) $dateChif2;
    //print_r($dateChif1);print_r('<br/>');print_r($dateChif2);
    if ($df1 > $df2)
        return true;
    else
        return false;
}

function plusRecente($date1, $date2) {
    $d1 = new DateTime($date1);
    $d2 = new DateTime($date2);
    return $d1 > $d2;
}

function compareDate2($date1, $date2) {
    $d1 = new DateTime($date1);
    $d2 = new DateTime($date2);
    return $d1 > $d2;
}

function ajouterUnMois($date) {
    $newdate = strtotime('+1 month', strtotime($date));
    $new = date('Y-m-j', $newdate);
    return $new;
}

function convertDate($strDate, $lang = "fr") {
    if (strlen($strDate) != 10)
        return null;
    if ($lang == "en") { // fr vers en
        $splitDate = explode('/', $strDate);
        return $splitDate[2] . "-" . $splitDate[1] . "-" . $splitDate[0];
    } else { // en vers fr
        $splitDate = explode('-', $strDate);
        return $splitDate[2] . "/" . $splitDate[1] . "/" . $splitDate[0];
    }
}

function makeLimite($debut, $limite) {

    return ' LIMIT ' . (int) $limite . ' OFFSET ' . (int) $debut;
}



?>