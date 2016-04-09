<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="description" content="Peel Scraping Engine">
  <link rel="stylesheet" href="css/peel.css">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
  <section class="login nicebox">
    <h1>Peel login</h1>
    <form method="POST" action=".">
      <div><label for="u">Brukernavn</label><input type="text" id="u" name="u" /></div>
      <div><label for="p">Passord</label><input type="password" id="p" name="p" /></div>
      <div><input type="submit" value="Log in" /></div>
    </form>
  </section>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>
  <script>
    $( document ).ready( function() {
      $( '#u' ).focus();;
    } )
  </script>
</body>
</html>