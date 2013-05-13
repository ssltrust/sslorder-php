# SSL-Order Client

## Einleitung
Mit dem SSL-Order Client kann die API von SSL-Trust über das
REST-Protokoll per JSON angesprochen werden.
Über die API können unter anderem automatisiert SSL-Zertifikate bestellt werden sowie
der Status von Zertifikaten abzufragt werden.

## Voraussetzungen
Der Client setzt PHP5 (>= 5.2.0), sowie die installierte CURL-Erweiterung mit
HTTPS-Unterstützung voraus.
Unter debian/ubuntu können die Pakete mit folgendem Befehl installiert werden:

	apt-get install php5 php5-curl

## Verwendung
In der example.php ist beschrieben, wie eine Instanz des Clients erzeugt
wird und mit diesem Zertifikate bestellt und abgefragt werden können.

## Callback
Bei der Ausstellung des Zertifikats kann das Zertifikats-Objekt per HTTP an ein
Script gesendet werden, um automatisch weiterverarbeitet zu werden.
Die callback.php zeigt die Funktionsweise eines solches Scriptes.

## Lizenz
Dieser Client steht unter der MIT-Lizenz.
