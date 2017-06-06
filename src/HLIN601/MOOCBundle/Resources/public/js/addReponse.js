$(document).ready(function() {

          var $addReponseLink = $('<a href="#" id="add_reponse" class="btn btn-default">Ajouter une Réponse</a>');

          // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
          var $containerR = $('div#hlin601_moocbundle_qcm_questions_0_reponses');

          $containerR.append($addReponseLink);

          // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
          var indexR = $containerR.find(':input').length;

          // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
          $('#add_reponse').click(function(e) {
            addReponse($containerR);

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
          });

          // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
          if (indexR == 0) {
            addReponse($containerR);
          } else {
            // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
            $containerR.children('div').each(function() {
              addDeleteLink($(this));
            });
          }

          // La fonction qui ajoute un formulaire CategoryType
          function addReponse($container) {
            // Dans le contenu de l'attribut « data-prototype », on remplace :
            // - le texte "__name__label__" qu'il contient par le label du champ
            // - le texte "__name__" qu'il contient par le numéro du champ
            var template = $container.attr('data-prototype')
              .replace(/__reponse__label__/g, 'Reponse n°' + (indexR+1))
              .replace(/__reponse__/g,        indexR)
            ;

            // On crée un objet jquery qui contient ce template
            var $prototype = $(template);

            // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
            addDeleteLink($prototype);

            // On ajoute le prototype modifié à la fin de la balise <div>
            $container.append($prototype);

            $addReponseLink.before($prototype);

            // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
            indexR++;
          }

          // La fonction qui ajoute un lien de suppression d'une catégorie
          function addDeleteLink($prototype) {
            // Création du lien
            var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

            // Ajout du lien
            $prototype.append($deleteLink);

            // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
            $deleteLink.click(function(e) {
              $prototype.remove();
              indexR--;
              e.preventDefault(); // évite qu'un # apparaisse dans l'URL
              return false;
            });
          }
        });