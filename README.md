#  Benutzersynchronisierung

## Die  Benutzersynchronisierung verknüpft Benutzerprofile über mehrere einzelne WordPress-Installationen hinweg.

Erstelle und verwalte Benutzerkonten auf einer Hauptliste, mit der Du eine Verbindung zu einer beliebigen Unterwebseite herstellen kannst - ohne Multisite.

### Intelligente, sichere, Benutzerverwaltung

Durch die gemeinsame Nutzung und Synchronisierung von Profil, Kennwort, Benutzerrolle und E-Mail-Adresse wird die Benutzerverwaltung an einen praktischen Ort verschoben. Verbinde mehr Personen mit mehr Inhalten, indem Du Benutzern erlaubst, sich einmal in einer Hauptliste zu registrieren und dieselben Anmeldeinformationen für alle angehängten Webseiten zu verwenden. Schütze Deine Benutzer mit kugelsicheren Sicherheitsschlüsseln. Lasse Webseiten für die fortlaufende Kontinuität verbunden oder übertrage Benutzer und trenne die Verbindung. Verwende die Deinstallation und lösche Deine Datenbank vollständig von Plugin-Artefakten.


### Hebelkontrolle  und Einfachheit

Die Konfiguration dauert nur wenige Sekunden und erfordert nur eine URL und einen Sicherheitsschlüssel. Verwende Synchronize-Overwrite für eine identische Übereinstimmung mit der Hauptliste oder unterstütze die Kompatibilität mit Rückwärtsbenutzerkennwörtern auf vorhandenen Webseiten, indem Du Duplikate zulässt.


Du kannst einen Benutzer auch von einer bestimmten Unterwebseite verbannen, indem Du verhinderst, dass gelöschte Benutzer bei der Synchronisierung mit der Hauptliste erneut gefüllt werden. Schütze Identitäten, bleibe synchron und spare unzählige Stunden mit der Benutzersynchronisierung. Erhalte eine verknüpfte Benutzerverwaltung zum Übertragen von Benutzerkonten von einer Hauptliste auf alle Deine WordPress basierenden-Webseiten.

##  Verwendung

###  So installierst Du:

1. Lade die Plugin-Datei herunter. 2 \. Entpacke die Datei in einen Ordner auf Deiner Festplatte. 3 \. Lade den Ordner ** / benutzer-sync / ** in den Ordner ** /wp-content/plugins/ ** auf den Webseiten hoch, zwischen denen Du synchronisieren möchtest. 4 \. Melde Dich bei Deinem Admin-Panel für Deine Webseite an und aktiviere das User Synchronisztion-Plugin in ** Plugins ** .

###  Zu verwenden:

Sobald das Plugin aktiviert ist, wird ein neues "User Sync" -Menü hinzugefügt. 1. Gehe im Dashboard der Webseite, die Du zur Master-Site machen möchtest, zu ** User Sync ** .

*    Dies ist die übergeordnete Webseite, mit der alle Benutzer mit Deinen anderen Webseiten synchronisiert/dupliziert werden

2. Klicke auf ** Diese Webseite zur Master-Seite machen ** 

 3. Jetzt werden Deine Master-Seiten-URL und Dein Sicherheitsschlüssel angezeigt. 

4. Melde Dich nun bei den Dashboard-Webseiten an, mit denen Du Benutzer von der Master-Seite synchronisieren möchtest, und gehe zu ** Benutzersynchronisierung **. 5. Klicke auf ** Dies ist eine Sub-Seite **. 

6. Füge die URL der Master-Seite und den Schlüssel der Master-Seite hinzu, wähle Standardeinstellungen aus und klicke auf ** Diese Seite mit der Master-Seite verbinden und eine VOLLSTÄNDIGE Synchronisierung durchführen ** .

*    Verwende  _ ** Vorhandene Benutzer überschreiben ** _ mit Vorsicht - überschreibt vorhandene Benutzer und sperrt ein Administrator-Benutzerkonto, wenn sie denselben Benutzernamen verwenden
*    Überschreibe keine vorhandenen Benutzer (füge sie als zusätzliche Benutzer hinzu) ist normalerweise die beste Option. Wenn Du diese Option verwendest und ein Benutzer von der Master-Seite denselben Benutzernamen wie die Unterwebseite hat, wird der neue Benutzer mit dem Suffix _sync erstellt, das an seinen Benutzernamen angehängt ist. Beispielsweise. Wenn der Benutzername admin bereits auf beiden Webseiten vorhanden wäre, würde der Benutzername auf der Unterwebseite als admin_sync erstellt.

7. Alle neuen Benutzer auf der Master-Seite werden auf den Unterwebsites repliziert, einschließlich Kennwörtern, E-Mails und Benutzerrollen. Sie werden nun in der Benutzerliste der Unterwebseite aufgeführt. 8. Wenn jetzt ein neuer Benutzer zur Master-Seite hinzugefügt wird, werden diese automatisch mit denselben Details auf den Unterwebseiten erstellt. 9. Um eine Unterwebseite zu entfernen, damit sie nicht mehr mit der Master-Seite synchronisiert wird, musst Du nur in der ** Benutzersynchronisierung der Unterwebseite ** auf ** Von der Master-Seite trennen ** klicken . 

10 \. Um das Plugin zu deinstallieren oder alle Einstellungen des Plugins zurückzusetzen, klicke einfach unten auf der Seite auf ** "Deinstallationsoptionen" ** .

###  Debug-Modus:

Wenn Du Probleme mit Synchronisierungsbenutzern hast, kannst Du den Debug-Modus zum Schreiben einiger Vorgänge in die Protokolldatei verwenden. ** Verwendung: ** 1 \. Aktiviere das Kontrollkästchen "Debug-Modus verwenden" auf der Haupt-Plugin-Seite, bevor Du "Master" oder "Sub-Seite" auswählst.
