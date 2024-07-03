
## Authentification


Pour l'authentification, j'ai utilisé [Sanctum](https://laravel.com/docs/11.x/sanctum).

Sanctum gère lui-même les réponses, donc il est nécessaire de préciser que l'on souhaite du JSON en retour.

Pour cela, il faut ajouter en en-tête : `"Accept": "application/json"`. 


## Problèmes liés à l'envoi d'images par PUT

J'ai rencontré des difficultés lors de la modification de profil, car j'essayais d'envoyer une image via PUT. Après avoir effectué des recherches, j'ai découvert qu'en PHP, il n'est pas possible d'envoyer des form-data avec la méthode PUT.

Donc, j'ai créé deux méthodes d'update différentes :
1. Une mise à jour qui suit la norme CRUD et utilise PUT pour modifier le profil. Dans cette approche, il n'est pas possible de modifier l'image.
2. La deuxième méthode utilise POST pour effectuer l'update, ce qui permet d'envoyer des `form-data` et donc d'inclure l'image.


## Retour

Comme je l'ai mentionné à Emma dans mon e-mail, je m'excuse pour ce retard. J'aurais aimé l'envoyer dès que possible, mais mon emploi du temps était chargé. 

PS : J'ai trouvé un moment pour effectuer quelques modifications depuis mon e-mail de 17h.

Dernière modification : Jeudi à 01h.