<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bookmark</title>
        <meta name="viewport" content="width=device-width, initial−scale=1.0"></meta>
        <meta charset="utf-8"></meta>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> 
        <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript" lang="javascript"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="../style.css"/>
        <script type="text/javascript" lan="javascript" src="account.js"></script> 
        
    </head>
    <body class='shop_background'>
        <!--Navbar-->
        <nav class="navbar navbar-expand-md navbar-expand-lg navbar-dark bg-dark sticky-top">
            <!--Logo-->
            <a class="navbar-brand" href="../homepage.php"><h5 class="navbarlogo">BOOKMARK</h5></a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="collapse navbar-collapse" id="navbarResponsive">
                <!--Menù-->
                <ul class="navbar-nav ml-auto">
                    <li id="nav1" class="nav-item">
                        <a class="nav-link" href="../homepage.php">HOME</a>
                    </li>
                    <li id="nav2" class="nav-item active">
                        <a class="nav-link" href="../shop/shop.php">SHOP</a>
                    </li>
                    <li id="nav3" class="nav-item">
                        <a class="nav-link" href="../faq/faq.php">FAQ</a>
                    </li>
                    <?php 
                        if(isset($_SESSION['username'])) {
                            echo "<li class='nav-item dropdown'>
                            <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownMenuLink' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            CIAO, ".$_SESSION['username']."
                            </a>
                            <div class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>
                              <a class='dropdown-item' href='account.php'>Account</a>
                              <div class='dropdown-divider'></div>
                              <a class='dropdown-item' href='../logout.php'>Logout</a>
                            </div>
                          </li>"; 
                          }
                          else{
                            echo "<li id='nav3' class='nav-item'>
                            <a class='nav-link' href='../login/login.html'>LOGIN</a>
                            </li>";
                        }
                    ?>
                </ul>
            </span>
        </nav>
        <?php
            $dbconn= pg_connect("host=localhost port=5432 dbname=Bookmark user=postgres password=postgres")
            or die('Could not connect:'. pg_last_error());
        
            $ql="select * from users where name=$1";
            $result=pg_query_params($dbconn,$ql,array($_SESSION['username']));
            $line= pg_fetch_array($result, null, PGSQL_ASSOC);
        
            echo "<h2 class='h2-w font-weight-bolder'>Il tuo profilo, ".$_SESSION['username']."</h2>";
        ?>
        <form name="accountForm" action="changes.php" method="POST">

            <table class="table">
                <tr>
                    <td>
                        <b><h5 class="h5-w font-weight-bolder">Indirizzo email:</h5></b>
                    </td>
                    <td>
                        <?php
                            if(isset($_GET['change_em'])) echo "<h6 class=' font-weight-bolder'><font color='red'>Email già esistente.</font></h6>";
                            echo "<input class='input_change' name='email' size='40' type='email' value='".$line['email']."' onkeyup='checkEmail()'></input>";
                        ?>
                    </td>
                    
                    <td>
                        <input class="btn btn-light" type="submit" name="subemail" id="button1" value="Modifica" disabled>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><h5 class="h5-w font-weight-bolder">Username:</h5></b>
                    </td>
                    <td>
                        <?php
                            if(isset($_GET['change_us'])) echo "<h6 class=' font-weight-bolder'><font color='red'>Username non disponibile.</font></h6>";
                            echo "<input class='input_change' name='username' type='text' value='".$line['name']."' onkeyup='disable_but()'></input>";
                        ?>
                    </td>
                    <td>
                        <input class="btn btn-light" type="submit"  id ="button2" name="subuser" value="Modifica" disabled>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><h5 class="h5-w font-weight-bolder">Password:</h5></b>
                    </td>
                    <td>
                        <?php
                        
                            echo "<input id='password1' name='password' class='input_change' type='password' value='".$line['password']."' onclick='clean()' onkeyup='checkPassword()'></input>";
                        ?>
                        <button class="mycheckbox" type="button" id="ckbox" name="ckbox" onclick="show1()" disabled>
                            <i id="icon" class="fa fa-eye-slash"></i>
                        </button>
                    </td>
                    <td>
                        <input class="btn btn-light" type="submit" id="button3" name="subpass" value="Modifica" disabled>
                    </td>
                </tr>
                <tr>
                    <td></td><td></td>
                    <td>
                        <input class="btn btn-light" type="submit" name="subremove" value="Elimina account"><br>
                        <b><h7 class="h5-w font-weight-bolder">Nota: L'azione <br>è IRREVERSIBILE.</h7></b>
                    </td>
                </tr>

            </table>
            
        </form>

            <h4 class='h4-w font-weight-bolder'>I tuoi preferiti:</h4>
            <div class='container text-left'>
            <table class='table table-dark table-striped' >
                
                <?php
                    $ql="select * from lovesbook,books where username=$1 and book=books.name";
                    $result=pg_query_params($dbconn,$ql,array($_SESSION['username']));
                    if($line= pg_fetch_array($result, null, PGSQL_ASSOC)){

                        
                        $result=pg_query_params($dbconn,$ql,array($_SESSION['username']));
                        while($line= pg_fetch_array($result, null, PGSQL_ASSOC)){

                            $title = str_replace(" ","-",$line['name']);
                            $author = str_replace(" ","-",$line['author']);
                            $genre = $line['genre'];

                            echo "<tr>";
                            echo "<td><a href=../shop/details.php?title=".$title."&author=".$author."&genre=".$genre."&img=".$line["img"].">
                            <img class='img-wishlist img_found' src='../images/covers/".$line['img']."'></a></td>
                            <td><h5 class='h5-w title-wishlist'>".$line['book']."</h5></td>
                            <td><h5 class='h5-w author-wishlist'>".$line['author']."</h5></td>
                            <td><h5 class='h5-w genre-wishlist'>".$line['genre']."</h5></td>";
                            echo "</tr>";
                        }
                        
                    }
                    else{
                        echo "<tr><h4 class='h4-w font-weight-bolder'>Non hai alcun preferito...</h4></tr>";
                    }
                ?>
            </table>

            </div>


            <h4 class='h4-w font-weight-bolder'>Le tue prenotazioni:</h4>
            <div class='container text-left'>
            <table class='table table-dark table-striped' >
                
                <?php
                    $ql="select * from booking,books,libraries where username=$1 and book=books.name and libraries.name=library";
                    $result=pg_query_params($dbconn,$ql,array($_SESSION['username']));
                    if($line= pg_fetch_array($result, null, PGSQL_ASSOC)){

                        
                        $result=pg_query_params($dbconn,$ql,array($_SESSION['username']));
                        while($line= pg_fetch_array($result, null, PGSQL_ASSOC)){
                            echo "<tr>";
                            echo "<td><img class='img-wishlist img_found' src='../images/covers/".$line['img']."'></td>
                            <td><h5 class='h5-w book-item'>".$line['book']."</h5></td>
                            <td><h5 class='h5-w book-item'>".$line['library']."</h5></td>
                            <td><h5 class='h5-w book-item'>".$line['date']."</h5></td>";
                            echo "</tr>";
                        }
                        
                    }
                    else{
                        echo "<tr><h4 class='h4-w font-weight-bolder'>Non hai effettuato prenotazioni ancora</h4></tr>";
                    }

                ?>
            </table>

            </div>

            <div>
                
            </div>
            
            <script>
                function disable_but(){
                    document.getElementById('button2').disabled=false;
                }
            </script>

        
        
        <!--Footer-->
        <footer class="text-center text-lg-start bg-dark text-muted">
            <br>
            <p>  Powered by <a href="https://www.uniroma1.it/it/pagina-strutturale/home" title="LaSapienza" target="_blank">Università La Sapienza</a> – Linguaggi e Tecnologie per il Web</p>
            <br>
        </footer>
    </body>
</html>