from flask import Flask, render_template
import requests

app=Flask(__name__)

@app.route("/")
def hello_world():
    return render_template("pokemon.html")

@app.route("/Simon")
def afficher_pokemon():
    url='https://pokeapi.co/api/v2/pokemon/mew'
    reponse = requests.get(url, timeout=10)
    data=reponse.json()
    return data

if __name__ == '__main__':
    # Cette ligne n'est exécutée que lorsque vous lancez le script directement (python API...)
    # Elle est ignorée lorsque pytest importe le module.
    app.run(debug=False)