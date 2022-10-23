<script src="<?= base_url('js/jquery-3.6.0.min.js') ?>"></script>
<script>
    $('#cancel-request').click(function() {
        let id = $(this).val();

        $.ajax({
            url: `<?= base_url('overtime/cancel') ?>/${id}`,
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
                        location.href = `<?= base_url('overtime') ?>`
                    })
                }
            }
        })
    })
</script>