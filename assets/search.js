




    $(document).ready(function() {
        // Nastavení obsluhy události pro změny ve vyhledávacím poli
        $('input[type="search"]').on('input', function() {
            // Získání hodnoty z vyhledávacího pole
            var query = $(this).val().toLowerCase();

            // Skrytí a zobrazení karet na základě shody s hledaným textem
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

