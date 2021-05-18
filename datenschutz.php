<!DOCTYPE html>
<?php require_once("config.php") ?>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Walter Hupfeld, info@hupfeld-software.de">
    <meta name="description" content="Georeferenzieter Ideenmelder">

    <title>Datenschutzerklärung</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

 <!--  Navbar -->
 <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#"><?= $strTitle ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
        <div class="collapse navbar-collapse" id="navbars">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?ref=1">Karte </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="liste.php">Liste</a>
                </li>
                </ul>
            
            <div>
             <ul class="navbar-nav mr-auto right">
                <li class="nav-item">
                    <a class="nav-link" href="impressum.php">Impressum</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="datenschutz.php">Datenschutzerklärung <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin/login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Ende Navbar -->

    <div class="container main">

    <p>&nbsp;</p>
    <div class="card">
        <div class="card-header"><h2>Datenschutzerklärung</h2></div>
    <div class="card-body">
    <h3>Datenschutz</h3>

    <p>Die Betreiber dieser Seiten nehmenden Schutz Ihrer persönlichen Daten sehr ernst. 
    Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend der gesetzlichen 
    Datenschutzvorschriften sowie dieser Datenschutzerklärung.</p>

    <p>Die Nutzung unserer Website ist in der Regel ohne Angabe personenbezogener Daten möglich.
    Soweit auf unseren Seiten den Namen als personenbezogenes Datum 
    erhoben, erfolgt dies auf freiwilliger Basis. Diese Daten werden
    ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben.</p>

    <p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation 
    per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff 
    durch Dritte ist nicht möglich.

    <h3>Cookies</h3>

<p>Die Internetseiten verwenden teilweise so genannte Cookies. Cookies richten auf Ihrem Rechner 
keinen Schaden an und enthalten keine Viren. Cookies dienen dazu, unser Angebot nutzerfreundlicher, 
effektiver und sicherer zu machen. Cookies sind kleine Textdateien, die auf Ihrem Rechner abgelegt 
werden und die Ihr Browser speichert.</p>

<p>Die meisten der von uns verwendeten Cookies sind so genannte „Session-Cookies“. Sie werden nach 
Ende Ihres Besuchs automatisch gelöscht. Andere Cookies bleiben auf Ihrem Endgerät gespeichert, 
bis Sie diese löschen. Diese Cookies ermöglichen es uns, Ihren Browser beim nächsten Besuch wiederzuerkennen.</p>

<p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden 
und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell 
ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browser aktivieren. 
Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.</p>

<h3>Server-Log-Files</h3>

Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten Server-Log Files, die Ihr 
Browser automatisch an uns übermittelt. Dies sind:
<ul>
    <li>Browsertyp und Browserversion</li>
    <li>verwendetes Betriebssystem</li>
    <li>Referrer URL</li>
    <li>Hostname des zugreifenden Rechners</li>
    <li>Uhrzeit der Serveranfrage</li>
</ul>
<p>Diese Daten sind nicht bestimmten Personen zuordenbar. Eine Zusammenführung dieser Daten mit anderen 
Datenquellen wird nicht vorgenommen. Wir behalten uns vor, diese Daten nachträglich zu prüfen, 
wenn uns konkrete Anhaltspunkte für eine rechtswidrige Nutzung bekannt werden.

<H3>SSL-Verschlüsselung</H3>

<p>Diese Seite nutzt aus Gründen der Sicherheit und zum Schutz der Übertragung vertraulicher Inhalte, wie zum 
Beispiel der Anfragen, die Sie an uns als Seitenbetreiber senden, eine SSL-Verschlüsselung. Eine verschlüsselte 
Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von „http:“ auf „https:“ wechselt und an dem 
Schloss-Symbol in Ihrer Browserzeile.</p>

<p>Wenn die SSL Verschlüsselung aktiviert ist, können die Daten, die Sie an uns übermitteln, 
nicht von Dritten mitgelesen werden.</p>

<h3>Recht auf Auskunft, Löschung, Sperrung</h3>

<p>Sie haben jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, 
    deren Herkunft und Empfänger und den Zweck der Datenverarbeitung sowie ein Recht auf Berichtigung, 
    Sperrung oder Löschung dieser Daten. Hierzu sowie zu weiteren Fragen zum Thema personenbezogene 
    Daten können Sie sich jederzeit unter der im Impressum angegebenen Adresse an uns wenden.</p>

<a class="btn btn-primary text-white" href="index.php?ref=1">zurück</a>

</div>
</div>

    
    </div>
</body>
</html>