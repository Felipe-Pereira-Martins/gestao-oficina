<?php 
@session_start(); // Inicia sessão (suprime erro se já iniciada)
@session_destroy(); // Destroi todas as sessões (suprime erro)
echo "<script language='javascript'> window.location='index.php' </script>"; // Redireciona para página de login
 ?>