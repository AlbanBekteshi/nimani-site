# Nimani Group – site web

Site vitrine statique (HTML/CSS/JS) du garage Nimani Group, Bosch Car Service à Lennik et Anderlecht.

- Bilingue FR / NL (bouton en haut à droite)
- Formulaire de rendez-vous envoyé directement par e-mail au garage choisi (`send.php`), ou par WhatsApp
- Lien WhatsApp Business dans l'en-tête et en bulle flottante

## À configurer
- `WHATSAPP_NUMBER` dans le `<script>` de `index.html` (format international, chiffres uniquement, ex. `32470123456`)
- Horaires et téléphones (textes surlignés en jaune, classe `ph`)
- `GARAGES` dans `send.php` : adresse qui reçoit les demandes de chaque garage
- `FROM_EMAIL` dans `send.php` : adresse d'expédition, à prendre sur le domaine du site (par défaut `noreply@nimanigroup.be`)

`send.php` utilise la fonction `mail()` de PHP : il marche sur l'hébergement OVH, pas sur GitHub Pages.

## Mise en ligne sur OVH
Chaque push sur `main` envoie le site sur l'hébergement OVH par FTP (workflow `.github/workflows/deploy-ovh.yml`).
On peut aussi le lancer à la main depuis l'onglet **Actions › Déploiement OVH › Run workflow**.

Secrets à créer dans **Settings › Secrets and variables › Actions › Secrets** (valeurs dans l'espace client OVH, Hébergements › FTP-SSH) :
- `OVH_FTP_SERVER` : serveur FTP, par ex. `ftp.clusterXXX.hosting.ovh.net`
- `OVH_FTP_USERNAME` : identifiant FTP
- `OVH_FTP_PASSWORD` : mot de passe FTP

Variables facultatives (onglet **Variables**) :
- `OVH_FTP_DIR` : dossier distant, `www/` par défaut
- `OVH_FTP_PROTOCOL` : `ftp` par défaut (l'hébergement mutualisé OVH refuse le FTPS)

Sans ces secrets, le workflow ne déploie rien et affiche un avertissement.
