from flask import Flask, render_template
import requests

app=Flask(name)

@app.route("/")
def hello_world():
    return render_template("pokemon.html")

@app.route("/Simon")
def afficher_pokemon():
    url='https://pokeapi.co/api/v2/pokemon/mew'
    reponse = requests.get(url)
    data=reponse.json
    return data


app.run(debug=True)