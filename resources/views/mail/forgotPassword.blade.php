<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- If you delete this tag, the sky will fall on your head -->
    <meta name="viewport" content="width=device-width" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Bienvenue - Welcome -  </title>

    <link rel="stylesheet" href="{{ asset('mail/css/welcome.css') }}">

</head>

<body bgcolor="#FFFFFF">

<!-- HEADER -->
<table class="head-wrap" bgcolor="#000000">
    <tr>
        <td style="color: #FFF;"> Mon Site ???? </td>
        <td class="header container">

            <div class="content">
                <table bgcolor="#000;">
                    <tr>
                        <td align="right"><h6 class="collapse">Bienvenue {{$email}}</h6></td>
                    </tr>
                </table>
            </div>

        </td>
        <td></td>
    </tr>
</table><!-- /HEADER -->


<!-- BODY -->
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            <div class="content">
                <table>
                    <tr>
                        <td>

                            <h3>Salut {{$email}}, </h3>
                            <p class="lead">Vous avez fait une demande de `mot de passe oublié`</p>

                            <h3>Votre mot de passe provisoir et : {{$passwordProvisoir}} pour accèder au site cliquez sur le bouton ci-dessous <small> :-p </small></h3>
                            <p>en vous souhaitons une bonne navigation sur le site.</p>
                            <a class="btn" href="#">Accèdez au site web</a>

                            <br/>
                            <br/>

                        </td>
                    </tr>
                </table>
            </div>

        </td>
        <td></td>
    </tr>
</table><!-- /BODY -->

</body>
</html>