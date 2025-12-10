import tkinter as tk
from tkinter import messagebox
import requests
import json

# --- 1. FONCTION DE MISE À JOUR (GÈRE LA REQUÊTE ET L'AFFICHAGE) ---

def catfact():
    """
    Se connecte à l'API 'catfact.ninja/fact', 
    récupère un fait et met à jour l'étiquette dans la fenêtre.
    """
    URL = 'https://catfact.ninja/fact'
    fact_label.config(text="Chargement du fait en cours...")
    fenetre.update() # Affiche le texte de chargement immédiatement

    try:
        # Effectuer la requête GET
        reponse = requests.get(URL, timeout=5)

        # Lever une exception pour les codes de statut 4xx/5xx
        reponse.raise_for_status() 

        # Récupérer les données JSON
        donnees = reponse.json()
        
        # Mettre à jour l'étiquette (Label) dans la fenêtre Tkinter
        fact_texte = donnees.get('fact', "Fact non trouvé.")
        
        # Le fait complet est stocké dans la clé 'fact'
        fact_label.config(
            text=f"Fait sur les chats : \n\n{fact_texte}",
            fg='green' 
        )
        print(f"✅ Fait récupéré : {fact_texte}")

    # 🛑 RÉINTÉGRATION DE LA GESTION DES ERREURS 🛑
    except requests.exceptions.RequestException as err:
        # Gère les erreurs de connexion, timeout, et les erreurs HTTP (4xx/5xx)
        message = f"Erreur lors de la récupération du fait. Détails : {err}"
        
        # Afficher l'erreur dans l'étiquette de l'application
        fact_label.config(
            text="Impossible de récupérer le fait. Veuillez vérifier votre connexion Internet ou l'état de l'API.", 
            fg='red'
        ) 
        
        # Afficher une boîte de dialogue d'erreur
        messagebox.showerror("Erreur API/Connexion", "Impossible de contacter le serveur de faits. Détails en console.")
        print(f"❌ ERREUR DE REQUÊTE : {err}")


# --- 2. CONFIGURATION DE LA FENÊTRE ---

fenetre = tk.Tk()
fenetre.title("Découvrez un nouveau fait sur les chats")
fenetre.geometry("600x300") 
fenetre.config(padx=30, pady=30)

# --- 3. CRÉATION DES WIDGETS ---

# Étiquette pour afficher le fait
fact_label = tk.Label(
    fenetre, 
    text="Cliquez sur le bouton pour un fait aléatoire !", 
    wraplength=550,
    justify=tk.CENTER,
    font=('Arial', 12)
)
fact_label.pack(pady=10)

# Bouton d'action
bouton = tk.Button(
    fenetre,
    text="Nouveau Fait sur les Chats 🐱",
    command=catfact,
    font=('Arial', 14, 'bold'),
    bg='#FFE0B2', 
    fg='black'
)
bouton.pack(pady=20)


# --- 4. LANCEMENT ---
fenetre.mainloop()