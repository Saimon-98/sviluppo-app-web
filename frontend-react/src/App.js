import React, { useState, useEffect } from "react";
import axios from "axios";
import './App.css';

// URL dell'API backend Laravel
const API_URL = `${process.env.REACT_APP_API_URL}/tasks`;

function App() {
  // Stato per le attività ricevute dall'API
  const [tasks, setTasks] = useState([]);

  // Stato per il titolo dell'attività in fase di inserimento/modifica
  const [title, setTitle] = useState("");

  // Stato per tenere traccia dell'attività da modificare (null se si sta creando)
  const [editingId, setEditingId] = useState(null);

  // Carica le attività all'avvio
  useEffect(() => {
    fetchTasks();
  }, []);

  // Recupera l'elenco delle attività dal backend
  const fetchTasks = async () => {
    const res = await axios.get(API_URL);
    setTasks(res.data); // Aggiorna lo stato con le attività ricevute
  };

  // Gestionde del submit del form (sia per creare che aggiornare un'attività)
  const handleSubmit = async (e) => {
    e.preventDefault(); // Previene il comportamento predefinito del form
    if (editingId) {
      // Se id è presente, significa che stiamo modificando un'attività esistente
      await axios.put(`${API_URL}/${editingId}`, { title });
      setEditingId(null); // Esce dalla modalità modifica
    } else {
      // Altimenti si tratta di una nuova attività
      await axios.post(API_URL, { title });
    }
    setTitle(""); // Pulisce il campo input
    fetchTasks(); // Ricarica la lista aggiornata
  };

  // Carica il titolo nel form e imposta l'ID dell'attività da modificare
  const handleEdit = (task) => {
    setTitle(task.title);
    setEditingId(task.id);
  };

  // Elimina un'attività esistente
  const handleDelete = async (id) => {
    await axios.delete(`${API_URL}/${id}`);
    fetchTasks(); // Ricarica la lista aggiornata
  };

  return (
    // Imposta un po' di stile
    <div style={{ padding: "2rem", fontFamily: "Arial, sans-serif" }}>
      <h1>Gestione Attività</h1>
      
      {/* Form per aggiungere o modificare attività */}
      <form onSubmit={handleSubmit}>
        <input
          type="text"
          placeholder="Titolo attività"
          value={title}
          onChange={(e) => setTitle(e.target.value)} // Aggiorna il titolo
          required
        />
        <button type="submit">
          {editingId ? "Aggiorna" : "Aggiungi"} {/* Cambia il testo del pulsante */}
        </button>
      </form>

      {/* Elenco delle attività */}
      <ul>
        {tasks.map((task) => (
          <li key={task.id}>
            {task.title}{" "}
            {/* Pulsante che abilita la modalità modifica ed elimina */}
            <button onClick={() => handleEdit(task)}>Modifica</button>{" "}
            <button onClick={() => handleDelete(task.id)}>Elimina</button>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;
