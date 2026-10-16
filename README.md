# Nimani Group – site web

Site vitrine statique (HTML/CSS/JS) du garage Nimani Group, Bosch Car Service à Lennik et Anderlecht.

- Bilingue FR / NL (bouton en haut à droite)
- Formulaire de rendez-vous qui prépare un message WhatsApp
- Lien WhatsApp Business dans l'en-tête et en bulle flottante

## À configurer
- `WHATSAPP_NUMBER` dans le `<script>` de `index.html` (format international, chiffres uniquement, ex. `32470123456`)
- Adresses, horaires, téléphones et e-mail (textes surlignés en jaune, classe `ph`)

## Mise en ligne sur OVH
Chaque push sur `main` envoie le site sur l'hébergement OVH par FTP (workflow `.github/workflows/deploy-ovh.yml`).
On peut aussi le lancer à la main depuis l'onglet **Actions › Déploiement OVH › Run workflow**.

Secrets à créer dans **Settings › Secrets and variables › Actions › Secrets** (valeurs dans l'espace client OVH, Hébergements › FTP-SSH) :
- `OVH_FTP_SERVER` : serveur FTP, par ex. `ftp.clusterXXX.hosting.ovh.net`
- `OVH_FTP_USERNAME` : identifiant FTP
- `OVH_FTP_PASSWORD` : mot de passe FTP

Variables facultatives (onglet **Variables**) :
- `OVH_FTP_DIR` : dossier distant, `www/` par défaut
- `OVH_FTP_PROTOCOL` : `ftps` par défaut, mettre `ftp` si la connexion chiffrée est refusée

Sans ces secrets, le workflow ne déploie rien et affiche un avertissement.
