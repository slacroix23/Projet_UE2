from flask import Flask, render_template
import requests

app=Flask(name)

@app.route("/")
def hello_world():
    return render_template("pokeapi.co/api/v2/pokemon/mew")

@app.route("/Simon")
def afficher_pokemon():
    url=''
    reponse = requests.get(url)
    data=reponse.json
    return data


app.run(debug=True)