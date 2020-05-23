<?php
if(strtr($_POST['__EVENTTARGET'],[':' => '$']) == 'ctl00$contentPlaceHolderConteudo$BuscaNomeEmpresa1$btnTodas'){
    include 'POST/busca-empresa-listada.html';
}else{
    include 'GET/busca-empresa-listada.html';
}
