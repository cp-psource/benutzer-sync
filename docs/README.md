# WordPress User Sync

** Benutzersynchronisierung - Mit diesem Plugin kannst Du eine Master-Seite erstellen, von der aus Du eine Benutzerliste mit beliebig vielen anderen Seiten synchronisieren kannst.

## Benutzersynchronisierung verknüpft Benutzerprofile über mehrere einzelne WordPress-Installationen hinweg.

Erstellen und verwalten Sie Benutzerkonten auf einer Masterliste, die zur Verbindung mit jeder Unterwebsite verwendet werden kann – ohne Multisite.

### Intelligenteres, sichereres Benutzermanagement

Durch die automatische Freigabe und Synchronisierung von Profil, Passwort, Benutzerrolle und E-Mail-Adresse wird die Benutzerverwaltung an einen praktischen Ort verschoben. Verbinden Sie mehr Menschen mit mehr Inhalten, indem Sie Benutzern ermöglichen, sich einmal in einer Masterliste zu registrieren und auf allen angeschlossenen Websites dieselben Anmeldeinformationen zu verwenden. Schützen Sie Ihre Benutzer mit kugelsicheren Sicherheitsschlüsseln. Lassen Sie die Sites verbunden, um eine kontinuierliche Kontinuität zu gewährleisten, oder übertragen Sie Benutzer und trennen Sie die Verbindung. Verwenden Sie die Deinstallation und löschen Sie Ihre Datenbank vollständig von allen Plugin-Artefakten. 

