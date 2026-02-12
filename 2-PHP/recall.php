<!-- this file is used to recall the connection to the database, so we don't have to write the connection code in every file that needs to access the database -->

<?php


    if (isset($_POST['s']))
        {
            echo "Nome:          " . $_POST['nome'] . "<br>";
            echo "E-mail:     " . $_POST['email'] . "<br>";
        }
    else
        {
            echo "FORM ACTION=" .$_SERVER['PHP_SELF'] . " METHOD=POST>";
            echo "Nome: <input type='text' name='nome'><br>
            E-mail: <input type='text' name='email'><br>;
            </table>";
        }
?>