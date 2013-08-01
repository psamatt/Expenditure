function editExpenditure(anchor) {

    $anchor = $(anchor);
    
    var $form = $('form[name="expenditureForm"]');
    
    $form.find('input[name="expenditureID"]').val($anchor.data('id'));
    $form.find('input[name="title"]').val($anchor.data('title'));
    $form.find('input[name="price"]').val($anchor.data('price'));
}