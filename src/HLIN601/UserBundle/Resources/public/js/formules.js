var $formule = $('#fos_user_registration_form_formule');
if(!$formule.length){
	$formule = $('#fos_user_profile_form_formule');
}
$formule.change(function() {
	var $form = $(this).closest('form');
	var data = {};
	data[$formule.attr('name')] = $formule.val();
	$.ajax({
		url : $form.attr('action'),
		type: $form.attr('method'),
		data : data,
		success: function(html) {
			var tab = [
				'#fos_user_registration_form_matieres',
				'#fos_user_registration_form_classe',
				'#fos_user_registration_form_singleMatiere',
				'#fos_user_profile_form_matieres',
				'#fos_user_profile_form_classe',
				'#fos_user_profile_form_singleMatiere'
			];
			var $select = $('#fos_user_registration_form_matieres');
			var i = 0;
			while(!$select.length && i < tab.length){
				$select = $(tab[i]);
				i++;
			}

			$label = $($select).closest('.form-group').find('label');
			console.log('old');
			console.log($select);
			console.log($label);

			i = 0;
			if($formule.val() === 'class'){
				$newSelect = $(html).find('#fos_user_registration_form_classe')
				if(!$newSelect.length){
					$newSelect = $(html).find('#fos_user_profile_form_classe')
				}
			}
			else if($formule.val() === 'one'){
				$newSelect = $(html).find('#fos_user_registration_form_singleMatiere')
				if(!$newSelect.length){
					$newSelect = $(html).find('#fos_user_profile_form_singleMatiere')
				}
			}
			else{
				var $newSelect = $(html).find('#fos_user_registration_form_matieres');
				if(!$newSelect.length){
					$newSelect = $(html).find('#fos_user_profile_form_matieres')
				}
			}
			$newLabel = $($newSelect).closest('.form-group').find('label');
			console.log('new');
			console.log($newSelect);
			console.log($newLabel);
			$select.replaceWith($newSelect);
			$label.replaceWith($newLabel);
    	}
  	});
});