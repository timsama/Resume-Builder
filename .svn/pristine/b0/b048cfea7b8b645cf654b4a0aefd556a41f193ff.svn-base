/**
 * 
 */

// gets the current list of users and displays them in the users_table div
function getUsers(){
	
	// adapted from Jim's gender_example.js
	$.ajax(
	{
	    type:     'POST',
	    url:      'ajax/getusers.php',
	    data:     '',
	    dataType: 'html',  		      // The type of data that is getting returned.
	    
	    beforeSend: function(){ },
	    
	    success: function(response)
	    {
	    	$('#users_table').html(response);
	    },
	    
	    error: function( response, options, error )
	    {
	    	// something went wrong
	    	var jContent = $('#users_table');
	    	jContent.html( "<h3>Fatal Error</h3>"  );
	    	console.log('response.statusText: ' + response.statusText );
	    	console.log('options: ' + options );
	    	console.log('error: ' + error );
	    }

	});
}

//deletes a user and then refreshes the users_table div
function deleteUser(userid){
	
	// adapted from Jim's gender_example.js
	$.ajax(
	{
	    type:     'POST',
	    url:      'ajax/deleteuser.php',
	    data:     {userid: userid},
	    dataType: 'html',  		      // The type of data that is getting returned.
	    
	    beforeSend: function(){ return confirm('Are you sure you want to delete this user?'); },
	    
	    success: function(response)
	    {
	    	$('#users_table').html(response);
	    },
	    
	    error: function( response, options, error )
	    {
	    	// something went wrong
	    	var jContent = $('#users_table');
	    	jContent.html( "<h3>Fatal Error</h3>"  );
	    	console.log('response.statusText: ' + response.statusText );
	    	console.log('options: ' + options );
	    	console.log('error: ' + error );
	    }

	});
}

// updates a user's account role and then refreshes the users_table div
function updateRole(userid){
	// get the role from the user's radio button selection
	var role = $('input:radio[name="role' + userid + '"]:checked').val();
	
	// adapted from Jim's gender_example.js
	$.ajax(
	{
	    type:     'POST',
	    url:      'ajax/updateuserrole.php',
	    data:     {userid: userid, role: role},
	    dataType: 'html',  		      // The type of data that is getting returned.
	    
	    beforeSend: function(){ },
	    
	    success: function(response)
	    {
	    	$('#users_table').html(response);
	    },
	    
	    error: function( response, options, error )
	    {
	    	// something went wrong
	    	var jContent = $('#users_table');
	    	jContent.html( "<h3>Fatal Error</h3>"  );
	    	console.log('response.statusText: ' + response.statusText );
	    	console.log('options: ' + options );
	    	console.log('error: ' + error );
	    }

	});
}