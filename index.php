<?php

$me = '';
$me2 = '';
$me3 = '';

$handle = fopen('shared-items.json', "r") or die("could not read file");
while(!feof($handle)) {
    $me .= iconv("UTF-8","UTF-8",fgets($handle));
    }
fclose($handle);

$handle2 = fopen('starred-items.json', "r") or die("could not read file");
while(!feof($handle2)) {
    $me2 .= iconv("UTF-8","UTF-8",fgets($handle2));
    }
fclose($handle2);

$handle3 = fopen('notes.json', "r") or die("could not read file");
while(!feof($handle3)) {
    $me3 .= iconv("UTF-8","UTF-8",fgets($handle3));
    }
fclose($handle3);

$dec = json_decode($me,TRUE);
$dec2 = json_decode($me2,TRUE);
$dec3 = json_decode($me3,TRUE);

$mynewarray = array();
$i = 0;

foreach($dec['items'] as $row) {
    $mynewarr[$i] = $row;
    $mynewarr[$i]['activitytype'] = 'shared';
    ++$i;
    }
    
foreach($dec2['items'] as $row) {
    $mynewarr[$i] = $row;
    $mynewarr[$i]['activitytype'] = 'starred';
    ++$i;
    }
    
foreach($dec3['items'] as $row) {
    $mynewarr[$i] = $row;
    $mynewarr[$i]['activitytype'] = 'noted';
    ++$i;
    }


$newhandle = fopen('mystuff.csv',"w+") or die("could not write to file");

$mstate = 'user/10792115378921219579/state/com.google/broadcast';

foreach($mynewarr as $row) {
    $fields = array();
        $fields[] = (isset($row['commentInfo'])) ? $row['commentInfo'][$mstate]['permalinkUrl'] : ( (isset($row['origin'])) ? $row['origin']['htmlUrl'] : '' );
        $fields[] = ''.((isset($row['title'])) ? $row['title'] : ( (isset($row['origin'])) ? $row['origin']['title'] : '' ));
        $fields[] = (isset($row['alternate'])) ? $row['alternate'][0]['href'] : '';
        $fields[] = (isset($row['content'])) ? base64_encode($row['content']['content']) : '';
        $fields[] = date('Y-m-d H:i:s',$row['updated']);
        $fields[] = $row['activitytype'];
        fputcsv($newhandle,$fields);
    }
    echo count($mynewarr);
var_dump($mynewarr[0]);
fclose($newhandle);
    
?>