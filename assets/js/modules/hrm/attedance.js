let oTableModal;

const config_validate_form = {
    submitHandler: function (form,e) {
            App_template.loadingStart();
            $(form).ajaxSubmit({
                success: async function (data) {
                    try{
                    	data = JSON.parse(data);
                    	set_data_from_date(data);
                    	pageDepartmentList(data);
                        await App_template.timeout(3000);
                        App_template.loadingEnd();
                    }
                    catch(err){
                        console.log(err);
                        await App_template.timeout(1000);
                         App_template.loadingEnd();
                    }
                    
                },
                error : function(){
                     App_template.loadingEnd();
                }
            });
        return false;
    }
};

const _set_html_nav = (dataSearch) => {
	let html = '';

	html = '<div class="panel with-nav-tabs panel-success">'+
				'<div class="panel-heading">'+
					'<ul class="nav nav-tabs">'+
						dataSearch.map( (obj,indeks) => {
								return '<li class = "'+((obj.selected) ? 'active' : '')+'"><a href = "javascript:void(0)" key = "'+obj['department_id']+'" class = "navDepartment">'+obj.code_bagian+''+' <span style = "color:blue;">('+obj.attedance_total_in+')</span>'+'</a></li>';
						}).join('')+
					'</ul>'+	
				'</div>'+
				'<div class="panel-body">'+
					'<div class="tab-content">'+
						dataSearch.map((obj,indeks) => {
							const attedance = obj.attedance;
							return '<div class = "tab-pane '+((obj.selected) ? 'active in' : '' )+' " nav-active = "'+((obj.selected) ? 'active' : '' )+'">'+
										'<div class = "row">'+
											'<div class = "col-md-12">'+
												'<table class = "table tableInput">'+
													'<thead>'+
														'<tr>'+
															'<th>'+get_language['nama']+'</th>'+
															'<th>'+get_language['code']+'</th>'+
															'<th>'+get_language['code_groups']+'</th>'+
															'<th>'+get_language['start']+'</th>'+
															'<th>'+'*'+'</th>'+
														'</tr>'+
													'</thead>'+
													'<tbody>'+
													attedance.map( (objAtt,indeksAtt) => {
														return '<tr id-attedance = "'+objAtt.attedance_id+'">'+
																	'<td>'+
																		objAtt.nama+
																	'</td>'+
																	'<td>'+
																		objAtt.code+
																	'</td>'+
																	'<td>'+
																		objAtt.code_groups+
																	'</td>'+
																	'<td>'+
																		'<div id="div_formTime" data-no="1" class="input-group div_formTime">'+
											                                '<input data-format="hh:mm" type="text" class="form-control form-start formtime" value="'+reformat_time(objAtt.start)+'" readonly />'+
											                                '<span class="add-on input-group-addon">'+
											                                    '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>'+
											                                '</span>'+
											                            '</div>'+
																	'</td>'+	
																	'<td>'+'<button class = "btn btn-sm btn-danger btn-delete-employee-attendance" data-employee_id = "'+objAtt.employee_id+'">'+get_language['delete']+'</button>'+
																	'</td>'+
															   '</tr>';	
													}).join('')+
													'</tbody>'+
												'</table>'+
											'</div>'+
										'</div>'+
								   '</div>';
						}).join('')+
					'</div>'+
				'</div>'+
		   '</div>';

		   return html;
}

const set_data_from_date = (dataSearch) => {
		let temp = [];
		data_from_date = temp.slice(0);
		data_select_modal = temp.slice(0);
		data_from_select_modal = temp.slice(0);

		data_from_date = dataSearch.find(key => {
			return key.selected === true;
		})
}


const pageDepartmentList = (dataSearch) => {
	const selPage = $('.formInputListData');
	let html = '';


	let set_html_nav = _set_html_nav(dataSearch);

	html = '<div class = "row">'+
				'<div class = "col-md-12">'+
					'<div class = "pull-right">'+
						'<button class = "btn btn-sm btn-add-employee">Add Employee</button>'+
					'</div>'+
				'</div>'+
		   '</div>'+
		   '<div class = "row" style = "margin-top:10px;">'+
		   		'<div class = "col-md-12">'+
		   			set_html_nav+
		   		'</div>'+
		   '</div>';
	selPage.html(html);

	$('.div_formTime').datetimepicker({
	    pickDate: false,
	    pickSeconds : false
	});
}	


const load_default = async() => {

	// datepicker
	$('.date').datepicker({
	    format: 'yyyy-mm-dd',
	    autoclose: true
	});

	$("#form").validate(config_validate_form);

	// disable error validator summernote
	$('#form').each(function () {
	    if ($(this).data('validator'))
	        $(this).data('validator').settings.ignore = ".note-editor *";
	});
}

