$.ajax({
    type: 'POST',
    url: "http://127.0.0.1/" ,   //...url
    data: {
        action: 'myModuleAjax'
        
    },
    dataType: 'json',
    success: function (response) {
        let moduleName = response.name
        console.log('name :' + moduleName);
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.error(thrownError);
    }
}); 