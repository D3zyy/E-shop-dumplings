




    $(document).ready(function() {
 
        $('input[type="search"]').on('input', function() {
           
            var query = $(this).val().toLowerCase();
            $('.card').each(function() {
                var productName = $(this).find('.card-text').text().toLowerCase();
                if (productName.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

