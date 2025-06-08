<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importa le classi necessarie per gestire richieste HTTP (Request)
// e risposte in formato JSON (JsonResponse).
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    // Storage simulato in memoria
    // File usato per salvare e leggere le attività in formato JSON
    private static $filePath = '';

    // Inizializza per l'acceso a $filePath
    private static function getFilePath(): string
    {
        return storage_path('app/tasks.json');
    }

    // Funzione statica che legge le attività dal file JSON
    private static function readTasks(): array
    {
        $file = self::getFilePath();
        // Esiste il file? Se non esiste restituisce un array vuoto
        if (!file_exists($file)) {
            return [];
        }
        $json = file_get_contents($file); // Legge il contenuto
        return json_decode($json, true) ?: []; // Decodifica la stringa JSON
    }

    // Funzione statica che scrive l'array delle attività nel file JSON
    private static function writeTasks(array $tasks): void
    {
        // Trasforma l'array PHP in una stringa JSON, poi scrive quella stringa dentro al $filepath
        $file = self::getFilePath();
        file_put_contents($file, json_encode($tasks));
    }

    // GET /tasks
    public function index(): JsonResponse
    {
        $tasks = self::readTasks();
        // Restituisce tutti i task correnti come JSON
        return response()->json($tasks);
    }

    // POST /tasks
    public function store(Request $request): JsonResponse
    {
        // Verifica la richiesta per assicurarsi che ci sia un title valido
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $tasks = self::readTasks();
        $nextId = count($tasks) > 0 ? max(array_column($tasks, 'id')) + 1 : 1;

        // Crea un nuovo task con un ID univoco e lo aggiunge all'array $tasks
        $task = ['id' => $nextId, 'title' => $validated['title']];

        $tasks[] = $task;

        self::writeTasks($tasks);
        // Restituisce il nuovo task con codice HTTP 201 (Created)
        return response()->json($task, 201);
    }

    // PUT /tasks/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        // Verifica la richiesta (title richiesto e valido)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $tasks = self::readTasks();
        // Cerca il task per id, lo aggiorna se trovato e restituisce il task aggiornato
        foreach ($tasks as &$task) {
            if ($task['id'] === $id) {
                $task['title'] = $validated['title'];
                self::writeTasks($tasks);
                return response()->json($task);
            }
        }

        // Se il task non esiste, restituisce un errore 404 con messaggio
        return response()->json(['message' => 'Task not found'], 404);
    }

    // DELETE /tasks/{id}
    public function destroy(int $id): JsonResponse
    {   
        $tasks = self::readTasks();
        $found = false;
        // Cerca il task per id, lo elimina usando array_splice 
        // (per rimuovere l'elemento mantenendo l'indice coerente
        // Dopodiché restituisce un messaggio di conferma
        foreach ($tasks as $index => $task) {
            if ($task['id'] === $id) {
                array_splice($tasks, $index, 1);
                $found = true;
                break;
            }
        }

        if ($found){
            self::writeTasks($tasks);
            return response()->json(['message' => 'Task deleted']);
        }

        // Se il task non viene trovato, restituisce un errore 404
        return response()->json(['message' => 'Task not found'], 404);
    }

    // Metodo statico di debug per visualizzare lo stato attuale dei task
    public static function debugTasks(): JsonResponse
    {
        return response()->json(self::readTasks());
    }
}
