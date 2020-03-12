<?php
require_once 'head.php';
 
/* 過濾變數，設定預設值 */
$op = system_CleanVars($_REQUEST, 'op', 'op_list', 'string');
$sn = system_CleanVars($_REQUEST, 'sn', '', 'int');


/* 程式流程 */
switch ($op){

  case "xxx" :
    // $msg = xxx();
    break;


  default:
    $op = "op_list";
    $_SESSION['returnUrl'] = getCurrentUrl();
    op_list();
    break;  
}
/*---- 將變數送至樣版----*/

$smarty->assign("WEB", $WEB);
$smarty->assign("op", $op);
   

/*---- 程式結尾-----*/
$smarty->display('theme.tpl');

function op_list(){
    global $smarty,$db;

    $levelMenus = get_kinds("levelMenu");
    $smarty->assign("levelMenus", $levelMenus);
    
    // print_r($levelMenus);die();
    

}

function get_kinds($kind,$ofsn=0,$level=1){
    global $db;
    #層數
    $stop_level = 2;
  
    #結束條件
    if($level > $stop_level)return;
    $next_level = $level++;
    
    $sql = "SELECT *
            FROM `kinds`
            WHERE `kind`='{$kind}' and `ofsn`='{$ofsn}'
            ORDER BY `sort`
    ";//die($sql);
    $result = $db->query($sql) or die($db->error() . $sql);
    $rows=[];//array();
    while($row = $result->fetch_assoc()){ 
      $sn = (int)$row['sn'];//分類
      $ofsn = (int)$row['ofsn'];//分類
      $title = htmlspecialchars($row['title']);//標題
      $enable = (int)$row['enable'];//狀態 
      $url = htmlspecialchars($row['url']);//網址
      $target = (int)$row['target'];//外連 
     
      $sub = get_kinds($kind,$sn,$next_level);
      
      $rows[] = [
              'sn' => $sn,
              'ofsn' => $ofsn,
              'title' => $title,
              'enable' => $enable,
              'url' => $url,
              'target' => $target,
              'sub' => $sub
      ];
    }
    return $rows;
  }