<?php
$xml=simplexml_load_file("sugupuu.xml");
// väljastab massivist getChildrens / будет выводить из массива getChildrens
function getPeoples($xml){
    $array=getChildrens($xml);
    return $array;
}
// väljastab laste andmed / будет выводить данные детей
function getChildrens($people){
    $result=array($people);
    $childs=$people -> lapsed -> inimene;

    if(empty($childs))
        return $result;

    foreach ($childs as $child){
        $array=getChildrens($child);
        $result=array_merge($result, $array);
    }
    return $result;
}
function getParent($peoples, $people){
    if($people== null) return null;
    foreach($peoples as $parent){
        if(!hasChilds($parent)) continue;
    foreach ($parent->lapsed->inimene as $child){
        if($child->nimi == $people->nimi){
            return $parent;
        }
    }
    }
    return null;
}
function hasChilds($people){
    return !empty($people -> lapsed -> inimene);
}
$peoples=getPeoples($xml);
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Sugupuu ülesanded</title>
</head>
<body>
<h1>Elizabeth 2 Sugupuu ülesanded</h1>
<h2>1. Trüki välja kõikide inimeste sünniaastad / Вывести все года рождения людей /</h2>
<?php
foreach ($peoples as $people){
    echo $people->attributes()->synniaasta.', ';
}

?>
<br>
<h2>2. Väljastatakse nimed, kel on vähemalt kaks last / Вывести все имена, у кого мин 2 ребенка /</h2>
<?php
    foreach ($peoples as $people){
        $lapsed = $people -> lapsed -> inimene;
        if(empty($lapsed)) continue;
        if(count($lapsed)>1){
            echo $people->nimi. ' - '.count($lapsed). ' last<br>';
        }
    }
?>

<h2>3, 4, 5...</h2>
<table border="1">
    <tr>
        <th>Vanema vanem</th>
        <th>Vanem</th>
        <th>Laps</th>
        <th>Sünniaasta</th>
        <th>Vanus</th>
    </tr>
    <?php
    foreach ($peoples as $people){
        $parent=getParent($peoples, $people);
        if(empty($parent)) continue;

        $parentOfParent=getparent($peoples, $parent);
        echo '<tr>';
        if(empty($parentOfParent)){
            echo '<td bgcolor="#b0c4de">puudub</td>';
        }else

    echo '<td>'.$parentOfParent -> nimi.'</td>';
    echo '<td>'.$parent -> nimi.'</td>';
    echo '<td>'.$people -> nimi.'</td>';
    echo '<td>'.$people->attributes()->synniaasta.'</td>';

    $yearNow=(int)date("Y"); //2022
        $childrenYear=(int)$people->attributes()->synniaasta;
        echo '<td>'.(int)($yearNow - $childrenYear).'</td>';
    echo '</tr>';
    }
    ?>
</table>
</body>
</html>
