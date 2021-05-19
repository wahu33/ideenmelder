# Ideenmelder

Die Anwendung ermöglicht die Markierung von Standorten in einem begrenzten Bezirk (Stadt Hamm). Die Eingaben können beschrieben und bewertet werden. Das Hochladen von Bildern und das Kommentieren von Beiträgen ist möglich.

Die Idee zu dieser Anwendung kam durch einen Artikel in der ADFC-Zeitschrift Radwelt zu Ibbenbüren, wo Vorschläge zur Verkehrsinfrastruktur über eine Webanwendung erfasst wurden. Ich habe diese Idee übernommen und neu programmiert. Eine Demoanwendung findet man unter  https://karte.hpadm.de  (Login: test  Passwort: testtest)

Es sind aus meiner Sicht aber auch viele andere Anwendungsmöglichkeiten denkbar, z.B. Erfassung/Meldung von Eichenprozessionsspinnern, Markierung/Erfassung von Stromtankstellen etc..

## Installation

### Installation der Andwendung

Die Anwendung verfügt über ein Setup, das die Datenbank und fehlende Verzeichnisse anlegt, einen Nutzeraccount anlegt und die Anpassung der Texte ermöglicht.

### Konfiguration

Nach Installation können über die Konfiguration jederzeit die Parameter angepasst werden.

Folgende Funktionalitäten können an- bzw. abgeschaltet werden:

* Userinformationen (Altersklassen und überwiegend benutztes Verkehrsmittel)
* Rating (Positive und negative Bewertung)
* Hochladen von Bildern (erlaubt jpg/gif/png)
* Kommentare

### Reverse Georeferenzierung

Aus den übermittelten Geodaten wird die Adresse ermittelt. Dazu wird der Dienst https://locationiq.com verwendet. Die Adressen werden nur im Backend angezeigt, um die Auswertung der Daten zu erleichtern.
Für locationiq.com muss ein Api-Key beantragt werden. Dieser ist in der Datei /lib/geocoding.php einzutragen. Für die Georeferenzierung kann auch 

---

## Versionen

### Version 2.2

* Behandlung der Eingaben mit Zeilenumbruch, Anführungszeichen und Hochkommas
* Die Konfiguration wird jetzt in der Datenbank gespeichert.
* Bei der Eingabe werden die Adressen georeferenziert (reverse), d.h. zum Standort wird eine Adresse ermittelt. Das ist nur im Backend sichtbar und dient zur Auswertung der Daten.
* Im Backend können jetzt Einträge bearbeitet werden (Änderung der Beschreibung, Änderung der Mängelkategorie und Hochladen von Bildern).

---

## Verwendete Bibliotheken

Die verwendeten Bibliotheken:

Die Speicherung erfolgt in einer SQlite-Datenbank im Verzeichnis /db. Sie wird während des Setups automtisch angelelgt. Zum Backup kann man die Datei locations.db einfach speichern.

### JQuery

Javacript-Framework
* https://jquery.com/
* Lizenz: MIT

### Bootstrap 4

CSS-Framework
* https://getbootstrap.com/
* Lizenz: MIT

### Leaflet

JS-Framwork für GIS-Anwendungen (Openstreetmap)

* https://leafletjs.com
* 2-clause BSD License

#### Leaflet-Ajax

Bibliothek, um Geojson-Datein per Ajax zu laden. Kann eigentlich durch JQuery-Ajax ersetzt werden.

* https://github.com/calvinmetcalf/leaflet-ajax
* Lizenz: MIT

#### Leaflet-Awesome-Markers

Ermöglicht, die Marker mit Fontawesom-Symbolen zu gestalten. die Bibliothek ist veraltet und bietet nur Zugriff auf die Fontawesome 4.x und nicht auf die neue Version 5.x.. Daher sollte sie eigentlich mal angepasst werden.

* https://github.com/lvoogdt/Leaflet.awesome-markers
* https://fontawesome.com/
* Lizenz: MIT


#### Leaflet-Snogylop

Erweiterung vom Leaflet um ein Polygon zu invertieren. 
* https://github.com/ebrelsford/Leaflet.snogylop
* Lizenz: MIT

### Lightbox

Zur Darstellung von Bildern
* https://lokeshdhakar.com/projects/lightbox2/
* Lizenz: MIT

### Datatable

Darstellung der Tabelle

* https://datatables.net
* Lizenz: MIT

### Shapefile

Export von Shape-Files

* https://gasparesganga.com/labs/php-shapefile/
* Lizenz: MIT

## Improvements

* Rating durch Cookie absichern, so dass nicht zwei mal während einer Sitzung ein Maker betätigit werden kann.
* Alert nach Rating überarbeiten (z.B. mit Bootstrap)

