#  Benutzersynchronisierung

## Die  Benutzersynchronisierung verknüpft Benutzerprofile über mehrere einzelne WordPress-Installationen hinweg.

Erstelle und verwalte Benutzerkonten auf einer Hauptliste, mit der Du eine Verbindung zu einer beliebigen Unterwebseite herstellen kannst - ohne Multisite.

### Intelligente, sichere, Benutzerverwaltung

Durch die gemeinsame Nutzung und Synchronisierung von Profil, Kennwort, Benutzerrolle und E-Mail-Adresse wird die Benutzerverwaltung an einen praktischen Ort verschoben. Verbinden Sie mehr Personen mit mehr Inhalten, indem Sie Benutzern erlauben, sich einmal in einer Hauptliste zu registrieren und dieselben Anmeldeinformationen für alle angehängten Websites zu verwenden. Schützen Sie Ihre Benutzer mit kugelsicheren Sicherheitsschlüsseln. Lassen Sie Websites für die fortlaufende Kontinuität verbunden oder übertragen Sie Benutzer und trennen Sie die Verbindung. Verwenden Sie die Deinstallation und löschen Sie Ihre Datenbank vollständig von Plugin-Artefakten.


### Hebelkontrolle  und Einfachheit

Die Konfiguration dauert nur wenige Sekunden und erfordert nur eine URL und einen Sicherheitsschlüssel. Verwenden Sie Synchronize-Overwrite für eine identische Übereinstimmung mit der Hauptliste oder unterstützen Sie die Kompatibilität mit Rückwärtsbenutzerkennwörtern auf vorhandenen Websites, indem Sie Duplikate zulassen.


Sie können einen Benutzer auch von einer bestimmten Unterwebsite verbannen, indem Sie verhindern, dass gelöschte Benutzer bei der Synchronisierung mit der Hauptliste erneut gefüllt werden. Schützen Sie Identitäten, bleiben Sie synchron und sparen Sie unzählige Stunden mit der Benutzersynchronisierung. Erhalten Sie eine verknüpfte Benutzerverwaltung zum Übertragen von Benutzerkonten von einer Hauptliste auf alle Ihre WordPress-Sites.

##  Verwendung

###  So installieren Sie:

1. Laden Sie die Plugin-Datei herunter. 2 \. Entpacken Sie die Datei in einen Ordner auf Ihrer Festplatte. 3 \. Laden Sie den Ordner ** / user-sync / ** in den Ordner ** / wp-content / plugins / ** auf den Sites hoch, zwischen denen Sie synchronisieren möchten. 4 \. Melden Sie sich bei Ihrem Admin-Panel für Ihre WordPress-Site an und aktivieren Sie das User Synchronisztion-Plugin in ** Plugins ** .

###  Zu verwenden:

Sobald das Plugin aktiviert ist, wird ein neues "User Sync" -Menü hinzugefügt. 1. Gehen Sie im Dashboard der Site, die Sie zur Master-Site machen möchten, zu ** User Sync ** .

*    Dies ist die übergeordnete Site, mit der alle Benutzer mit Ihren anderen Sites synchronisiert / dupliziert werden

2. Klicken Sie auf ** Diese Site zur Master-Site machen ** 

 3. Jetzt werden Ihre Master-Site-URL und Ihr Sicherheitsschlüssel angezeigt. 

4. Melden Sie sich nun bei den Dashboard-Sites an, mit denen Sie Benutzer von der Master-Site synchronisieren möchten, und gehen Sie zu ** Benutzersynchronisierung **. 5. Klicken Sie auf ** Dies ist eine Sub-Site **. 

6. Fügen Sie die URL der Master-Site und den Schlüssel der Master-Site hinzu, wählen Sie Standardeinstellungen aus und klicken Sie auf ** Diese Site mit der Master-Site verbinden und eine VOLLSTÄNDIGE Synchronisierung durchführen ** .

*    Verwenden Sie vorhandene Benutzer mit _ ** Vorsicht überschreiben ** _ - überschreibt vorhandene Benutzer und sperrt ein Administrator-Benutzerkonto, wenn sie denselben Benutzernamen verwenden
*    Überschreiben Sie keine vorhandenen Benutzer (fügen Sie sie als zusätzliche Benutzer hinzu) ist normalerweise die beste Option. Wenn Sie diese Option verwenden und ein Benutzer von der Master-Site denselben Benutzernamen wie die Unterwebsite hat, wird der neue Benutzer mit dem Suffix _sync erstellt, das an seinen Benutzernamen angehängt ist. Beispielsweise. Wenn der Benutzername admin bereits auf beiden Sites vorhanden wäre, würde der Benutzername auf der Unterwebsite als admin_sync erstellt.

7. Alle neuen Benutzer auf der Master-Site werden auf den Unterwebsites repliziert, einschließlich Kennwörtern, E-Mails und Benutzerrollen. Sie werden nun in der Benutzerliste der Unterwebsite aufgeführt. 8. Wenn jetzt ein neuer Benutzer zur Master-Site hinzugefügt wird, werden diese automatisch mit denselben Details auf den Unterwebsites erstellt. 9. Um eine Unterwebsite zu entfernen, damit sie nicht mehr mit der Master-Site synchronisiert wird, müssen Sie nur in der ** Benutzersynchronisierung der Unterwebsite ** auf ** Von der Master-Site trennen ** klicken . 

10 \. Um das Plugin zu deinstallieren oder alle Einstellungen des Plugins zurückzusetzen, klicken Sie einfach unten auf der Seite auf ** "Deinstallationsoptionen" ** .

###  Debug-Modus:

Wenn Sie Probleme mit Synchronisierungsbenutzern haben, können Sie den Debug-Modus zum Schreiben einiger Vorgänge in die Protokolldatei verwenden. ** Verwendung: ** 1 \. Aktivieren Sie das Kontrollkästchen "Debug-Modus verwenden" auf der Haupt-Plugin-Seite, bevor Sie "Master" oder "Sub-Site" auswählen.
