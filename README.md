# sviluppo-app-web
✔️ Init: struttura progetto frontend e backend

✔️ Aggiunta API Laravel RESTful per gestione attività

✔️ Aggiunta interfaccia React per visualizzare e modificare attività

✔️ Implementato Dockerfile per backend Laravel

✔️ Implementato Dockerfile per frontend React

✔️ Configurato Docker Compose per orchestrazione

✔️ Corretto CORS e problemi comuni

✔️ Aggiunto .gitignore e documentazione di progetto

PHP 8.2.
Lavarel 4.2.9 con extension=zip.
Partiamo da una directory sviluppo-web-app.
Per la configurazione ho aggiunto routes/api.php e config/cors.php.
Creo un nuovo progetto Laravel con promt dei comandi.
laravel new backend-laravel.
cd backend-laravel -> mi sposto nella directory backend-laravel.
Dopodiché creo il controller per gestire le attività in app/http/Controllers/TaskController.php.
php artisan make: controller TaskController.
per la gestione degli endpoint GET, POST, PUT, DELETE.
Semplice storage in array per memorizzare le attività con semplicità utilizzando un file per salvare e leggere le attività in formato JSON:
private static $filePath = '';
Definizione delle rotte API in routes/api.php.
Avvia il server con promt: php artisan serve.
L'API sarà disponibile su: http://127.0.0.1:8000/api/tasks.
Tutto verrà salvato in storage/app/tasks.json.

Creo un nuovo progetto React con promt dei comandi.
npx create-react-app frontend-react.
cd frontend-react -> mi sposto nella directory frontend-react.
npm install axios -> Installiamo axios per effettuare richieste http al backend.
In App.js l’interfaccia utente deve permettere all’utente di:

•	Visualizzare l'elenco delle attività.

•	Aggiungere una nuova attività.

•	Modificare un'attività esistente.

•	Eliminare un'attività.

Creiamo un file .env nel progetto React per la configurazione e il collegamento Laravel con contenuto:
REACT_APP_API_URL=http://localhost:8000/api/tasks.
Utilizziamo npm start per avviare il frontend e per gestire le dipendenze del frontend.

Ora facciamo la containerizzazione completa dell’applicazione con Docker con struttura:

sviluppo-app-web/

--- backend-laravel/

---frontend-react/

---docker-compose.yml

Crea backend-laravel/Dockerfile.
Utilizzo dell’immagine Docker ufficiale di PHP 8.2 con Apache preinstallato, dopodiché aggiorna il package, installa librerie richieste, poi compila ed abilita estensioni PHP necessarie a Laravel.
Abilita il modulo mod_rewrite per le route pulite di Laravel. Imposta la cartella pubblica di Apache su public/, modificando il file di configurazione per usare /public come root della web app.
Copia tutti i file del progetto Laravel dentro il container. Imposta la directory di lavoro a /var/www/html. Copia composer dalla immagine ufficiale di Composer, così può usarlo per installare le dipendenze. Installa le dipendenze PHP escludendo quelle di sviluppo e ottimizza l’autoloader.
Laravel richiede che queste cartelle siano scrivibili dal web server. Dichiara che il container ascolta sulla porta 80.

Crea frontend-react/Dockerfile.
Utilizzo dell’immagine Docker con una versione leggera di Node.js basata su Alpine Linux.
Imposta la directory di lavoro all’interno del container (/app). Copia solo i file package.json e package-lock.json dalla macchina host al container nella cartella /app. Installa tutte le dipendenze del progetto elencate in package.json e copia tutti i file fel progetto dalla macchina host al container.
Indica che il container utilizza la porta 3000 e definisce il comando di default eseguito quando il container viene avviato. In quanto andando su localhost in comando sarà già avviato.

Crea docker-compose.yml.
L’immagine Docker va costruita da zero, ./backend-laravel il percorso dove si trovano i file per costruire l’immagine, specificato il nome del file Docker da usare.
Mappa la porta 80 interna del container sulla porta 8000 esterna. Visiti http://localhost:8000 e accedi al Laravel.
Costruita l’immagine per React a partire dal percorso ./frontend-react. Mappa la porta 300 del container alla porta 3000 del tuo host. Così accedi all’interfaccia su http://localhost:3000.
Indica che il container del frontend parte solo dopo che il backend è stato avviato.

Per avviare l’app, aprire Docker Desktop e dopo averlo eseguito, nel promt dei comandi, alla cartella sviluppo-app-web:
docker-compose up --build.
Dopo qualche momento:

•	Backend API sarà disponibile su: http://localhost:8000/api/tasks
Una volta inserito nel terminale: php artisan serve.

•	Frontend React su: http://localhost:3000
