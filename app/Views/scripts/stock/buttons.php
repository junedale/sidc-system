<script src="<?= base_url('js/jquery-3.6.0.min.js') ?>"></script>
<script>
    $('.delete-item').click(function() {
        let id = $(this).val()

        $.ajax({
            url:    `<?= base_url('stock/delete') ?>/${id}`,
            method: 'delete',
            success: function ()
            {
                location.reload()
            }
        })
    })

    $('#cancel-request').click(function() {
        let id = $(this).val();

        $.ajax({
            url: `<?= base_url('stock/cancel') ?>/${id}`,
            method: 'put',
            success: function(data)
            {
               if(data.length !== 0)
               {
                   let response = JSON.parse(data)
                   $('.modal-title').text(response.type)
                   $('.modal-body').text(response.message)
                   const modal = bootstrap.Modal.getOrCreateInstance('#staticBackdrop', {})
                   modal.show()
                   $('#understood, #close').click(function() {
                       location.href = `<?= base_url('stock') ?>`
                   })
               }

            }
        })
    })
</script>