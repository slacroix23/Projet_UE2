import tkinter as tk
import requests

def clique():
    print("bouton cliqué !")

fenetre = tk.TK()
fenetre.title("Catfact.API")

bouton = tk.Button(fenetre, text="Cliquez pour avoir un fact sur les chats", command=clique)
bouton.pack()

fenetre.mainloop()