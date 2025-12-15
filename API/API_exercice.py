import os # <<< AJOUTEZ CETTE LIGNE
from flask import Flask, render_template, jsonify, Response
from requests.exceptions import RequestException 
import requests
from typing import Dict, Union, Tuple 
# ... le reste de vos imports

# ... le reste de votre code qui utilise 'os' ...

# 🚨 MODIFICATION IMPORTANTE 🚨
# 1. Détermine le chemin absolu du répertoire où se trouve ce fichier (API_exercice.py)
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# 2. Initialise Flask en lui donnant le chemin absolu du dossier templates
app = Flask(
    "API",  # Nom du package (pour les imports)
    template_folder=os.path.join(BASE_DIR, "templates"),  # Chemin ABSOLU des templates
)
# Note : Si vous utilisez Python 3.10+, vous pouvez aussi utiliser pathlib

# ... (Le reste de votre code de route reste inchangé)


# ROUTE 1: Affiche la page HTML (doit utiliser render_template)
@app.route("/")
def hello_world():
    # Cette route est responsable du rendu de la page index.html
    return render_template("index.html")


# ROUTE 2: API qui fournit les faits (doit contenir le try/except)
ReturnType = Union[Dict, Tuple[Response, int]]

@app.route("/Simon")
def afficher_catfacts() -> ReturnType:
    url = 'https://catfact.ninja/fact'
    try:
        reponse = requests.get(url, timeout=10) 
        reponse.raise_for_status()

        data: Dict = reponse.json() # Annotation optionnelle de 'data'
        return data # Retourne Dict (JSON)
    
    # API_exercice.py (Bloc except corrigé)

    except RequestException as e:
    # 1. Loggez l'erreur D'ABORD, si vous voulez la voir dans la console.
        print(f"Erreur lors de la récupération du fait: {e}")
    
    # 2. Retournez le résultat (une seule fois)
    # C'est la syntaxe standard de Flask pour retourner un corps de réponse ET un statut.
        return (
            jsonify(
                {"erreur": "Impossible de contacter l'API Cat Facts.", "detail": str(e)}
            ),
            503,
        )


# Le serveur ne se lance que si le script est exécuté directement
if __name__ == "__main__":
    app.run(debug=False)
