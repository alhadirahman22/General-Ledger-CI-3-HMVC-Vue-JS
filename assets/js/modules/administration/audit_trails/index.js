$(document).on('click','.btnRequestDetail',function(e){
	const itsme =  $(this);
	const data_token_show = jwt_decode(itsme.attr('data-token'));
	const usernameShow = itsme.attr('data-user');

	$('#ModalForm .modal-title').html('<h4>Request Detail '+usernameShow+'</h4>');
	$('#ModalForm .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
	$('#ModalForm .modal-body').html(data_token_show);

	$('#ModalForm').modal({
	    'show' : true,
	    'backdrop' : 'static'
	});
})

$(document).on('click','.btnHeaderDetail',function(e){
	const itsme =  $(this);
	const data_token_show = jwt_decode(itsme.attr('data-token'));
	const usernameShow = itsme.attr('data-user');

	$('#ModalForm .modal-title').html('<h4>Header Detail '+usernameShow+'</h4>');
	$('#ModalForm .modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
	$('#ModalForm .modal-body').html(data_token_show);

	$('#ModalForm').modal({
	    'show' : true,
	    'backdrop' : 'static'
	});
})