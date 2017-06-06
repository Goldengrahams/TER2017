$(document).ready(function() {

  // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
  var $container = $('div#hlin601_moocbundle_qcm_questions');

  var $addQuestionLink = $('<a href="#" id="add_question" class="btn btn-default">Ajouter une Question</a>');

  $container.append($addQuestionLink);

  // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
  var index = $container.find(':input').length;

  var indexR = [];

  // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
  $('#add_question').click(function(e) {
    addQuestion($container);

    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
    return false;
  });

  // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
  if (index == 0) {
    addQuestion($container);
  } else {
    // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
    $container.children('div').each(function() {
      addDeleteLink($(this));
    });
  }         

  


  // La fonction qui ajoute un formulaire CategoryType
  function addQuestion($container) {
    // Dans le contenu de l'attribut « data-prototype », on remplace :
    // - le texte "__name__label__" qu'il contient par le label du champ
    // - le texte "__name__" qu'il contient par le numéro du champ
    var template = $container.attr('data-prototype')
      .replace(/__name__label__/g, 'Question')
      .replace(/__name__/g,        index)
    ;

    // On crée un objet jquery qui contient ce template
    var $prototype = $(template);

    // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
    addDeleteLinkQuestion($prototype);

    // On ajoute le prototype modifié à la fin de la balise <div>
    $container.append($prototype);

    indexR.push(0);

    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $containerR = $('div#hlin601_moocbundle_qcm_questions_'+index+'_reponses');

    var $addReponseLink = $('<a href="#" id="add_reponse_'+index+'" class="btn btn-default">Ajouter une Réponse</a>');

    $containerR.append($addReponseLink);

    $('#add_reponse_'+index).click(function(e) {
    addReponse($containerR);

    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
    return false;
  });

    // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
    index++;
  }


  // La fonction qui ajoute un formulaire CategoryType
  function addReponse($container) {
    // Dans le contenu de l'attribut « data-prototype », on remplace :
    // - le texte "__name__label__" qu'il contient par le label du champ
    // - le texte "__name__" qu'il contient par le numéro du champ
    console.log('indexR = '+indexR);
    console.log('index = '+index);
    console.log('indexR[index-1] = '+indexR[index-1]);

    var template = $container.attr('data-prototype')
      .replace(/__reponse__label__/g, 'Reponse')
      .replace(/__reponse__/g,        indexR[index-1])
    ;

    // On crée un objet jquery qui contient ce template
    var $prototype = $(template);

    // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
    addDeleteLinkReponse($prototype);

    // On ajoute le prototype modifié à la fin de la balise <div>
    $container.append($prototype);

    //$addReponseLink.before($prototype);

    // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
    indexR[index-1]++;
  }

  // La fonction qui ajoute un lien de suppression d'une catégorie
  function addDeleteLinkQuestion($prototype) {
    // Création du lien
    var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer Question</a>');

    // Ajout du lien
    $prototype.append($deleteLink);

    // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
    $deleteLink.click(function(e) {
      $prototype.remove();
      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
      return false;
    });
  }

  // La fonction qui ajoute un lien de suppression d'une catégorie
  function addDeleteLinkReponse($prototype) {
    // Création du lien
    var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer Reponse</a>');

    // Ajout du lien
    $prototype.append($deleteLink);

    // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
    $deleteLink.click(function(e) {
      $prototype.remove();
      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
      return false;
    });
  }
});