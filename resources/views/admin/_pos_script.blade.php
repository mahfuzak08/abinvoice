<script>
// product selection
      $(function() {
        $("#product-search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete_product_search') }}",
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name + ' - ' + item.category_name + ' - ' + item.size,
                                value: item,
                                id: item.id
                            };
                        }));
                    }
                });
            },
            minLength: 2, // Minimum characters to trigger autocomplete
            select: function(event, ui) {
                // Do something when a product is selected
                let already = false;
                $("#items .item").each(function(){
                  if($(this).find('.product_id').val() === ui.item.value.id + '@' + ui.item.value.variant_id){
                    already = true;
                    $(this).find('.quantity').val(Number($(this).find('.quantity').val()) + 1);
                  }
                });
                
                if(already === false){
                  let rowsample = $("#rowsample");
                  let rowlen = Number($("#items tr").length);
                  let clonedRow = rowsample.clone(); // Clone the last row
                  clonedRow.find('.sl').text(rowlen+1); // row number
                  clonedRow.removeClass('hidden'); // remove hidden class
                  clonedRow.find('input[type="text"]').val(''); // clear text field
                  clonedRow.find('.product_id').val(ui.item.value.id + '@' + ui.item.value.variant_id); // add product name
                  clonedRow.find('.product_name').val(ui.item.value.size ? ui.item.value.name + "(" + ui.item.value.size + ")" : ui.item.value.name); // add product name
                  clonedRow.find('.product_name_txt').text(ui.item.value.size ? ui.item.value.name + "(" + ui.item.value.size + ")" : ui.item.value.name); // add product name
                  clonedRow.find('.product_details').val(ui.item.value.description); // add product description
                  clonedRow.find('.product_details_txt').html(ui.item.value.description + "<br>Stock Qty. = " + ui.item.value.qty); // add product description
                  clonedRow.find('.quantity').val(1); // add product quantity
                  if($("#rowsample").hasClass('sales'))
                    clonedRow.find('.price').val(ui.item.value.price); // add product sales price
                  else
                    clonedRow.find('.price').val(ui.item.value.buy_price); // add product price
                  
                  // Append the cloned row to the table body
                  $("#items").append(clonedRow);
                }
                adjust_price();

                $("#product-search").val(''); // Clear the input field
                return false; // Prevent the default behavior of selecting an item
            },
            close: function(event, ui) {
                // Clear the input field when the autocomplete menu is closed
                $("#product-search").val('');
            }
        });
      });

      function adjust_price(){
        $('#items .item').each(function(){
          let q = Number($(this).find('.quantity').val());
          let p = Number($(this).find('.price').val());
          $(this).find('.total').val(q*p);
        });
        var total = 0;
        $('#items .total').each(function(){
          const value = parseFloat($(this).val());
          if (!isNaN(value)) {
            total += Number(value);
          }
        });
        $('#total').val(Number(total));
      }
      
      // remove item row
      $(document).on("click", ".remove_row", function(){
        let $tr = $(this).closest('.item');
        $tr.remove();
        let i = 1;
        // rearrange sl
        $("tr .sl").each(function() {
          $(this).text(i++)
        });
        adjust_price();
      });
      
      // add new payment row
      $(document).on("click", ".add_payment_row", function(){
        let $tr = $(this).closest('.payment_row');
        let $clone = $tr.clone();
        $clone.find(':text').val('');
        $tr.after($clone);
        $(this).addClass('remove_payment_row').removeClass('add_payment_row');
        $(this).addClass('btn-inverse-danger').removeClass('btn-inverse-success');
        $(this).find('i').addClass('mdi-delete').removeClass('mdi-plus');
      });
      
      // remove payment row
      $(document).on("click", ".remove_payment_row", function(){
        let $tr = $(this).closest('tr');
        $tr.remove();
      });
</script>