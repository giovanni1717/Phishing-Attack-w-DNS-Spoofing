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

  