![Übertragen und löschen Sie Ihre Datenbank.](http://premium.wpmudev.org/wp-content/uploads/2011/04/uninstall.jpg)

  Übertragen Sie Ihre Datenbank und löschen Sie sie.

### Nutzen Sie Kontrolle und Einfachheit

Die Konfiguration dauert nur wenige Sekunden und erfordert lediglich eine URL und einen Sicherheitsschlüssel. Verwenden Sie „Synchronisieren-Überschreiben“ für eine identische Masterlistenübereinstimmung oder unterstützen Sie die Abwärtskompatibilität von Benutzerkennwörtern auf vorhandenen Websites, indem Sie Duplikate zulassen.

![Volle Kontrolle über Überschreibungen und Duplikate](http://premium.wpmudev.org/wp-content/uploads/2011/04/sync-list.jpg)

  Volle Kontrolle über Überschreibungen und Duplikate

  Sie können einen Benutzer auch von einer bestimmten Unterseite ausschließen, indem Sie verhindern, dass gelöschte Benutzer bei der Synchronisierung mit der Masterliste erneut aufgefüllt werden. Schützen Sie Identitäten, bleiben Sie synchronisiert und sparen Sie unzählige Stunden mit der Benutzersynchronisierung. Erhalten Sie eine verknüpfte Benutzerverwaltung für die Übertragung von Benutzerkonten von einer Masterliste auf alle Ihre WordPress-Sites.

## Verwendung

Lesen Sie zunächst den Abschnitt [Plugins installieren](https://wpmudev.com/docs/using-wordpress/installing-wordpress-plugins/) in unserem umfassenden [WordPress- und WordPress-Multisite-Handbuch](https://premium.wpmudev.org). /wpmu-manual/), wenn Sie neu bei WordPress sind.

### Installieren:

1. Laden Sie die Plugin-Datei herunter. 2\. Entpacken Sie die Datei in einen Ordner auf Ihrer Festplatte. 3\. Laden Sie den Ordner **/user-sync/** in den Ordner **/wp-content/plugins/** auf den Websites hoch, zwischen denen Sie synchronisieren möchten. 4\. Melden Sie sich bei Ihrem Admin-Panel für Ihre WordPress-Site an und aktivieren Sie das Benutzersynchronisierungs-Plugin unter **Plugins**.

### Benutzen:

Sobald das Plugin aktiviert ist, wird ein neues Menü „Benutzersynchronisierung“ hinzugefügt. 1.  Gehen Sie im Dashboard der Site, die Sie zur Master-Site machen möchten, zu **Benutzersynchronisierung**.

* Dies ist die übergeordnete Site, die zum Synchronisieren/Duplizieren aller Benutzer mit Ihren anderen Sites verwendet wird

![Benutzersynchronisierungsmenü](https://premium.wpmudev.org/wp-content/uploads/2011/09/sync_menu01.png)

2. Klicken Sie auf **Diese Site zur Master-Site machen**

![Ste zur Master-Site machen](https://premium.wpmudev.org/wp-content/uploads/2011/04/sync64.jpg)

  3.  Jetzt werden Ihre Master-Site-URL und Ihr Sicherheitsschlüssel angezeigt.

![Ihre Hauptschlüsseldetails](https://premium.wpmudev.org/wp-content/uploads/2011/04/mastersitekey.jpg)

  4.  Melden Sie sich nun bei der/den Dashboard-Site(s) an, mit der/denen Sie Benutzer von der Master-Site synchronisieren möchten, und gehen Sie zu **Benutzersynchronisierung** 5.  Klicken Sie auf **Dies zu einer Sub-Site machen**

![Machen Sie dies zu einer Unterseite](https://premium.wpmudev.org/wp-content/uploads/2011/04/sync65.jpg)

  6.  Fügen Sie die URL der Master-Site und den Schlüssel der Master-Site hinzu, wählen Sie Standardeinstellungen aus und klicken Sie dann auf **Diese Site mit der Master-Site verbinden und eine VOLLSTÄNDIGE Synchronisierung durchführen**.

* Verwenden Sie „Vorhandene Benutzer überschreiben“ mit _**Vorsicht**_ – es überschreibt vorhandene Benutzer und sperrt ein Administrator-Benutzerkonto, wenn sie denselben Benutzernamen verwenden
* Normalerweise ist es die beste Option, keine vorhandenen Benutzer zu überschreiben (sie als zusätzliche Benutzer hinzuzufügen). Wenn Sie diese Option verwenden und ein Benutzer von der Master-Site denselben Benutzernamen wie die Untersite hat, wird der neue Benutzer mit dem Suffix _sync an seinem Benutzernamen erstellt. Zum Beispiel. Wenn der Benutzername admin bereits auf beiden Sites vorhanden wäre, würde der Benutzername auf der Subsite als admin_sync erstellt.

![Verbindung mit Master-Site herstellen](https://premium.wpmudev.org/wp-content/uploads/2011/09/sync_connect.png)

  7. Alle neuen Benutzer auf der Master-Site werden auf der/den Subsite(s) repliziert, einschließlich Passwörtern, E-Mail-Adressen und Benutzerrollen, und sie werden nun in der Benutzerliste der Subsite aufgeführt. 8. Wenn nun ein neuer Benutzer zur Master-Site hinzugefügt wird, wird er automatisch mit denselben Details auf der/den Subsite(s) erstellt. 9. Um eine Unterwebsite zu entfernen, damit sie nicht mehr mit der Master-Website synchronisiert wird, müssen Sie nur im Bereich **Benutzersynchronisierung** der Unterwebsite auf **Von der Master-Website trennen** klicken.

![Von der Master-Site trennen](https://premium.wpmudev.org/wp-content/uploads/2011/09/sync_discon.png)

  10\. Um das Plugin zu deinstallieren oder alle Einstellungen des Plugins zurückzusetzen, klicken Sie einfach unten auf der Seite auf **„Deinstallationsoptionen“**.

### Debug-Modus:

Wenn Sie Probleme mit der Synchronisierung von Benutzern haben, können Sie den Debug-Modus verwenden, um einige Vorgänge in die Protokolldatei zu schreiben. **Anwendung:** 1\. Aktivieren Sie das Kontrollkästchen „Debug-Modus verwenden“ auf der Haupt-Plugin-Seite, bevor Sie „Master“ oder „Subsite“ auswählen.

![Debug-Modus](https://premium.wpmudev.org/wp-content/uploads/2011/09/sync_debug.png)