<!-- 
    Esempio di form che ricorda i dati inseriti
    Se l'utente ha già inserito i dati, questi vengono visualizzati
    altrimenti viene visualizzato il form per l'inserimento dei dati
-->

<?php
    if (isset($_POST['s']))
    {
        echo "Nome: " . $_POST['username'] . "<BR>";
        echo "Casella di posta: " . $_POST['email'] . "<BR>";
    }
    else
    {
        echo "<FORM ACTION='" . $_SERVER['PHP_SELF'] . "' METHOD='POST'>";
        echo "<TABLE><TR><TD>Nome utente:</TD>";
        echo "<TD><INPUT TYPE='text' NAME='username'></TD></TR>";
        echo "<TR><TD>Email:</TD>";
        echo "<TD><INPUT TYPE='text' NAME='email'></TD></TR>";
        echo "</TABLE>";
        echo "<INPUT TYPE='submit' NAME='s' VALUE='invia'>";
        echo "</FORM>";
    }
?>