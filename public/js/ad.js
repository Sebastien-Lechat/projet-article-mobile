$('#add-image').click(function(){
    //Je Récupére le numero des futurs champs que je vais Créer
    const index= +$('#widgets-counter').val();
    //Récupere le prototype des entrées
    const tmpl= $('#ad_images').data('prototype').replace(/__name__/g, index);
    //J'injecte ce code au sein de le div
    //console.log(tmpl);-test 
  $('#ad_images').append(tmpl);
  // Je rajoute 1 aux widgets-counter 
  $('widgets-counter').val(index + 1);
  //je gere le bouton surpprimer
    handleDeleteButtons();
 });

 function handleDeleteButtons()
 {
     //ciblé tout les buttons data-action 
     $('button[data-action="delete"]').click(function()
     {
          //Ajout d'un élément sur clique 
        const target=this.dataset.target;
         $(target).remove();
         //console.log(target);
      });
 }

    function update(){
        const count= +$('#ad_images div.form-group').length;
       $('#wigets-counter').val(count);

    }
        //Appel de la fonction dès le debut de rechargement la page 
         handleDeleteButtons();
         update();