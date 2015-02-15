<?php
if(isAdmin()) {
    db_query('UPDATE sequences SET deleted=1 WHERE id="'.$id.'"');
    header('Location: /');
}
?>