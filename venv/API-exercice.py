from flask import Flask, render_template
import requests

app=Flask(name)

@app.route("/")
def hello_world():
    return render_template("index.html")

@app.route("/Simon")
def afficher_catfacts():
    url='https://catfact.ninja/fact'
    reponse = requests.get(url)
    data=reponse.json
    return data


app.run(debug=True)