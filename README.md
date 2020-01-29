# MON SUPER PROJET

## Comment travailler sur le projet ?

Première étape on récupere le dépot :
'''bash
cd C:/xampp/htdocs
git clone URL NOMDUPROJET
cd NOMDUPROJET
'''

On installe les dépendances :

'''bash
composer install
'''

On configure la bdd dans '''.env.local'''

On crée la bdd : 

'''bash
php bin/console doctrine:databse:create
'''

on crée le schéma : 

'''bash
php bin/console doctrine:databse:create
'''