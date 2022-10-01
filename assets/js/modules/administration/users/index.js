// const get_select2_employees = async() => {
// 	const url = base_url + 'administration/user_management/users/select2_employees';
// 	const selector = $('#employee_id');
// 	const selected_id = $('#employee_id option:selected').val(); 
// 	const selected_id_text = $('#employee_id option:selected').text(); 
// 	const placeholder = {
// 		id: selected_id, // the value of the option
// 		text: selected_id_text
// 	} 

// 	try{
// 		const response = await App_template.select2LoadDB(selector,url,3,placeholder);
// 	}
// 	catch(err){
// 		console.log(err);
// 	}
// }

// $(document).ready(function(e){
// 	get_select2_employees();
// })