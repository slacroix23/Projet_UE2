import pytest
from unittest.mock import patch, Mock

# 1. IMPORTATION DE L'APPLICATION (Relative)
# Utilise le point '.' pour importer API_exercice depuis le même répertoire/package.
from .API_exercice import app


# Fixture Pytest pour créer un client de test pour l'application Flask
@pytest.fixture
def client():
    """
    Configure l'application Flask pour les tests et crée un client de test.
    """
    app.config["TESTING"] = True
    with app.test_client() as client:
        yield client


### Tests pour la route '/' ###


def test_hello_world_status_code(client):
    """Vérifie que la page d'accueil est accessible (Code 200)."""
    reponse = client.get("/")
    assert reponse.status_code == 200


def test_hello_world_renders_html_content(client):
    """
    Vérifie que la page d'accueil rend le contenu HTML attendu (Faits sur les chats).
    """
    reponse = client.get("/")
    assert b"Faits sur les chats !" in reponse.data
    assert (
        b'<button onclick="fetchCatFact()">Voir un autre fait</button>' in reponse.data
    )


### Tests pour la route '/Simon' ###


# 2. MOCKING (Chemin Absolu)
# Le chemin DOIT refléter la façon dont Python voit le module : [package].[module].[fonction à mocker]
@patch("API.API_exercice.requests.get")
def test_afficher_catfacts_succes(mock_get, client):
    """
    Simule une réponse réussie de l'API catfact.ninja.
    """
    # 1. Configurer l'objet de réponse simulée
    mock_reponse = Mock()
    fact_data = {
        "fact": "Le nez d'un chat est aussi unique que l'empreinte digitale humaine.",
        "length": 65,
    }
    mock_reponse.json.return_value = fact_data
    mock_get.return_value = mock_reponse

    # 2. Exécuter la requête sur l'application Flask
    reponse = client.get("/Simon")

    # 3. Assertions (Vérifications)
    assert reponse.status_code == 200
    assert reponse.get_json() == fact_data

    # Ajoutez ce code à la fin de votre fichier API/test_api_exercice.py


from requests.exceptions import RequestException


# MOCKING (Chemin Absolu)
# On réutilise @patch('API.API_exercice.requests.get')
@patch("API.API_exercice.requests.get")
def test_afficher_catfacts_echec_reseau(mock_get, client):
    mock_get.side_effect = RequestException("Erreur de connexion simulée")
    reponse = client.get("/Simon")
    assert reponse.status_code == 503  # <- Devrait être 503 maintenant
    data = reponse.get_json()
    assert "erreur" in data
