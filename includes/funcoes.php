<?php
// Evita que textos cadastrados sejam interpretados como codigo HTML.
function texto($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
