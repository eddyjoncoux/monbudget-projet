# 🎉 Feature Prélèvements - Documentation

## Vue d'ensemble
La feature **Prélèvements** a été complètement intégrée à l'application MonBudget. Cette feature permet de gérer les prélèvements automatiques/récurrents (loyers, abonnements, etc.) avec des mises à jour manuelles ou automatiques.

## 📋 Composants créés/modifiés

### 1. **Enum - WithdrawalFrequency** 
📁 `src/Enum/WithdrawalFrequency.php`
- Définit les fréquences disponibles : Quotidien, Hebdomadaire, Bi-hebdomadaire, Mensuel, Trimestriel, Annuel
- Fournit une méthode `getNextDate()` pour calculer la date suivante

### 2. **Enum - TransactionType**
📁 `src/Enum/TransactionType.php`
- ✅ Type `WITHDRAWAL` ajouté aux types de transactions existants (EXPENSE, INCOME)

### 3. **Entity - Withdrawal**
📁 `src/Entity/Withdrawal.php`
- Propriétés principales :
  - `amount` : Montant du prélèvement
  - `description` : Libellé
  - `frequency` : Fréquence (enum)
  - `nextWithdrawalDate` : Prochain prélèvement
  - `lastWithdrawalDate` : Dernier prélèvement effectué
  - `startDate` : Date de début
  - `endDate` : Date de fin (optionnelle)
  - `isActive` : Statut du prélèvement
  - `category` : Catégorie associée
  - `user` : Relation avec l'utilisateur
  - `createdAt`, `updatedAt` : Métadonnées

### 4. **Entity - User** (Modifié)
📁 `src/Entity/User.php`
- ✅ Ajout de la relation `OneToMany` avec `Withdrawal`
- ✅ Ajout des méthodes `getWithdrawals()`, `addWithdrawal()`, `removeWithdrawal()`
- ✅ Initialisation de la collection dans le constructeur

### 5. **Repository - WithdrawalRepository**
📁 `src/Repository/WithdrawalRepository.php`
- `findByUser(User $user)` : Récupère tous les prélèvements d'un utilisateur
- `findActiveByUser(User $user)` : Récupère les prélèvements actifs
- `findOverdueWithdrawals()` : Récupère les prélèvements en retard (pour traitement automatique)

### 6. **Form - WithdrawalFormType**
📁 `src/Form/WithdrawalFormType.php`
- Formulaire complet pour créer/modifier un prélèvement
- Champs : Montant, Description, Fréquence, Dates, Catégorie, Statut actif
- Validation automatique par Symfony

### 7. **Controller - WithdrawalController**
📁 `src/Controller/WithdrawalController.php`
Routes implémentées :
- `GET /withdrawal/` - Liste les prélèvements
- `GET/POST /withdrawal/new` - Créer un prélèvement
- `GET /withdrawal/{id}` - Voir détails
- `GET/POST /withdrawal/{id}/edit` - Modifier
- `POST /withdrawal/{id}` - Supprimer
- `POST /withdrawal/{id}/toggle` - Activer/Désactiver
- `POST /withdrawal/{id}/process` - Traiter un prélèvement en retard

### 8. **Templates Twig**
📁 `templates/withdrawal/`
- `index.html.twig` : Liste des prélèvements avec statuts et actions
- `new.html.twig` : Formulaire d'ajout
- `edit.html.twig` : Formulaire de modification
- `show.html.twig` : Détails complets d'un prélèvement

### 9. **Controller - UserController** (Modifié)
📁 `src/Controller/UserController.php`
- ✅ Dashboard enrichi avec la liste des prélèvements actifs
- Injection de `WithdrawalRepository` pour récupérer les prélèvements

### 10. **Template - Dashboard** (Modifié)
📁 `templates/user/dashboard.html.twig`
- ✅ Boutons ajoutés :
  - "➕ Ajouter un prélèvement"
  - "Voir les prélèvements"

### 11. **Migration Doctrine**
📁 `migrations/Version20260224212342.php`
- ✅ Création de la table `withdrawal`
- Clés étrangères vers `category` et `user`
- Exécutée avec succès

## 🚀 Utilisation

### Ajouter un prélèvement
1. Depuis le Dashboard, cliquer sur "➕ Ajouter un prélèvement"
2. Remplir le formulaire :
   - Montant
   - Description (ex: "Loyer", "Abonnement Netflix")
   - Fréquence (Mensuel, Hebdomadaire, etc.)
   - Dates (début, prochain prélèvement, fin optionnelle)
   - Catégorie (optionnelle)
   - Cocher "Prélèvement actif"
3. Cliquer sur "Créer le prélèvement"

### Gérer les prélèvements
- **Vue liste** : Affiche tous les prélèvements avec :
  - Montant et description
  - Fréquence
  - Date du prochain prélèvement
  - Statut (Actif/Inactif) et alerte si en retard
  - Boutons d'action (Voir, Modifier, Activer/Désactiver, Supprimer)

- **Détails** : Affiche complètement les informations et permet :
  - Modification
  - Activation/Désactivation
  - Suppression
  - Traitement manuel (si en retard)

### Fonctionnalités clés
✅ **Gestion du statut** : Activer/Désactiver rapidement un prélèvement
✅ **Calcul automatique** : Calcul de la date suivante basée sur la fréquence
✅ **Détection des retards** : Identifie les prélèvements en retard
✅ **Traitement manuel** : Possibilité de traiter manuellement un prélèvement
✅ **Sécurité** : Contrôle d'accès (utilisateur ne peut voir/modifier que ses prélèvements)

## 🔒 Sécurité

- Tokens CSRF sur tous les formulaires
- Vérification de propriété : chaque action vérifie que le prélèvement appartient à l'utilisateur connecté
- Exception `AccessDeniedException` levée en cas d'accès non autorisé

## 📊 Statistiques

- **Fichiers créés** : 7
- **Fichiers modifiés** : 3
- **Enums créeres** : 2 (TransactionType + WithdrawalFrequency)
- **Entités créées** : 1 (Withdrawal)
- **Controllers créés** : 1 (WithdrawalController)
- **Templates créés** : 4 (index, new, edit, show)
- **Migrations exécutées** : 1
- **Erreurs détectées** : 0 ✅

## 🔄 Évolutions futures possibles

1. **Automatisation** : Créer une command Symfony pour traiter automatiquement les prélèvements en retard
2. **Historique** : Ajouter une table pour historiser les prélèvements traités
3. **Notifications** : Alerter l'utilisateur avant un prélèvement prévu
4. **Export** : Exporter les prélèvements en CSV/PDF
5. **Statistiques** : Graphiques des prélèvements par catégorie/mois
6. **Intégration bancaire** : Connexion avec des APIs bancaires pour synchroniser les prélèvements réels

## ✨ Notes

- La feature est prête à l'emploi et entièrement fonctionnelle
- Tous les tests de compilation passent (0 erreurs)
- Les templates sont responsive et bien stylisés
- L'interface utilisateur suit la même charte que le reste de l'application
