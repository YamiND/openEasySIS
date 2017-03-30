<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewCreateUserForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}


function viewCreateUserForm($mysqli)
{
    echo '
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Create User");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#administrator" data-toggle="tab">User</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="administrator">
                                    <br>
            ';
								createUserForm();
        echo '
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
';

}

function createUserForm()
{
echo <<<EOF
<form action="../includes/adminFunctions/createUser" method="POST">

  <div class="form-group">
    <label>User's Email</label>
    <input class="form-control" type="email" placeholder="User's Email" name="userEmail">
  </div>
  
  <div class="form-group">
    <label>User's First Name</label>
    <input class="form-control" type="text" placeholder="User's First Name" name="userFirstName">
  </div>
  
  <div class="form-group">
    <label>User's Last Name</label>
    <input class="form-control" type="text" placeholder="User's Last Name" name="userLastName">
  </div>

<label>Special User Permissions</label> 
  <div class="form-group">
     <div class="checkbox">
		<label>
      	<input type="checkbox" name="modClassList"> User can assign student to Classes
		</label>
     </div>
   </div>
  <div class="form-group">
     <div class="checkbox">
		<label><input type="checkbox" name="viewAllGrades"> User can view all Student Grades</label>
     </div>
   </div>

  <label>Select User Roles:</label> <br> 
   <div class="form-group">
     <div class="checkbox">
		<label>
         <input type="checkbox" id="isAdmin">Is Admin
		</label>
     </div>
   </div>
   <div class="form-group">
     <div class="checkbox">
		<label>
         <input type="checkbox" id="isPrincipal">Is Principal
		</label>
     </div>
   </div>
   <div class="form-group">
     <div class="checkbox">
		<label>
         <input type="checkbox" id="isSchoolAdmin">Is School Admin
		</label>
         </div>
      </div>
         <div class="form-group">
     <div class="checkbox">
		<label>
         <input type="checkbox" id="isTeacher">Is Teacher
		</label>
         </div>
      </div>
         <div class="form-group" id="isParentDiv">
     <div class="checkbox">
			<label>
        		<input type="checkbox" id="isParent">Is Parent
			</label>
          </div>
      </div>
         <div class="form-group" id="isStudentDiv">
     <div class="checkbox">
		<label>
        	<input type="checkbox" id="isStudent">Is Student
		</label>
      </div>
      </div>
    
     <button name="creatUserButton" type="submit" class="btn btn-default">Create User</button>
     
</form>

<script>
$(document).ready(function(){
   /*
    When Checkbox 1 is checked, the 1 input field will be created. If it is unchecked and the field exists, it will be removed from the DOM. When removing the element, all data related to it will go with it, including any bound events and held data.
    */
    $('#isAdmin').click(function(){
       if(document.getElementById('isAdmin').checked){
       // $('#isAdmin').after('<div class="form-group" id="isAdminDiv">1: <input type="text" name="adminBox" id="text1"></div>');
       $('#isAdmin').after('<input type="hidden" id="userIsAdmin" name="userIsAdmin" value="1">');
        }
        else{
            if( $('#userIsAdmin').length ){
                $('#userIsAdmin').remove();
            }
        }
    });

     $('#isPrincipal').click(function(){
       if(document.getElementById('isPrincipal').checked){
       $('#isPrincipal').after('<input type="hidden" id="userIsPrincipal" name="userIsPrincipal" value="1">');
        }
        else{
            if( $('#userIsPrincipal').length ){
                $('#userIsPrincipal').remove();
            }
        }
    });

     $('#isSchoolAdmin').click(function(){
       if(document.getElementById('isSchoolAdmin').checked){
       /*
         We have to use .after() to identify where the new element should be created in the DOM. In this case, it goes just after the checkbox. If your input field is going into a weird spot you can always make an invisible div right where it should be. The label has to be placed inside of the div so it is remove with the input field, you may want to assign the input field its own ID.
         */
       // $('#isAdmin').after('<div class="form-group" id="isAdminDiv">1: <input type="text" name="adminBox" id="text1"></div>');
       $('#isSchoolAdmin').after('<input type="hidden" id="userIsSchoolAdmin" name="userIsSchoolAdmin" value="1">');
        }
        else{
            if( $('#userIsSchoolAdmin').length ){
                $('#userIsSchoolAdmin').remove();
            }
        }
    });
    
    $('#isTeacher').click(function(){
       if(document.getElementById('isTeacher').checked){
       /*
         We have to use .after() to identify where the new element should be created in the DOM. In this case, it goes just after the checkbox. If your input field is going into a weird spot you can always make an invisible div right where it should be. The label has to be placed inside of the div so it is remove with the input field, you may want to assign the input field its own ID.
         */
       // $('#isAdmin').after('<div class="form-group" id="isAdminDiv">1: <input type="text" name="adminBox" id="text1"></div>');
       $('#isTeacher').after('<input type="hidden" id="userIsTeacher" name="userIsTeacher" value="1">');
        }
        else{
            if( $('#userIsTeacher').length ){
                $('#userIsTeacher').remove();
            }
        }
    });
    
    
    /*
     When Checkbox 2 is checked, we check to see if we have created the 1 input field yet. If so, we create the 2 input field below it. If we have not created it, we create the 2 input field below Checkbox 2. Again, when unchecked the element is removed if it exists.
     */
    $('#isParent').click(function(){
        if(document.getElementById('isParent').checked){
            if( $('#isParentDiv').length){
                $('#isParentDiv').after('<div class="form-group" id="userIsParent"><input type="hidden" id="userIsParent" name="userIsParent" value="1"></div> <div class="form-group" id="parentAddressField"><input type="text" class="form-control" name="parentAddress" placeholder="Home Address"></div><div class="form-group" id="parentPhoneField"> <input type="text" class="form-control" name="parentPhone" placeholder="Phone Number"></div>');
             }
             else{
                 $('#isParentDiv').after('<div class="form-group" id="userIsParent"><input type="hidden" id="userIsParent" name="userIsParent" value="1"></div> <div class="form-group" id="parentAddressField"><input class="form-control" type="text" name="parentAddress" placeholder="Home Address"></div><div class="form-group" id="parentPhoneField"> <input type="text" class="form-control" name="parentPhone" placeholder="Phone Number"></div>');
             }
         }
         else{
             if ( $('#userIsParent').length ){
                  $('#userIsParent').remove();
             }
             if ( $('#parentAddressField').length ){
                  $('#parentAddressField').remove();
             }
              if ( $('#parentPhoneField').length ){
                  $('#parentPhoneField').remove();
             }
        } 
    });
    
    $('#isStudent').click(function(){
        if(document.getElementById('isStudent').checked){
            if( $('#isStudentDiv').length){
                $('#isStudentDiv').after('<div class="form-group" id="userIsStudent"><input type="hidden" id="userIsStudent" name="userIsStudent" value="1"></div><div class="form-group" id="studentGradeField"><label>Student Grade Level</label> <select class="form-control" name="studentGradeLevel"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></div><div class="form-group"><label>Student Birthdate</label><input class="form-control" type="date" name="studentBirthdate"></div>');
             }
             else{
                $('#isStudentDiv').after('<div class="form-group" id="userIsStudent"><input type="hidden" id="userIsStudent" name="userIsStudent" value="1"></div><div class="form-group" id="studentGradeField"><label>Student Grade Level</label> <select class="form-control" name="studentGradeLevel"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></div><div class="form-group"><label>Student Birthdate</label><input class="form-control" type="date" name="studentBirthdate"></div>');
             }
         }
         else{
             if ( $('#userIsStudent').length ){
                  $('#userIsStudent').remove();
             }
             if ( $('#studentGradeField').length ){
                  $('#studentGradeField').remove();
             }
        } 
    });
    
  });
</script>

EOF;

}

?>
