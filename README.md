# Hallo liebe Coder!

# Installation eines frischen Engelsystems

## Mindestvorrausetzungen (bzw. getestet unter)
 * PHP 5.4.x (cgi-fcgi)
 * PHP mcrypt
 * MySQL-Server 5.5.x
 * Webserver mit PHP-Anbindung, z.B. lighttpd, nginx oder Apache

## Installation
 * Klonen des `master` Branches inkl. submodules in lokales Verzeichnis: `git clone --recursive https://github.com/welcomehelpde/engelsystem.git`
 * Der Webserver muss Schreibrechte auf das Verzeichnis `import` bekommen, für alle anderen Dateien reichen Leserechte.
 * Der Webserver muss auf `public` als http-root zeigen.

 * Empfehlung: Dirlisting sollte deaktiviert sein.
 * Es muss eine MySQL-Datenbank angelegt werden und ein User existieren, der alle Rechte auf dieser Datenbank besitzt.
 * Es muss die db/install.sql importiert/ausgeführt werden, eventuell auch die update.php.
 * `config/config-dist.php` nach `config/config.php` kopieren und eigenen Datenbank- und SMTP-Zugang eintragen
 * Engelsystem im Browser aufrufen, Anmeldung mit admin:asdfasdf vornehmen und Admin-Passwort ändern.

Das Engelsystem ist jetzt einsatzbereit.

## Mithelfen!

Fehler bitte auf GitHub melden: https://github.com/welcomehelpde/engelsystem/issues  

Tickets lösen: https://github.com/welcomehelpde/engelsystem/issues?utf8=%E2%9C%93&q=is%3Aopen+-label%3A%22wait+for+response%22+
