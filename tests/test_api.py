import sys
from pathlib import Path

from API.API_Exercice_Pokemon import app 
import pytest
# ... le reste du code de votre test

# ... le reste de votre code de test (fixture client, test_accueil, etc.)

# ... le reste de votre code

# La fixture doit contenir un seul bloc 'with yield' pour fournir le client.
@pytest.fixture
def client():
    # 1. Configurer l'application pour le mode test
    app.config['TESTING'] = True
    
    # 2. Utiliser le client de test
    # Le 'yield' fournit le client aux fonctions de test.
    # Le code après 'yield' (le bloc 'with' dans ce cas) est exécuté au nettoyage.
    with app.test_client() as client:
        yield client

# Exemple de test 1...
def test_page_accueil_existe(client):
    # ... (le corps du test est correct)
    response = client.get('/')
    assert response.status_code == 200

# Exemple de test 2...
def test_page_accueil_contient_pokemon(client):
    # ... (le corps du test est correct)
    response = client.get('/')
    assert b"Pokemon" in response.data or "Pokémon".encode('utf-8') in response.data