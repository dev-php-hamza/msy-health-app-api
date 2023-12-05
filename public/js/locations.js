$(document).ready(function(){
     $("#checkAll").click(function () {
         $('#users input:checkbox').not(this).prop('checked', this.checked);
     });
})
function getLocations(elem){
    let countryId = elem.value;
    let base_url = window.location.origin;
    if (countryId != '') {
        $('#locations').empty();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: base_url+"/admin/locations/country/"+countryId,
            dataType: "json",
            beforeSend: function(){
            },
            success: function(response){

                console.log(response);
                console.log(response['status']);

                if(response['status']) {
                    // $('#locations').removeAttr('disabled');
                    let newOpt = '';
                    newOpt += "<option value=''>Please Choose Locaility</option>"
                    $.each(response['locations'], function(index,location){
                        console.log(index+">"+location)
                        newOpt += "<option value='"+location['id']+"'>"+location['name']+"</option>"

                    });

                    $("#locations").append(newOpt);
                }
            }
        });
    }
}

function getCompAndLoc(elem){
    let countryId = elem.value;
    let base_url = window.location.origin;
    if (countryId != '') {
        $('#companies').empty();
        $('#locations').empty();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: base_url+"/admin/locations/country-companies/"+countryId,
            dataType: "json",
            beforeSend: function(){
            },
            success: function(response){

                console.log(response);
                console.log(response['status']);

                if(response['status']) {
                    let newOpt = '';
                    newOpt += "<option value=''>Please Choose Company</option>"
                    $.each(response['companies'], function(index,company){
                        newOpt += "<option value='"+company['id']+"'>"+company['name']+"</option>"
                    });

                    let newOptLoc = '';
                    newOptLoc += "<option value=''>Please Choose Location</option>"
                    $.each(response['locations'], function(index,location){
                        newOptLoc += "<option value='"+location['id']+"'>"+location['name']+"</option>"
                    });

                    $("#companies").append(newOpt);
                    $("#locations").append(newOptLoc);
                }
            }
        });
    }
}

function getUsersbyCountry(elem){
    let countryId = elem.value;
    let base_url = window.location.origin;
    if (countryId != '') {
        $('#users').empty();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: base_url+"/admin/notifications/country-users/"+countryId,
            dataType: "json",
            beforeSend: function(){
            },
            success: function(response){
                console.log(response);
                console.log(response['status']);

                if(response['status']) {
                    // $('#locations').removeAttr('disabled');
                    let newChkBox = '';
                    $.each(response['users'], function(index,user){
                        newChkBox +=  "<div class='col-md-3'><input type='checkbox' name='user[]' value="+user['id']+" id=''>"+user['name']+"<br><br></div>";
                    });

                    $("#users").append(newChkBox);
                }
            }
        });
    }
}

