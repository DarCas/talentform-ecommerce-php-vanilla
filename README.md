# Pseudo E-Commerce in PHP-vanilla

- Bootstrap 5
- PHP 8.2 o successiva
- MariaDB 11 o successiva

## Struttura del front-end

 - Homepage
   - Hero
   - Prodotti in evidenza
   - Cerca prodotto
 - Prodotti
   - Lista dei prodotti paginata
     - Pagina dettaglio prodotto
 - Carrello (pseudo)
 - Contatti

## Homepage

### Hero

L'Hero è un prodotto scelto da mettere in evidenza con una call-to-action (CTA) che punta alla pagina del dettaglio prodotto.

### Prodotti in evidenza

Visualizzare 3 prodotti in evidenza. Se non ci sono o se non sono abbastanza, visualizzarne altri in maniera casuale. Da questi, bisogna assicurarsi di non visualizzare quello nell'Hero. Ogni prodotto avrà la CTA che punta alla pagina del dettaglio prodotto.

### Cerca prodotto

Campo di testo (to-be-defined, TBD). Farà la ricerca all'interno del titolo e della descrizione del prodotto.

## Prodotti

La pagina dei prodotti avrà un box (TBD) con le categorie di prodotti cliccabili allo scopo di filtrare l'elenco. Accanto a ogni categoria, se possibile, visualizzeremo il numero di prodotti all'interno.

Avrà un ulteriore box con elencati gli ultimi 5 prodotti visualizzati.

Un prodotto potrebbe essere non disponibile, in quanto la quantità di magazzino è pari a 0 (zero).

### Lista dei prodotti paginata

(TBD) Ci saranno due pulsanti, uno per aggiungere al carrello (una volta aggiunto al carrello, non dev'essere più attivo il pulsante) e l'altro per visualizzare il dettaglio dei prodotti. Un prodotto potrebbe essere non disponibile.

La paginazione dovrebbe prevedere la visualizzazione di almeno 3/4 righe di prodotti. Per la paginazione, utilizziamo il sistema di visualizzazione di Bootstrap 5.

### Pagina dettaglio prodotto

- Nella pagina sarà presente un'immagine più grande del prodotto. 
- Sarà visualizzato il testo completo della descrizione.
- Sarà visualizzato la quantità disponibile (eventualmente la non disponibilià).

Aggiungere un bottone che permetta di andare alla pagina contatti con un riferimento al prodotto, in modo da poter chiedere informazioni.

Dare la possibilità di aggiungere al carrello il prodotto, se non già presente e se disponibile.

## Carrello

Funzionalità basilare di elenco dei prodotti in stile Amazon, con possibilità di eliminare o modificare il quantitativo.

Simulare una conferma d'ordine con decurtazione del quantitativo ordinato dal magazzino. Quando l'ordine viene inviato, chiedere le seguenti informazioni:

 - Nome (campo di testo, obbligatorio)
 - Cognome (campo di testo, obbligatorio)
 - Codice fiscale (campo di testo, obbligatorio)
 - Indirizzo (campo di testo, obbligatorio)
 - E-mail (campo e-mail, obbligatorio)
 - Telefono (campo telefono, obbligatorio)
 - Note (area di testo max 255 car., facoltativo)

# Contatti

Pagina con un form di contatto avente i seguenti campi:

 - Oggetto (nascosto, eventualmente compilato se la pagina arriva da un prodotto)
 - Nome (campo di testo, obbligatorio)
 - E-mail (campo e-mail, obbligatorio)
 - Telefono (campo telefono, facoltativo)
 - Messaggio (area di testo max 255 car., obbligatorio)

Una volta inviato il messaggio, visualizzare un feedback (positivo o negativo).

## Funzionalità del back-end

- Autenticazione
  - Username
  - Password
- Prodotti
  - CRUD prodotto
    - Categoria (testo)
    - Titolo (testo)
    - Descrizione (testo)
    - Immagine (testo)
    - Quantitativo (numero positivo)
    - In evidenza (booleano)
    - Stato (numero)
      - Cestinato (-1)
      - Bozza (0)
      - Pubblico (1)
- Cliente
    - RUD
      - Nome (testo)
      - Cognome (testo)
      - Codice fiscale (testo)
      - Indirizzo (testo)
      - E-mail (testo)
      - Telefono (testo)
      - Note (testo, facoltativo)
- Carrello
  - RUD
    - Referenza al prodotto (#ID)
    - Referenza al cliente (#ID)
    - Quantitativo ordinato (numero positivo)
    - Data ordine (data e ora)
    - Data cambio stato (data e ora, facoltativo)
    - Data spedizione (data e ora, facoltativo)
    - Codice tracciamento (testo, facoltivo)
    - Stato dell'ordine (numero)
      - Annullato (-1)
      - Ricevuto (0)
      - Elaborato (1)
      - Evaso (2)
      - Spedito (3)
    - (TBD)
