function getCompanies(elem){
    let countryId = elem.value;
    let base_url = window.location.origin;
    if (countryId != '') {
        $('#companies').empty();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: base_url+"/admin/companies/country/"+countryId,
            dataType: "json",
            beforeSend: function(){
            },
            success: function(response){

                console.log(response);
                console.log(response['status']);

                if(response['status']) {
                    let newOpt = '';
                    newOpt += "<option value=''>Choose Company</option>"
                    $.each(response['companies'], function(index,company){
                        console.log(index+">"+company)
                        newOpt += "<option value='"+company['id']+"'>"+company['name']+"</option>"
                    });

                    $("#companies").append(newOpt);
                }
            }
        });
    }
}