const navDepartment_click = async(itsme) => {
	const department_id = itsme.attr('key');
	App_template.loadingStart();
	try{
		const url = data_php['form'].action;
		let data = {
			select_date : $('#select_date').val(),
			department_id : department_id,
		};

		const json = await App_template.AjaxSubmitFormPromisesNoToken(url,data);
		set_data_from_date(json);
		pageDepartmentList(json);

		await App_template.timeout(3000);
		App_template.loadingEnd();
	}
	catch(err){
		console.log(err);
		App_template.loadingEnd();
	}
}

const set_data_from_select_modal = () => {
	let temp = [];
	data_from_select_modal = temp.slice(0);
};

const modal_add_employee = async() => {
	set_data_from_select_modal();

	const where_in = data_from_date['attedance'].map(x => x.employee_id);

	const url = data_php.module_url+'list_modal_employee';
	const data = {
		department_id :data_from_date.department_id,
		where_in : where_in
	};
	const token = jwt_encode(data,jwtKey);
	try{
		const get_html =  await App_template.AjaxHtmlFormPromises(url,token);
		$('#ModalFormLarge .modal-title').html('<h4>Add Employee</h4>');
		$('#ModalFormLarge .modal-footer').html('<button type = "button" class = "btn btn-success btnSaveModal">Submit</button> &nbsp <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

		let html = '';
		html = '<div class = "row">'+
					'<div class = "col-xs-6">'+
						get_html+
					'</div>'+
					'<div class = "col-xs-6 page_from_select_modal">'+
						'<table class = "table table_data_from_select_modal">'+
							'<thead>'+
								'<tr>'+
									'<th>'+get_language['code']+'</th>'+
									'<th>'+get_language['nama']+'</th>'+
									'<th>'+get_language['code_groups']+'</th>'+
								'</tr>'+
							'</thead>'+
							'<tbody></tbody>'+
						'</table>'+
					'</div>'+
				'</div>';
		$('#ModalFormLarge .modal-body').html(html);

        $('#ModalFormLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        // $('#ModalFormLarge').find('.filterSearch').find('select[tabindex!="-1"]').removeClass('form-control');
        // $('#ModalFormLarge').find('.filterSearch').find('select[tabindex!="-1"]').removeClass('form-control-sm');
        $('#ModalFormLarge').find('.filterSearch').find('select[tabindex!="-1"]').select2({
            allowClear: true
        });

        var columns = [];
        __modal_table_config();
        $('#ModalFormLarge ').find('.column th').each(function () {
            columns.push({
                data: $(this).attr('data-data')
            });
        });
        modal_table_config.columns = columns;
        var table = $('#ModalFormLarge').find('#table_default').DataTable(modal_table_config);
        oTableModal = table;
        $('#ModalFormLarge').find('.buttons-colvis').detach().appendTo('#action');
        $('#ModalFormLarge').on('keyup', '.filterSearch input', function (e) {
            if (e.keyCode == 13) {
                oTableModal.ajax.reload((e) => {
                    // event function
                });
            }
        });

        $('#ModalFormLarge').on('change', '.filterSearch select', function (e) {
            oTableModal.ajax.reload((e) => {
                
            });
        });

	}
	catch(err){
		console.log(err);
	}
}

const set_add_data_from_select_modal = (itsme) => {
	const decode_data_token = jwt_decode(itsme.attr('data-token'));
	const new_data = {
		employee_id : decode_data_token.employee_id,
		code : decode_data_token.code,
		code_groups : decode_data_token.code_groups,
		nama : decode_data_token.nama
	};

	// check data exist
	const check = data_from_select_modal.some(obj => obj.employee_id === new_data.employee_id );

	if (!check) { // if no data then add
		data_from_select_modal = [...data_from_select_modal,new_data];
	}

	set_table_data_from_select_modal();
}

const set_table_data_from_select_modal = () => {
	$('.table_data_from_select_modal').find('tbody').html(
			data_from_select_modal.map((obj,indeks) => {
				return '<tr>'+
							'<td>'+obj.code+'</td>'+
							'<td>'+obj.nama+'</td>'+
							'<td>'+obj.code_groups+'</td>'+
							'<td>'+'<button class = "btn btn-sm btn-danger btn-delete-employee" data-key="'+obj.employee_id+'">'+get_language['delete']+'</button>'+
							'</td>'+
						'</tr>'
			}).join('')
	);

}

const set_data_select_modal = (itsme) => {

	attedance =  data_from_date.attedance;

	const get_data_select_modal = data_from_select_modal.filter(o => {
		return data_select_modal.every((k,v) => {
			//console.log(o.employee_id+ ' ==== '+ k.employee_id)
			if (o.employee_id !== k.employee_id) {
				return true;
			}

			return false;
		})
	});
	

	get_data_select_modal.forEach(a=>data_select_modal.push(a));

	data_select_modal = data_select_modal.filter(o => {
		return attedance.every((k,v) => {
			if (o.employee_id !== k.employee_id) {
				return true;
			}

			return false;
		})
	});

	set_in_tableInput();
	
	data_select_modal.forEach(a=>attedance.push(a));
}

const set_in_tableInput = () => {
	$('.tab-pane[nav-active="active"]').find('.tableInput').find('tbody').append(
		data_select_modal.map( (obj,indeks) =>{
			return '<tr id-attedance = "null">'+
						'<td>'+
							obj.nama+
						'</td>'+
						'<td>'+
							obj.code+
						'</td>'+
						'<td>'+
							obj.code_groups+
						'</td>'+
						'<td>'+
							'<div id="div_formTime" data-no="1" class="input-group input-append date datetimepicker div_formTime">'+
                                '<input data-format="hh:mm" type="text" class="form-control form-start formtime" value="00:00" readonly />'+
                                '<span class="add-on input-group-addon">'+
                                    '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>'+
                                '</span>'+
                            '</div>'+
						'</td>'+	
						'<td>'+'<button class = "btn btn-sm btn-danger btn-delete-employee-attendance" data-employee_id = "'+obj.employee_id+'">'+get_language['delete']+'</button>'+
						'</td>'+
				   '</tr>';
		}).join('')
	);

	$('.div_formTime').datetimepicker({
	    pickDate: false,
	    pickSeconds : false
	});
}

const delete_data_from_select_modal = (itsme) => {
	const employee_id = itsme.attr('data-key');
	data_from_select_modal = data_from_select_modal.filter(x => x.employee_id !== employee_id);
	set_table_data_from_select_modal();
}

const delete_data_from_date = async(itsme) => {
	attedance_id = itsme.closest('tr').attr('id-attedance');
	employee_id = itsme.attr('data-employee_id');
	attedance =  data_from_date.attedance;
	new_attedance = attedance.filter(x => {
		if (attedance_id == 'null') {
			if (employee_id !== x.employee_id && x.attedance_id === undefined) {
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return attedance_id !== x.attedance_id;
		}

		return false;
	})

	data_from_date.attedance = new_attedance.slice(0);

	data_select_modal =  data_select_modal.filter(x => x.employee_id !== employee_id);

	itsme.closest('tr').remove();
}

const save_attedance = async(itsme) => {
	let htmlBtn = itsme.html();
	try{

		if (confirm(get_language['confirmation'])) {
			App_template.loading_button(itsme);
			const url = data_php.module_url+'save_attedance';
			const attedance_date = $('#select_date').val();
			let data = [];
			$('.tab-pane[nav-active="active"]').find('.tableInput').find('tbody').find('tr').each(function(e){
				const employee_id = $(this).find('.btn-delete-employee-attendance').attr('data-employee_id');
				const start = $(this).find('.formtime').val();

				const temp = {
					employee_id : employee_id,
					start : start,
					attedance_date : attedance_date,
				}

				data.push(temp);
			})

			const new_data = {
				department_id : data_from_date.department_id,
				data : data,
			}

			
			const token = jwt_encode(new_data,jwtKey);

			const json = await App_template.AjaxSubmitFormPromises(url,token)
			if (json == 1 || json == '1') {
				toastr.success(get_language['save_success2']);
				$('.nav').find('li[class="active"]').find('.navDepartment').trigger('click');
			}
			else
			{
				toastr.error('error!!!');
			}

			App_template.end_loading_button(itsme,htmlBtn);
		}
		
	}
	catch(err){
		console.log(err);
		App_template.end_loading_button(itsme,htmlBtn);
		toastr.error(err);
	}
	

}

const reformat_time = (timeData) =>{
	 var st = timeData.split(':');
	 return timeData = moment().hour(st[0]).minute(st[1]).format('HH:mm');
} 

$(document).ready(function(e){
	load_default();
})

$(document).on('click','.navDepartment',function(e){
	const itsme = $(this);
	navDepartment_click(itsme);
})

$(document).on('click','.btn-add-employee',function(e){
	modal_add_employee();
})

$(document).on('click','.btn_data_select_modal',function(e){
	const itsme = $(this);
	set_add_data_from_select_modal(itsme);
})

$(document).on('click','.btnSaveModal',function(e){
	const itsme =  $(this);
	set_data_select_modal(itsme);
})

$(document).on('click','.btn-delete-employee',function(e){
	delete_data_from_select_modal($(this));
})

$(document).on('click','.btn-delete-employee-attendance',function(e){
	delete_data_from_date($(this));
})

$(document).on('click','.btn-save-attedance',function(e){
	save_attedance($(this));
})